<?php

require_once __DIR__ . '/../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $current_data = $data;

    $formData = $current_data['form'];
    $items = $current_data['items'];

    function validateText($text, $field = null, $max_length = 255)
    {
        $text = trim($text);
        if ((empty($text) || !preg_match("/^[\p{L}\s-]+$/u", $text)) && $field !== null) {
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

    function validateNumber($number, $length, $field)
    {
        if (!preg_match("/^\d{1," . $length . "}$/", $number)) {
            echo json_encode(['success' => false, 'message' => "Некорректное значение: {$field}"]);
            exit;
        }
        return $number;
    }

    function validatePhone($phone, $max_length = 20)
    {
        if (!preg_match("/^[0-9+\-() ]+$/", $phone) || strlen($phone) > $max_length) {
            echo json_encode(['success' => false, 'message' => "Некорректный телефон"]);
            exit;
        }
        return $phone;
    }

    function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => "Некорректный email"]);
            exit;
        }
        return $email;
    }

    $client_type = validateText($formData['client_type'], 'Тип клиента', 100);
    $country = validateText($formData['country'], 'Страна', 40);
    $city = validateText($formData['city'], 'Город', 40);
    $street = validateText($formData['street'], 'Улица', 50);
    $house = validateNumber($formData['house'], 3, 'Дом');
    $apartment = validateNumber($formData['apartment'], 3, 'Квартира');
    $index = validateNumber($formData['index'], 6, 'Индекс');
    $user_name = validateText($formData['user-name'], 'Имя', 20);
    $user_lastname = validateText($formData['user-lastname'], 'Фамилия', 30);
    $telephone = validatePhone($formData['telephone']);
    $comments = validateText($formData['comments'], null, 200);
    $email = validateEmail($formData['email']);


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
    $emailBody .= "🚚 *Доставка:* " . $formData['delivery-method'] . "\n";
    $emailBody .= "💳 *Оплата:* " . $formData['payment-method'] . "\n";
    $emailBody .= "📝 *Комментарий:* " . $comments . "\n";
    $emailBody .= "📞 *Звонок:* " . ($formData['call-me'] ? "Да" : "Нет") . "\n";
    $emailBody .= "✅ *Согласие на обработку персональных данных:* " . ($formData['privacy'] ? "Да" : "Нет") . "\n";


    // Данные компании
    if ($formData['client_type'] === "Юридическое лицо") {
        $company_name = validateText($formData['company-name'], 'Название компании', 40);
        $company_adress = validateText($formData['company-adress'], 'Юридический адрес', 80);
        $INN = validateNumber($formData['INN'], 10, 'ИНН');
        $KPP = validateNumber($formData['KPP'], 9, 'КПП');
        $OGRN = validateNumber($formData['OGRN'], 13, 'ОГРН');
        $BIK = validateNumber($formData['BIK'], 9, 'БИК');
        $cash_number = validateNumber($formData['cash-number'], 20, 'Расчетный счет');
        $company_index = validateNumber($formData['company-index'], 6, 'Индекс компании');
        $company_telephone = validatePhone($formData['company-telephone']);

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

    $to = 'chernigovsky108@gmail.com';

    $subject = 'Новый заказ на сайте';

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";
    $headers .= "From: andrey@andrew.ru" . "\r\n";
    $CHAT_ID = CHAT_ID;
    $TOKEN = TOKEN;
    $message = urlencode($emailBody);
    $url = "https://api.telegram.org/bot$TOKEN/sendMessage?chat_id=$CHAT_ID&text=$$message";
    file_get_contents($url);
    if (mail($to, $subject, $emailBody, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Письмо отправлено']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Не удалось отправить письмо']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Не удалось обработать данные']);
}