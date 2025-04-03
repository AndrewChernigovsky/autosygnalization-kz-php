<?php
include_once __DIR__ . '/../data/quality.php';
?>

<section class="quality" id="quality">
  <?php foreach ($quality_videos['videos'] as $video): ?>
    <video class="quality__video quality__video--bg" preload="auto" autoplay loop muted
      poster="<?= htmlspecialchars($video['poster']); ?>">
      <source src="<?= htmlspecialchars($video['srcMob']); ?>" data-src="<?= htmlspecialchars($video['srcMob']); ?>"
        type="<?= htmlspecialchars($video['type'][0]); ?>" media="(max-width:768px)" />
      <?php foreach ($video['src'] as $index => $source): ?>
        <source src="<?= htmlspecialchars($source); ?>" data-src="<?= htmlspecialchars($source); ?>"
          type="<?= htmlspecialchars($video['type'][$index]); ?>" />
      <?php endforeach; ?>
      Ваш браузер не поддерживает тег video.
    </video>
  <?php endforeach; ?>
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
  <div class="quality__present">
    <h2>
      <p>
        <span>
          <?php echo htmlspecialchars($quality_videos['title']); ?>
        </span>
      </p>
    </h2>
    <div class="quality__list-wrapper">
      <div class="container">
        <ul class="quality__list list-style-none">
          <?php foreach ($quality_videos['qualities'] as $item): ?>
            <li class="quality__item"><?php echo htmlspecialchars($item); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>