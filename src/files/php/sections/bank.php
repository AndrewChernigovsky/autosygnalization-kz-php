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
    <h2>Возможно оформление <span style="color: red">в рассрочку </span>/ <span style="color: orangered">
        кредит</span> через Каспи Банк</h2>
    <button type='button' class="y-button-primary button buy-btn">Оставить заявку</button>
    <div class="bank__wrapper">
      <div class="bank__image--square">
        <img src="<?= $path . '/assets/images/kaspi-1.avif' ?>" alt="логотип Каспи Банка" width='100' height='100'>
      </div>
      <!-- <div class="bank__image--round" style="background-color: #fff; border: 4px solid white; border-radius: 50%;">
        <img src="<?= $path . '/assets/images/kaspi.avif' ?>" alt="логотип Каспи Банка" width='50' height='50'>      
      </div> -->
      <p>0-0-12</p>
    </div>
  </div>
</section>