<section class="cost">
  <div class="container">
    <h2>Рассчитать стоимость сайта</h2>
    <div class="cost__steps">
      <p></p>
      <button type="button" id="step-back">Шаг назад</button>
    </div>
    <div class="cost__line">
      <div class="cost__line cost__line--active"></div>
    </div>
    <form action="./../../../functions/mail/mail.php" method="post" id="cost" class="swiper swiper-cost">

      <div class="swiper-wrapper">
        <?php
        $base_path = __DIR__;

        $files_to_include = [
          'slide-1.php',
          'slide-2.php',
          'slide-3.php',
          'slide-4.php',
          'slide-5.php',
        ];

        foreach ($files_to_include as $file) {
          $file_path = $base_path . '/' . $file;

          if (file_exists($file_path)) {
            include $file_path;
          } else {
            echo "Файл $file не найден.<br>";
          }
        }
        ?>
      </div>
    </form>
  </div>
</section>