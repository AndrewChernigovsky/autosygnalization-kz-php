<?php

namespace DATA;

use DATABASE\DataBase;

class WorksData extends DataBase {

    protected $pdo;

    public function __construct() {
        $db = DataBase::getInstance();
        $this->pdo = $db->getPdo();
    }


    public function getAllWorks() {
        try {
            $query = "SELECT * FROM Works ORDER BY position ASC";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $works = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $works;
        } catch (\Exception $e) {
            error_log("Ошибка получения данных: " . $e->getMessage());
            return [
                [
                    'image_path' => "/client/images/works/setup.avif",
                    'title' => 'УСТАНОВКА И РЕМОНТ АВТОСИГНАЛИЗАЦИЙ',
                    'link' => '/service?service=setup'
                ],
                [
                    'image_path' => "/client/images/works/setup-media.avif",
                    'title' => 'УСТАНОВКА АВТОЗВУКА И МУЛЬТИМЕДИА',
                    'link' => '/service?service=setup-media'
                ],
                [
                    'image_path' => "/client/images/works/locks.avif",
                    'title' => 'РЕМОНТ ЦЕНТРОЗАМКОВ',
                    'link' => '/service?service=locks'
                ],
                [
                    'image_path' => "/client/images/works/rus.avif",
                    'title' => 'РУСИФИКАЦИЯ АВТО И ЧИПТЮНИНГ',
                    'link' => '/service?service=rus',
                ],
                [
                    'image_path' => "/client/images/works/system-parking.avif",
                    'title' => 'УСТАНОВКА СИСТЕМ ПАРКИНГА',
                    'link' => '/service?service=setup-system-parking'
                ],
                [
                    'image_path' => "/client/images/works/autoelectric.avif",
                    'title' => 'УСЛУГИ АВТОЭЛЕКТРИКА',
                    'link' => '/service?service=autoelectric'
                ],
                [
                    'image_path' => "/client/images/works/diagnostic.avif",
                    'title' => 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА',
                    'link' => '/service?service=diagnostic'
                ],
                [
                    'image_path' => "/client/images/works/disabled-autosynal.avif",
                    'title' => 'ОТКЛЮЧЕНИЕ СИГНАЛИЗАЦИИ',
                    'link' => '/service?service=disabled-autosynal'
                ],
                [
                    'image_path' => "/client/images/works/setup-videoregistration.avif",
                    'title' => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ И АНТИРАДАРОВ',
                    'link' => '/service?service=setup-videoregistration'
                ],
            ];
        }
    }
}