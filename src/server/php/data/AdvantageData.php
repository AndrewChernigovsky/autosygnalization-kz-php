<?php

namespace DATA;

use DATABASE\DataBase;

class AdvantageData extends DataBase
{
  protected $pdo;

public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }

public function getAllAdvantage(array $types = [])
        {
            try {
                $query = "SELECT content, image_path, position FROM Advantage ORDER BY position ASC";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();

                $advantageArr = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                return $advantageArr;

            } catch (\Exception $e) {
                error_log("Ошибка получения данных: " . $e->getMessage());
                return [['content' => 'none', 'image_path' => '/', 'position' => 0]];
            }
        }

}
