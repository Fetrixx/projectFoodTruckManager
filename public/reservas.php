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


// ERROR: NO ENCUENTRA EL ID USUARIO 
if (!$userId) {
    // header('Location: login.php');
    // exit;
}

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    $reservas = $reservaDAO->getReservasByUsuarioId($userId, true);
} else {
    // Obtener reservas del usuario actual si no es admin
    $reservas = $reservaDAO->getReservasByUsuarioId($userId);
}
// Obtener todos los food trucks para mapear nombre por id
$foodtrucks = $foodtruckDAO->getAllFoodTrucks();
$foodtrucksMap = [];
foreach ($foodtrucks as $ft) {
    $foodtrucksMap[$ft['id']] = $ft['nombre'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
        $reservaId = $_POST['id'];
        $reserva = $reservaDAO->getReservaById($reservaId);

        if ($reserva && $reserva['usuario_id'] === $userId) {
            $reservaDAO->deleteReserva($reservaId);
            // Redirigir o mostrar un mensaje de éxito
            header('Location: reservas.php?message=Reserva eliminada con éxito');
            exit;
        }
    }
    if (isset($_POST['action']) && $_POST['action'] === 'cancel' && isset($_POST['id'])) {
        $reservaId = $_POST['id'];
        $reserva = $reservaDAO->getReservaById($reservaId);

        if ($reserva && $reserva['usuario_id'] === $userId) {
            $reservaDAO->updateReservaEstado($reservaId, 'cancelada');
            header('Location: reservas.php?message=Reserva cancelada con éxito');
            exit;
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'complete' && isset($_POST['id'])) {
        $reservaId = $_POST['id'];
        $reserva = $reservaDAO->getReservaById($reservaId);

        if ($reserva && $reserva['usuario_id'] === $userId) {
            $reservaDAO->updateReservaEstado($reservaId, 'confirmada');
            header('Location: reservas.php?message=Reserva completada con éxito');
            exit;
        }
    }
}

$pageTitle = "Dashboard Reservas";

// include BASE_PATH . '/templates/header.php';
include __DIR__ . '/../templates/header.php';
?>

<section class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm p-6 md:p-8 my-12">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="font-heading text-3xl text-primary">Dashboard de Reservas</h1>
            <p class="text-gray-600 mt-2">Administra y realiza un seguimiento de todas tus reservas</p>
        </div>
        <a href="booking.php"
            class="bg-secondary hover:bg-orange-600 text-white font-medium py-3 px-6 rounded-lg transition flex items-center shadow-md">
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

                    <!-- 1. Encabezado principal -->
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold text-xl text-gray-800"><?= htmlspecialchars($foodtruckName) ?></h3>
                        <span class="status-badge badge-<?= htmlspecialchars($reserva['estado'] ?? 'pendiente') ?>">
                            <?= htmlspecialchars($reserva['estado'] ?? 'pendiente') ?>
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm mb-4">ID: <?= htmlspecialchars($reserva['id']) ?></p>

                    <!-- 2. Usuario y fecha/hora -->
                    <div class="flex space-x-6 mb-4 text-gray-600">
                        <div class="flex items-center">
                            <i class="material-icons mr-2 text-sm">person</i>
                            <span><?= htmlspecialchars($reserva['usuario_nombre']) ?></span>
                        </div>
                        <div class="flex items-center">
                            <i class="material-icons mr-2 text-sm">schedule</i>
                            <span><?= htmlspecialchars($reserva['fecha'] ?? '') ?>
                                <?= htmlspecialchars($reserva['hora'] ?? '') ?></span>
                        </div>
                    </div>

                    <!-- 3. Lista de items -->
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
                                            <p class="text-xs text-gray-500 mt-1">
                                                <?= htmlspecialchars($item['descripcion'] ?? '') ?>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="block font-semibold">$<?= number_format($item['precio_unitario'] * $item['cantidad'], 2) ?></span>
                                            <span class="text-xs text-gray-500"><?= htmlspecialchars($item['cantidad']) ?> x
                                                $<?= number_format($item['precio_unitario'], 2) ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>



                    <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                        <span class="font-bold text-lg">Total: $<?= number_format($reserva['total'], 2) ?></span>
                        <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Tu
                            reserva</span>
                        <!--   <div>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $reserva['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                            </form>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $reserva['id'] ?>">
                                <input type="hidden" name="action" value="cancel">
                                <button type="submit" class="text-orange-600 hover:underline">Cancelar</button>
                            </form>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $reserva['id'] ?>">
                                <input type="hidden" name="action" value="complete">
                                <button type="submit" class="text-green-600 hover:underline">Confirmar</button>
                            </form>
                           Opcional: Editar
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $reserva['id'] ?>">
                                <input type="hidden" name="action" value="edit">
                                <button type="submit" class="text-blue-600 hover:underline">Editar</button>
                            </form>
                          
                        </div>  -->
                    </div>

                    <div class="mt-4 max-w-md mx-auto">
                        <div class="flex flex-wrap gap-2">
                            <!-- Cancelar -->
                            <form action="" method="POST" class="flex-1 min-w-[120px]">
                                <input type="hidden" name="id" value="<?= $reserva['id'] ?>">
                                <input type="hidden" name="action" value="cancel">
                                <button type="submit"
                                    class="w-full px-3 py-2 border border-orange-600 text-orange-600 rounded-md bg-orange-50 hover:bg-orange-600 hover:text-white transition focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1"
                                    aria-label="Cancelar reserva <?= htmlspecialchars($reserva['id']) ?>">
                                    Cancelar
                                </button>
                            </form>

                            <!-- Confirmar -->
                            <form action="" method="POST" class="flex-1 min-w-[120px]">
                                <input type="hidden" name="id" value="<?= $reserva['id'] ?>">
                                <input type="hidden" name="action" value="complete">
                                <button type="submit"
                                    class="w-full px-3 py-2 border border-green-600 text-green-600 rounded-md bg-green-50 hover:bg-green-600 hover:text-white transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1"
                                    aria-label="Confirmar reserva <?= htmlspecialchars($reserva['id']) ?>">
                                    Confirmar
                                </button>
                            </form>

                            <!-- Eliminar (solo admin), ocupa todo el ancho -->
                            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                                <form action="" method="POST" class="flex-1 min-w-[120px]">
                                    <input type="hidden" name="id" value="<?= $reserva['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit"
                                        class="w-full px-3 py-2 border border-red-600 text-red-600 rounded-md bg-red-50 hover:bg-red-600 hover:text-white transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                        aria-label="Eliminar reserva <?= htmlspecialchars($reserva['id']) ?>">
                                        Eliminar
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>





                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>
</section>


<style>
    .reserva-card {
        transition: all 0.3s ease;
        border-left: 4px solid;
        border-radius: 8px;
        overflow: hidden;
    }

    .reserva-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .user-reserva {
        border-left-color: #f97316;
        background: linear-gradient(to right, #fff7ed, #ffffff);
    }

    .other-reserva {
        border-left-color: #d1d5db;
        background: linear-gradient(to right, #f9fafb, #ffffff);
    }

    .item-list {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 8px;
    }

    /* Scrollbar personalizada */
    .item-list::-webkit-scrollbar {
        width: 6px;
    }

    .item-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .item-list::-webkit-scrollbar-thumb {
        background: #c5c5c5;
        border-radius: 10px;
    }

    .item-list::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .badge-pendiente {
        background-color: #fef3c7;
        color: #d97706;
    }

    .badge-confirmada {
        background-color: #dcfce7;
        color: #16a34a;
    }

    .badge-completada {
        background-color: #dbeafe;
        color: #2563eb;
    }

    .badge-cancelada {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .empty-state {
        background-color: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .hidden-item {
        display: none;
    }
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>