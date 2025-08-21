<?php

namespace AUTH;

function log_message($message)
{
    $log_file = sys_get_temp_dir() . '/login-debug.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] " . $message . "\n", FILE_APPEND);
}
