<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$requestUri = $_SERVER['REQUEST_URI'];
$currentUrl = $protocol . $host . $requestUri;

$distPath = $_SERVER['DOCUMENT_ROOT'] . '/dist';

// Проверяем, существует ли папка dist
if (is_dir($distPath)) {
  $currentUrl = "http://localhost:3000/dist/index.php";
  $pathFile = "http://localhost:3000/dist";
  $pathFile_URL = '/dist';
} else {
  $currentUrl = "/index.php";
  $pathFile = "";
  $pathFile_URL = '';
}

$link1 = $currentUrl . '#advantages';
$link2 = $currentUrl . '#quality';
$link3 = $currentUrl . '#tarifs';
$link4 = $currentUrl . '#prices';
$link5 = $currentUrl . '#reasons';
$link6 = $currentUrl . '#about';
$link2form = $currentUrl . '#form';

$logo = $pathFile_URL . '/assets/images/logo.avif';
$phone = '+7 953 232 21 12';
?>

<header class="header">
  <div class="container">
    <div class="header__wrapper">
      <div class="header__inner">
        <button class="header__menu-btn" type="button" id="btn-open-menu"><span class="visually-hidden">Открыть
            окно</span></button>
        <a class='tel' href="tel:<?php echo str_replace(' ', '', $phone) ?>"><?php echo $phone ?></a>
        <img src="<?php echo $logo; ?>" alt="логотип академии Андрея Андреевича Изосимова" width="50" height="50">
      </div>

      <div class="header__intro">
        <span class="add-text">Хостинг на год в подарок</span>
        <div class="logo">
          <img src="<?php echo $logo; ?>" alt="логотип академии Андрея Андреевича Изосимова" width="100" height="100">
          <a class="tel" href="tel:<?php echo str_replace(' ', '', $phone) ?>"><?php echo $phone ?></a>
          <nav class="nav">
            <ul class="nav__list list-style-none ">
              <li class="nav__item"><a href="<?php echo $link1 ?>">Преимущества</a></li>
              <li class="nav__item"><a href="<?php echo $link2 ?>">Гарантия</a></li>
              <li class="nav__item"><a href="<?php echo $link3 ?>">Обо мне</a></li>
              <li class="nav__item"><a href="<?php echo $link4 ?>">Тарифы</a></li>
              <li class="nav__item"><a href="<?php echo $link5 ?>">Цены</a></li>
              <li class="nav__item"><a href="<?php echo $link6 ?>">Почему Я</a></li>
            </ul>
          </nav>
        </div>
        <a href="<?php echo $link2form ?>" class="add-text">ЗАКАЗАТЬ САЙТ</a>
      </div>
    </div>
  </div>
</header>