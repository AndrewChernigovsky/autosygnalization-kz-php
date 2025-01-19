<?php
class SetVariables
{
  private $currentPath;
  private $pathFile;
  private $pathFile_URL;
  private $basePath;

  private $distPath;

  private static $docRoot;

  public function __construct()
  {
    self::$docRoot = $_SERVER['DOCUMENT_ROOT'];
  }

  public function setVar()
  {
    $distPath = self::$docRoot . '/dist';

    // Проверяем, существует ли папка dist
    if (is_dir($distPath)) {
      $this->currentPath = "http://localhost:3000/dist/index.php";
      $this->pathFile = "http://localhost:3000/dist";
      $this->pathFile_URL = '/dist';
    } else {
      $this->currentPath = "/index.php";
      $this->pathFile = "";
      $this->pathFile_URL = '';
    }
  }

  public function getCurrentPath()
  {
    return $this->currentPath;
  }

  public function getPathFile()
  {
    return $this->pathFile;
  }

  public function getPathFileURL()
  {
    return $this->pathFile_URL;
  }
  public function getBasePath()
  {

    $this->basePath = self::getDocRoot() . $this->pathFile_URL;

    return $this->basePath;
  }

  public static function getDocRoot()
  {
    return self::$docRoot;
  }
}

?>