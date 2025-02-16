<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mark = $_POST['mark'] ?? '';
    $model = trim($_POST['model'] ?? '');
    $releaseYear = trim($_POST['release-year'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $phone = $_POST['phone'] ?? '';
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
        foreach($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        exit; 
    }

    $to = "lady.mescheryakowa@yandex.ru"; 
    $subject = "Новая заявка на установку сигнализации";
    $body = "Марка: $mark\nМодель: $model\nГод выпуска: $releaseYear\nИмя: $name\nТелефон: $phone\nСообщение: $message";
    $headers = "From: lady.mescheryakowa@yandex.ru"; 

    if (mail($to, $subject, $body, $headers)) {
        echo "Заявка успешно отправлена!";
    } else {
        echo "Ошибка при отправке заявки. Пожалуйста, попробуйте еще раз.";
    }
} else {
    echo "Неверный метод запроса.";
}
?>