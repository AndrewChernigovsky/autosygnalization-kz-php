<?php

namespace DATA;

use DATABASE\DataBase;

class SertificatesData extends DataBase
{

    protected $pdo;

    public function __construct()
    {
        $db = DataBase::getInstance();
        $this->pdo = $db->getPdo();
    }


    public function getAllSertificates(): array
    {
        $query = "SELECT * FROM Sertificates ORDER BY position ASC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        
        // Получаем данные один раз и сохраняем в переменную
        $sertificates = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Логируем содержимое переменной
        error_log('SertificatesData: ' . print_r($sertificates, true));
        
        // Возвращаем данные
        return $sertificates;
    }
}
