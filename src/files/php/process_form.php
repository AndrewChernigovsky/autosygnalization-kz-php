<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

// очистка буфера
ob_clean();

require_once __DIR__ . '/config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mark = $_POST['mark'] ?? '';
    $model = trim($_POST['model'] ?? '');
    $releaseYear = trim($_POST['release-year'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];

    if (empty($name)) {
        $errors[] = 'Введите ваше имя';
    }

    if (empty($phone)) {
        $errors[] = 'Введите ваш номер телефона';
    }

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    $emailBody = "Новая заявка от клиента\n" .
                 "Имя клиента: $name\n" .
                 "Телефон клиента: $phone\n" .
                 "Марка машины: $mark\n" .
                 "Модель машины: $model\n" .
                 "Год выпуска машины: $releaseYear\n" .
                 "Сообщение клиента: $message\n";

    $to = 'chernigovsky108@gmail.com';
    $subject = 'Новая заявка на сайте';
    $headers = "MIME-Version: 1.0\r\n" .
               "Content-Type: text/plain; charset=UTF-8\r\n" .
               "From: andrey@andrew.ru\r\n";

    // отправка в телегу
    $telegramUrl = "https://api.telegram.org/bot" . TOKEN . "/sendMessage?chat_id=" . CHAT_ID . "&text=" . urlencode($emailBody);
    $telegramResult = file_get_contents($telegramUrl);

    $mailResult = mail($to, $subject, $emailBody, $headers);
    
    echo json_encode([
        'success' => $mailResult,
        'message' => $mailResult ? 'Письмо отправлено' : 'Не удалось отправить письмо',
        'data' => [
            'name' => $name,
            'phone' => $phone,
            'mark' => $mark,
            'model' => $model,
            'releaseYear' => $releaseYear,
            'message' => $message
        ]
    ]);
    exit;
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Метод запроса должен быть POST'
    ]);
    exit;
}