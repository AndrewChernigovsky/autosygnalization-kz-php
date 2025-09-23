<?php

namespace AUTH\SESSIONS;

require_once __DIR__ . '/../logger.php';

use function AUTH\log_message;

function destroySession(): void
{
    log_message('destroySession() called');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        $_SESSION = [];
        session_destroy();
    }


}
