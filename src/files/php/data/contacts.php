<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

class Contacts
{
  private $variables;

  private $logo_description = 'Логотип Компании Auto Security';
  private $phones = [
    ['phone' => '+7 707 747 8212'],
    ['phone' => '+7 701 747 8212'],
  ];

  private $logo = '/assets/images/logo.avif';
  private $address = "Казахстан, г.Алматы, ул.Абая 145/г, бокс №15";
  private $email = "autosecurity.kz@mail.ru";
  private $web_site = "www.autosecurity.kz";

  private function social($path)
  {
    return [
      "instagramm" => ['name' => 'Instagram', 'width' => '50', 'height' => '50', "image" => $path . '/assets/images/vectors/sprite.svg#instagramm-icon', 'href' => '#'],
      "geo" => ['name' => 'geo', 'width' => '50', 'height' => '50', "image" => $path . '/assets/images/vectors/sprite.svg#geo', 'href' => '#'],
    ];
  }

  public function __construct()
  {
    $this->variables = new SetVariables();
    $this->variables->setVar();
  }

  public function getSocial()
  {
    $path = $this->variables->getPathFileURL();

    return $this->social($path);
  }
  public function getEmail()
  {
    return $this->email;
  }
  public function getPhones()
  {
    return $this->phones;
  }
  public function getWebsite()
  {
    return $this->web_site;
  }
  public function getAddress()
  {
    return $this->address;
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
      "social" => $this->getSocial(),
    ];
  }
}





?>