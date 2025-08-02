<?php
namespace DATA;
require_once __DIR__ . '/../config/config.php';

// Чтение входных данных
$inputStream = fopen('php://input', 'r');
$data = json_decode(stream_get_contents($inputStream), true);
fclose($inputStream);
error_log(print_r($data, true) . " данные");
if ($data) {
  // Логирование полученных данных
  function validateText($text, $field = null, $max_length = 255)
  {
    $text = trim($text);
    // Если поле пустое и не обязательное
    if (empty($text) && $field === 'Коментарий') {
      return '';
    }

    // Особая валидация для комментария
    if ($field === 'Коментарий') {
      // Проверяем, содержит ли текст только цифры
      if (preg_match("/^[0-9\s]*$/", $text)) {
        echo json_encode(['success' => false, 'message' => "Комментарий не может содержать только цифры"]);
        exit;
      }
      // Разрешаем буквы, цифры, пробелы и знаки препинания
      if (!preg_match("/^[\p{L}\p{N}\s\-.,!?()]+$/u", $text)) {
        echo json_encode(['success' => false, 'message' => "Некорректные символы в поле: $field"]);
        exit;
      }
    } else {
      // Для остальных полей оставляем старую валидацию
      if ((empty($text) || !preg_match("/^[\p{L}\s-]+$/u", $text))) {
        echo json_encode(['success' => false, 'message' => "Некорректное значение: $field"]);
        exit;
      }
    }

    if (mb_strlen($text) > $max_length) {
      echo json_encode(['success' => false, 'message' => "$field не должен превышать $max_length символов"]);
      exit;
    } else if (mb_strlen($text) <= 0) {
      return 'Не указано';
    }

    return $text;
  }

  function validatePhone($phone, $max_length = 20)
  {
    // Удаляем все пробелы и лишние символы, кроме цифр и + в начале
    $phone = trim($phone);

    // Проверяем, начинается ли с +7
    if (!preg_match("/^\+7/", $phone)) {
      echo json_encode(['success' => false, 'message' => "Некорректный номер телефона. Должен начинаться с +7"]);
      exit;
    }

    // Оставляем только +7 в начале и цифры
    $cleanPhone = '+7' . preg_replace('/[^0-9]/', '', substr($phone, 2));

    // Проверяем длину номера (должно быть от 10 до 18 символов: +7 + 7-15 цифр)
    if (strlen($cleanPhone) < 10 || strlen($cleanPhone) > 18) {
      echo json_encode(['success' => false, 'message' => "Некорректная длина номера телефона"]);
      exit;
    }

    // Финальная проверка формата
    if (!preg_match("/^\+7[0-9]{7,15}$/", $cleanPhone)) {
      echo json_encode(['success' => false, 'message' => "Некорректный формат номера телефона"]);
      exit;
    }

    return $cleanPhone;
  }


  $current_data = $data;
  $name = validateText($current_data['name'], 'Имя', 50);
  $phone = validatePhone($current_data['phone']);

  $emailBody = " *Имя:* " . $name . "\n";
  $emailBody .= " *Телефон:* " . $phone . "\n";

  // Обработка комментария (необязательное поле)
  $comment = "Не указан";
  if (isset($current_data['message']) && !empty(trim($current_data['message']))) {
    // Только валидируем непустой комментарий
    $comment = validateText($current_data['message'], 'Коментарий', 200);
  }
  $emailBody .= " *Коментарий:* " . $comment . "\n";

  $to = 'autosecurity.kz@mail.ru';
  $subject = 'Новая заявка на сайте';

  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";
  $headers .= "From: Starline-Service <autosecurity@starline-service.kz>" . "\r\n";
  $headers .= "Reply-To: Starline-Service" . "\r\n";

  // Отправка в Telegram через cURL
  $CHAT_ID = CHAT_ID;
  $TOKEN = TOKEN;
  $message = urlencode($emailBody);
  $url = "https://api.telegram.org/bot$TOKEN/sendMessage?chat_id=$CHAT_ID&text=$message";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Отключение проверки SSL (не рекомендуется для продакшена)
  $response = curl_exec($ch);

  if ($response === false) {
    $error = curl_error($ch);
    error_log("Ошибка при отправке запроса в Telegram: " . $error);
  } else {
    $responseData = json_decode($response, true);
    if (isset($responseData['ok']) && $responseData['ok']) {
      error_log("Сообщение успешно отправлено в Telegram");
    } else {
      error_log("Ошибка Telegram API: " . print_r($responseData, true));
    }
  }

  curl_close($ch);

  // Отправка email
  if (mail($to, $subject, $emailBody, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Письмо отправлено']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Не удалось отправить письмо']);
  }

} else {
  echo json_encode(['success' => false, 'message' => 'Не удалось обработать данные']);
}