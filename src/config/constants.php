<?php
// Generar nuevo hash para la contraseña
$password = 'elias';
$nombre_presentador = 'Elias Medina';
// define('VALID_USERNAME', 'Elias');
define('VALID_USERNAME', 'Elias');
define('VALID_EMAIL', 'elias.medina@mail.com');
define('VALID_PASSWORD', password_hash($password, PASSWORD_DEFAULT));
define('PRESENTADO_POR', $nombre_presentador);
?>