<?php

namespace DATA;

use DATABASE\DataBase;

class AboutusData extends DataBase
{
  protected $pdo;

  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }

  public function getAllAboutUs(array $types = [])
  {
    try {
      if (empty($types)) {
        $query = "
                    SELECT type, title, content, image_path, position 
                    FROM AboutUs 
                    ORDER BY 
                        FIELD(type, 'present-slogan-block', 'present-text-block', 'advantages-list', 'comment-block', 'appeal-text-block', 'tech-photo-image'), 
                        position ASC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
      } else {
        $placeholders = [];
        $params = [];
        foreach ($types as $index => $type) {
          $ph = ":type$index";
          $placeholders[] = $ph;
          $params[$ph] = $type;
        }

        $inClause = implode(', ', $placeholders);

        $fieldList = implode(', ', array_map(function ($t) {
          return $this->pdo->quote($t);
        }, $types));

        $query = "
                    SELECT type, title, content, image_path, position
                    FROM AboutUs
                    WHERE type IN ($inClause)
                    ORDER BY FIELD(type, $fieldList)
                ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
      }

      $itemArr = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      if (empty($itemArr)) {
        return [['type' => 'none', 'title' => 'none', 'content' => 'none', 'image_path' => '/', 'position' => 0]];
      }

      return $itemArr;

    } catch (\Exception $e) {
      error_log("Ошибка получения данных: " . $e->getMessage());
      return [['type' => 'none', 'title' => 'none', 'content' => 'none', 'image_path' => '/', 'position' => 0]];
    }
  }


  public function getDataImages()
  {
    $imagesAboutUs = [
      ["src" => "/client/images/about_us/about_1.avif"],
      ["src" => "/client/images/about_us/about_2.avif"],
      ["src" => "/client/images/about_us/about_3.avif"],
    ];
    return $imagesAboutUs;
  }
  public function getData()
  {
    $reviewsAboutUs = [
      [
        'id' => 1,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Карина К",
        "model" => "",
        "date" => "3 недели назад",
        "rate" => "5",
        "text" => "Сервис на высшем уровне. <br> Порекомендовали что лучше, установили очень быстро и все доступно объяснили как пользоваться. <br> Спасибо Алексею. Однозначно рекомендую.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 2,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Sergei Kim",
        "model" => "",
        "date" => "1 месяц назад",
        "rate" => "5",
        "text" => "Отличный сервис, качественная работа!",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 3,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Vas Li",
        "model" => "",
        "date" => "1 год назад",
        "rate" => "5",
        "text" => "Отличные мастера по Сигнализациям и Русификации. Василий, Антон так держать!",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 4,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Rem.photographer",
        "model" => "",
        "date" => "2 года назад",
        "rate" => "5",
        "text" => "Установщик Василий лучший.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 5,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Ildar Galiyev",
        "model" => "",
        "date" => "2 года назад",
        "rate" => "5",
        "text" => "Highly recommended.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 6,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Ерсултан Сонгулов",
        "model" => "",
        "date" => "2 года назад",
        "rate" => "4",
        "text" => "Все хорошо.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 7,
        "src" => "/client/images/about_us/reviews/review_1.avif",
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
        'id' => 8,
        "src" => "/client/images/about_us/reviews/review_1.avif",
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
        'id' => 9,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Бахтияр",
        "model" => "Кia picanto 2005 г.",
        "date" => "06.11.2022",
        "rate" => "5",
        "text" => "Спасибо большое за работу, все понравилось, <br> установили все хорошо, <br> специалист Алексей все рассказал, объяснил как пользоваться сигнализацией, <br> профессионал своего дела. Всем рекомендую.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 10,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Volk Seriy",
        "model" => "",
        "date" => "3 года назад",
        "rate" => "5",
        "text" => "Идеальный сервис можна ченится",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 11,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "FoSko",
        "model" => "",
        "date" => "09.11.2021",
        "rate" => "5",
        "text" => "Спасибо.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 12,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Ayana Karimbayeva",
        "model" => "",
        "date" => "4 года назад",
        "rate" => "5",
        "text" => "Наконец я нашла это место, где мне разблокировали мою магнитолу ура ура ура, <br> у кого будут такие проблемы с блокировкой магнитолы в машине,может е смело обращаться.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 13,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Исмаилов Руслан",
        "model" => "Тойота ярис",
        "date" => "07.06.2020",
        "rate" => "5",
        "text" => "Очень благодарен за проделанную работую обратился с проблемой замены камеры заднего вида <br> (до этого сам пытался заменить не работало) <br> но как только профессионал взялся за дело все само заработало.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 14,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "baitik Dosoev",
        "model" => "Honda akord",
        "date" => "07.05.2020",
        "rate" => "5",
        "text" => "Отличная работа, все сделано на уровне, <br> сделали подарок центральный замок отремонтировали, <br> и по времени заняло не больше Полтора часа !!! <br> Отличный мастер и подскажет что да как!",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 15,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Огородников Илья",
        "model" => "Toyota Sequoia",
        "date" => "13.04.2020",
        "rate" => "5",
        "text" => "Уже на протяжении многих лет, <br> как только сталкиваюсь с необходимостью обеспечения безопасности своего транспортного средства, <br> не задумываясь направляюсь в сервис Auto Security.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 16,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Черников Игорь",
        "model" => "Мерседес",
        "date" => "13.04.2020",
        "rate" => "5",
        "text" => "Замечательный сервис! <br> Не раз обращались. Все во время и качественно! Спасибо!",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 17,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Жеенбаев Кубат",
        "model" => "Тойота камри",
        "date" => "12.04.2020",
        "rate" => "5",
        "text" => "Спасибо Алексей, отличная работа!!! Я доволен.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 18,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Igor",
        "model" => "Civic",
        "date" => "10.04.2020",
        "rate" => "5",
        "text" => "Профессионал своего дела, я лично очень доволен сервисом!",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 19,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Тихонов Сергей Валерьевич",
        "model" => "Субару Аутбэк",
        "date" => "09.04.2020",
        "rate" => "5",
        "text" => "Отличные ребята, знающие своё дело!",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 20,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Петр Петрович",
        "model" => "Тойта Ист",
        "date" => "09.04.2020",
        "rate" => "5",
        "text" => "Добрый день. Устанавливали сигнализацию StarLine A93 на Toyota Ist 2003 г. <br> Установили качественно, быстро. <br> Всё настроили и объяснили, все работает. <br> Мы с женой очень довольны и благодарны, спасибо ребятам!",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 21,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Ахматов",
        "model" => "Субару Аутбек 2011 г",
        "date" => "23.03.2020",
        "rate" => "5",
        "text" => "За многие годы моего водительского стажа видел много автоэлектриков. <br> Хочу отметить хорошую и профессиональную работу Алексея и его напарника. Сделали всё на высшем уровне! Остался доволен.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 22,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Arizjan Sidikov",
        "model" => "",
        "date" => "04.03.2020",
        "rate" => "5",
        "text" => "Все делают умно и на совесть! Молодцы, мне нравится...",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 23,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Шухрат Муминов",
        "model" => "",
        "date" => "04.03.2020",
        "rate" => "5",
        "text" => "Разные виды сигнализаций. Устанавливают качественно и профессионально. <br> Им можно довериться. <br> Starline - советую именно эту сигнализацию, очень комфортная, и она у них есть в наличии.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 24,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Светлана О. В.",
        "model" => "Toyota Prius 30. StarLine A93",
        "date" => "15.04.2019",
        "rate" => "5",
        "text" => "Понравился подход, все по делу и без лишней суеты. Установку произвели качественно, <br> все разъяснили и рассказали. <br> Спасибо Алексею за отличную работу.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 25,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Мерседес Киа",
        "model" => "",
        "date" => "6 лет назад",
        "rate" => "5",
        "text" => "Разные виды сигнализаций. Устанавливают качественно и профессионально. <br> Им можно довериться. <br> Magicar - советую именно эту сигнализацию очень комфортная, и она у них есть. <br> Располагается прям на стыке шаляпина и сатпаева рядом с азс роял петрол.",
        "admin" => [
          "date" => "",
          "name" => "Администратор",
          "text" => ""
        ]
      ],
      [
        'id' => 26,
        "src" => "/client/images/about_us/reviews/review_1.avif",
        "name" => "Yarmukhamed Zhumassayev",
        "model" => "",
        "date" => "7 лет назад",
        "rate" => "5",
        "text" => "Хороший сервис по установке автосигнализаций (оригинал), автомагнитол и прочих аксессуаров для Вашего железного коня.",
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
