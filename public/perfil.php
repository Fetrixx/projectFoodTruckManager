<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

// Incluir DAOs y configuraciones necesarias
require_once __DIR__ . '/../src/config/Database.php';
require_once __DIR__ . '/../src/dao/ReservaDAO.php';
require_once __DIR__ . '/../src/dao/FavoritoDAO.php';
require_once __DIR__ . '/../src/dao/ReviewDAO.php';
require_once __DIR__ . '/../src/dao/FoodTruckDAO.php';

use Src\Config\Database;
use Src\DAO\ReservaDAO;
use Src\DAO\FavoritoDAO;
use Src\DAO\ReviewDAO;
use Src\DAO\FoodTruckDAO;

$db = new Database();
$reservaDAO = new ReservaDAO($db);
$favoritoDAO = new FavoritoDAO($db);
$reviewDAO = new ReviewDAO($db);
$foodTruckDAO = new FoodTruckDAO($db);

$userId = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? null;

if (!$userId) {
    // Usuario no encontrado, manejar error
    die("Error: Usuario no encontrado");
}

// Obtener datos del usuario
$reservas = $reservaDAO->getReservasByUsuarioId($userId);
$reseñas = $reviewDAO->getReviewsByUserId($userId);

// Preparar datos para las secciones
$profileData = [
    'historial' => [],
    'reseñas' => []
];

foreach ($reservas as $reserva) {
    $items = $reservaDAO->getItemsByReservaId($reserva['id']);
    $foodTruck = $foodTruckDAO->getFoodTruckById($reserva['foodtruck_id']);

    $profileData['historial'][] = [
        'id' => $reserva['id'],
        'fecha' => date('d/m/Y', strtotime($reserva['fecha'])),
        'hora' => date('H:i', strtotime($reserva['hora'])),
        'total' => number_format($reserva['total'], 2),
        'estado' => $reserva['estado'],
        'foodtruck' => $foodTruck['nombre'] ?? 'Desconocido',
        'items' => $items
    ];
}

foreach ($reseñas as $reseña) {
    $foodTruck = $foodTruckDAO->getFoodTruckById($reseña['foodtruck_id']);

    $profileData['reseñas'][] = [
        'id' => $reseña['id'],
        'rating' => $reseña['rating'],
        'comentario' => $reseña['comentario'],
        'fecha' => date('d/m/Y', strtotime($reseña['fecha'])),
        'foodtruck' => $foodTruck['nombre'] ?? 'Desconocido'
    ];
}

$pageTitle = "Perfil de Usuario";

include __DIR__ . '/../templates/header.php';

// Variable para controlar qué sección mostrar
$activeTab = $_GET['tab'] ?? 'historial';
?>

