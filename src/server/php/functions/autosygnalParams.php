<?php

namespace FUNCTIONS;

function log_message($message)
{
  $log_file = sys_get_temp_dir() . '/login-debug.log';
  $timestamp = date('Y-m-d H:i:s');
  file_put_contents($log_file, "[$timestamp] " . $message . "\n", FILE_APPEND);
}

function getParamsAutosygnals($type)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Создаём уникальный ключ только для autosygnal страницы с разными типами
    $sessionKey = $type;
    if ($type === 'get_params_remote_controls') {
        // Для autosygnal всегда добавляем тип (дефолтный = auto)
        $pageType = $_GET['type'] ?? 'auto';
        $sessionKey = $type . '_' . $pageType; // Например: get_params_remote_controls_auto
    }

    if (isset($_GET['SELECT'])) {
        // Сохраняем новые параметры
        if (isset($_SESSION[$sessionKey])) {
            $_SESSION[$sessionKey] = array_merge($_SESSION[$sessionKey], $_GET);
        } else {
            $_SESSION[$sessionKey] = $_GET;
        }
        
        // ПРОВЕРЯЕМ: если текущие GET параметры НЕ полные по сравнению с сохранёнными
        $savedParams = $_SESSION[$sessionKey];
        $currentParams = $_GET;
        
        // Если в сохранённых параметрах есть фильтры, которых нет в текущих GET
        $missingParams = array_diff_key($savedParams, $currentParams);
        
        if (!empty($missingParams)) {
            // Объединяем сохранённые с текущими и делаем редирект
            $fullParams = array_merge($savedParams, $currentParams);
            $redirect_url = $_SERVER['PHP_SELF'] . '?' . http_build_query($fullParams);
            log_message("REDIRECTING with full params for $sessionKey: " . print_r($fullParams, true));
            header("Location: $redirect_url");
            exit();
        }
        
        log_message("SAVING params for $sessionKey: " . print_r($_SESSION[$sessionKey], true));
    } elseif (!isset($_GET['SELECT']) && isset($_SESSION[$sessionKey])) {
        $savedParams = $_SESSION[$sessionKey];
        $redirect_url = $_SERVER['PHP_SELF'] . '?' . http_build_query($savedParams);
        log_message("RESTORING params for $sessionKey: " . print_r($_SESSION[$sessionKey], true));
        header("Location: $redirect_url");
        exit();
    } else {
        log_message("NO ACTION taken for $sessionKey");
    }
}
