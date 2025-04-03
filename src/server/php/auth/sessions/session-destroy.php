<?php

namespace AUTH\SESSIONS;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $_SESSION = [];
    session_destroy();
}
