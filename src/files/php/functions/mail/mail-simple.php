<?php
include_once './config.php';
include_once './telegram.php';
?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $usertel = $_POST['user-tel'];
  $social = $_POST['social'];

  $email_message = "Номер телефона: " . $usertel . "\n";
  $email_message .= "Мессенджер: " . $social . "\n";

  $headers = `From: ` . DOMEN_EMAIL . "\r\n";

  $recaptchaResponse = $_POST['g-recaptcha-response'];
  $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={SECRET_KEY}&response={$recaptchaResponse}");
  $responseKeys = json_decode($response, true);

  if (intval($responseKeys["success"]) !== 1) {
    echo json_encode(["status" => "error", "message" => "Проверка reCAPTCHA не пройдена. Пожалуйста, попробуйте еще раз."]);
  } else {
  }
  mail(TO_EMAIL, 'Новая заявка с сайта', $email_message, $headers);
  sendToTelegram($email_message);
  echo json_encode(["status" => "success", "message" => "Форма успешно отправлена!"]);
}