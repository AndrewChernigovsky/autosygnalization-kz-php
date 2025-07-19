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
$locationDescription = $contacts->getLocationDescription();
$allContacts = $contacts->getAllContact(['contact-phone', 'social','email', 'address', 'schedule']);
error_log(print_r($allContacts, true) . 'Я ВЕСЬ МАССИВ КОНТАКТОВ');
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
            <?php foreach ($allContacts as $item): ?>
              <?php if ($item['type'] === 'contact-phone'): ?>
                <li class="contacts-section__item contacts-section__item--whatsap">
                  <a href="<?php echo str_replace(' ', '', $item['link']) ?>">
                    <svg width="25" height="25" aria-hidden="true">
                      <use href="<?php echo str_replace(' ', '', $item['icon_path']) ?>"></use>
                    </svg>
                    <h3><?php echo htmlspecialchars($item['title']) ?></h3>
                    <p><?php echo htmlspecialchars($item['content']) ?></p>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($item['type'] === 'social'): ?>
                <li class="contacts-section__item contacts-section__item--<?= htmlspecialchars($item['title']) ?>">
                  <a href="<?php echo str_replace(' ', '', $item['link']) ?>">
                    <svg width="25" height="25" aria-hidden="true">
                      <use href="<?php echo str_replace(' ', '', $item['icon_path']) ?>"></use>
                    </svg>
                    <h3><?php echo htmlspecialchars($item['title']) ?></h3>
                    <p><?php echo htmlspecialchars($item['content']) ?></p>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($item['type'] === 'email'): ?>
                <li class="contacts-section__item contacts-section__item--<?= htmlspecialchars($item['type']) ?>">
                  <a href="<?php echo str_replace(' ', '', $item['link']) ?>">
                    <svg width="25" height="25" aria-hidden="true">
                      <use width="25" height="25" href="<?php echo str_replace(' ', '', $item['icon_path']) ?>"></use>
                    </svg>
                    <h3><?php echo htmlspecialchars($item['title']) ?></h3>
                    <p><?php echo htmlspecialchars($item['content']) ?></p>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($item['type'] === 'address'): ?>
                <li class="contacts-section__item contacts-section__item--address">
                  <a href="<?php echo str_replace(' ', '', $item['link']) ?>">
                    <svg width="25" height="25" aria-hidden="true">
                      <use width="25" height="25" href="<?php echo str_replace(' ', '', $item['icon_path']) ?>"></use>
                    </svg>
                    <h3><?php echo htmlspecialchars($item['title']) ?></h3>
                    <p><?php echo $item['content'] ?></p>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($item['type'] === 'schedule'): ?>
                <li class="contacts-section__item contacts-section__item--schedule">
                    <svg width="25" height="25" aria-hidden="true">
                      <use width="25" height="25" href="<?php echo str_replace(' ', '', $item['icon_path']) ?>"></use>
                    </svg>
                    <h3><?php echo htmlspecialchars($item['title']) ?></h3>
                    <p><?php echo $item['content'] ?></p>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>

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
            <?php if (!empty($locationDescription)): ?>
                  <h2><?php echo htmlspecialchars($locationDescription[0]['title']) ?></h2>
                  <p><?php echo $locationDescription[0]['path'] ?></p>
            <?php endif; ?>
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
