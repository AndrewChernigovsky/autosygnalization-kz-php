<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

class AboutusData
{
  private $path;

  public function __construct()
  {
    $variables = new SetVariables();
    $variables->setVar();
    $this->path = $variables->getPathFileURL();
  }

  public function getImagesAboutUs()
  {
    $imagesAboutUs = [
      ["src" => "{$this->path}/assets/images/about_us/about_1.avif"],
      ["src" => "{$this->path}/assets/images/about_us/about_2.avif"],
    ];
    return $imagesAboutUs;
  }
  public function getReviewsAboutUs()
  {
    $reviewsAboutUs = [
      [
        'id' => 1,
        "src" => "{$this->path}/assets/images/about_us/reviews/review_1.avif",
        "name" => "Денис",
        "model" => "Toyota Fortuner",
        "date" => "07.11.2022",
        "rate" => "5",
        "text" => "Быстро и качественно!",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 2,
        "src" => "{$this->path}/assets/images/about_us/reviews/review_1.avif",
        "name" => "Ильдар",
        "model" => "Nissan X-Trail",
        "date" => "06.11.2022",
        "rate" => "5",
        "text" => "В кратчайшие сроки и очень качественно был установлен видеорегистратор со скрытой проводкой.<br/>Все быстро, точно в оговорённые сроки и очень качественно.<br/>Благодарю, Алексей.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 3,
        "src" => "{$this->path}/assets/images/about_us/reviews/review_1.avif",
        "name" => "Денис",
        "model" => "Toyota Fortuner",
        "date" => "07.11.2022",
        "rate" => "5",
        "text" => "Быстро и качественно!",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 4,
        "src" => "{$this->path}/assets/images/about_us/reviews/review_1.avif",
        "name" => "Денис",
        "model" => "Toyota Fortuner",
        "date" => "07.11.2022",
        "rate" => "5",
        "text" => "Быстро и качественно!",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
    ];
    return $reviewsAboutUs;
  }
}
?>