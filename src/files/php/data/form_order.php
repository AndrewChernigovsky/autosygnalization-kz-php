<?php

require_once __DIR__ . '/../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $current_data = $data;

    $formData = $current_data['form'];
    $items = $current_data['items'];

    $emailBody = "📦 *Новый заказ от клиента:*\n\n";
    $emailBody .= "👤 *Тип клиента:* " . $formData['client_type'] . "\n";
    $emailBody .= "🌍 *Страна:* " . $formData['country'] . "\n";
    $emailBody .= "🏙 *Город:* " . $formData['city'] . "\n";
    $emailBody .= "📍 *Адрес:* " . $formData['street'] . ", " . $formData['house'] . "-" . $formData['apartment'] . "\n";
    $emailBody .= "📬 *Индекс:* " . $formData['index'] . "\n";
    if ($formData['client_type'] === "Физическое лицо") {
        $emailBody .= "🆔 *Имя:* " . $formData['user-name'] . "\n";
        $emailBody .= "🆔 *Фамилия:* " . $formData['user-lastname'] . "\n";
    }
    $emailBody .= "📞 *Телефон:* " . $formData['telephone'] . "\n";
    $emailBody .= "✉️ *Email:* " . $formData['email'] . "\n";
    $emailBody .= "🚚 *Доставка:* " . $formData['delivery-method'] . "\n";
    $emailBody .= "💳 *Оплата:* " . $formData['payment-method'] . "\n";
    $emailBody .= "📝 *Комментарий:* " . $formData['comments'] . "\n";
    $emailBody .= "📞 *Звонок:* " . ($formData['call-me'] ? "Да" : "Нет") . "\n";
    $emailBody .= "✅ *Согласие:* " . ($formData['privacy'] ? "Да" : "Нет") . "\n";


    // Данные компании
    if ($formData['client_type'] === "Юридическое лицо") {
        $emailBody .= "\n🏢 *Данные компании:*\n";
        $emailBody .= "🏛 *Название:* " . $formData['company-name'] . "\n";
        $emailBody .= "📍 *Юридический адрес:* " . $formData['company-adress'] . "\n";
        $emailBody .= "📬 *Индекс:* " . $formData['index'] . "\n";
        $emailBody .= "🆔 *ИНН:* " . $formData['INN'] . "\n";
        $emailBody .= "📜 *КПП:* " . $formData['KPP'] . "\n";
        $emailBody .= "📃 *ОГРН:* " . $formData['OGRN'] . "\n";
        $emailBody .= "🏦 *БИК:* " . $formData['BIK'] . "\n";
        $emailBody .= "💳 *Расчетный счет:* " . $formData['cash-number'] . "\n";
        $emailBody .= "📞 *Телефон компании:* " . $formData['telephone'] . "\n";
    }

    $emailBody .= "\n🛍 *Товары:*\n";
    foreach ($items as $item) {
        $emailBody .= "🔹 *" . $item['title'] . "*\n";
        $emailBody .= "   📦 Количество: " . $item['quantity'] . "\n";
        $emailBody .= "   💰 Цена: " . $item['price'] . " рублей\n\n";
    }

    $to = 'chernigovsky108@gmail.com';

    $subject = 'Новый заказ на сайте';

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";
    $headers .= "From: andrey@andrew.ru" . "\r\n";
    $CHAT_ID = 'CHAT_ID';
    $TOKEN = 'TOKEN';
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
