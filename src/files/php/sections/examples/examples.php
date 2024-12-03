<section class="examples">
  <div class="container">
    <h2 class="secondary-title">Некоторые из работ</h2>
    <div class="swiper swiper-examples">
      <ul class="swiper-wrapper list-style-none">
        <?php
        $base_path = __DIR__;

        $files_to_include = [
          'examples-1.php',
          'examples-2.php',
          'examples-3.php',
          'examples-4.php',
          'examples-5.php',
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