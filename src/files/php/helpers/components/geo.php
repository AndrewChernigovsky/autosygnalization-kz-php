<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../helpers/classes/createSVG.php';
include_once __DIR__ . '/../../data/contacts.php';

class GEO
{
  private $variables;
  private $insertSVG;
  private $address = "https://maps.app.goo.gl/72eQCZUbxVCKh43PA";
  private $contacts;

  public function __construct()
  {
    $this->variables = new SetVariables();
    $this->variables->setVar();
    $this->insertSVG = new CreateSVG();
    $this->contacts = new Contacts();
  }

  public function getGeo()
  {
    $social = $this->contacts->getSocial();
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

?>