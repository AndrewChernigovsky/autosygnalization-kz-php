<?php

namespace LAYOUT;

include_once __DIR__ . '/../data/navigation-links.php';

use DATA\Contacts;
use COMPONENTS\GEO;
use COMPONENTS\Logo;
use COMPONENTS\InsertPhone;
use HELPERS\GenerateFooterLinks;
use DATA\NavigationLinks;

class Footer
{
    private $basePath;
    private $path;
    private $docROOT;

    public function __construct($basePath, $path, $docROOT)
    {
        $this->basePath = $basePath;
        $this->path = $path;
        $this->docROOT = $docROOT;
    }

    public function setHead(): string
    {
        // Инициализация компонентов
        $geo = new GEO();
        $logo = new Logo();
        $contacts = new Contacts();
        $phones = $contacts->getPhones();
        $email = $contacts->getEmail(true);
        $web_site = $contacts->getWebsite(true);
        $socialIcons = $contacts->getSocialIcons();

        $insertPHONE = new InsertPhone();
        $icon_phone = [
            'name' => 'icon_phone',
            'width' => '50',
            'height' => '50',
            "image" => "/client/images/vectors/sprite.svg#phone-no-border",
            'href' => '#'
        ];

        $navigationFooterLinks = new GenerateFooterLinks((new NavigationLinks())->getNavigationFooterLinks());

        // Генерация HTML
        return <<<HTML
<footer class="footer">
  <div class="footer__wrapper">
    <div class="container">
      <div class="footer__inner">
        <div class="footer__contacts">
          {$logo->getLogo()}
          <div class="social">
            <p>Социальные сети</p>
            <ul class="social__icons list-style-none">
              {$this->generateSocialIcons($socialIcons, $contacts)}
            </ul>
          </div>
          <div class="phones">
            {$insertPHONE->displayPhones($phones, $icon_phone)}
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
    <p class="footer__copy">© 2024 Auto Security. Все права защищены</p>
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
