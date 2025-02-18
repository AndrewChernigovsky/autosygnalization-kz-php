<?php

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $current_data = $data;

    $formData = $current_data['form'];
    $items = $current_data['items'];

    $emailBody = "Заказ от клиента:\n\n";

    $emailBody .= "Тип клиента: " . $formData['client_type'] . "\n";
    $emailBody .= "Страна: " . $formData['country'] . "\n";
    $emailBody .= "Город: " . $formData['city'] . "\n";
    $emailBody .= "Улица: " . $formData['street'] . "\n";
    $emailBody .= "Индекс: " . $formData['index'] . "\n";
    $emailBody .= "Дом: " . $formData['house'] . "\n";
    $emailBody .= "Корпус: " . $formData['corpus'] . "\n";
    $emailBody .= "Квартира: " . $formData['apartment'] . "\n";
    $emailBody .= "Имя: " . $formData['user-name'] . "\n";
    $emailBody .= "Фамилия: " . $formData['user-lastname'] . "\n";
    $emailBody .= "Телефон: " . $formData['telephone'] . "\n";
    $emailBody .= "Email: " . $formData['email'] . "\n";
    $emailBody .= "Метод доставки: " . $formData['delivery-method'] . "\n";
    $emailBody .= "Метод оплаты: " . $formData['payment-method'] . "\n";
    $emailBody .= "Комментарий: " . $formData['comments'] . "\n";
    $emailBody .= "Включить звонок: " . $formData['call-me'] . "\n";
    $emailBody .= "Согласие на обработку данных: " . $formData['privacy'] . "\n";

    $emailBody .= "\n\nТовары:\n";

    foreach ($items as $item) {
        $emailBody .= "Название: " . $item['title'] . "\n";
        $emailBody .= "Количество: " . $item['quantity'] . "\n";
        $emailBody .= "Цена: " . $item['price'] . " рублей\n\n";
    }

    $to = 'chernigovsky108@gmail.com';

    $subject = 'Новый заказ на сайте';

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";
    $headers .= "From: andrey@andrew.ru" . "\r\n";

    if (mail($to, $subject, $emailBody, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Письмо отправлено']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Не удалось отправить письмо']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Не удалось обработать данные']);
}
