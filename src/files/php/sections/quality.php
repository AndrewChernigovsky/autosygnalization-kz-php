<?php
include_once __DIR__ . '/../data/quality.php';
?>

<section class="quality" id="quality">
  <?php foreach ($quality_videos['videos'] as $video): ?>
    <video class="quality__video" preload="auto" autoplay loop muted poster="<?= htmlspecialchars($video['poster']); ?>">
      <source src="<?= htmlspecialchars($video['srcMob']); ?>" data-src="<?= htmlspecialchars($video['srcMob']); ?>"
        type="<?= htmlspecialchars($video['type'][0]); ?>" media="(max-width:768px)" />
      <?php foreach ($video['src'] as $index => $source): ?>
        <source src="<?= htmlspecialchars($source); ?>" data-src="<?= htmlspecialchars($source); ?>"
          type="<?= htmlspecialchars($video['type'][$index]); ?>" />
      <?php endforeach; ?>
      Ваш браузер не поддерживает тег video.
    </video>
  <?php endforeach; ?>
  <div class="container">
    <h2><?php echo htmlspecialchars($quality_videos['title']); ?></h2>


    <ul class="quality__list">
      <?php foreach ($quality_videos['qualities'] as $item): ?>
        <li class="quality__item"><?php echo htmlspecialchars($item); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>