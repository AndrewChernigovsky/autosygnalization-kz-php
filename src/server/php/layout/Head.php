<?php

namespace LAYOUT;

class Head
{
  private $title;
  private $styles;
  private $metaTags;
  private $includeYandexMetrika;

  public function __construct(
    string $title = null,
    array $styles = [],
    array $metaTags = [],
    bool $includeYandexMetrika = false
  ) {
    $this->title = $title ?? "Auto Security - Магазин автоэлектроники в Алматы";
    $this->styles = $styles;
    $this->metaTags = $metaTags;
    $this->includeYandexMetrika = $includeYandexMetrika;
  }

  public function setHead(): string
  {
    $headContent = <<<HTML
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="robots" content="noindex, nofollow">
            <meta name="keywords" content="купить автосигнализацию, купить видеорегистратор, купить антирадар, купить камеру заднего вида, установить автосигнализацию, услуги автоэлектрика, компьютерная диагностика, русификация авто, Starline">
            <meta name="description" content="Auto Security - магазин автоэлектроники и установочный центр в г.Алматы. Авторизованный партнер Starline.">
            <title>{$this->title}</title>
            <meta property="og:url" content="https://autosecurity.kz/">
            <meta property="og:type" content="article">
            <meta property="og:title" content="Главная">
            <meta property="og:description" content="Auto Security - магазин автоэлектроники и установочный центр в г.Алматы. Авторизованный партнер Starline.">
        HTML;

    // Add custom meta tags
    foreach ($this->metaTags as $metaTag) {
      if ($this->isValidMetaTag($metaTag)) {
        $headContent .= "$metaTag\n";
      }
    }

    // Preload fonts
    $headContent .= $this->getPreloadedFonts();

    // Add default stylesheets
    $headContent .= $this->getDefaultStylesheets();

    // Add default scripts
    $headContent .= $this->getDefaultScripts();

    // Add custom stylesheets
    foreach ($this->styles as $style) {
      $headContent .= "<link rel='stylesheet' href='$style?v=" . $this->getFileVersion($style) . "'>\n";
    }

    // Add Yandex.Metrika if enabled
    if ($this->includeYandexMetrika) {
      $headContent .= $this->setYandexMetrika();
    }

    return $headContent;
  }

  private function getPreloadedFonts(): string
  {
    return "
            <link rel='preload' href='/client/fonts/din-pro-400.woff2' as='font' type='font/woff2' crossorigin='anonymous'>
            <link rel='preload' href='/client/fonts/din-pro-400.woff' as='font' type='font/woff' crossorigin='anonymous'>
            <link rel='preload' href='/client/fonts/din-pro-700.woff2' as='font' type='font/woff2' crossorigin='anonymous'>
            <link rel='preload' href='/client/fonts/din-pro-700.woff' as='font' type='font/woff' crossorigin='anonymous'>
            <link rel='preload' href='/client/fonts/oswald-bold.woff2' as='font' type='font/woff2' crossorigin='anonymous'>
            <link rel='preload' href='/client/fonts/oswald-bold.woff' as='font' type='font/woff' crossorigin='anonymous'>
            <link rel='preload' href='/client/fonts/oswald-regular.woff2' as='font' type='font/woff2' crossorigin='anonymous'>
            <link rel='preload' href='/client/fonts/oswald-regular.woff' as='font' type='font/woff' crossorigin='anonymous'>
        ";
  }

  private function getDefaultStylesheets(): string
  {
    return "
            <link rel='stylesheet' href='/client/libs/index.css'>
            <link rel='stylesheet' href='/client/css/style.css'>
        ";
  }

  private function getDefaultScripts(): string
  {
    return "
            <script src='/client/libs/index.js' defer type='module'></script>
            <script src='/client/js/main.js?v=" . $this->getFileVersion('/client/js/main.js') . "' defer type='module'></script>
        ";
  }

  private function isValidMetaTag(string $metaTag): bool
  {
    return preg_match('/^<meta\s+[^>]+>$/', $metaTag) === 1;
  }

  private function getFileVersion(string $filePath): string
  {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
      return md5_file($_SERVER['DOCUMENT_ROOT'] . $filePath);
    }
    return '1.0.0';
  }

  private function setYandexMetrika(): string
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