<main class="container mx-auto px-6 py-12">
    <section class="max-w-4xl mx-auto bg-white rounded-lg custom-shadow p-8">
        <h1 class="text-3xl font-bold mb-8 text-center">
            Perfil de <?= htmlspecialchars($username) ?>
        </h1>

        <div class="tabs flex border-b mb-6">
            <a href="?tab=historial" 
               class="tab py-2 px-4 font-medium border-b-2 border-transparent hover:text-secondary transition-colors duration-200 <?= $activeTab === 'historial' ? 'active border-secondary text-secondary' : '' ?>">
                Historial de Pedidos
            </a>
            <a href="?tab=favoritos" 
               class="tab py-2 px-4 font-medium border-b-2 border-transparent hover:text-secondary transition-colors duration-200 <?= $activeTab === 'favoritos' ? 'active border-secondary text-secondary' : '' ?>">
                Food Trucks Favoritos
            </a>
            <a href="?tab=reseñas" 
               class="tab py-2 px-4 font-medium border-b-2 border-transparent hover:text-secondary transition-colors duration-200 <?= $activeTab === 'reseñas' ? 'active border-secondary text-secondary' : '' ?>">
                Mis Reseñas
            </a>
        </div>

        <!-- Historial de Pedidos -->
        <?php if ($activeTab === 'historial'): ?>
            <div class="tab-content active" id="historial">
                <?php if (!empty($profileData['historial'])): ?>
                    <div class="space-y-6">
                        <?php foreach ($profileData['historial'] as $reserva): ?>
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-semibold"><?= htmlspecialchars($reserva['foodtruck']) ?></h3>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                                        <?= $reserva['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                                        <?= $reserva['estado'] === 'confirmada' ? 'bg-green-100 text-green-800' : '' ?>
                                        <?= $reserva['estado'] === 'cancelada' ? 'bg-red-100 text-red-800' : '' ?>">
                                        <?= ucfirst($reserva['estado']) ?>
                                    </span>
                                </div>

                                <p class="text-gray-600 mb-2">
                                    <?= $reserva['fecha'] ?> a las <?= $reserva['hora'] ?>
                                </p>

                                <div class="mb-3">
                                    <h4 class="font-medium mb-1">Items:</h4>
                                    <ul class="list-disc pl-5">
                                        <?php foreach ($reserva['items'] as $item): ?>
                                            <li>
                                                <?= $item['cantidad'] ?>x <?= htmlspecialchars($item['menu_nombre'] ?? 'Producto') ?> - 
                                                $<?= number_format($item['precio_unitario'], 2) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>

                                <p class="text-right font-bold text-lg">
                                    Total: $<?= $reserva['total'] ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center py-8 text-gray-500">No tienes pedidos registrados</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Food Trucks Favoritos - Incrustación directa -->
        <?php if ($activeTab === 'favoritos'): ?>
            <div class="tab-content active" id="favoritos">
                <?php
                // Incluir directamente la lógica de favoritos
                $error = '';
                $successMsg = '';
                
                // Manejo de eliminación de favorito
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
                    $foodtruck_id = intval($_POST['foodtruck_id'] ?? 0);
                    if ($foodtruck_id > 0 && $userId) {
                        $deleted = $favoritoDAO->deleteFavorito($userId, $foodtruck_id);
                        if ($deleted) {
                            $successMsg = "Food truck eliminado de favoritos.";
                        } else {
                            $error = "No se pudo eliminar el favorito.";
                        }
                    }
                }
                
                // Obtener IDs de favoritos del usuario
                $favoritosIds = $favoritoDAO->getFavoritosByUsuarioId($userId);
                
                // Obtener datos completos de los food trucks favoritos
                $favoritos = [];
                if (!empty($favoritosIds)) {
                    foreach ($favoritosIds as $ftId) {
                        $ft = $foodTruckDAO->getFoodTruckById($ftId);
                        if ($ft) {
                            $favoritos[] = $ft;
                        }
                    }
                }
                ?>
                
                <?php if (!empty($error)): ?>
                    <div class="error-message mb-4 text-red-600 font-semibold"><?= htmlspecialchars($error) ?></div>
                <?php elseif (!empty($successMsg)): ?>
                    <div class="success-message mb-4 text-green-600 font-semibold"><?= htmlspecialchars($successMsg) ?></div>
                <?php endif; ?>
                
                <div id="favoritesContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (empty($favoritos)): ?>
                        <div class="text-center py-12" id="noFavoritesMessage">
                            <i class="material-icons text-5xl text-gray-300 mb-4">favorite_border</i>
                            <p class="text-textSecondary">Aún no tienes food trucks favoritos</p>
                            <p class="mt-2 text-sm text-gray-500">Visita la página de reservas y marca tus favoritos</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($favoritos as $foodtruck): ?>
                            <div class="bg-white border rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-center mb-3">
                                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center">
                                        <?php if (!empty($foodtruck['imagen'])): ?>
                                            <img src="<?= htmlspecialchars($foodtruck['imagen']) ?>" alt="<?= htmlspecialchars($foodtruck['nombre']) ?>" class="w-16 h-16 object-cover rounded-xl">
                                        <?php else: ?>
                                            <i class="material-icons text-4xl text-gray-400">fastfood</i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-semibold"><?= htmlspecialchars($foodtruck['nombre']) ?></h3>
                                        <?php if (isset($foodtruck['rating'])): ?>
                                            <div class="flex text-orange-400">
                                                <?= str_repeat('★', intval($foodtruck['rating'])) . str_repeat('☆', 5 - intval($foodtruck['rating'])) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="text-textSecondary text-sm mb-4"><?= htmlspecialchars($foodtruck['descripcion'] ?? 'Sin descripción') ?></p>
                                <div class="flex justify-between">
                                    <form method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este favorito?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="foodtruck_id" value="<?= intval($foodtruck['id']) ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700 flex items-center">
                                            <i class="material-icons mr-1">delete</i> Eliminar
                                        </button>
                                    </form>
                                    <a href="booking.php?foodtruck=<?= intval($foodtruck['id']) ?>" class="bg-secondary text-white px-3 py-1 rounded-lg text-sm hover:bg-orange-600 transition">
                                        Reservar
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Reseñas -->
        <?php if ($activeTab === 'reseñas'): ?>
            <div class="tab-content active" id="reseñas">
                <?php if (!empty($profileData['reseñas'])): ?>
                    <div class="space-y-6">
                        <?php foreach ($profileData['reseñas'] as $reseña): ?>
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-semibold"><?= htmlspecialchars($reseña['foodtruck']) ?></h3>
                                    <div class="flex items-center">
                                        <span class="text-yellow-500 mr-1">★</span>
                                        <span><?= $reseña['rating'] ?></span>/5
                                    </div>
                                </div>

                                <p class="text-gray-600 mb-2"><?= $reseña['fecha'] ?></p>

                                <p class="text-gray-800"><?= htmlspecialchars($reseña['comentario']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center py-8 text-gray-500">No has escrito ninguna reseña</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>