<?php
namespace API\ADMIN;

// Подключение автозагрузчика Composer
require_once __DIR__ . '/../../vendor/autoload.php';

header('Content-Type: application/json');

use DATABASE\InitDataBase;
use Exception;

// Функция для логирования
function logMessage($message)
{
  $logFile = __DIR__ . '/slides-log.txt';
  $timestamp = date('Y-m-d H:i:s');
  file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

logMessage('Запрос на получение данных о слайдах получен');


try {
  $db = new InitDataBase();
  $stmt = $db->prepare("SELECT id, video_filename, video_path, title, advantages, button_text, button_link FROM Videos_intro_slider WHERE is_active = TRUE ORDER BY created_at DESC");
  $stmt->execute();
  $videos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
  $formattedVideos = array_map(function($video) {
    return [
      'id' => $video['id'],
      'poster' => $video['video_path'],
      'srcMob' => $video['video_path'],
      'src' => [$video['video_path']],
      'type' => ['video/mp4'],
      'title' => $video['title'],
      'advantages' => json_decode($video['advantages'], true) ?: [],
      'link' => $video['button_link'],
      'video_path' => $video['video_path']
    ];
  }, $videos);
  echo json_encode($formattedVideos);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>