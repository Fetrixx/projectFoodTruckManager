<?php
// edit_reserva.php
require_once __DIR__ . '/../src/auth/session.php';
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/DAO/ReservaDAO.php';

session_start();

$db = new Src\Config\Database();
$reservaDAO = new Src\DAO\ReservaDAO($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $reservaId = $_GET['id'];
    $reserva = $reservaDAO->getReservaById($reservaId); // Método que debes implementar

    if (!$reserva || $reserva['usuario_id'] !== $_SESSION['user_id']) {
        // Manejar el error si la reserva no existe o no pertenece al usuario
        header('Location: reservas.php');
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Implementar la lógica para actualizar la reserva aquí
}
?>

<!-- Aquí va el HTML para mostrar el formulario de edición -->