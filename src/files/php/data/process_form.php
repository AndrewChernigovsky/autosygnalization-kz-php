<?php

require_once __DIR__ . '/../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    error_log(print_r($data, true)); // Логирование полученных данных

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
    $CHAT_ID = CHAT_ID;
    $TOKEN = TOKEN;
    $message = urlencode($emailBody);
    $url = "https://api.telegram.org/bot$TOKEN/sendMessage?chat_id=$CHAT_ID&text=$message";
    file_get_contents($url);
    if (mail($to, $subject, $emailBody, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Письмо отправлено']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Не удалось отправить письмо']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Не удалось обработать данные']);
}