<?php

namespace FUNCTIONS;

function getParamsAutosygnals($type)
{
    session_start();

    if (isset($_GET['SELECT'])) {
        $_SESSION[$type] = $_GET;
    } elseif (!isset($_GET['SELECT']) && isset($_SESSION[$type])) {
        $savedParams = $_SESSION[$type];
        $redirect_url = $_SERVER['PHP_SELF'] . '?' . http_build_query($savedParams);
        session_unset();
        session_destroy();
        header("Location: $redirect_url");
        exit();
    }

}
