<?php
require_once __DIR__ . '/../src/auth/session.php';

session_start();
destroySession();

// Redirección absoluta
header("Location: /projectFoodTruckManager/public/login.php");
exit;