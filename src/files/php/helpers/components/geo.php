<?php

class GEO extends CreateTag
{
  public function insertGeo()
  {
    $output = '';
    
    <div class="contacts">
    <div class="geo">
      <a href="https://maps.app.goo.gl/72eQCZUbxVCKh43PA" class="geo-image link">
        <div class="image">
          <svg width="50" height="50">
            <use href="<?php echo $pathFile_URL . '/assets/images/vectors/sprite.svg#geo' ?>"></use>
          </svg>
        </div>
      </a>
      <address>
        <?php
        if (!empty($contacts_phone)) {
          foreach ($contacts_phone as $phone) {
            $cleanedPhone = str_replace(' ', '', $phone['phone']);
            echo '<a href="tel:' . htmlspecialchars($cleanedPhone) . '">' . htmlspecialchars($phone['phone']) . '</a>';
          }
        }
        ?>
        <span>
          Казахстан, г.Алматы, ул.Абая 145/г, бокс №15
        </span>
      </address>
    </div>
    <div class="cart">
      <a class="link" href="<?php echo $pathFile_URL . '/files/php/pages/cart/cart.php' ?>">
        <svg width="50" height="50">
          <use href="<?php echo $pathFile_URL . '/assets/images/vectors/sprite.svg#cart' ?>"></use>
        </svg>
        <div class="counter">1</div>
      </a>
    </div>
    <div class="menu-toggle">
      <button type="button" id="btn-open-menu" class="button"><span class="visually-hidden">Открыть
          меню</span></button>
    </div>
  </div>
    

    return $output;
  }
}

?>