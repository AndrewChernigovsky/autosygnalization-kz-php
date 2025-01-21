<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

class ArticleData
{

  private $variables;
  private $path;
  public function __construct()
  {

    $this->variables = new SetVariables();
    $this->variables->setVar();
    $this->path = $this->variables->getPathFileURL();
  }
  public function getArticleData()
  {

    $data = [
      [
        "title" => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОВ',
        "image" => "$this->path/assets/images/services/service-9.avif",
        "href" => "$this->path/files/php/pages/service/service.php?service=setup",
        "quantity" => 11,
        "alt" => "Описание"
      ],
      [
        "title" => 'КАТАЛОГ АВТОСИГНАЛИЗАЦИЙ STARLINE',
        "image" => "$this->path/assets/images/services/service-7.avif",
        "href" => "$this->path/files/php/pages/autosygnals/autosygnals.php?auto=catalog",
        "quantity" => 12,
        "alt" => "Описание"
      ],
    ];

    return $data;

  }
}
?>