<?php
require_once __DIR__ . '/../config/config.php';
header('Content-Type: application/json; charset=utf-8'); // для ответа в формате json

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mark = $_POST['mark'] ?? '';
    $model = trim($_POST['model'] ?? '');
    $releaseYear = trim($_POST['release-year'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];

    error_log(print_r($name, true) . ' : NAME');
    error_log(print_r(empty($name), true) . ' : NAME');

    if ($name != '') {
        error_log(print_r($name, true) . ' : NAME');
        error_log(print_r(empty($name), true) . ' : NAME');
    }

    if ($name != '') {
        $errors[] = 'Введите ваше имя';
    }

    if ($phone != '') {
        if ($phone != '') {
            $errors[] = 'Введите ваш номер телефона';
        }

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        echo json_encode($data);
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        echo json_encode($data);

        $to = "";
        $subject = "Новая заявка на установку сигнализации";
        $body = "Марка: $mark\nМодель: $model\nГод выпуска: $releaseYear\nИмя: $name\nТелефон: $phone\nСообщение: $message";
        $headers = "From: ";


        mail($to, $subject, $body, $headers);

        echo json_encode(['success' => true,
            "name" => $name,
            "phone" => $phone
            ]);
        exit;
    }
}

<?php

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $current_data = $data;

    $formData = $current_data['form'];
    $items = $current_data['items'];

    
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
