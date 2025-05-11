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
    // Инициализация компонентов
    $logo = new Logo();
    $cart = new Cart();
    $contacts = new ContactsData();
    $phones = $contacts->getPhones();
    $address = $contacts->getAddress();

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
                <a href="https://2gis.kz/almaty/geo/70000001027313872" class="link geo-address">
                    <div class="header__image image">
                        <svg width="50" height="50">
                            <use href="/client/vectors/sprite.svg#geo"></use>
                        </svg>
                    </div>
                    {$address}
                </a>
            </div>
        </div>
    </div>
</header>
HTML;
  }

  private function generateNavigationLinks($links): string
  {
    $html = '';
    foreach ($links as $link) {
      $uniqueId = 'link_' . preg_replace('/[\/?=&]/', '_', $link['path']);
      $isActive = $this->isActive($link['path'], $this->currentPath);
      $html .= "<li class='nav-item'>
                        <a class='link {$isActive}' href='" . htmlspecialchars($link['path']) . "' id='" . htmlspecialchars($uniqueId) . "'>" . htmlspecialchars($link['name']) . "</a>
                      </li>";
    }
    return $html;
  }

  private function generatePhoneLinks($phones): string
  {
    $html = '';
    if (!empty($phones)) {
      foreach ($phones as $phone) {
        $cleanedPhone = str_replace(' ', '', $phone['phone']);
        $html .= "<li><a href='tel:" . htmlspecialchars($cleanedPhone) . "'>" . htmlspecialchars($phone['phone']) . "</a></li>";
      }
    }
    return $html;
  }

  private function isActive($linkPath, $currentPath): string
  {
    return $linkPath === $currentPath ? 'active' : '';
  }
}
