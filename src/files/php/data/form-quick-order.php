<?php
header('Content-Type: application/json');
error_log("Получен запрос form-quick-order.php");

require_once __DIR__ . '/../config/config.php';

$raw_input = file_get_contents('php://input');
error_log("Сырые входящие данные: " . $raw_input);

// данные не пустые
if (empty($raw_input)) {
    echo json_encode([
        'success' => false,
        'message' => 'Не получены данные',
        'error' => 'Пустой запрос',
        'received_data' => null
    ]);
    exit;
}

$data = json_decode($raw_input, true);
error_log("Декодированные данные: " . print_r($data, true));

// JSON корректный
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'success' => false,
        'message' => 'Неверный формат данных',
        'error' => json_last_error_msg(),
        'received_data' => $raw_input
    ]);
    exit;
}

if ($data && isset($data['name']) && isset($data['phone'])) {
    error_log("Данные валидны, обрабатываем...");

    $emailBody = " *Имя:* " . $data['name'] . "\n";
    $emailBody .= " *Телефон:* " . $data['phone'] . "\n";
    if (isset($data['message'])) {
        $emailBody .= " *Сообщение:* " . $data['message'] . "\n";
    }

    // на email
    $to = 'chernigovsky108@gmail.com';
    $subject = 'Новый заказ звонка';
    $headers = array(
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'From: andrey@andrew.ru',
        'Reply-To: andrey@andrew.ru',
        'X-Mailer: PHP/' . phpversion()
    );

    // в телеграмм
    $CHAT_ID = CHAT_ID;
    $TOKEN = TOKEN;
    $message = urlencode($emailBody);
    $url = "https://api.telegram.org/bot$TOKEN/sendMessage?chat_id=$CHAT_ID&text=$message&parse_mode=Markdown";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $telegram_success = false;

    if ($response === false) {
        error_log("Ошибка при отправке запроса в Telegram: " . curl_error($ch));
    } else {
        $responseData = json_decode($response, true);
        $telegram_success = isset($responseData['ok']) && $responseData['ok'];
        error_log("Ответ Telegram: " . print_r($responseData, true));
    }

    curl_close($ch);

    // Пробуем отправить email (но не зависим от его успеха)
    $mail_result = mail($to, $subject, $emailBody, implode("\r\n", $headers));
    error_log("Результат отправки email: " . ($mail_result ? "успешно" : "неудачно"));

    // Формируем ответ на основе успеха отправки в Telegram
    $response_data = [
        'success' => $telegram_success,
        'message' => $telegram_success ? 'Заявка успешно отправлена' : 'Ошибка при отправке заявки',
        'telegram_sent' => $telegram_success,
        'email_sent' => $mail_result,
        'received_data' => $data
    ];

    echo json_encode($response_data);
    exit;

} else {
    $response_data = [
        'success' => false,
        'message' => 'Не удалось обработать данные',
        'error' => 'Отсутствуют обязательные поля name и/или phone',
        'received_data' => $data
    ];

    echo json_encode($response_data);
    exit;
}