<?php
function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

function checkAuth() {
    if (!isLoggedIn()) {
        header('Location: /projectFoodTruckManager/public/login.php');
        exit;
    }
}

function destroySession() {
    $_SESSION = array();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
}