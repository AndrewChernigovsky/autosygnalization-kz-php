<?php
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';


$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();
$basePath = $variables->getBasePath();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$sections_path = $docROOT . $path . '/files/php/helpers/include-sections.php';
$base_path = $docROOT . $path . '/files/php/layout';
$social = $basePath . '/files/php/helpers/components/social.php';
$contacts_data = $basePath . '/files/php/data/contacts.php';

include_once $head_path;
include_once $sections_path;
include_once $contacts_data;
include_once $social;

$title = 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова';
$head = new Head($title, [], []);


$contacts = new Contacts(); /* Создаем экземпляр Contacts */
$socialIcons = $contacts->getSocialIcons();
$email = $contacts->getEmail(true);
$address = $contacts->getAddress();
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
        <h2 class="contacts-section__title">Контакты</h2>
        <div class="contacts-section__contacts">
          <div class="contacts-section__contact">
            <p class="contacts-section__contact--title">Beeline:</p>
            <p class="contacts-section__contact--phone">
              <a href="tel:+77077478212">+77077478212</a>
            </p>
          </div>
          <div class="contacts-section__contact">
            <p class="contacts-section__contact--title">Kcell:</p>
            <p class="contacts-section__contact--phone">
              <a href="tel:+77077478212">+77077478212</a>
            </p>
          </div>
          <div class="contacts-section__contact">
            <p class="contacts-section__contact--whatsapp">Whatsapp:</p>
            <p class="contacts-section__contact--phone">
              <a href="https://wa.me/77077478212" target="_blank">+77077478212</a>
            </p>
          </div>
          <div class="contacts-section__contact">
            <p class="contacts-section__contact--email">Почта:</p>
            <p><?php echo $email ?></p>
          </div>
          <div class="contacts-section__contact">
            <p class="contacts-section__contact--address-title">Адрес:</p>
            <p class="contacts-section__contact--address-text"><?php echo $address ?></p>
          </div>
          <div class="contacts-section__contact">
              <p class="contacts-section__contact--schedule">График работы:</p>
              <div class="contacts-section__contact--work-time">
                <p>Вс. - Чт.: 10:00 - 18:00</p>
                <p>Пт.: 10:00-15:00</p>
                <p>Сб.: Выходной</p>
            </div>
          </div>
          <div class="contacts-section__contact">
            <p class="contacts-section__contact--social">Соцсети:</p>
            <div class="contacts-section__contact--social-icons">
              <?php
              foreach ($socialIcons as $social) {
                echo $contacts->setSocial($social);
              }
              ?>
            </div>
          </div>
          <div class="contacts-section__contact">
            <button type="button" class="button y-button-primary" id="print-btn">Распечатать контакты</button>
          </div>
        </div>
      </div>
      <div class="map">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1453.4679397503296!2d76.8722813!3d43.231804!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3883693b733bff39%3A0x716633e11986b3f8!2sAuto%20Security!5e0!3m2!1sru!2sru!4v1735233649305!5m2!1sru!2sru"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
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