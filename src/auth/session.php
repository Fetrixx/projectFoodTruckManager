<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}


function destroySession() {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), 
            '', 
            time() - 42000,
            $params["path"], 
            $params["domain"],
            $params["secure"], 
            $params["httponly"]
        );
    }
    session_destroy();
}

function checkAuth() {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: /projectFoodTruckManager/public/login.php");
        exit;
    }
}

function requireAdmin() {
    if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
        header('HTTP/1.0 403 Forbidden');
        exit('Acceso prohibido');
    }
}