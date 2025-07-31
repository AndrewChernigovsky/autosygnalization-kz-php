<?php

namespace COMPONENTS;

use COMPONENTS\InsertSVG;
use DATA\ContactsData;

class GEO
{
  private $insertSVG;
  private $contacts;

  public function __construct()
  {
    $this->insertSVG = new InsertSVG();
    $this->contacts = new ContactsData();
  }

  public function getGeo()
  {
    $contactData = $this->contacts->getAddress();
    $address = $contactData[0]['address'];
    $addressLink = $contactData[0]['link'];
    $addressSvgPath = $contactData[0]['svg_path'];
    $social = $this->contacts->getGeoIcon();
    $geoIcon = isset($social['geo']) ? $social['geo'] : null;
    if ($geoIcon === null) {
      return "<p>Гео-иконка не найдена.</p>";
    }

    // Формируем HTML-вывод
    $output = "
      <a href='{$addressLink}' class='geo link'>
          <div class='geo__wrapper menu-geo-phone'>";
    $output .= $this->insertSVG->insertSvg($geoIcon);
    $output .= '<span>' . $address . '</span>';
    $output .= "</div>
      </a>";

    return $output;
  }
}
