<?php

class Share
{
  public function getShare()
  {
    ob_start();
    ?>
    <div class="share">
      <p>Поделиться</p>
    </div>

    <?php
    return ob_get_clean();
  }
}

?>