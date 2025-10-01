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

    // Проверяем наличие параметров фильтрации (исключая обязательные type и SELECT)
    $filterParams = [
        'autosetup', 'control-phone', 'free-monitoring', 'bluetooth-smart', 
        'block-engine-can', 'control-before-start', 'data-level-bensin', 
        'smart-diagnostic', 'vnedorojnik', 'legkoe-avto', 'min-value-cost', 
        'max-value-cost'
    ];
    
    $hasActiveFilters = !empty(array_intersect_key($_GET, array_flip($filterParams)));
    $hasBasicParams = isset($_GET['type']) || isset($_GET['SELECT']);
    $isReset = isset($_GET['reset']) && $_GET['reset'] == '1';

    // Если это сброс - очищаем сессию и не восстанавливаем фильтры
    if ($isReset && isset($_SESSION[$sessionKey])) {
        unset($_SESSION[$sessionKey]);
        log_message("RESET: Cleared session for $sessionKey");
        
        // Перенаправляем без параметра reset
        $cleanParams = $_GET;
        unset($cleanParams['reset']);
        $redirect_url = $_SERVER['PHP_SELF'] . '?' . http_build_query($cleanParams);
        header("Location: $redirect_url");
        exit();
    }

    // СНАЧАЛА проверяем, нужно ли восстанавливать фильтры
    if (!$hasActiveFilters && $hasBasicParams && isset($_SESSION[$sessionKey]) && !$isReset) {
        $savedParams = $_SESSION[$sessionKey];
        
        // Проверяем, есть ли в сохраненных параметрах активные фильтры
        $savedHasFilters = !empty(array_intersect_key($savedParams, array_flip($filterParams)));
        
        if ($savedHasFilters) {
            // Объединяем сохраненные фильтры с текущими базовыми параметрами
            $fullParams = array_merge($savedParams, $_GET);
            $redirect_url = $_SERVER['PHP_SELF'] . '?' . http_build_query($fullParams);
            log_message("RESTORING filters for $sessionKey: " . print_r($fullParams, true));
            header("Location: $redirect_url");
            exit();
        }
    }
    
    // Если нет параметров вообще, восстанавливаем все сохраненные
    if (!$hasActiveFilters && !$hasBasicParams && isset($_SESSION[$sessionKey])) {
        $savedParams = $_SESSION[$sessionKey];
        $redirect_url = $_SERVER['PHP_SELF'] . '?' . http_build_query($savedParams);
        log_message("RESTORING all params for $sessionKey: " . print_r($_SESSION[$sessionKey], true));
        header("Location: $redirect_url");
        exit();
    }

    // ТОЛЬКО ПОСЛЕ проверки восстановления - сохраняем новые параметры
    if ($hasActiveFilters || $hasBasicParams) {
        $_SESSION[$sessionKey] = $_GET;
        log_message("SAVING params for $sessionKey: " . print_r($_SESSION[$sessionKey], true));
    } else {
        log_message("NO ACTION taken for $sessionKey");
    }
}
