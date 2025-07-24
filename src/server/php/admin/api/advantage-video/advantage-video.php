<?php

namespace API\ADMIN;

// CORS-заголовки
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\DataBase;
use Exception;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit(0);
}

class AdvantageVideoAPI extends DataBase
{
  protected $pdo;
  private $upload_dir;
    
  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
    $this->upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/advantage-video/';
    if (!is_dir($this->upload_dir)) {
        mkdir($this->upload_dir, 0777, true);
    }
  }

  public function getAll()
  {
    try {
      $query_videos = "SELECT * FROM AdvantageVideos ORDER BY position ASC";
      $stmt_videos = $this->pdo->prepare($query_videos);
      $stmt_videos->execute();
      $videos = $stmt_videos->fetchAll(\PDO::FETCH_ASSOC);

      $query_sources = "SELECT source_id, src_path, src_type FROM AdvantageVideoSources WHERE video_id = :video_id";
      $stmt_sources = $this->pdo->prepare($query_sources);

      foreach ($videos as &$video) {
        $stmt_sources->execute([':video_id' => $video['video_id']]);
        $video['sources'] = $stmt_sources->fetchAll(\PDO::FETCH_ASSOC);
      }
      unset($video);

      return $this->success($videos);

    } catch (\Exception $e) {
      error_log("Ошибка получения данных 'Видео преимуществ': " . $e->getMessage());
      return $this->error("Ошибка получения данных", 500);
    }
  }

  public function update($post, $files)
  {
    if (!isset($post['video_id'])) {
        return $this->error("Отсутствует ID видео для обновления", 400);
    }
    $id = $post['video_id'];

    try {
        $this->pdo->beginTransaction();

        $old_paths_stmt = $this->pdo->prepare("SELECT title_icon, video_poster, video_src_mob FROM AdvantageVideos WHERE video_id = :id");
        $old_paths_stmt->execute([':id' => $id]);
        $old_main_paths = $old_paths_stmt->fetch(\PDO::FETCH_ASSOC);

        $update_fields = [];
        $params = [':id' => $id];

        // Обновление заголовка
        if (isset($post['title'])) {
            $update_fields[] = 'title = :title';
            $params[':title'] = $post['title'];
        }

        // Обработка иконки заголовка
        if (isset($post['remove_title_icon']) && $post['remove_title_icon'] == '1') {
            $this->deleteFile($old_main_paths['title_icon']);
            $update_fields[] = 'title_icon = NULL';
        } else if (isset($files['title_icon'])) {
            $new_path = $this->uploadFile($files['title_icon'], 'icon');
            if ($new_path) {
                $this->deleteFile($old_main_paths['title_icon']);
                $update_fields[] = 'title_icon = :title_icon';
                $params[':title_icon'] = $new_path;
            }
        }
        
        // Обработка основного видео
        if (isset($files['main_video'])) {
            $temp_video_path = $files['main_video']['tmp_name'];
            
            // 1. Создаем новый постер .avif
            $poster_filename = 'poster_' . uniqid() . '.avif';
            $poster_server_path = $this->upload_dir . $poster_filename;
            shell_exec("ffmpeg -i {$temp_video_path} -ss 00:00:01.000 -vframes 1 {$poster_server_path} 2>&1");
            if (!file_exists($poster_server_path)) {
                throw new Exception("Не удалось создать постер для видео. Проверьте, установлен ли FFmpeg.");
            }
            
            // 2. Создаем мобильное видео .webm
            $mob_filename = 'mob_' . uniqid() . '.webm';
            $mob_server_path = $this->upload_dir . $mob_filename;
            shell_exec("ffmpeg -i {$temp_video_path} -c:v libvpx-vp9 -crf 32 -b:v 0 -an {$mob_server_path} 2>&1");
             if (!file_exists($mob_server_path)) {
                throw new Exception("Не удалось конвертировать видео для мобильных устройств.");
            }

            // 3. Создаем десктопные видео .webm и .mp4
            $desktop_webm_filename = 'desktop_' . uniqid() . '.webm';
            $desktop_webm_path = $this->upload_dir . $desktop_webm_filename;
            shell_exec("ffmpeg -i {$temp_video_path} -c:v libvpx-vp9 -crf 28 -b:v 0 -an {$desktop_webm_path} 2>&1");
             if (!file_exists($desktop_webm_path)) {
                throw new Exception("Не удалось конвертировать видео в WebM для десктопа.");
            }
            
            $desktop_mp4_filename = 'desktop_' . uniqid() . '.mp4';
            $desktop_mp4_path = $this->upload_dir . $desktop_mp4_filename;
            shell_exec("ffmpeg -i {$temp_video_path} -c:v libx264 -crf 23 -an {$desktop_mp4_path} 2>&1");
            if (!file_exists($desktop_mp4_path)) {
                throw new Exception("Не удалось конвертировать видео в MP4 для десктопа.");
            }

            // Удаляем старые файлы
            $this->deleteFile($old_main_paths['video_poster']);
            $this->deleteFile($old_main_paths['video_src_mob']);
            $old_sources_stmt = $this->pdo->prepare("SELECT src_path FROM AdvantageVideoSources WHERE video_id = :id");
            $old_sources_stmt->execute([':id' => $id]);
            while ($row = $old_sources_stmt->fetch(\PDO::FETCH_ASSOC)) {
                $this->deleteFile($row['src_path']);
            }
            
            // Обновляем пути в основной таблице
            $update_fields[] = 'video_poster = :poster';
            $params[':poster'] = '/server/uploads/advantage-video/' . $poster_filename;
            $update_fields[] = 'video_src_mob = :mob';
            $params[':mob'] = '/server/uploads/advantage-video/' . $mob_filename;

            // Обновляем источники
            $this->pdo->prepare("DELETE FROM AdvantageVideoSources WHERE video_id = :id")->execute([':id' => $id]);
            $source_stmt = $this->pdo->prepare("INSERT INTO AdvantageVideoSources (video_id, src_path, src_type) VALUES (:video_id, :src_path, :src_type)");
            $source_stmt->execute([':video_id' => $id, ':src_path' => '/server/uploads/advantage-video/' . $desktop_webm_filename, ':src_type' => 'video/webm']);
            $source_stmt->execute([':video_id' => $id, ':src_path' => '/server/uploads/advantage-video/' . $desktop_mp4_filename, ':src_type' => 'video/mp4']);
        }
        
        // Выполняем основной запрос на обновление
        if (!empty($update_fields)) {
            $query = "UPDATE AdvantageVideos SET " . implode(', ', $update_fields) . " WHERE video_id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
        }

        $this->pdo->commit();
        return $this->success(["status" => "updated"]);

    } catch (\Exception $e) {
        $this->pdo->rollBack();
        error_log("Ошибка обновления видео: " . $e->getMessage());
        return $this->error($e->getMessage(), 500);
    }
  }

  public function delete($id)
  {
    if (!$id) {
      return $this->error("Отсутствует ID для удаления", 400);
    }
    
    try {
      $this->pdo->beginTransaction();
      $stmt_main = $this->pdo->prepare("SELECT title_icon, video_poster, video_src_mob FROM AdvantageVideos WHERE video_id = :id");
      $stmt_main->execute([':id' => $id]);
      $main_files = $stmt_main->fetch(\PDO::FETCH_ASSOC);

      $stmt_sources = $this->pdo->prepare("SELECT src_path FROM AdvantageVideoSources WHERE video_id = :id");
      $stmt_sources->execute([':id' => $id]);
      $source_files = $stmt_sources->fetchAll(\PDO::FETCH_COLUMN);

      $files_to_delete = $main_files ? array_values($main_files) : [];
      $files_to_delete = array_merge($files_to_delete, $source_files);

      $stmt_delete = $this->pdo->prepare("DELETE FROM AdvantageVideos WHERE video_id = :id");
      $stmt_delete->execute([':id' => $id]);

      if ($stmt_delete->rowCount() > 0) {
        foreach ($files_to_delete as $file_path) {
            $this->deleteFile($file_path);
        }
        $this->pdo->commit();
        return $this->success(['deleted_id' => $id]);
      } else {
        $this->pdo->rollBack();
        return $this->error("Видео с таким ID не найдено", 404);
      }

    } catch (\Exception $e) {
      $this->pdo->rollBack();
      error_log("Ошибка удаления видео: " . $e->getMessage());
      return $this->error("Ошибка на сервере при удалении видео", 500);
    }
  }

  private function uploadFile($file, $prefix) {
    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . '_' . uniqid() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $this->upload_dir . $filename)) {
        return '/server/uploads/advantage-video/' . $filename;
    }
    return null;
  }

  private function deleteFile($path) {
    if ($path && file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
        unlink($_SERVER['DOCUMENT_ROOT'] . $path);
    }
  }

  private function success($data, $statusCode = 200)
  {
    http_response_code($statusCode);
    return json_encode(['success' => true, 'data' => $data]);
  }

  private function error($message, $statusCode = 400)
  {
    http_response_code($statusCode);
    return json_encode(['success' => false, 'error' => $message]);
  }
}

try {
    $api = new AdvantageVideoAPI();
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            echo $api->getAll();
            break;

        case 'POST':
            echo $api->update($_POST, $_FILES);
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? null;
            echo $api->delete($id);
            break;

        default:
            echo $api->error("Метод не поддерживается", 405);
            break;
    }
} catch (\Exception $e) {
    error_log("Критическая ошибка API 'Видео преимуществ': " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Внутренняя ошибка сервера']);
} 