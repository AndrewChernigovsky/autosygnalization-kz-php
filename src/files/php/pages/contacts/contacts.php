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
include_once $social;
include_once $contacts_data;


$title = 'Контакты | Auto Security';
$head = new Head($title, [], []);


$contacts = new Contacts(); /* Создаем экземпляр Contacts */
$socialIcons = $contacts->getSocialIcons();
$email = $contacts->getEmail(true);
$address = $contacts->getAddress();
$phones = $contacts->getPhones();
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
        <ul class="contacts-section__list">
          <li class="contacts-section__item">
            <a href="tel:+77077478212">
              <h3>Beeline:</h3>
              <p>+77077478212</p>
            </a>
          </li>
          <li class="contacts-section__item">
            <a href="tel:+77017478212">
              <h3>Kcell:</h3>
              <p>+77017478212</p>
            </a>
          </li>
          <li class=" contacts-section__item contacts-section__item--whatsap">
            <a href="https://wa.me/77077478212" target="_blank">
              <h3>Whatsapp:</h3>
              <p>+77017478212</p>
            </a>
          </li>
          <li class="contacts-section__item contacts-section__item--email">
            <h3>Почта:</h3>
            <p><?php echo $email ?></p>
          </li>
          <li class="contacts-section__item contacts-section__item--address">
            <h3>Адрес:</h3>
            <p><?php echo $address ?></p>
          </li>
          <li class="contacts-section__item contacts-section__item--schedule">
            <h3>График работы:</h3>
              <p>Вс. - Чт.: 10:00 - 18:00 <br>
                Пт.: 10:00-15:00 <br>
                <span>Сб.: Выходной</span>
              </p>
          </li>
          <li class="contacts-section__item contacts-section__item--social">
            <h3>Соцсети:</h3>
            <ul class="contacts-section__item--social-icons">
              <?php
              foreach ($socialIcons as $social) {
                echo '<li>';
                  echo $contacts->setSocial($social);
                echo '</li>';
              }
              ?>
            </ul>
          </li>
          <li class="contacts-section__item contacts-section__item--btn">
            <button type="button" class="button y-button-primary" id="print-btn">Распечатать контакты</button>
          </li>
        </ul>
      </div>
      <div class="map">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1453.4679397503296!2d76.8722813!3d43.231804!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3883693b733bff39%3A0x716633e11986b3f8!2sAuto%20Security!5e0!3m2!1sru!2sru!4v1735233649305!5m2!1sru!2sru"
          width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </section>
    <section class="contacts-location">
      <div class="container">
        <h2>КАК К НАМ ДОБРАТЬСЯ</h2>
        <p>Едем по Абая со стороны Мате Залка в сторону Большой Алматинки, <br> перед речкой поворот направо, заезжаем на
          территорию СТО. <br> Наш бокс №15.
        </p>
        <div class="contacts-location__phone">
          <p>По всем вопросам звоните:</p>
          <div class="contacts-location__box">
            <?php foreach($phones as $phone): ?>
              <a href="tel:<?php echo str_replace(' ', '', $phone['phone']); ?>">
                <?php echo $phone['phone']; ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
        <p class="contacts-location__slogan">БУДЕМ РАДЫ ВИДЕТЬ ВАС В НАШЕМ УСТАНОВОЧНОМ ЦЕНТРЕ!</p>
      </div>
    </section>
  </main>
  <?php include $base_path . '/footer.php'; ?>
</body>
</html>