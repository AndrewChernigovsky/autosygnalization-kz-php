<?php

namespace DATA;

use COMPONENTS\InsertSVG;
use DATABASE\DataBase;

class ContactsData extends DataBase
{
  private $insertSVG;
  private $webSite = "www.autosecurity.kz";


  protected $pdo;

public function __construct()
  {
    
    $this->insertSVG = new InsertSVG();

    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }

  public function getSocialIcons()
  {
    return [
      "instagramm" => ['name' => 'Instagram', 'width' => '50', 'height' => '50', "image" => '/client/vectors/sprite.svg#instagramm-icon', 'href' => "https://www.instagram.com/autosecurity_kz"],
    ];
  }
  public function getGeoIcon()
  {
    return [
      "geo" => ['name' => 'geo', 'width' => '50', 'height' => '50', "image" => '/client/vectors/sprite.svg#geo', 'href' => 'href="https://maps.app.goo.gl/72eQCZUbxVCKh43PA"'],
    ];
  }

  // Обновленная функция получение рассписания
  public function getSchedule()
  {
      try {
          $query = "SELECT content as text, title as title, icon_path as svg_path FROM Contacts WHERE type = 'schedule' ORDER BY contact_id ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $scheduleArr = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          error_log(print_r($scheduleArr, true));
          
          if (empty($scheduleArr)) {
              return [['title' => 'График работы:','text' => 'Вс. - Чт.: 9:30 - 18:00 <br> Пт.: 9:30-15:00 <br> <span>Сб.: Выходной</span>','svg_path' => 'client/vectors/clock.sv']];
          }
          return $scheduleArr;
      } catch (\Exception $e) {
          error_log("Ошибка получения email: " . $e->getMessage());
          return [['title' => 'График работы:','text' => 'Вс. - Чт.: 9:30 - 18:00 <br> Пт.: 9:30-15:00 <br> <span>Сб.: Выходной</span>','svg_path' => 'client/vectors/clock.sv']];
      }
  }

