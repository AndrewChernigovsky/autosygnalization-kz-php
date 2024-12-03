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
    $title = $this->title ?? 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова';
    $headContent = <<<HTML
            <meta name="description" content="Профессиональная разработка и продвижение сайтов: лендинги, сайты-визитки, каталоги. Уникальный дизайн, адаптивность, базовая SEO-настройка. Хостинг на год в подарок.">
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <meta property="og:site_name" content="Академия Андрея Андреевича Изосимова" />
            <meta property="og:title" content="Создание и продвижение сайтов" />
            <meta property="og:description" content="Профессиональная разработка и продвижение сайтов: лендинги, сайты-визитки, каталоги. Уникальный дизайн, адаптивность, базовая SEO-настройка. Хостинг на год в подарок." />
            <meta property="og:type" content="website" />
            <meta property="og:image" content="https://xn----7sbbihceda5ae9bf1bg0j.xn--p1ai/logo.png" />
            <meta property="og:image:width" content="300" />
            <meta property="og:image:height" content="300" />
            <meta property="og:url" content="https://xn----7sbbihceda5ae9bf1bg0j.xn--p1ai/" />
            <title>$title</title>
            HTML;
    foreach ($this->meta_tags as $meta_tag) {
      $headContent .= "$meta_tag\n";
    }
    $headContent .= "
            <link type='image/x-icon' rel='shortcut icon' href='./favicon.ico'>
            <link type='image/png' sizes='16x16' rel='icon' href='{$pathFile_URL}/assets/images/favicons/favicon-16x16.png'>
            <link type='image/png' sizes='32x32' rel='icon' href='{$pathFile_URL}/assets/images/favicons/favicon-32x32.png'>
            <link type='image/png' sizes='96x96' rel='icon' href='{$pathFile_URL}/assets/images/favicons/favicon-96x96.png'>
            <link type='image/png' sizes='120x120' rel='icon' href='{$pathFile_URL}/assets/images/favicons/favicon-120x120.png'>
            <link type='image/png' sizes='72x72' rel='icon' href='{$pathFile_URL}/assets/images/favicons/android-icon-72x72.png'>
            <link type='image/png' sizes='96x96' rel='icon' href='{$pathFile_URL}/assets/images/favicons/android-icon-96x96.png'>
            <link type='image/png' sizes='144x144' rel='icon' href='{$pathFile_URL}/assets/images/favicons/android-icon-144x144.png'>
            <link type='image/png' sizes='192x192' rel='icon' href='{$pathFile_URL}/assets/images/favicons/android-icon-192x192.png'> 
            <link type='image/png' sizes='512x512' rel='icon' href='{$pathFile_URL}/assets/images/favicons/android-icon-512x512.png'>
            <link rel='manifest' href='./manifest.json'>
            <link sizes='57x57' rel='apple-touch-icon' href='{$pathFile_URL}/assets/images/favicons/apple-touch-icon-57x57.png'>
            <link sizes='60x60' rel='apple-touch-icon' href='{$pathFile_URL}/assets/images/favicons/apple-touch-icon-60x60.png'>
            <link sizes='72x72' rel='apple-touch-icon' href='{$pathFile_URL}/assets/images/favicons/apple-touch-icon-72x72.png'>
            <link sizes='76x76' rel='apple-touch-icon' href='{$pathFile_URL}/assets/images/favicons/apple-touch-icon-76x76.png'>
            <link sizes='114x114' rel='apple-touch-icon' href='{$pathFile_URL}/assets/images/favicons/apple-touch-icon-114x114.png'>
            <link sizes='120x120' rel='apple-touch-icon' href='{$pathFile_URL}/assets/images/favicons/apple-touch-icon-120x120.png'>
            <link sizes='144x144' rel='apple-touch-icon' href='{$pathFile_URL}/assets/images/favicons/apple-touch-icon-144x144.png'>
            <link sizes='152x152' rel='apple-touch-icon' href='{$pathFile_URL}/assets/images/favicons/apple-touch-icon-152x152.png'>
            <link sizes='180x180' rel='apple-touch-icon' href='{$pathFile_URL}/assets/images/favicons/apple-touch-icon-180x180.png'>
            <link color='#e52037' rel='mask-icon' href='{$pathFile_URL}/assets/images/favicons/safari-pinned-tab.svg'>
            <meta name='msapplication-TileColor' content='#2b5797'>
            <meta name='msapplication-TileImage' content='{$pathFile_URL}/assets/images/favicons/mstile-144x144.png'>
            <meta name='msapplication-square70x70logo' content='{$pathFile_URL}/assets/images/favicons/mstile-70x70.png'>
            <meta name='msapplication-square150x150logo' content='{$pathFile_URL}/assets/images/favicons/mstile-150x150.png'>
            <meta name='msapplication-wide310x150logo' content='{$pathFile_URL}/assets/images/favicons/mstile-310x310.png'>
            <meta name='msapplication-square310x310logo' content='{$pathFile_URL}/assets/images/favicons/mstile-310x150.png'>
            <meta name='application-name' content='My Application'>
            <meta name='msapplication-config' content='./browserconfig.xml'>";
    $headContent .= "
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/Russo_One.woff2' as='font' type='font/woff2' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/Russo_One.woff' as='font' type='font/woff' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/Russo_One.ttf' as='font' type='font/ttf' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/Rubik-Bold.woff2' as='font' type='font/woff2' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/Rubik-Bold.woff' as='font' type='font/woff' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/Rubik-Bold.ttf' as='font' type='font/ttf' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/Rubik-Regular.woff2' as='font' type='font/woff2' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/Rubik-Regular.woff' as='font' type='font/woff' crossorigin>
            <link rel='preload' href='{$pathFile_URL}/assets/fonts/Rubik-Regular.ttf' as='font' type='font/ttf' crossorigin>
        ";
    $headContent .= "<link rel='stylesheet' href='$pathFile_URL/assets/libs/libs.css'>";
    $headContent .= "<link rel='stylesheet' href='$pathFile_URL/files/css/style.css'>";
    $headContent .= "<script src='$pathFile_URL/assets/libs/libs.js' defer type='module'></script>";
    $headContent .= "<script src='$pathFile_URL/files/js/main.js?v=1.0.0' defer type='module'></script>";

    foreach ($this->styles as $style) {
      $headContent .= "<link rel='stylesheet' href='$style?v=1.0.0'>";
    }



    $headContent .= $this->setYandexMetrika();

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