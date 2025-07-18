<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use DATA\ContactsData;
use LAYOUT\Header;
use LAYOUT\Head;
use LAYOUT\Footer;
use COMPONENTS\ModalForm;

use function AUTH\SESSIONS\initSession;
use function FUNCTIONS\renderPhoneButton;

initSession();

$title = 'Контакты | Auto Security';
$head = new Head($title, [], []);
$header = new Header();
$footer = new Footer();

$contacts = new ContactsData();
$socialIcons = $contacts->getSocialIcons();
$email = $contacts->getEmail();
$address = $contacts->getAddress();
$phones = $contacts->getPhones();
$contactPhones = $contacts->getContactPhones();
$social = $contacts->getSocial();
$schedule = $contacts->getSchedule();
$map = $contacts->getMap();
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>

  <?= $header->getHeader(); ?>
  <main class="main">
    <section class="contacts-section">
      <div class="container">
        <h2>Контакты</h2>
        <ul class="contacts-section__list">
            <?php foreach ($contactPhones as $phone): ?>
              <li class="contacts-section__item">

                  <a href="<?php echo str_replace(' ', '', $phone['link']) ?>">
                  <div class="icon-container">                  
                    <svg width="25" height="25" aria-hidden="true"><use href="<?php echo str_replace(' ', '', $phone['svg_path']) ?>" width="25" height="25" aria-hidden="true"></use></svg>
                    <h3><?php echo str_replace(' ', '', $phone['title']) ?></h3>
                  </div>                  
                    <p><?php echo str_replace(' ', '', $phone['phone']) ?></p>
                  </a>
              </li>
            <?php endforeach; ?>
            <?php if (!empty($social)): ?>
              <li class="contacts-section__item contacts-section__item--whatsap">
                <a href="<?php echo str_replace(' ', '', $social[0]['link']) ?>">
                <div class="icon-container">       
                  <svg width="25" height="25" aria-hidden="true">
                    <use href="<?php echo str_replace(' ', '', $social[0]['svg_path']) ?>"></use>
                  </svg>
                  <h3><?php echo htmlspecialchars($social[0]['title']) ?></h3>
                </div>     
                  <p><?php echo htmlspecialchars($social[0]['content']) ?></p>
                </a>
              </li>
            <?php endif; ?>
            <?php if (!empty($email)): ?>
              <li class="contacts-section__item contacts-section__item--email">
                <a href="<?php echo str_replace(' ', '', $email[0]['link']) ?>">
                <div class="icon-container">      
                  <svg width="25" height="25" aria-hidden="true">
                    <use width="25" height="25" href="<?php echo str_replace(' ', '', $email[0]['svg_path']) ?>"></use>
                  </svg>
                  <h3><?php echo htmlspecialchars($email[0]['title']) ?></h3>
                </div>     
                  <p><?php echo htmlspecialchars($email[0]['email']) ?></p>
                </a>
              </li>
            <?php endif; ?>
            <?php if (!empty($address)): ?>
              <li class="contacts-section__item contacts-section__item--address">

                <a href="<?php echo str_replace(' ', '', $address[0]['link']) ?>">
                <div class="icon-container">  
                  <svg width="25" height="25" aria-hidden="true">
                    <use width="25" height="25" href="<?php echo str_replace(' ', '', $address[0]['svg_path']) ?>"></use>
                  </svg>
                  <h3><?php echo htmlspecialchars($address[0]['title']) ?></h3>
                </div>  
                  <p><?php echo $address[0]['address'] ?></p>
                </a>
              </li>
            <?php endif; ?>
            <?php if (!empty($schedule)): ?>
              <li class="contacts-section__item contacts-section__item--schedule">
              <div class="icon-container">  
                <svg width="25" height="25" aria-hidden="true">
                  <use width="25" height="25" href="<?php echo str_replace(' ', '', $schedule[0]['svg_path']) ?>"></use>
                </svg>
                <h3><?php echo htmlspecialchars($schedule[0]['title']) ?></h3>
              </div>  
                  <p><?php echo $schedule[0]['text'] ?></p>
              </li>
            <?php endif; ?>
            <?php if (!empty($social)): ?>
              <li class="contacts-section__item contacts-section__item--social">
                <a class="contacts-section__item--social-icons" href="<?php echo str_replace(' ', '', $social[1]['link']) ?>">
                  <h3><?php echo htmlspecialchars($social[1]['title']) ?></h3> 
                  <svg width="25" height="25" aria-hidden="true">
                      <use href="<?php echo str_replace(' ', '', $social[1]['svg_path']) ?>"></use>
                    </svg>
                </a>
              </li>
            <?php endif; ?>
            <li class="contacts-section__item contacts-section__item--btn">
              <button type="button" class="button y-button-primary" id="print-btn">Распечатать контакты</button>
            </li>
        </ul>
      </div>
      <div class="map" id="location">
            <?php if (!empty($map)): ?>
                <iframe
                  title='Map'
                  src="<?php echo str_replace(' ', '', $map[0]['link']) ?>"
                  width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
            <?php endif; ?>
      </div>
    </section>
    <section class="contacts-location">
      <div class="container">
        <h2>КАК К НАМ ДОБРАТЬСЯ</h2>
        <p>Едем по Абая со стороны Мате Залка в сторону Большой Алматинки, <br> перед речкой поворот направо, заезжаем
          на
          территорию СТО. <br> Наш бокс №15.
        </p>
        <div class="contacts-location__phone">
          <p>По всем вопросам звоните:</p>
          <div class="contacts-location__box">
            <?php foreach ($phones as $phone): ?>
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
  <?= $footer->getFooter(); ?>
  <?= (new ModalForm())->render(); ?>
  <?= renderPhoneButton(); ?>
</body>

</html>
