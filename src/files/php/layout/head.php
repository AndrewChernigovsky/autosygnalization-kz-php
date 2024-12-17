<?php

$distPath = $_SERVER['DOCUMENT_ROOT'] . '/dist';

if (is_dir($distPath)) {
  $currentUrl = "http://localhost:3000/dist/index.php";
  $pathFile = $distPath;
  $pathFile_URL = '/dist';
} else {
  $currentUrl = "/index.php";
  $pathFile = $_SERVER['DOCUMENT_ROOT'];
  $pathFile_URL = '';
}
class Head
{
  private $title;
  private $styles;
  private $meta_tags;

  public function __construct(string $title = null, array $styles = [], array $meta_tags = [])
  {
    $this->title = $title;
    $this->styles = $styles;
    $this->meta_tags = $meta_tags;
  }

  public function setHead(): string
  {
    global $pathFile_URL;
    $title = $this->title ?? "Auto Security - магазин автоэлектроники и установочный центр d в г.Алматы. Авторизованный партнер Starline.";
    $headContent = <<<HTML
            <meta charset="utf-8">
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <meta name="keywords" content="купить автосигнализацию, купить видеорегистратор, купить антирадар, купить камеру заднего вида, установить автосигнализацию, услуги автоэлектрика, компьютерная диагностика, русификация авто, Starline">
            <meta name="description" content="Auto Security - магазин автоэлектроники и установочный центр в г.Алматы. Авторизованный партнер Starline.">
            <title>$title</title>
            <meta property="og:url" content="https://autosecurity.kz/">
            <meta property="og:type" content="article">
            <meta property="og:title" content="Главная">
            <meta property="og:description" content="Auto Security - магазин автоэлектроники и установочный центр d в г.Алматы. Авторизованный партнер Starline.">
            HTML;
    foreach ($this->meta_tags as $meta_tag) {
      $headContent .= "$meta_tag\n";
    }
    $favicons = "";

    $reload_fonts = "
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/din-pro-400.woff2' as='font' type='font/woff2' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/din-pro-400.woff' as='font' type='font/woff' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/din-pro-700.woff2' as='font' type='font/woff2' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/din-pro-700.woff' as='font' type='font/woff' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/oswald-bold.woff2' as='font' type='font/woff2' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/oswald-bold.woff' as='font' type='font/woff' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/oswald-regular.woff2' as='font' type='font/woff2' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/oswald-regular.woff' as='font' type='font/woff' crossorigin>
        ";
    $headContent .= $reload_fonts;
    $headContent .= "<link rel='stylesheet' href='$pathFile_URL/assets/libs/libs.css'>";
    $headContent .= "<link rel='stylesheet' href='$pathFile_URL/files/css/style.css'>";
    $headContent .= "<script src='$pathFile_URL/assets/libs/libs.js' defer type='module'></script>";
    $headContent .= "<script src='$pathFile_URL/files/js/main.js?v=1.0.0' defer type='module'></script>";
    $headContent .= "<link rel='preconnect' href='https://fonts.googleapis.com'>";
    $headContent .= "<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>";
    $headContent .= "<link href='https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Oswald:wght@200..700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap' rel='stylesheet'>";
    foreach ($this->styles as $style) {
      $headContent .= "<link rel='stylesheet' href='$style?v=1.0.0'>";
    }



    // $headContent .= $this->setYandexMetrika();

    return $headContent;
  }

  public function setYandexMetrika()
  {
    return <<<HTML
    <!-- Yandex.Metrika counter -->
    <script type='text/javascript'>
       (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
       m[i].l=1*new Date();
       for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
       k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
       (window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js', 'ym');

       ym(99037128, 'init', {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true
       });
    </script>
    <noscript><div><img src='https://mc.yandex.ru/watch/99037128' style='position:absolute; left:-9999px;' alt='' /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    HTML;
  }
}
?>