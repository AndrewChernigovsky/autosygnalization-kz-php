<?php

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
        $errors[] = 'Введите ваше имя';
    }

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

    $to = "lady.mescheryakowa@yandex.ru";
    $subject = "Новая заявка на установку сигнализации";
    $body = "Марка: $mark\nМодель: $model\nГод выпуска: $releaseYear\nИмя: $name\nТелефон: $phone\nСообщение: $message";
    $headers = "From: lady.mescheryakowa@yandex.ru";


    mail($to, $subject, $body, $headers);

    echo json_encode(['success' => true,
  "name" => $name,
  "phone" => $phone
  ]);
    exit;
}
