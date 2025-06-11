<?php
session_start();
require_once __DIR__ . '/../src/config/Database.php';
require_once __DIR__ . '/../src/DAO/ReservaDAO.php';
// require_once __DIR__ . '/../src/DAO/ReservaItemDAO.php';

$db = new Src\Config\Database();
$reservaDAO = new Src\DAO\ReservaDAO($db);
$reservaItemDAO = new Src\DAO\ReservaDAO($db);

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];
$foodtruck_id = $data['foodtruck_id'];
$items = $data['items'];
$total = $data['total'];

// Fecha y hora actual
$fecha = date('Y-m-d');
$hora = date('H:i:s');

$response = ['success' => false, 'message' => '', 'reserva_id' => null];

// Crear reserva
$reserva_id = $reservaDAO->createReserva($user_id, $foodtruck_id, $fecha, $hora, $total);

if ($reserva_id) {
    // Guardar items de la reserva
    $allItemsSaved = true;
    
    foreach ($items as $item) {
        $saved = $reservaItemDAO->createReservaItem(
            $reserva_id, 
            $item['id'], 
            $item['quantity'], 
            $item['precio']
        );
        
        if (!$saved) {
            $allItemsSaved = false;
            break;
        }
    }
    
    if ($allItemsSaved) {
        $response['success'] = true;
        $response['reserva_id'] = $reserva_id;
    } else {
        // Si falla algún item, borrar la reserva
        $reservaDAO->deleteReserva($reserva_id);
        $response['message'] = 'Error al guardar los items de la reserva';
    }
} else {
    $response['message'] = 'Error al crear la reserva';
}

header('Content-Type: application/json');
echo json_encode($response);
?>