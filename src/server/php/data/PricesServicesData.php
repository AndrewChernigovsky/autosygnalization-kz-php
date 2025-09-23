<?php

namespace DATA;
require_once __DIR__ . '/../../vendor/autoload.php';

use DATABASE\DataBase;
use PDO;
use Exeption;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
// header('Content-Type: application/json');



class PricesServicesData extends DataBase
 {

  protected $pdo;

  function __construct() {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }
    public function getData()
    {
        return [
            [
                'title' => 'Установка магнитолы',
                'id' => 'installation-radio',
                'productServicesPrice' => 'от 10 000',
                'currency' => '₸',
                'link' => "",
            ],
            [
                'title' => 'Установка простой сигнализации',
                'id' => 'installation-simple-alarm',
                'productServicesPrice' => 'от 15 000',
                'currency' => '₸',
                'link' => "",
            ],
            [
                'title' => 'Установка сигнализации с обратной связью',
                'id' => 'installation-alarm-feedback',
                'productServicesPrice' => 'от 20 000',
                'currency' => '₸',
                'link' => "",
            ],
            [
                'title' => 'Установка автозапуска',
                'id' => 'installation-autostart',
                'productServicesPrice' => 'от 30 000',
                'currency' => '₸',
                'link' => "",
            ],
            [
                'title' => 'Установка камеры заднего вида',
                'id' => 'installation-rear-view-camera',
                'productServicesPrice' => 'от 15 000',
                'currency' => '₸',
                'link' => "",
            ],
            [
                'title' => 'Установка видеорегистратора с 1&nbsp;камерой',
                'id' => 'installation-video-recorder',
                'productServicesPrice' => 'от 10 000',
                'currency' => '₸',
                'link' => "",
            ],
            [
                'title' => 'Компьютерная диагностика',
                'id' => 'computer-diagnostics',
                'productServicesPrice' => 'от 5 000',
                'currency' => '₸',
                'link' => "",
            ],
            [
                'title' => 'Выезд',
                'id' => 'departure',
                'productServicesPrice' => 'от 5 000',
                'currency' => '₸',
                'link' => "",
            ],
            [
                'title' => 'Ремонт центрозамка',
                'id' => 'cantral-lock-repair',
                'productServicesPrice' => 'от 15 000 тг/дверь',
                'currency' => '',
                'link' => "",
            ],
            [
                'title' => 'Установка колонок ',
                'id' => 'installation-speakers',
                'productServicesPrice' => 'от 10 000 тг/пара',
                'currency' => '',
                'link' => "",
            ],
            [
                'title' => 'Настройка сигнализации, запись пультов',
                'id' => 'alarm-setup',
                'productServicesPrice' => 'от 2 000',
                'currency' => '₸',
                'link' => "",
            ],
            [
                'title' => 'Отключение автосигнализации',
                'id' => 'disabling-car-alarms',
                'productServicesPrice' => 'от 8 000',
                'currency' => '₸',
                'link' => "",
            ],
        ];
    }

    public function getAddedServices() {
      try {
          $query = "SELECT * FROM add_services";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();
          $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
  
          error_log("Получены все элементы прайсов: " . count($result));
          return $result;
      } catch (\Exception $e) {
          error_log("Ошибка получения прайсов: " . $e->getMessage());
          return $this->error("Ошибка получения прайсов", 500);
      }
  }
}
