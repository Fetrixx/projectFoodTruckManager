<?php
require_once __DIR__ . '/session.php';

destroySession();
header('Location: /projectFoodTruckManager/public/login.php');
exit;