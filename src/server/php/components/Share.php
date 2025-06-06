<?php

namespace COMPONENTS;

use DATA\SocialIconsData;

class Share
{
  private $path;
  private $icons;
  public function __construct()
  {
    $icons = (new SocialIconsData())->getData();
    $this->icons = $icons;
  }
  public function getShare()
  {
    ob_start();
    ?>
    <div class="share">
      <p style="background-image: url(<?= htmlspecialchars('/client/vectors/share-icon.svg'); ?>);">
        Поделиться:</p>
      <ul class="share__list list-style-none">
        <?php if (isset($this->icons) && is_array($this->icons)): ?>
          <?php foreach ($this->icons as $icon): ?>
            <li>
              <a href="<?= htmlspecialchars($icon['href']); ?>" title="<?= htmlspecialchars($icon['name']); ?>" <?php
                  if (isset($icon['attributes']) && is_array($icon['attributes'])) {
                    foreach ($icon['attributes'] as $attr => $value) {
                      echo htmlspecialchars($attr) . '="' . htmlspecialchars($value) . '" ';
                    }
                  }
                  ?>>
                <img src="<?= htmlspecialchars($icon['path']); ?>" width="<?= htmlspecialchars($icon['width']); ?>"
                  height="<?= htmlspecialchars($icon['height']); ?>" alt="<?= htmlspecialchars($icon['name']); ?>" />
                <span class="visually-hidden"><?= htmlspecialchars($icon['name']); ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>Иконки не найдены.</li>
        <?php endif; ?>
      </ul>
    </div>

    <?php
    return ob_get_clean();
  }
}

?>