<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

require_once __DIR__ . '/../src/config/Database.php';
require_once __DIR__ . '/../src/DAO/FavoritoDAO.php';
require_once __DIR__ . '/../src/DAO/FoodTruckDAO.php';

use Src\Config\Database;
use Src\DAO\FavoritoDAO;
use Src\DAO\FoodTruckDAO;

$db = new Database();
$favoritoDAO = new FavoritoDAO($db);
$foodTruckDAO = new FoodTruckDAO($db);

$userId = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? '';

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
$pageTitle = "Mis Favoritos";
include __DIR__ . '/../templates/header.php';
?>

<section class="max-w-6xl mx-auto bg-white rounded-lg custom-shadow p-8 my-12">
    <h2 class="font-heading text-3xl text-primary mb-8 text-center">Tus Food Trucks Favoritos</h2>

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
                            <!-- Aquí podrías mostrar rating si tienes -->
                            <!-- Ejemplo: -->
                            <?php
                            // Si tienes rating en tu tabla o calculado, muéstralo aquí
                            // Por ejemplo, si $foodtruck['rating'] existe:
                            if (isset($foodtruck['rating'])) {
                                echo '<div class="flex text-orange-400">';
                                echo str_repeat('★', intval($foodtruck['rating'])) . str_repeat('☆', 5 - intval($foodtruck['rating']));
                                echo '</div>';
                            }
                            ?>
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
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
