<?php
// reservas.php

define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/DAO/FoodtruckDAO.php';
require_once __DIR__ . '/../src/DAO/ReservaDAO.php';
// require_once __DIR__ . '/../src/DAO/FavoritoDAO.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Definir ruta base para inclusiones
// define('BASE_PATH', realpath(__DIR__ . '/../'));

// Incluir archivos necesarios usando ruta absoluta
// require_once BASE_PATH . '/src/auth/session.php';
// require_once BASE_PATH . '/src/config/database.php';
// require_once BASE_PATH . '/src/DAO/ReservaDAO.php';
// require_once BASE_PATH . '/src/DAO/FoodTruckDAO.php';

checkAuth();

$db = new Src\Config\Database();
$reservaDAO = new Src\DAO\ReservaDAO($db);
$foodtruckDAO = new Src\DAO\FoodTruckDAO($db);

$userId = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? '';
// error_log($_SESSION);
error_log($userId);
echo $userId;
error_log($username);
echo $username;

echo '<pre>';
print_r($_SESSION);
echo '</pre>';

// ERROR: NO ENCUENTRA EL ID USUARIO 
if (!$userId) {
    // header('Location: login.php');
    // exit;
}

// Obtener reservas del usuario actual
$reservas = $reservaDAO->getReservasByUsuarioId($userId);

// Obtener todos los food trucks para mapear nombre por id
$foodtrucks = $foodtruckDAO->getAllFoodTrucks();
$foodtrucksMap = [];
foreach ($foodtrucks as $ft) {
    $foodtrucksMap[$ft['id']] = $ft['nombre'];
}

// include BASE_PATH . '/templates/header.php';
include __DIR__ . '/../templates/header.php';
?>

<section class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm p-6 md:p-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="font-heading text-3xl text-primary">Dashboard de Reservas</h1>
            <p class="text-gray-600 mt-2">Administra y realiza un seguimiento de todas tus reservas</p>
        </div>
        <a href="booking.php" class="bg-secondary hover:bg-orange-600 text-white font-medium py-3 px-6 rounded-lg transition flex items-center shadow-md">
            <i class="material-icons mr-2">add</i> Nuevo Pedido
        </a>
    </div>

    <?php if (empty($reservas)): ?>
        <div class="col-span-full empty-state text-center p-12 border-2 border-dashed rounded-lg">
            <i class="material-icons text-6xl text-gray-300 mb-4">inventory_2</i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay reservas registradas</h3>
            <p class="text-gray-600 mb-4">Cuando realices una reserva, aparecerá aquí</p>
            <a href="booking.php" class="inline-flex items-center text-secondary font-medium hover:underline">
                <i class="material-icons mr-1">add</i> Crear primera reserva
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($reservas as $reserva):
                $foodtruckName = $foodtrucksMap[$reserva['foodtruck_id']] ?? 'Food Truck Desconocido';
                $items = $reservaDAO->getItemsByReservaId($reserva['id']);
            ?>
            <div class="reserva-card p-6 border rounded-lg shadow hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-semibold text-xl text-gray-800"><?= htmlspecialchars($foodtruckName) ?></h3>
                        <p class="text-gray-500 text-sm mt-1">ID: <?= htmlspecialchars($reserva['id']) ?></p>
                    </div>
                    <span class="status-badge badge-<?= htmlspecialchars($reserva['estado'] ?? 'pendiente') ?>">
                        <?= htmlspecialchars($reserva['estado'] ?? 'pendiente') ?>
                    </span>
                </div>

                <div class="mb-4">
                    <div class="flex items-center text-gray-600 mb-2">
                        <i class="material-icons mr-2 text-sm">person</i>
                        <span><?= htmlspecialchars($username) ?></span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="material-icons mr-2 text-sm">schedule</i>
                        <span><?= htmlspecialchars($reserva['fecha'] ?? '') ?> <?= htmlspecialchars($reserva['hora'] ?? '') ?></span>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="font-medium text-gray-700 mb-2 flex items-center">
                        <i class="material-icons mr-2 text-sm">list</i>
                        Items:
                    </p>
                    <div class="item-list pl-2">
                        <ul class="space-y-2">
                            <?php foreach ($items as $item): ?>
                                <li class="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                    <div>
                                        <span class="font-medium"><?= htmlspecialchars($item['menu_nombre']) ?></span>
                                        <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($item['descripcion'] ?? '') ?></p>
                                    </div>
                                    <div class="text-right">
                                        <span class="block font-semibold">$<?= number_format($item['precio_unitario'] * $item['cantidad'], 2) ?></span>
                                        <span class="text-xs text-gray-500"><?= htmlspecialchars($item['cantidad']) ?> x $<?= number_format($item['precio_unitario'], 2) ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                    <span class="font-bold text-lg">Total: $<?= number_format($reserva['total'], 2) ?></span>
                    <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Tu reserva</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<style>
/* Aquí puedes agregar o mantener los estilos CSS que usas para la página */
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>

