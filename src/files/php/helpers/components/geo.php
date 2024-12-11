<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../helpers/classes/createSVG.php';
include_once __DIR__ . '/../../data/contacts.php';

class GEO
{
  private $variables;
  private $insertSVG;
  private $address = "https://maps.app.goo.gl/72eQCZUbxVCKh43PA";
  private $icon;

  public function __construct()
  {
    $this->variables = new SetVariables();
    $this->variables->setVar();
    $this->insertSVG = new CreateSVG();
    $this->icon = new Contacts();
  }

  public function getGeo()
  {
    $socialIcons = $this->icon->getSocial();
    $geoIcon = isset($socialIcons['geo']) ? $socialIcons['geo'] : null;
    if ($geoIcon === null) {
      return "<p>Гео-иконка не найдена.</p>";
    }

    // Формируем HTML-вывод
    $output = "
      <a href='{$this->address}' class='geo-image link'>
          <div class='image'>";
    $output .= $this->insertSVG->insertSvg($geoIcon);
    $output .= "</div>
      </a>";

    return $output;
  }
}

?>