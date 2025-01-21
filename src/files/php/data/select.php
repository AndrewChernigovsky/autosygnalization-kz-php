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
        "class" => 'select-item',
      ],
      [
        "name" => 'Цена',
        "value" => 'price',
        "class" => 'select-item',
      ],
      [
        "name" => 'Дата',
        "value" => 'date',
        "class" => 'select-item',
      ],
      [
        "name" => 'Популярность',
        "value" => 'popularity',
        "class" => 'select-item',
      ],
      [
        "name" => 'Предустановленная',
        "value" => 'preset',
        "class" => 'select-item default',
      ],
    ];

    return $data;

  }
}

?>
