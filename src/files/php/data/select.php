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
        "name" => 'название',
        "value" => 'name',
      ],
      [
        "name" => 'цена',
        "value" => 'price',
      ],
      [
        "name" => 'дата',
        "value" => 'date',
      ],
      [
        "name" => 'популярность',
        "value" => 'popularity',
      ],
      [
        "name" => 'предустановленная',
        "value" => 'preset',
      ],
    ];

    return $data;

  }
}

?>
