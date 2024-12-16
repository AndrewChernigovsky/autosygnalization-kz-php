<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../data/contacts.php';
class Logo
{
  private $variables;
  private $icon;

  public function __construct()
  {
    $this->variables = new SetVariables();
    $this->variables->setVar();
    $this->icon = new Contacts();
  }

  public function getLogo()
  {
    $logo = $this->icon->getLogo();
    $logoIcon = isset($logo['icon']) ? $logo['icon'] : null;
    $logo_description = $logo['description'];

    $logo_path = $this->variables->getPathFileURL() . $logoIcon;

    if ($logoIcon === null) {
      return "<p>Логотип-иконка не найдена.</p>";
    }

    $output = "
            <a href='/' class='logo'>
                <img src='" . htmlspecialchars($logo_path) . "' alt='" . htmlspecialchars($logo_description) . "' width='142' height='40'/>
            </a>";

    return $output;
  }
}
?>