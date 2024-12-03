<section class="reviews">
  <div class="container">
    <div class="swiper swiper-reviews">
      <ul class="swiper-wrapper">
        <?php
        $base_path = __DIR__;

        $files_to_include = [
          'review-1.php',
          'review-2.php',
          'review-3.php',
          'review-4.php',
          'review-5.php',
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
      </ul>
      <div class="swiper-pagination"></div>

    </div>

  </div>
</section>