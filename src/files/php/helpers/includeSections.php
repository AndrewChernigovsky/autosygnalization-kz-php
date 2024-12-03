<?php
class IncludeSections
{
  private $base_path;
  private $files_to_include;

  public function __construct($base_path, array $files)
  {
    $this->base_path = rtrim($base_path, '/');
    $this->files_to_include = $files;
  }

  public function includeFiles()
  {
    foreach ($this->files_to_include as $file) {
      $file_path = $this->base_path . '/' . ltrim($file, '/');
      if (file_exists($file_path)) {
        include $file_path;
      } else {
        echo "Файл $file не найден.<br>";
      }
    }
  }
}