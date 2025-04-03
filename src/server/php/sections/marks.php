<?php
use HELPERS\SetVariables;

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$marks_path = '/client/images/cars-brand/';

$marks = [
  'changan',
  'KIA',
  'lexus',
  'toyota',
  'chevrolet',
  'haval',
  'hyundai',
  'mitsubishi',
]
  ?>

<section class="marks">
  <h2 class="visually-hidden">МАРКИ-АВТОМОБИЛЕЙ</h2>
  <div class="marks__wrapper">
    <ul class="marks__list list-style-none" id="marks-list">
      <?php foreach ($marks as $mark):
        $pathImage = $path . $marks_path . $mark . '.avif';
        ?>
        <li class="marks__item">
          <img class="marks__image" src="<?php echo $pathImage ?>" width="140" height="140" alt="Марка автомобиля.">
        </li>
      <?php endforeach; ?>
    </ul>
    <ul class="marks__list list-style-none" id="marks-list">
      <?php foreach ($marks as $mark):
        $pathImage = $path . $marks_path . $mark . '.avif';
        ?>
        <li class="marks__item">
          <img class="marks__image" src="<?php echo $pathImage ?>" width="140" height="140" alt="Марка автомобиля.">
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>