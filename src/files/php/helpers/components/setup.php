<?php
include_once __DIR__ . '/../../data/setup.php';

$shop = $setups['setup'];
?>

<section class='setup'>
  <div class='setup__container'>
    <div class='setup__wrapper'>
      <h2 class='setup__title'>
        <?php echo $shop['title'] ?>
      </h2>
      <?php foreach ($shop['descs'] as $desc): ?>
        <p class='setup__description'>
          <?php echo $desc ?>
        </p>
      <?php endforeach; ?>
      <button class='setup__btn button' type='button'>В раздел</button>
    </div>
    <div class='setup__img-container'>
      <picture>
        <source type="image/png" media="(min-width: 768px)" srcset="<?php echo $shop['src']['desktop'] ?>" width="700"
          height="554">
        <img src="<?php echo $shop['src']['mob'] ?>" width='300' height='350' alt="Серсис">
      </picture>
    </div>
  </div>
</section>