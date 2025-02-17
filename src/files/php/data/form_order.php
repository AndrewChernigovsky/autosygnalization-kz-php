<?php

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {

    file_put_contents('order_log.txt', print_r($data, true));

    echo json_encode(['success' => true]);
} else {

    echo json_encode(['success' => false, 'message' => 'Не удалось обработать данные']);
}
?>
