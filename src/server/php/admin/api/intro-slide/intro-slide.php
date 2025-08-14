<?php

namespace API\ADMIN;

header("Access-control-allow-origin: http://localhost:5173");
header("Access-control-allow-methods: GET, POST, DELETE, OPTIONS");
header("Access-control-allow-headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../vendor/autoload.php';

use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use DATABASE\DataBase;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\WebM;
use Exception;

function log_message($message)
{
  $log_file = sys_get_temp_dir() . '/intro-slide-debug.log';
  error_log(print_r($log_file, true) . " - log_file");
  $timestamp = date('Y-m-d H:i:s');
  file_put_contents($log_file, "[$timestamp] " . $message . "\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit(0);
}

class IntroSlideAPI extends DataBase
{
  protected $pdo;
  private $upload_dir;
  private $ffmpeg;

  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
    $this->upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/intro-slide/';
    if (!is_dir($this->upload_dir)) {
      mkdir($this->upload_dir, 0777, true);
    }
    try {
        $this->ffmpeg = FFMpeg::create();
        log_message("FFMpeg успешно инициализирован");
      } catch (Exception $e) {
        log_message("Ошибка инициализации FFMpeg: " . $e->getMessage());
        log_message("Ошибка инициализации FFMpeg: " . $e->getLine());
        log_message("Ошибка инициализации FFMpeg: " . $e->getFile());
        http_response_code(500);
        throw $e;
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

  public function sendAll()
  {
    try {
      $query = "SELECT * FROM Videos_intro_slider ORDER BY position ASC";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute();
      $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      foreach ($data as &$item) {
        if (isset($item['advantages']) && is_string($item['advantages'])) {
          $item['advantages'] = json_decode($item['advantages'], true);
        }
      }
      unset($item);

      return $this->success($data);
    } catch (Exception $e) {
      log_message("Ошибка получения данных: " . $e->getMessage());
      return $this->error("Ошибка получения данных", 500);
    }
  }

  private function getVideoFilePaths($id)
  {
    $stmt = $this->pdo->prepare("SELECT video_path, poster_path, video_path_mob FROM Videos_intro_slider WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  private function deleteFile($path)
  {
    if (!$path) {
      log_message("deleteFile: path is empty or null. Skipping.");
      return;
    }

    $full_path = $_SERVER['DOCUMENT_ROOT'] . $path;
    log_message("deleteFile: Attempting to delete path: {$path} (Full path: {$full_path})");

    if (file_exists($full_path)) {
      log_message("deleteFile: File exists. Unlinking...");
      if (unlink($full_path)) {
        log_message("deleteFile: Successfully unlinked.");
      } else {
        log_message("deleteFile: FAILED to unlink. Check file permissions.");
      }
    } else {
      log_message("deleteFile: File does not exist at path.");
    }
  }

  private function processVideoWithFFmpeg($temp_path, $generatePoster = true)
  {
    $video = $this->ffmpeg->open($temp_path);
    $base_name = 'video_' . uniqid();

    $paths = [
      'video_path' => "/server/uploads/intro-slide/{$base_name}.mp4",
      'poster_path' => null, // Default to null
      'video_path_mob' => "/server/uploads/intro-slide/{$base_name}_mob.webm",
    ];

    $server_paths = [
      'video_path' => $this->upload_dir . "{$base_name}.mp4",
      'poster_path' => $this->upload_dir . "posters/{$base_name}.avif",
      'video_path_mob' => $this->upload_dir . "{$base_name}_mob.webm",
    ];

    if ($generatePoster) {
      if (!is_dir($this->upload_dir . "posters/")) {
        mkdir($this->upload_dir . "posters/", 0777, true);
      }
      $video->frame(TimeCode::fromSeconds(1))->save($server_paths['poster_path']);
      $paths['poster_path'] = "/server/uploads/intro-slide/posters/{$base_name}.avif";
    }

    $format_mp4 = new X264();
    $format_mp4->setAdditionalParameters(['-an']); // No audio
    $video->save($format_mp4, $server_paths['video_path']);

    $format_webm_mobile = new WebM();
    $format_webm_mobile->setAdditionalParameters(['-crf', '32', '-b:v', '0', '-an']);
    $video->save($format_webm_mobile, $server_paths['video_path_mob']);

    return $paths;
  }

  public function updateSlide($post, $files)
  {
    try {
      if (!isset($post['id'])) {
        return $this->error("Отсутствует ID для обновления", 400);
      }

      $id = $post['id'];
      $this->pdo->beginTransaction();
      $old_paths = $this->getVideoFilePaths($id);
      log_message("Old paths for ID {$id}: " . print_r($old_paths, true));
      set_time_limit(600); // Set execution time to 10 minutes (600 seconds)

      // --- File Validation ---
      if (isset($files['video'])) {
        if ($files['video']['error'] !== UPLOAD_ERR_OK) {
          throw new Exception('Ошибка при загрузке видеофайла. Код: ' . $files['video']['error']);
        }
        $max_video_size = 50 * 1024 * 1024; // 50 MB
        if ($files['video']['size'] > $max_video_size) {
          throw new Exception('Размер видеофайла не должен превышать 50 МБ.');
        }
      }
      if (isset($files['poster'])) {
        if ($files['poster']['error'] !== UPLOAD_ERR_OK) {
          throw new Exception('Ошибка при загрузке постера. Код: ' . $files['poster']['error']);
        }
        $max_poster_size = 5 * 1024 * 1024; // 5 MB
        if ($files['poster']['size'] > $max_poster_size) {
          throw new Exception('Размер файла постера не должен превышать 5 МБ.');
        }
      }
      // --- End File Validation ---

      $update_fields = [];
      $params = [':id' => $id];

      $fields = ['title', 'button_text', 'button_link'];
      foreach ($fields as $field) {
        if (isset($post[$field])) {
          $update_fields[] = "$field = :$field";
          $params[":$field"] = $post[$field];
        }
      }

      if (isset($post['advantages'])) {
        $update_fields[] = "advantages = :advantages";
        $params[':advantages'] = $post['advantages']; // Already a JSON string from FormData
      }

      // Handle file deletions first
      if (!empty($post['remove_video'])) {
        $this->deleteFile($old_paths['video_path']);
        $this->deleteFile($old_paths['video_path_mob']);
        $this->deleteFile($old_paths['poster_path']); // Poster is dependent on video
        $update_fields[] = "video_path = ''";
        $update_fields[] = "video_path_mob = ''";
        $update_fields[] = "poster_path = ''";
        $update_fields[] = "video_filename = ''";
      } elseif (!empty($post['remove_poster'])) {
        $this->deleteFile($old_paths['poster_path']);
        $update_fields[] = "poster_path = ''";
      }

      if (isset($files['video'])) {
        $generatePoster = !isset($files['poster']) && empty($post['remove_poster']);
        $video_paths = $this->processVideoWithFFmpeg($files['video']['tmp_name'], $generatePoster);

        $this->deleteFile($old_paths['video_path']);
        $this->deleteFile($old_paths['video_path_mob']);

        $update_fields[] = "video_path = :video_path";
        $params[':video_path'] = $video_paths['video_path'];
        $update_fields[] = "video_filename = :video_filename";
        $params[':video_filename'] = $files['video']['name'];
        $update_fields[] = "video_path_mob = :video_path_mob";
        $params[':video_path_mob'] = $video_paths['video_path_mob'];

        if ($generatePoster && $video_paths['poster_path']) {
          $this->deleteFile($old_paths['poster_path']);
          $update_fields[] = "poster_path = :poster_path";
          $params[':poster_path'] = $video_paths['poster_path'];
        }
      }

      if (isset($files['poster'])) {
        $this->deleteFile($old_paths['poster_path']);
        $ext = pathinfo($files['poster']['name'], PATHINFO_EXTENSION);
        $poster_filename = 'poster_' . uniqid() . '.' . $ext;
        $poster_path = '/server/uploads/intro-slide/posters/' . $poster_filename;
        if (!is_dir($this->upload_dir . "posters/")) {
          mkdir($this->upload_dir . "posters/", 0777, true);
        }
        move_uploaded_file($files['poster']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $poster_path);

        $update_fields[] = "poster_path = :poster_path";
        $params[':poster_path'] = $poster_path;
      }

      if (!empty($update_fields)) {
        $query = "UPDATE Videos_intro_slider SET " . implode(', ', $update_fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
      }

      $this->pdo->commit();
      return $this->success(['id' => $id, 'status' => 'updated']);
    } catch (Exception $e) {
      $this->pdo->rollBack();
      log_message("Ошибка обновления слайда: " . $e->getMessage());
      return $this->error("Ошибка на сервере при обновлении слайда: " . $e->getMessage(), 500);
    }
  }

  public function handleRequest()
  {
    $method = $_SERVER['REQUEST_METHOD'];
    switch ($method) {
      case 'GET':
        echo $this->sendAll();
        break;
      case 'POST':
        echo $this->updateSlide($_POST, $_FILES);
        break;
    }
  }
}

$api = new IntroSlideAPI();
$api->handleRequest();



