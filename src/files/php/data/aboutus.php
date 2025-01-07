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
        "src" => "{$this->path}/assets/images/about_us/reviews/review_1.avif",
        "name" => "Денис",
        "model" => "Toyota Fortuner",
        "date" => "07.11.2022",
        "rate" => "5",
        "text" => "Быстро и качественно!"
      ],
      [
        "src" => "{$this->path}/assets/images/about_us/reviews/review_1.avif",
        "name" => "Денис",
        "model" => "Toyota Fortuner",
        "date" => "07.11.2022",
        "rate" => "5",
        "text" => "Быстро и качественно!"
      ],
      [
        "src" => "{$this->path}/assets/images/about_us/reviews/review_1.avif",
        "name" => "Денис",
        "model" => "Toyota Fortuner",
        "date" => "07.11.2022",
        "rate" => "5",
        "text" => "Быстро и качественно!"
      ],
      [
        "src" => "{$this->path}/assets/images/about_us/reviews/review_1.avif",
        "name" => "Денис",
        "model" => "Toyota Fortuner",
        "date" => "07.11.2022",
        "rate" => "5",
        "text" => "Быстро и качественно!"
      ],
    ];
    return $reviewsAboutUs;
  }
}
?>