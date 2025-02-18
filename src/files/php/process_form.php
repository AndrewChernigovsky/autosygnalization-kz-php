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

    if (empty($mark)) {
        $errors[] = 'Выберите марку автомобиля';
    }

    if (empty($model)) {
        $errors[] = 'Введите модель автомобиля';
    }

    if (empty($releaseYear)) {
        $errors[] = 'Введите год выпуска автомобиля';
    }

    if (empty($name)) {
        $errors[] = 'Введите ваше имя';
    }

    if (empty($phone)) {
        $errors[] = 'Введите ваш номер телефона';
    }

    if (empty($message)) {
        $errors[] = 'Введите ваше сообщение';
    }

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit; 
    }


    $to = "lady.mescheryakowa@yandex.ru"; 
    $subject = "Новая заявка на установку сигнализации";
    $body = "Марка: $mark\nМодель: $model\nГод выпуска: $releaseYear\nИмя: $name\nТелефон: $phone\nСообщение: $message";
    $headers = "From: lady.mescheryakowa@yandex.ru"; 


    mail($to, $subject, $body, $headers);

    echo json_encode(['success' => true]);
    exit; 
}
?>