<?php

namespace LAYOUT;

use DATA\ContactsData;
use COMPONENTS\GEO;
use COMPONENTS\Logo;
use COMPONENTS\InsertPhone;
use HELPERS\GenerateFooterLinks;
use DATA\NavigationLinks;

class Footer
{
  public function getFooter(): string
  {
    // Инициализация компонентов
    $geo = new GEO();
    $logo = new Logo();
    $contacts = new ContactsData();
    $phones = $contacts->getPhones();
    $email = $contacts->getEmail(true);
    $web_site = $contacts->getWebsite(true);
    $socialIcons = $contacts->getSocialIcons();

    $insertPHONE = new InsertPhone();
    $icon_phone = [
      'name' => 'icon_phone',
      'width' => '50',
      'height' => '50',
      "image" => "/client/vectors/sprite.svg#phone-no-border",
      'href' => '#'
    ];

    $navigationFooterLinks = new GenerateFooterLinks((new NavigationLinks())->getNavigationFooterLinks());
    $phones_footer = $insertPHONE->displayPhones($phones, $icon_phone);
    $date = date('Y');

    // Генерация HTML
    return <<<HTML
<footer class="footer">
  <div class="footer__wrapper">
    <div class="container">
      <div class="footer__inner">
        <div class="footer__contacts">
          {$logo->getLogo()}
          <div class="social">
            <p>Instagram</p>
            <!-- <ul class="social__icons list-style-none">
              {$this->generateSocialIcons($socialIcons, $contacts)}
            </ul> -->
          </div>
          <div class="phones">
            {$phones_footer}
          </div>
          <div class="email-site">
            <div class="email">{$email}</div>
            <div class="site">{$web_site}</div>
          </div>
          {$geo->getGeo()}
        </div>
        <div class="footer__menu">
          {$navigationFooterLinks->generateFooter()}
        </div>
      </div>
    </div>
    <p class="footer__copy">© {$date} Auto Security. Все права защищены</p>
  </div>
</footer>
HTML;
  }

  private function generateSocialIcons($socialIcons, $contacts): string
  {
    $html = '';
    foreach ($socialIcons as $social) {
      $html .= '<li>' . $contacts->setSocial($social) . '</li>';
    }
    return $html;
  }
}
