<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';
include_once __DIR__ . '/../data/contacts.php';

$variables = new SetVariables();
$variables->setVar();
$path = $variables->getPathFileURL();
$contacts = new Contacts();
$phones = $contacts->getPhones();
?>
<section class="bank">
  <div class="container">
    <h2>Есть возможность взять: <span style="color: red">в рассрочку </span>/ <span style="color: orangered">в
        кредит</span> через Каспи Банк</h2>
    <div class="bank__image">
      <p>Звоните:</p>
      <div class="bank__phones">
        <?php
        if (!empty($phones)) {
          foreach ($phones as $phone) {
            $cleanedPhone = str_replace(' ', '', $phone['phone']);
            echo '<a href="tel:' . htmlspecialchars($cleanedPhone) . '">' . htmlspecialchars($phone['phone']) . '</a>';
          }
        }
        ?>
      </div>
      <div class="bank__image" style="background-color: #fff; border: 4px solid white; border-radius: 50%;">
        <img src="<?= $path . '/assets/images/kaspi.avif' ?>" alt="логотип Каспи Банка" width='50' height='50'>
      </div>
    </div>
  </div>
</section>