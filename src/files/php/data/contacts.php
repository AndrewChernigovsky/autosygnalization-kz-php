<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';
include_once __DIR__ . '/../helpers/components/social.php';
include_once __DIR__ . '/../helpers/classes/createSVG.php';

class Contacts
{
  private $variables;
  private $insertSVG;

  private $path;

  private $logo_description = 'Логотип Компании Auto Security';
  private $phones = [
    ['phone' => '+7 707 747 8212'],
    ['phone' => '+7 701 747 8212'],
  ];

  private $logo = '/assets/images/logo.avif';
  private $address = "Казахстан, г.Алматы, ул.Абая 145/г, бокс №15";
  private $email = "autosecurity.kz@mail.ru";
  private $web_site = "www.autosecurity.kz";

  public function __construct()
  {
    $this->variables = new SetVariables();
    $this->variables->setVar();
    $this->insertSVG = new CreateSVG();
    $this->path = $this->variables->getPathFileURL();
  }
  public function getSocialIcons()
  {
    return [
      "instagramm" => ['name' => 'Instagram', 'width' => '50', 'height' => '50', "image" => $this->path . '/assets/images/vectors/sprite.svg#instagramm-icon', 'href' => "https://www.instagram.com/autosecurity_kz"],
    ];
  }
  public function getGeoIcon()
  {
    return [
      "geo" => ['name' => 'geo', 'width' => '50', 'height' => '50', "image" => $this->path . '/assets/images/vectors/sprite.svg#geo', 'href' => 'href="https://maps.app.goo.gl/72eQCZUbxVCKh43PA"'],
    ];
  }

  public function getEmail($link = false)
  {
    if ($link) {
      $output = '';
      $path = htmlspecialchars($this->variables->getPathFileURL() . '/assets/images/vectors/message-icon.svg');
      $output .= "<a class='link' style='background-image: url(\"$path\")' href='mailto:autosecurity.site@mail.ru'>" . htmlspecialchars($this->email) . '</a>';
      return $output;
    } else {
      return htmlspecialchars($this->email);
    }
  }
  public function getPhones()
  {
    return $this->phones;
  }
  public function getWebsite($link = false)
  {
    if ($link) {
      $output = '';
      $path = htmlspecialchars($this->variables->getPathFileURL() . '/assets/images/vectors/home-icon.svg');
      $output .= "<a class='link' style='background-image: url(\"$path\");' href='http://autosecurity.site'>" . htmlspecialchars($this->web_site) . '</a>'; // Используйте экранирование для URL
      return $output;
    } else {
      return htmlspecialchars($this->web_site);
    }
  }
  public function getAddress()
  {
    return htmlspecialchars($this->address);
  }
  public function getLogo()
  {
    return [
      "icon" => $this->logo,
      "description" => $this->logo_description
    ];
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





?>