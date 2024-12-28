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
        "title" => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОВ',
      ],
      [
        "title" => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОВ',
      ],
      [
        "title" => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОВ',
      ],
    ];

    return $data;

  }
}

?>