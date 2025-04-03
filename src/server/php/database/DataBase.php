<?php

namespace DATABASE;

require_once __DIR__ . '/../config/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DataBase
{
    private $host;
    private $dbName;
    private $user;
    private $password;
    protected $pdo;
    private static $instance = null;
    private $migrationsPath;

    private function __construct()
    {
        $this->host = DB_HOST;
        $this->dbName = DB_NAME;
        $this->user = DB_USER;
        $this->password = DB_PASSWORD;
        $this->migrationsPath = __DIR__ . '/migrations/';
    }

    protected static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function connect()
    {
        try {
            // 1. Сначала подключаемся без указания базы данных
            $this->pdo = new \PDO("mysql:host=$this->host;charset=utf8mb4", $this->user, $this->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // 2. Проверяем существование базы данных
            $stmt = $this->pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$this->dbName'");

            if (!$stmt->fetchColumn()) {
                // 3. Создаём базу, если её нет
                $this->pdo->exec("CREATE DATABASE `$this->dbName`");
                error_log("База данных $this->dbName создана успешно.");
            }

            // 4. Переподключаемся с выбором базы
            $this->pdo = new \PDO(
                "mysql:host=$this->host;dbname=$this->dbName;charset=utf8mb4",
                $this->user,
                $this->password
            );
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            error_log("Соединение с базой данных установлено");

        } catch (\PDOException $e) {
            error_log("ФАТАЛЬНАЯ ошибка подключения: " . $e->getMessage());
            throw new \RuntimeException("Не удалось установить соединение с базой данных");
        }
    }

  // Добавляем метод prepare
    public function prepare($query)
    {
      return $this->getPdo()->prepare($query);
    }

    protected function init()
    {
        $this->runMigrations();
    }

    private function runMigrations()
    {
        try {
            $migrations = [
                'users.sql',
                'categories.sql',
                'tags.sql',
                'social_links.sql',
                'news/news.sql',
                'news/news_categories.sql',
                'news/news_content_all.sql',
                'news/news_content.sql',
                'news/news_images_all.sql',
                'news/news_images.sql',
                'news/news_tags.sql',
                'articles/articles.sql',
                'articles/articles_categories.sql',
                'articles/articles_content_all.sql',
                'articles/articles_content.sql',
                'articles/articles_images_all.sql',
                'articles/articles_images.sql',
                'articles/articles_tags.sql'
            ];

            error_log("Начало выполнения миграций. Всего миграций: " . count($migrations));

            foreach ($migrations as $index => $migration) {
                error_log("Выполняется миграция [{$index}]: {$migration}");

                try {
                    $this->executeMigration($migration);
                    error_log("✓ Миграция {$migration} успешно выполнена");
                } catch (\Exception $e) {
                    error_log("✗ Ошибка при выполнении миграции {$migration}: " . $e->getMessage());
                    throw $e;
                }
            }

            error_log("Все миграции успешно выполнены");
        } catch (\Exception $e) {
            error_log("Критическая ошибка при выполнении миграций: " . $e->getMessage());
            throw $e;
        }
    }

    private function executeMigration($filename)
    {
        $filepath = $this->migrationsPath . $filename;

        if (!file_exists($filepath)) {
            error_log("Файл миграции не найден: $filepath");
            return;
        }

        try {
            $sql = file_get_contents($filepath);
            if ($sql === false) {
                throw new \RuntimeException("Не удалось прочитать файл миграции: $filepath");
            }

            $this->pdo->exec($sql);
            error_log("Миграция $filename выполнена успешно");
        } catch (\PDOException $e) {
            error_log("Ошибка при выполнении миграции $filename: " . $e->getMessage());
            throw $e;
        }
    }

    protected function getPdo(): \PDO
    {
        if (!$this->pdo) {
            $this->connect();
        }
        return $this->pdo;
    }

    protected function close()
    {
        $this->pdo = null;
        error_log("Соединение с базой данных закрыто.");
    }
}
