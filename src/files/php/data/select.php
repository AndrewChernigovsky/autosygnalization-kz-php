<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

class SelectData
{

  private $variables;
  private $path;
  public function __construct()
  {

    $this->variables = new SetVariables();
    $this->variables->setVar();
    $this->path = $this->variables->getPathFileURL();
  }
  public function getSelectData()
  {

    $data = [
      [
        "name" => 'Название',
        "value" => 'name',
        "class" => 'select-item default',
      ],
      [
        "name" => 'Цена',
        "value" => 'price',
        "class" => 'select-item',
      ],
    ];

    return $data;

  }
}

?>
