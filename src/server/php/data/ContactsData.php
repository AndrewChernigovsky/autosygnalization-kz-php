<?php

namespace DATA;

use COMPONENTS\InsertSVG;
use DATABASE\DataBase;

class ContactsData extends DataBase
{
  private $insertSVG;
  private $phones;

  private $address;
  private $email = "autosecurity.kz@mail.ru";
  private $webSite = "www.autosecurity.kz";


  protected $pdo;

public function __construct(array $phones = [], array $address = [])
  {
    $this->phones = !empty($phones) ? $phones : [
          ['phone' => '+7 707 747 8212'],
          ['phone' => '+7 701 747 8212'],
    ];
        $this->address = !empty($address) ? $address : [['address' => 'Казахстан, г.Алматы,<br/> пр.Абая 145/г, бокс №15', 'link' => 'https://2gis.kz/almaty/geo/70000001027313872', 'svg_path' => '/client/vectors/sprite.svg#geo']];
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

  public function getEmail($link = false)
  {
    if ($link) {
      $output = '';
      $output .= "<a class='link link-message' href='mailto:autosecurity.site@mail.ru'>" . htmlspecialchars($this->email) . '</a>';
      return $output;
    } else {
      return htmlspecialchars($this->email);
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
  // Обновленная функция получение адреса
  public function getAddress()
  {
      try {
          $query = "SELECT content as address, link as link, icon_path as svg_path FROM Contacts WHERE type = 'address' ORDER BY contact_id ASC";
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
