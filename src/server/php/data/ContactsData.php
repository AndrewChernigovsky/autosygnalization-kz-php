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
          $query = "SELECT content as text, title as title, icon_path as svg_path FROM Contacts WHERE type = 'Расписание' ORDER BY contact_id ASC";
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

public function getAllContact(array $types = [])
{
    try {
        if (empty($types)) {
            $query = "SELECT type, title, content, link, icon_path FROM Contacts WHERE on_page = 1 ORDER BY sort_order ASC";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        } else {
            // Генерируем плейсхолдеры и параметры
            $placeholders = [];
            $params = [];
            foreach ($types as $index => $type) {
                $ph = ":type$index";
                $placeholders[] = $ph;
                $params[$ph] = $type;
            }

            $inClause = implode(', ', $placeholders);

            // Строим FIELD-список прямо из типов (в безопасной форме)
            $fieldList = implode(', ', array_map(function($t) {
                return $this->pdo->quote($t);
            }, $types));

            $query = "
                SELECT type, title, content, link, icon_path
                FROM Contacts
                WHERE type IN ($inClause) AND on_page = 1
                ORDER BY FIELD(type, $fieldList), sort_order ASC
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
        }

        $itemArr = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Обрабатываем контент по типу: для адреса/расписания добавляем m-0 к <p>,
        // для социальных сетей удаляем любые HTML-теги
        foreach ($itemArr as &$item) {
            if (in_array($item['type'], ['Адрес', 'Расписание'])) {
                $item['content'] = $this->addClassToPTags($item['content']);
            } elseif ($item['type'] === 'Социальные сети') {
                $item['content'] = strip_tags($item['content']);
            }
        }
        
        error_log(print_r($itemArr, true));

        if (empty($itemArr)) {
            return [['type' => 'none', 'title' => 'none', 'content' => 'none', 'link' => '/', 'icon_path' => '/']];
        }

        return $itemArr;

    } catch (\Exception $e) {
        error_log("Ошибка получения данных: " . $e->getMessage());
        return [['type' => 'none', 'title' => 'none', 'content' => 'none', 'link' => '/', 'icon_path' => '/']];
    }
}



  // Обновленная функция получение карты
  public function getMap()
  {
      try {
          $query = "SELECT link as link FROM Contacts WHERE type = 'Карта' ORDER BY contact_id ASC";
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
          $query = "SELECT content as email, title as title, link as link, icon_path as svg_path FROM Contacts WHERE type = 'Электронная почта' AND on_page = 1 ORDER BY sort_order ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $contactEmail = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          
          // Убираем HTML теги из email
          foreach ($contactEmail as &$email) {
              $email['email'] = strip_tags($email['email']);
          }
          
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
          $query = "SELECT content as phone FROM Contacts WHERE type = 'Основной телефон' AND on_page = 1 ORDER BY sort_order ASC";
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
          error_log("Ошибка получения телефонов: " . $e->getMessage());
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
          $query = "SELECT content as phone, title as title, link as link, icon_path as svg_path FROM Contacts WHERE type = 'Контактный телефон' ORDER BY contact_id ASC";
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
  public function getSocialMessenger()
  {
      try {
          $query = "SELECT content as content, title as title, link as link, icon_path as svg_path FROM Contacts WHERE type = 'Мессенджер' AND on_page = 1 ORDER BY sort_order ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $contactSocial = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          
          // Убираем HTML теги из контента социальных сетей
          foreach ($contactSocial as &$social) {
              $social['content'] = strip_tags($social['content']);
              $social['title'] = strip_tags($social['title']);
          }
          return $contactSocial;
      } catch (\Exception $e) {
          error_log("Ошибка получения социальных сетей: " . $e->getMessage());
          return [];
      }
  }
  public function getSocial()
  {
      try {
          $query = "SELECT content as content, title as title, link as link, icon_path as svg_path FROM Contacts WHERE type = 'Социальные сети' AND on_page = 1 ORDER BY sort_order ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $contactSocial = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          
          // Убираем HTML теги из контента социальных сетей
          foreach ($contactSocial as &$social) {
              $social['content'] = strip_tags($social['content']);
              $social['title'] = strip_tags($social['title']);
          }
          
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
          error_log("Ошибка получения социальных сетей: " . $e->getMessage());
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
          $query = "SELECT content as address, title as title, link as link, icon_path as svg_path FROM Contacts WHERE type = 'Адрес' AND on_page = 1 ORDER BY sort_order ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $addressArr = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          
          // Обрабатываем адрес, добавляя класс m-0 к тегам <p>
          foreach ($addressArr as &$address) {
              $address['address'] = $this->addClassToPTags($address['address']);
          }
          
          error_log(print_r($addressArr, true));
          if (empty($addressArr)) {
              return [['address' => '<p class="m-0">Казахстан, г.Алматы,</p><p class="m-0"> пр.Абая 145/г, бокс №15</p>', 'link' => 'https://2gis.kz/almaty/geo/70000001027313872', 'svg_path' => '/client/vectors/sprite.svg#geo']];
          }
          return $addressArr;
      } catch (\Exception $e) {
          error_log("Ошибка получения адресса: " . $e->getMessage());
          return [['address' => '<p class="m-0">Казахстан, г.Алматы,</p><p class="m-0"> пр.Абая 145/г, бокс №15</p>', 'link' => 'https://2gis.kz/almaty/geo/70000001027313872', 'svg_path' => '/client/vectors/sprite.svg#geo']];
      }
  }

  // Функция для добавления класса m-0 к тегам <p>
  private function addClassToPTags($content)
  {
      // Заменяем <p> на <p class="m-0"> и <p class="существующий-класс"> на <p class="существующий-класс m-0">
      $pattern = '/<p(\s+class=["\']([^"\']*)["\'])?([^>]*)>/i';
      $replacement = function($matches) {
          $existingClass = isset($matches[2]) ? $matches[2] : '';
          $otherAttrs = isset($matches[3]) ? $matches[3] : '';
          
          if (!empty($existingClass)) {
              // Если уже есть класс, добавляем m-0
              if (strpos($existingClass, 'm-0') === false) {
                  $newClass = $existingClass . ' m-0';
              } else {
                  $newClass = $existingClass;
              }
          } else {
              // Если класса нет, добавляем только m-0
              $newClass = 'm-0';
          }
          
          return '<p class="' . $newClass . '"' . $otherAttrs . '>';
      };
      
      return preg_replace_callback($pattern, $replacement, $content);
  }

  // Обновленная функция получение маршрута
  public function getLocationDescription()
  {
      try {
          $query = "SELECT content as path, title as title, FROM Contacts WHERE type = 'Как к нам добраться' ORDER BY contact_id ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $locationDescription = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          error_log(print_r($locationDescription, true));
          if (empty($locationDescription)) {
              return [['path' => 'Едем по Абая со стороны Мате Залка в сторону Большой Алматинки, <br> перед речкой поворот направо, заезжаем на территорию СТО. <br> Наш бокс №15.', 'title' => 'КАК К НАМ ДОБРАТЬСЯ']];
          }
          return $locationDescription;
      } catch (\Exception $e) {
          error_log("Ошибка получения маршрута: " . $e->getMessage());
              return [['path' => 'Едем по Абая со стороны Мате Залка в сторону Большой Алматинки, <br> перед речкой поворот направо, заезжаем на территорию СТО. <br> Наш бокс №15.', 'title' => 'КАК К НАМ ДОБРАТЬСЯ']];
      }
  }

  public function getWebsite($link = false)
  {
      try {
          // Возвращаем все сайты без сортировки по sort_order и без LIMIT
          $query = "SELECT content as website, link as website_link FROM Contacts WHERE type = 'Сайт' AND on_page = 1 ORDER BY sort_order ASC";
          $stmt = $this->pdo->prepare($query);
          $stmt->execute();

          $websites = $stmt->fetchAll(\PDO::FETCH_ASSOC);

          if (!empty($websites)) {
              // Нормализуем данные и по умолчанию возвращаем массив
              $normalized = array_map(function ($row) use ($link) {
                  $label = strip_tags($row['website'] ?? '');
                  $href = $row['website_link'] ?? '';
                  if ($link) {
                      // если ожидается строка HTML (обратная совместимость), собираем массив HTML-строк
                      return "<a class='link link-site' href='" . htmlspecialchars($href) . "'>" . htmlspecialchars($label) . '</a>';
                  }
                  return [
                      'website' => $label,
                      'website_link' => $href,
                  ];
              }, $websites);

              return $normalized;
          }

          // Пусто: используем fallback
          if ($link) {
              return ["<a class='link link-site' href='http://autosecurity.site'>" . htmlspecialchars($this->webSite) . '</a>'];
          }
          return [[
              'website' => htmlspecialchars($this->webSite),
              'website_link' => 'http://autosecurity.site',
          ]];
      } catch (\Exception $e) {
          error_log("Ошибка получения сайта: " . $e->getMessage());
          if ($link) {
              return ["<a class='link link-site' href='http://autosecurity.site'>" . htmlspecialchars($this->webSite) . '</a>'];
          }
          return [[
              'website' => htmlspecialchars($this->webSite),
              'website_link' => 'http://autosecurity.site',
          ]];
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
      "socialMessenger" => $this->getSocialMessenger(),
    ];
  }

  public function setSocial($social)
  {
    $output = '';
    $output .= "<a class='social-contact link' href=\"" . htmlspecialchars($social['href']) . "\">" . $this->insertSVG->insertSvg($social) . "</a>";
    return $output;
  }

}
