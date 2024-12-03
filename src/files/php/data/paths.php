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

$images_dir = '/assets/images/';
$portfolio_img_dir = $images_dir . 'portfolio/';
$portfolio_pages_dir = '/files/php/portfolio/';

// layout 
$footer_path = "$pathFile/files/php/layout/footer.php";
$header_path = "$pathFile/files/php/layout/header.php";

// buttons 
$buy_btn = "$pathFile_URL/index.php#form";

// links pages 
$audit_page = "$pathFile_URL/files/php/pages/services/audit-page.php";
$landing_page = "$pathFile_URL/files/php/pages/services/landing-page.php";
$visitka_page = "$pathFile_URL/files/php/pages/services/visitka-page.php";
$site_catalog_page = "$pathFile_URL/files/php/pages/services/site-catalog-page.php";

// images 
$logo = $pathFile_URL . $images_dir . '/logo';
$portfolio_site_1 = $pathFile_URL . $portfolio_img_dir . '/site-1/site-1.avif';
$portfolio_site_2 = $pathFile_URL . $portfolio_img_dir . '/site-2/site-2.avif';
$portfolio_site_3 = $pathFile_URL . $portfolio_img_dir . '/site-3/site-3.avif';
$portfolio_site_4 = $pathFile_URL . $portfolio_img_dir . '/site-4/site-4.avif';
$portfolio_site_5 = $pathFile_URL . $portfolio_img_dir . '/site-5/site-5.avif';
$portfolio_site_size_1 = $pathFile_URL . $portfolio_img_dir . '/site-1/site-1-size.avif';
$portfolio_site_size_2 = $pathFile_URL . $portfolio_img_dir . '/site-2/site-2-size.avif';
$portfolio_site_size_3 = $pathFile_URL . $portfolio_img_dir . '/site-3/site-3-size.avif';
$portfolio_site_size_4 = $pathFile_URL . $portfolio_img_dir . '/site-4/site-4-size.avif';
$portfolio_site_size_5 = $pathFile_URL . $portfolio_img_dir . '/site-5/site-5-size.avif';