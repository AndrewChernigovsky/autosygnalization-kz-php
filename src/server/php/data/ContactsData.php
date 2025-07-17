<?php

namespace DATA;

use COMPONENTS\InsertSVG;
use DATABASE\DataBase;

class ContactsData extends DataBase
{
  private $insertSVG;
  private $phones;

  private $address = "Казахстан, г.Алматы,<br/> пр.Абая 145/г, бокс №15";
  private $email = "autosecurity.kz@mail.ru";
  private $web_site = "www.autosecurity.kz";

  protected $pdo;

public function __construct(array $phones = [
    ['phone' => '+7 707 747 8212'],
    ['phone' => '+7 701 747 8212'],
])
  {
    $this->phones = !empty($phones) ? $phones : [
          ['phone' => '+7 707 747 8212'],
          ['phone' => '+7 701 747 8212'],
    ];
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
  // Обновленная функция получение телефонов
  public function getPhones()
  {
      try {
          $query = "SELECT phone as phone FROM Contacts ORDER BY contact_id ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $phonesArr = $stmt->fetchAll(\PDO::FETCH_ASSOC);

          if (empty($phonesArr)) {
              return [
                  ['phone' => '+7 707 747 8212'],
                  ['phone' => '+7 701 747 8212'],
              ];
          }
          return $phonesArr; // <-- точка с запятой!
      } catch (\Exception $e) {
          error_log("Ошибка получения навигации: " . $e->getMessage());
          return [
              ['phone' => '+7 707 747 8212'],
              ['phone' => '+7 7011 747 8212'],
          ];
      }
  }


  public function getWebsite($link = false)
  {
    if ($link) {
      $output = '';
      $output .= "<a class='link link-site'href='http://autosecurity.site'>" . htmlspecialchars($this->web_site) . '</a>'; // Используйте экранирование для URL
      return $output;
    } else {
      return htmlspecialchars($this->web_site);
    }
  }
  public function getAddress()
  {
    return $this->address;
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
      "web_site" => $this->getWebsite(),
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
