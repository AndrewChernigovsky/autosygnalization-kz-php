<?php
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$sections_path = $docROOT . $path . '/files/php/helpers/include-sections.php';
include_once $head_path;
include_once $sections_path;
$base_path = $docROOT . $path . '/files/php/layout';

$title = 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова';
$head = new Head($title, [], []);
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include $base_path . '/header.php'; ?>
  <main class="main">
    <section class="contacts-section">
      <div class="container">
        <h2>Контакты</h2>
        <div class="contacts-section__contacts">
          <div class="contacts-section__contact">
            <p>Beeline:</p>
            <p>+77077478212</p>
          </div>
          <div class="contacts-section__contact">
            <p>Kcell:</p>
            <p>+77017478212</p>
          </div>
          <div class="contacts-section__contact">
            <p>Whatsapp:</p>
            <p>+77077478212</p>
          </div>
          <div class="contacts-section__contact">
            <p>Почта:</p>
            <p>autosecurity.kz@mail.ru</p>
          </div>
          <div class="contacts-section__contact">
            <p>Адрес:</p>
            <p>Казахстан, г.Алматы, Абая 145/г, бокс №15</p>
          </div>
          <div class="contacts-section__contact">
            <p>График работы:</p>
            <p>Вс. - Чт.: 10:00 - 18:00</p>
            <p>Пт.: 10:00-15:00</p>
            <p>Сб.: Выходной</p>
          </div>
          <div class="contacts-section__contact">
            <p>Соцсети:</p>
          </div>
          <div class="contacts-section__contact">
            <button type="button" class="button y-button-primary" id="print-btn">Распечатать контакты</button>
          </div>
        </div>
        <div class="map">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1453.4679397503296!2d76.8722813!3d43.231804!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3883693b733bff39%3A0x716633e11986b3f8!2sAuto%20Security!5e0!3m2!1sru!2sru!4v1735233649305!5m2!1sru!2sru"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
      <section class="contacts-section-info">
        <div class="container">
          <h2>КАК К НАМ ДОБРАТЬСЯ</h2>
          <p>Едем по Абая со стороны Мате Залка в сторону Большой Алматинки, перед речкой поворот направо, заезжаем на
            территорию СТО. Наш бокс №15.</p>
          <p>
            По всем вопросам звоните:
            <a href="tel:+77077478212">+7 707 747-82-12</a>
            <a href="tel:+77017478212">+7 707 701-82-12</a>
          </p>
          <p>БУДЕМ РАДЫ ВИДЕТЬ ВАС В НАШЕМ УСТАНОВОЧНОМ ЦЕНТРЕ!</p>
        </div>
      </section>
    </section>
  </main>
  <?php include $base_path . '/footer.php'; ?>
</body>

</html>