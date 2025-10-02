<?php

namespace LAYOUT;

use DATA\ContactsData;
use COMPONENTS\Cart;
use COMPONENTS\Logo;
use DATA\NavigationLinks;

class Header
{
  private $currentPath;

  public function __construct()
  {
    $this->currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  }

  public function getHeader(): string
  {
    if (session_status() === PHP_SESSION_NONE) {
      // Настройка session cookie (гарантированно удаляется при закрытии браузера)
      session_set_cookie_params([
        'lifetime' => 0, // Session cookie - удаляется при закрытии
        'path' => '/',
        'secure' => false, // Для localhost
        'httponly' => true,
        'samesite' => 'Lax'
      ]);
      
      // Устанавливаем время жизни сессии
      ini_set('session.gc_maxlifetime', 0); // Удаляется при закрытии браузера
      
      session_start();
      
      // Принудительная очистка старых файлов сессий (только файлы, не текущую сессию)
      $this->cleanOldSessionFiles();
    }
    
    // Инициализация компонентов
    $logo = new Logo();
    $cart = new Cart();
    $contacts = new ContactsData();
    $phones = $contacts->getPhones();
    $contactData = $contacts->getAddress();
    $address = $contactData[0]['address'];
    $addressLink = $contactData[0]['link'];
    $addressSvgPath = $contactData[0]['svg_path'];


    $navigationLinks = new NavigationLinks();

    // Генерация HTML
    return <<<HTML
<header class="header">
    <div class="container">
        <div class="header__head">
            <div class="header__menu">
                {$logo->getLogo()}
                <nav class="nav">
                    <ul class="nav-list list-type-none">
                        {$this->generateNavigationLinks($navigationLinks->getNavlinks())}
                    </ul>
                </nav>
                <div class="header__contacts">
                    <div class="header__instagram">
                        <a href="https://www.instagram.com/autosecurity_kz" class="header__instagram-image link">
                            <div class="header__image image">
                                <svg width="50" height="50">
                                    <use href="/client/vectors/sprite.svg#instagramm-icon" fill="currentColor"></use>
                                </svg>
                            </div>
                        </a>
                        <address>
                            {$this->generatePhoneLinks($phones)}
                        </address>
                    </div>
                <a href="https://2gis.kz/almaty/geo/70000001027313872" class="link geo-address hidden-header-geo">
                    <div class="header__image image">
                        <svg width="50" height="50">
                            <use href="/client/vectors/sprite.svg#geo"></use>
                        </svg>
                    </div>
                </a>
                    {$cart->initCart()}
                    <div class="menu-toggle">
                        <button type="button" id="btn-open-menu" class="button">
                            <span class="visually-hidden">Открыть меню</span>
                        </button>
                    </div>
                </div>
                <div class="menu-btns">
                    <div class="search">
                        <input type="search" placeholder="Поиск..." name="Поиск" id="search-input" />
                    </div>
                </div>
                <div class="phone">
                    <svg width="50" height="50">
                        <use href="/client/vectors/sprite.svg#phone"></use>
                    </svg>
                    <ul>
                        {$this->generatePhoneLinks($phones)}
                    </ul>
                </div>
                <a href="{$addressLink}" class="link geo-address" id="geoAddress">
                    <div class="header__image image">
                        <svg width="50" height="50">
                            <use href="$addressSvgPath"></use>
                        </svg>
                    </div>
                    <div>
                        {$address}
                    </div>
                </a>
            </div>
        </div>
    </div>
</header>
HTML;
  }

  private function cleanOldSessionFiles(): void
  {
    // Очищаем только старые файлы сессий, НЕ текущую активную сессию
    $sessionPath = ini_get('session.save_path') ?: sys_get_temp_dir();
    $maxLifetime = 1440; // 24 минуты
    
    if (is_dir($sessionPath)) {
      $currentSessionId = session_id();
      $files = glob($sessionPath . '/sess_*');
      $currentTime = time();
      
      foreach ($files as $file) {
        if (is_file($file)) {
          $fileName = basename($file);
          $fileSessionId = str_replace('sess_', '', $fileName);
          
          // НЕ удаляем текущую активную сессию
          if ($fileSessionId !== $currentSessionId) {
            $fileTime = filemtime($file);
            if (($currentTime - $fileTime) > $maxLifetime) {
              unlink($file); // Удаляем только старые файлы
            }
          }
        }
      }
    }
  }

  private function generateNavigationLinks($links): string
  {
    $html = '';
    foreach ($links as $link) {
      $uniqueId = 'link_' . preg_replace('/[\/?=&]/', '_', $link['path']);
      $isActive = $this->isActive($link['path'], $this->currentPath);
      
      // Убираем HTML теги из контента
      $cleanContent = isset($link['content']) ? strip_tags($link['content']) : '';
      
      // Определяем текст для отображения: content или fallback на name
      $displayText = !empty($cleanContent) ? $cleanContent : $link['name'];
      
      // Если есть контент, добавляем name в title, иначе оставляем пустым
      $titleText = !empty($cleanContent) ? $link['name'] : '';
      $titleAttr = !empty($titleText) ? " title='" . htmlspecialchars($titleText) . "'" : '';
      
      $html .= "<li class='nav-item'>
                        <a class='link {$isActive}' href='" . htmlspecialchars($link['path']) . "' id='" . htmlspecialchars($uniqueId) . "'{$titleAttr}>" . htmlspecialchars($displayText) . "</a>
                      </li>";
    }
    return $html;
  }

  private function generatePhoneLinks($phones): string
  {
    $html = '';
    if (!empty($phones)) {
      foreach ($phones as $phone) {
        // Убираем HTML теги из номера телефона
        $cleanedPhoneText = strip_tags($phone['phone']);
        // Убираем пробелы для tel: ссылки
        $cleanedPhoneLink = str_replace(' ', '', $cleanedPhoneText);
        $html .= "<li><a href='tel:" . htmlspecialchars($cleanedPhoneLink) . "'>" . htmlspecialchars($cleanedPhoneText) . "</a></li>";
      }
    }
    return $html;
  }

  private function isActive($linkPath, $currentPath): string
  {
    return $linkPath === $currentPath ? 'active' : '';
  }
}
