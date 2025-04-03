<?php

namespace AUTH\SESSIONS;

function destroySession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        $_SESSION = [];
        session_destroy();
    }


}
