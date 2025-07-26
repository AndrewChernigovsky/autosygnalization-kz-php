<?php

namespace DATA;

use DATABASE\DataBase;

class AdvantageVideoData extends DataBase
{
    protected $pdo;

    public function __construct()
    {
        $db = DataBase::getInstance();
        $this->pdo = $db->getPdo();
    }

    public function getAllVideos()
    {
        try {
            // 1. Получаем все видео из основной таблицы
            $query_videos = "SELECT * FROM AdvantageVideos ORDER BY position ASC";
            $stmt_videos = $this->pdo->prepare($query_videos);
            $stmt_videos->execute();
            $videos = $stmt_videos->fetchAll(\PDO::FETCH_ASSOC);

            // 2. Для каждого видео получаем его источники
            $query_sources = "SELECT src_path, src_type FROM AdvantageVideoSources WHERE video_id = :video_id";
            $stmt_sources = $this->pdo->prepare($query_sources);

            $result = [];
            foreach ($videos as $video) {
                $stmt_sources->execute([':video_id' => $video['video_id']]);
                $sources = $stmt_sources->fetchAll(\PDO::FETCH_ASSOC);

                // 3. Формируем массив в нужном формате
                $result[] = [
                    'id' => $video['video_id'],
                    'title' => $video['title'],
                    'title_icon' => $video['title_icon'],
                    'poster' => $video['video_poster'],
                    'srcMob' => $video['video_src_mob'],
                    'src' => array_column($sources, 'src_path'),
                    'type' => array_column($sources, 'src_type'),
                ];
            }
            
            // Оборачиваем результат в структуру, которую ожидает фронтенд
            return [
                'title' => $result[0]['title'] ?? 'Наши преимущества',
                'videos' => $result
            ];

        } catch (\Exception $e) {
            error_log("Ошибка получения данных 'Видео преимуществ': " . $e->getMessage());
            return [
                'title' => 'Ошибка',
                'videos' => []
            ];
        }
    }
} 