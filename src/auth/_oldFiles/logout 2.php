<?php
require_once __DIR__ . '/session.php';

session_start();
destroySession();
header('Location: /projectFoodTruckManager/public/login.php');
exit;