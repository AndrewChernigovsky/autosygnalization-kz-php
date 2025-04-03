<?php

namespace COMPONENTS;

use COMPONENTS\InsertSVG;
use DATA\ContactsData;

class GEO
{
    private $insertSVG;
    private $address = "https://2gis.kz/almaty/geo/70000001027313872";
    private $contacts;

    public function __construct()
    {
        $this->insertSVG = new InsertSVG();
        $this->contacts = new ContactsData();
    }

    public function getGeo()
    {
        $social = $this->contacts->getGeoIcon();
        $geoIcon = isset($social['geo']) ? $social['geo'] : null;
        if ($geoIcon === null) {
            return "<p>Гео-иконка не найдена.</p>";
        }

        // Формируем HTML-вывод
        $output = "
      <a href='{$this->address}' class='geo link'>
          <div class='geo__wrapper'>";
        $output .= $this->insertSVG->insertSvg($geoIcon);
        $output .= '<span>' . $this->contacts->getAddress() . '</span>';
        $output .= "</div>
      </a>";

        return $output;
    }
}
