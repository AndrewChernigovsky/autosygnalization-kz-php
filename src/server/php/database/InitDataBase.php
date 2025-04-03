<?php

namespace DATABASE;

use DATABASE\DataBase as DataBase;
use MODELS\CATEGORIES\CRUDCategories as CRUDCategories;
use MODELS\NEWS\CRUDNew as CRUDNew;
use MODELS\SOCIAL\CRUDSocial as CRUDSocial;
use MODELS\AUTH\CRUDUsers as CRUDUsers;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class InitDataBase extends DataBase
{
    private $crud_news;
    private $crud_categories;
    private $crud_users;
    private $crud_social;
    protected $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();

        if (!$this->db->getPdo()) {
            $this->db->connect();
        }

        if (!$this->db->getPdo()) {
            throw new \RuntimeException("Не удалось установить соединение с базой данных.");
        }

        $this->crud_news = new CRUDNew();
        $this->crud_categories = new CRUDCategories();
        $this->crud_users = new CRUDUsers();
        $this->crud_social = new CRUDSocial();
    }

    public function init()
    {
        // error_log('Начало инициализации базовых данных');
        // $this->db->init();

        // // // Создаем базовые категории
        // error_log('Создание базовых категорий');
        // $this->createCategories();

        // // Создаем базовый тег "Все"
        // error_log('Создание базового тега');
        // $this->crud_news->findOrCreateTag('Все');

        // // Создаем социальные ссылки
        // error_log('Создание социальных ссылок');
        // $this->createSocialLinks();

        // error_log('Инициализация базовых данных завершена');
        // $this->db->close();
    }

    private function createCategories()
    {
        // Создаем первую категорию
        $this->crud_categories->createCategories(
            1,
            'Крипта',
            'Эта категория посвящена всем аспектам криптовалют и блокчейн-технологий. Здесь вы найдете статьи о популярных криптовалютах, таких как Bitcoin, Ethereum, и многие другие альткойны. Мы охватываем темы, включая основы торговли, инвестиционные стратегии, новости и тренды рынка, а также руководства по безопасному хранению и использованию криптовалют. Наша цель — помочь как новичкам, так и опытным трейдерам разобраться в мире цифровых активов и использовать их возможности на полную мощность.',
            date('Y-m-d H:i:s')
        );

        // Создаем вторую категорию
        $this->crud_categories->createCategories(
            2,
            'Инвестиции',
            'Эта категория охватывает различные аспекты инвестирования, включая фондовый рынок, недвижимость и альтернативные инвестиции. Здесь вы найдёте статьи, практические советы и новости, касающиеся инвестиционной деятельности.',
            date('Y-m-d H:i:s')
        );
    }

    private function createUser()
    {
    }

    private function createSocialLinks()
    {
        $links = [
          [
            'href' => 'https://facebook.com',
            'src' => '/client/vectors/sprite.svg#facebook',
            'alt' => 'Перейти в Facebook',
          ],
          [
            'href' => 'https://twitter.com',
            'src' => '/client/vectors/sprite.svg#twitter',
            'alt' => 'Перейти в Twitter',
          ],
          [
            'href' => 'https://instagram.com',
            'src' => '/client/vectors/sprite.svg#instagram',
            'alt' => 'Перейти в Instagram',
          ],
          [
            'href' => 'https://youtube.com',
            'src' => '/client/vectors/sprite.svg#youtube',
            'alt' => 'Перейти в YouTube',
          ],
          [
            'href' => 'https://telegram.org',
            'src' => '/client/vectors/sprite.svg#telegram',
            'alt' => 'Перейти в Telegram',
          ],
          [
            'href' => 'https://discord.com',
            'src' => '/client/vectors/sprite.svg#discord',
            'alt' => 'Перейти в Discord',
          ],
        ];

        if (empty($links)) {
            error_log("Список ссылок на социальные сети пуст.");
            return;
        }

        foreach ($links as $link) {
            $this->crud_social->createSocialLinks(
                $link['href'],
                $link['src'],
                $link['alt']
            );
        }
    }
}
