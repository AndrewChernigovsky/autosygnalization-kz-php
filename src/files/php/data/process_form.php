<?php

require_once __DIR__ . '/../config/config.php';

// Чтение входных данных
$inputStream = fopen('php://input', 'r');
$data = json_decode(stream_get_contents($inputStream), true);
fclose($inputStream);
error_log(print_r($data, true) . " данные");
if ($data) {
    // Логирование полученных данных

    $current_data = $data;

    $emailBody = " *Марка:* " . $current_data['model'] . "\n";
    $emailBody .= " *Год выпуска:* " . $current_data['release-year'] . "\n";
    $emailBody .= " *Имя:* " . $current_data['name'] . "\n";
    $emailBody .= " *Телефон:* " . $current_data['phone'] . "\n";
    $emailBody .= " *Коментарий:* " . $current_data['message'] . "\n";

    $to = 'chernigovsky108@gmail.com';
    $subject = 'Новый заказ на сайте';

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";
    $headers .= "From: andrey@andrew.ru" . "\r\n";

    // Отправка в Telegram через cURL
    $CHAT_ID = CHAT_ID;
    $TOKEN = TOKEN;
    $message = urlencode($emailBody);
    $url = "https://api.telegram.org/bot$TOKEN/sendMessage?chat_id=$CHAT_ID&text=$message";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Отключение проверки SSL (не рекомендуется для продакшена)
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