<?php
$distPath = $_SERVER['DOCUMENT_ROOT'] . '/dist';

// Проверяем, существует ли папка dist
if (is_dir($distPath)) {
  $currentUrl = $_SERVER['DOCUMENT_ROOT'] . '/dist' . '/files/php/data/contacts.php';
} else {
  $currentUrl = $_SERVER['DOCUMENT_ROOT'] . '/files/php/data/contacts.php';
}

include $currentUrl;
?>

<footer class="footer">
  <div class="container">
    <div class="footer__wrapper">
      <div class="footer__inner">
        <div class="footer__contacts">
          <p>Телефон</p>
          <a href="tel:<?php echo str_replace(' ', '', $phone) ?>"><?php echo $phone ?></a>
          <p>Почта</p>
          <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
        </div>
        <div class="footer__messangers">
          <p>Мессенджеры</p>
          <div class="footer__links">
            <a href="<?php echo $telegram ?>" class="telegram-icon"><span class="visually-hidden">telegram</span></a>
            <a href="<?php echo $watchapp ?>" class="whatsapp-icon"><span class="visually-hidden">whatsapp</span></a>
          </div>
        </div>
        <div class="footer__date">
          <p>Часы работы</p>
          <p>Пн-Пт с 9.00 по 20.00</p>
          <p>Сб-Вс с 10.00 по 14.00</p>
        </div>
      </div>
      <div class="footer__rights">
        <p>© 2024 Академия Андрея Андреевича. Все права защищены.
        <p>
          Любое копирование, распространение или использование материалов с данного сайта без предварительного
          письменного
          согласия владельца является грубым нарушением авторских прав и может повлечь за собой юридическую
          ответственность.
        <p>
        <p>
          Для получения разрешения на использование материалов, пожалуйста, свяжитесь с нами по адресу:
          <a href="mailto:<?php echo $email ?>" style="color: white; text-decoration: none;"><?php echo $email ?></a>
        </p>
        </p>
      </div>
    </div>
  </div>
</footer>