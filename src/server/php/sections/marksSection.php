<?php

namespace SECTIONS;

function marksSection()
{
  $marksPath = '/client/images/cars-brand/';
  $marks = [
    'changan',
    'KIA',
    'lexus',
    'toyota',
    'chevrolet',
    'haval',
    'hyundai',
    'mitsubishi',
  ];

  ob_start(); // Start output buffering
  ?>
  <section class="marks">
    <h2 class="visually-hidden secondary-title">МАРКИ-АВТОМОБИЛЕЙ</h2>
    <div class="marks__wrapper">
      <?php foreach (['first', 'second'] as $listId): ?>
        <ul class="marks__list list-style-none" id="marks-list-<?php echo $listId; ?>">
          <?php foreach ($marks as $mark): ?>
            <?php
            $pathImage = $marksPath . $mark . '.avif';
            $imageExists = file_exists($_SERVER['DOCUMENT_ROOT'] . $pathImage);
            ?>
            <li class="marks__item">
              <?php if ($imageExists): ?>
                <img class="marks__image" src="<?php echo $pathImage; ?>" width="140" height="140"
                  alt="Марка автомобиля: <?php echo ucfirst($mark); ?>">
              <?php else: ?>
                <span class="marks__placeholder">Изображение недоступно</span>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endforeach; ?>
    </div>
  </section>
  <?php
  return ob_get_clean(); // Return the buffered content
}