    // Обновленная функция получение карты
  public function getMap()
  {
      try {
          $query = "SELECT link as link FROM Contacts WHERE type = 'map' ORDER BY contact_id ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $map = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          error_log(print_r($map, true));
          
          if (empty($map)) {
              return [['link' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1453.4679397503296!2d76.8722813!3d43.231804!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3883693b733bff39%3A0x716633e11986b3f8!2sAuto%20Security!5e0!3m2!1sru!2sru!4v1735233649305!5m2!1sru!2sru']];
          }
          return $map;
      } catch (\Exception $e) {
          error_log("Ошибка получения email: " . $e->getMessage());
              return [['link' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1453.4679397503296!2d76.8722813!3d43.231804!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3883693b733bff39%3A0x716633e11986b3f8!2sAuto%20Security!5e0!3m2!1sru!2sru!4v1735233649305!5m2!1sru!2sru']];
      }
  }

  // Обновленная функция получение email
  public function getEmail()
  {
      try {
          $query = "SELECT content as email, title as title, link as link, icon_path as svg_path FROM Contacts WHERE type = 'email' ORDER BY contact_id ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $contactEmail = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    error_log(print_r($contactEmail, true));
          if (empty($contactEmail)) {
              return [['email' => 'autosecurity.site@mail.ru','title' => 'Почта','link' => 'mailto:autosecurity.site@mail.ru','svg_path' => 'client/vectors/message-icon.svg']];
          }
          return $contactEmail;
      } catch (\Exception $e) {
          error_log("Ошибка получения email: " . $e->getMessage());
          return [['email' => 'autosecurity.site@mail.ru','title' => 'Почта','link' => 'mailto:autosecurity.site@mail.ru','svg_path' => 'client/vectors/message-icon.svg']];
      }
  }

  // Обновленная функция получение телефонов на шапки и футер
  public function getPhones()
  {
      try {
          $query = "SELECT content as phone FROM Contacts WHERE type = 'main-phone' ORDER BY contact_id ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $phonesArr = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          error_log(print_r($phonesArr, true));
          if (empty($phonesArr)) {
              return [
                  ['phone' => '+7 707 747 8212'],
                  ['phone' => '+7 701 747 8212'],
              ];
          }
          return $phonesArr;
      } catch (\Exception $e) {
          error_log("Ошибка получения навигации: " . $e->getMessage());
          return [
              ['phone' => '+7 7071 747 8212'],
              ['phone' => '+7 7011 747 8212'],
          ];
      }
  }

  // Обновленная функция получение телефонов на странице контакты
  public function getContactPhones()
  {
      try {
          $query = "SELECT content as phone, title as title, link as link, icon_path as svg_path FROM Contacts WHERE type = 'contact-phone' ORDER BY contact_id ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $contactPhonesArr = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    error_log(print_r($contactPhonesArr, true));
          if (empty($contactPhonesArr)) {
              return [
          ['phone' => '+770 774 8212', 'title' => 'BEELINE', 'link' => 'tel:+7707748212', 'svg_path' => 'client/vectors/phone-no-border.svg'],
          ['phone' => '+770 174 8212', 'title' => 'KCELL', 'link' => 'tel:+7701748212', 'svg_path' => 'client/vectors/phone-no-border.svg'],
              ];
          }
          return $contactPhonesArr;
      } catch (\Exception $e) {
          error_log("Ошибка получения навигации: " . $e->getMessage());
          return [
          ['phone' => '+770 774 8212', 'title' => 'BEELINE', 'link' => 'tel:+7707748212', 'svg_path' => 'client/vectors/phone-no-border.svg'],
          ['phone' => '+770 174 8212', 'title' => 'KCELL', 'link' => 'tel:+7701748212', 'svg_path' => 'client/vectors/phone-no-border.svg'],
          ];
      }
  }

  // Обновленная функция получение Social на странице контакты
  public function getSocial()
  {
      try {
          $query = "SELECT content as content, title as title, link as link, icon_path as svg_path FROM Contacts WHERE type = 'social' ORDER BY contact_id ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $contactSocial = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    error_log(print_r($contactSocial, true));
          if (empty($contactSocial)) {
              return [[
                'content' => '+77077478212',
                'title' => 'Whatsapp:',
                'link' => 'https://wa.me/77077478212',
                'svg_path' => 'client/vectors/whatsapp.svg'],
                ['content' => '+77077478211',
                'title' => 'Instagramm:',
                'link' => 'https://www.instagram.com/autosecurity_kz',
                'svg_path' => '/client/vectors/sprite.svg#instagramm-icon'],];
          }
          return $contactSocial;
      } catch (\Exception $e) {
          error_log("Ошибка получения навигации: " . $e->getMessage());
              return [[
                'content' => '+77077478212',
                'title' => 'Whatsapp:',
                'link' => 'https://wa.me/77077478212',
                'svg_path' => 'client/vectors/whatsapp.svg'],
                ['content' => '+77077478211',
                'title' => 'Instagramm:',
                'link' => 'https://www.instagram.com/autosecurity_kz',
                'svg_path' => '/client/vectors/sprite.svg#instagramm-icon'],];
      }
  }
  // Обновленная функция получение адреса
  public function getAddress()
  {
      try {
          $query = "SELECT content as address, title as title, link as link, icon_path as svg_path FROM Contacts WHERE type = 'address' ORDER BY contact_id ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $addressArr = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          error_log(print_r($addressArr, true));
          if (empty($addressArr)) {
              return [['address' => 'Казахстан, г.Алматы,<br/> пр.Абая 145/г, бокс №15', 'link' => 'https://2gis.kz/almaty/geo/70000001027313872', 'svg_path' => '/client/vectors/sprite.svg#geo']];
          }
          return $addressArr;
      } catch (\Exception $e) {
          error_log("Ошибка получения адресса: " . $e->getMessage());
          return [['address' => 'Казахстан, г.Алматы,<br/> пр.Абая 145/г, бокс №15', 'link' => 'https://2gis.kz/almaty/geo/70000001027313872', 'svg_path' => '/client/vectors/sprite.svg#geo']];
      }
  }

  public function getWebsite($link = false)
  {
    if ($link) {
      $output = '';
      $output .= "<a class='link link-site'href='http://autosecurity.site'>" . htmlspecialchars($this->webSite) . '</a>'; // Используйте экранирование для URL
      return $output;
    } else {
      return htmlspecialchars($this->webSite);
    }
  }




  public function getLogo()
  {
    $logo = '/client/images/logo.avif';
    $logo_description = 'Логотип Компании Auto Security';
    return <<<HTML

          <a href='' class='logo'>
              <img src='{$logo}' alt='$logo_description' width='142' height='40'/>
          </a>
          HTML;
  }
  public function getContacts()
  {
    return [
      "email" => $this->getEmail(),
      "phones" => $this->getPhones(),
      "webSite" => $this->getWebsite(),
      "address" => $this->getAddress(),
      "social" => $this->getSocialIcons(),
    ];
  }

  public function setSocial($social)
  {
    $output = '';
    $output .= "<a class='social-contact link' href=\"" . htmlspecialchars($social['href']) . "\">" . $this->insertSVG->insertSvg($social) . "</a>";
    return $output;
  }

}
