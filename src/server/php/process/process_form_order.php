<?php
namespace DATA;
require_once __DIR__ . '/../config/config.php';

// Добавляем error reporting для отладки
error_reporting(E_ALL);
ini_set('display_errors', 0); // Не показываем ошибки в браузере
ini_set('log_errors', 1);

try {
  $data = json_decode(file_get_contents('php://input'), true);

  if ($data) {
    $current_data = $data;

    $formData = $current_data['form'];
    $items = $current_data['items'];

    function validateText($text, $field = null, $max_length = 255, $required = true)
    {
      $text = trim($text);

      // Для необязательных полей разрешаем пустое значение
      if (!$required && empty($text)) {
        return 'Не указано';
      }

      // Для комментариев разрешаем пустое значение
      if ($field === null && empty($text)) {
        return 'Не указано';
      }

      if ((empty($text) || !preg_match("/^[\p{L}\s-]+$/u", $text)) && $field !== null && $required) {
        echo json_encode(['success' => false, 'message' => "Некорректное значение: $field"]);
        exit;
      }

      if (mb_strlen($text) > $max_length) {
        echo json_encode(['success' => false, 'message' => "$field не должен превышать $max_length символов"]);
        exit;
      } else if (mb_strlen($text) <= 0) {
        return 'Не указано';
      }

      return $text;
    }

    function validateNumber($number, $length, $field, $required = true)
    {
      if (!$required && empty(trim($number))) {
        return 'Не указано';
      }

      if (!preg_match("/^\d{1," . $length . "}$/", $number)) {
        echo json_encode(['success' => false, 'message' => "Некорректное значение: {$field}"]);
        exit;
      }
      return $number;
    }

    function validatePhone($phone, $max_length = 20, $required = true)
    {
      if (!$required && empty(trim($phone))) {
        return 'Не указано';
      }

      if (!preg_match("/^[0-9+\-() ]+$/", $phone) || strlen($phone) > $max_length) {
        echo json_encode(['success' => false, 'message' => "Некорректный телефон"]);
        exit;
      }
      return $phone;
    }

    function validateEmail($email, $required = true)
    {
      if (!$required && empty(trim($email))) {
        return 'Не указано';
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => "Некорректный email"]);
        exit;
      }
      return $email;
    }

    $client_type = validateText($formData['client_type'] ?? '', 'Тип клиента', 100);
    $country = validateText($formData['country'] ?? '', 'Страна', 40, false);
    $city = validateText($formData['city'] ?? '', 'Город', 40);
    $street = validateText($formData['street'] ?? '', 'Улица', 50);
    $house = validateNumber($formData['house'] ?? '', 3, 'Дом');
    $apartment = validateNumber($formData['apartment'] ?? '', 3, 'Квартира');
    $index = validateNumber($formData['index'] ?? '', 6, 'Индекс');
    $user_name = validateText($formData['user-name'] ?? '', 'Имя', 20);
    $user_lastname = validateText($formData['user-lastname'] ?? '', 'Фамилия', 30);
    $telephone = validatePhone($formData['telephone'] ?? '');
    $comments = validateText($formData['comments'] ?? '', null, 200);
    $email = validateEmail($formData['email'] ?? '');


    $emailBody = "📦 *Новый заказ от клиента:*\n\n";
    $emailBody .= "👤 *Тип клиента:* " . $client_type . "\n";
    $emailBody .= "🌍 *Страна:* " . $country . "\n";
    $emailBody .= "🏙 *Город:* " . $city . "\n";
    $emailBody .= "📍 *Адрес:* " . $street . ", " . $house . "-" . $apartment . "\n";
    $emailBody .= "📬 *Индекс:* " . $index . "\n";
    $emailBody .= "🆔 *Имя:* " . $user_name . "\n";
    $emailBody .= "🆔 *Фамилия:* " . $user_lastname . "\n";
    $emailBody .= "📞 *Телефон:* " . $telephone . "\n";
    $emailBody .= "✉️ *Email:* " . $email . "\n";
    $emailBody .= "🚚 *Доставка:* " . ($formData['delivery-method'] ?? 'Не указано') . "\n";
    $emailBody .= "💳 *Оплата:* " . ($formData['payment-method'] ?? 'Не указано') . "\n";
    $emailBody .= "📝 *Комментарий:* " . $comments . "\n";
    $emailBody .= "📞 *Звонок:* " . (isset($formData['call-me']) && $formData['call-me'] ? "Да" : "Нет") . "\n";
    $emailBody .= "✅ *Согласие на обработку персональных данных:* " . (isset($formData['privacy']) && $formData['privacy'] ? "Да" : "Нет") . "\n";


    // Данные компании
    if (($formData['client_type'] ?? '') === "Юридическое лицо") {
      $company_name = validateText($formData['company-name'] ?? '', 'Название компании', 40);
      $company_adress = validateText($formData['company-adress'] ?? '', 'Юридический адрес', 80);
      $INN = validateNumber($formData['INN'] ?? '', 10, 'ИНН');
      $KPP = validateNumber($formData['KPP'] ?? '', 9, 'КПП');
      $OGRN = validateNumber($formData['OGRN'] ?? '', 13, 'ОГРН');
      $BIK = validateNumber($formData['BIK'] ?? '', 9, 'БИК');
      $cash_number = validateNumber($formData['cash-number'] ?? '', 20, 'Расчетный счет');
      $company_index = validateNumber($formData['company-index'] ?? '', 6, 'Индекс компании');
      $company_telephone = validatePhone($formData['company-telephone'] ?? '');

      $emailBody .= "\n🏢 *Данные компании:*\n";
      $emailBody .= "🏛 *Название:* {$company_name}\n";
      $emailBody .= "📍 *Юридический адрес:* {$company_adress}\n";
      $emailBody .= "📬 *Индекс:* {$company_index}\n";
      $emailBody .= "🆔 *ИНН:* {$INN}\n";
      $emailBody .= "📜 *КПП:* {$KPP}\n";
      $emailBody .= "📃 *ОГРН:* {$OGRN}\n";
      $emailBody .= "🏦 *БИК:* {$BIK}\n";
      $emailBody .= "💳 *Расчетный счет:* {$cash_number}\n";
      $emailBody .= "📞 *Телефон компании:* {$company_telephone}\n";
    }

    $emailBody .= "\n🛍 *Товары:*\n\n";
    foreach ($items as $item) {
      $emailBody .= "🔹 *" . $item['title'] . "*\n";
      $emailBody .= "   📦 Количество: " . $item['quantity'] . "\n";
      $emailBody .= "   💰 Цена: " . $item['price'] . " тенге\n\n";
    }

    $emailBody .= "\n💰 *Сумма заказа:* " . $current_data['cost'] . "\n";

    $to = 'autosecurity.kz@mail.ru';

    $subject = 'Новый заказ на сайте';

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";
    $headers .= "From: Starline-Service <autosecurity@starline-service.kz>" . "\r\n";
    $headers .= "Reply-To: Starline-Service" . "\r\n";

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

    if (mail($to, $subject, $emailBody, $headers)) {
      echo json_encode(['success' => true, 'message' => 'Письмо отправлено']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Не удалось отправить письмо']);
    }

  } else {
    echo json_encode(['success' => false, 'message' => 'Не удалось обработать данные']);
  }
} catch (Exception $e) {
  error_log("Ошибка в process_form_order.php: " . $e->getMessage());
  echo json_encode(['success' => false, 'message' => 'Внутренняя ошибка сервера']);
} catch (Error $e) {
  error_log("Фатальная ошибка в process_form_order.php: " . $e->getMessage());
  echo json_encode(['success' => false, 'message' => 'Внутренняя ошибка сервера']);
}