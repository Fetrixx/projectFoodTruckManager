<?php
session_start();

// Verificar autenticación
function checkAuth() {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: /projectFoodTruckManager/public/login.php");
        exit;
    }
}

// Destruir sesión
function destroySession() {
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(), '', 0, '/');
}

