<?php

class CreateDatabase
{
  private $host = 'localhost';
  private $username = 'root';
  private $password = '';
  private $dbname = 'auto_security';
  private $pdo;

  public function __construct()
  {
    $this->connect();
  }

  private function connect()
  {
    try {
      $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      if ($e->getCode() == 1049) {
        error_log("Database does not exist. Attempting to create it...");
        if ($this->createDatabase()) {
          $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
        } else {
          die("Failed to create database.");
        }
      } else {
        die("Connection failed: " . $e->getMessage());
      }
    }
  }

  private function createDatabase()
  {
    try {
      $connection = new PDO("mysql:host={$this->host}", $this->username, $this->password);
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $connection->exec("CREATE DATABASE IF NOT EXISTS {$this->dbname}");
      echo "Database created successfully.";
      return true;
    } catch (PDOException $e) {
      error_log("Failed to create database: " . $e->getMessage());
      return false;
    }
  }

  public function getConnection()
  {
    return $this->pdo;
  }
}
?>