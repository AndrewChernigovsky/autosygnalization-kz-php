<?php

function sendToTelegram($message)
{
  $ch = curl_init('https://api.telegram.org/bot' . TELEGRAM_BOT_TOKEN . '/sendMessage');
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, 'chat_id=' . TELEGRAM_CHAT_ID . '&parse_mode=html&text=' . urlencode($message));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $response = curl_exec($ch);
  curl_close($ch);
}