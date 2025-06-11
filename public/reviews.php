<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

require_once __DIR__ . '/../src/config/Database.php';
require_once __DIR__ . '/../src/DAO/ReviewDAO.php';
require_once __DIR__ . '/../src/DAO/FoodTruckDAO.php';

use Src\Config\Database;
use Src\DAO\ReviewDAO;
use Src\DAO\FoodTruckDAO;

$db = new Database();
$reviewDAO = new ReviewDAO($db);
$foodTruckDAO = new FoodTruckDAO($db);

$userId = $_SESSION['user_id'] ?? null; // Asegúrate de guardar el ID del usuario en sesión
$username = $_SESSION['username'] ?? '';

$error = '';
$successMsg = '';

// Manejo de formularios POST: crear, actualizar o eliminar reseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $review_id = intval($_POST['review_id'] ?? 0);
        if ($review_id > 0 && $userId) {
            $deleted = $reviewDAO->deleteReview($review_id, $userId);
            if ($deleted) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?msg=deleted");
                exit;
            } else {
                $error = "No se pudo eliminar la reseña.";
            }
        }
    } elseif ($action === 'update') {
        $review_id = intval($_POST['review_id'] ?? 0);
        $rating = intval($_POST['rating'] ?? 0);
        $comentario = trim($_POST['comentario'] ?? '');

        if ($review_id > 0 && $rating >= 1 && $rating <= 5 && $comentario !== '' && $userId) {
            $updated = $reviewDAO->updateReview($review_id, $userId, $rating, $comentario);
            if ($updated) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?msg=updated");
                exit;
            } else {
                $error = "No se pudo actualizar la reseña.";
            }
        } else {
            $error = "Por favor completa todos los campos correctamente para actualizar.";
        }
    } elseif ($action === 'create') {
        $foodtruck_id = intval($_POST['foodtruck_id'] ?? 0);
        $rating = intval($_POST['rating'] ?? 0);
        $comentario = trim($_POST['comentario'] ?? '');

        if ($foodtruck_id > 0 && $rating >= 1 && $rating <= 5 && $comentario !== '' && $userId) {
            $success = $reviewDAO->createReview($userId, $foodtruck_id, $rating, $comentario);
            if ($success) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?msg=success");
                exit;
            } else {
                $error = "Error al guardar la reseña. Intenta nuevamente.";
            }
        } else {
            $error = "Por favor completa todos los campos correctamente.";
        }
    }
}

// Mensajes de éxito por GET
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'success':
            $successMsg = "Reseña guardada correctamente.";
            break;
        case 'updated':
            $successMsg = "Reseña actualizada correctamente.";
            break;
        case 'deleted':
            $successMsg = "Reseña eliminada correctamente.";
            break;
    }
}

// Obtener lista de food trucks para el select
$foodtrucks = $foodTruckDAO->getAllFoodTrucks();

// Obtener últimas reseñas (puedes limitar la cantidad que quieras)
$latestReviews = $reviewDAO->getReviewsByFoodtruckIds(array_column($foodtrucks, 'id'));

$pageTitle = "Sistema de Reseñas";
include __DIR__ . '/../templates/header.php';
?>

<section class="max-w-6xl mx-auto bg-white rounded-lg custom-shadow p-8 my-12">
    <h2 class="font-heading text-3xl text-primary mb-8 text-center">Sistema de Reseñas</h2>

    <?php if (!empty($error)): ?>
        <div class="error-message mb-4 text-red-600 font-semibold"><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($successMsg)): ?>
        <div class="success-message mb-4 text-green-600 font-semibold"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Formulario de reseña -->
        <div class="space-y-6">
            <h3 class="text-xl font-semibold text-primary">Deja tu reseña</h3>
            <form id="reviewForm" method="POST" class="space-y-4">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="review_id" id="reviewId" value="">

                <div>
                    <label for="foodtruckSelect" class="block text-textPrimary mb-2">Selecciona un Food Truck</label>
                    <select id="foodtruckSelect" name="foodtruck_id" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-secondary" required>
                        <option value="">-- Selecciona --</option>
                        <?php foreach ($foodtrucks as $ft): ?>
                            <option value="<?= $ft['id'] ?>"><?= htmlspecialchars($ft['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div> 
                <div>
                    <label class="block text-textPrimary mb-2">Calificación</label>
                    <div class="rating flex space-x-1">
                        <span class="star text-3xl cursor-pointer" data-value="1">☆</span>
                        <span class="star text-3xl cursor-pointer" data-value="2">☆</span>
                        <span class="star text-3xl cursor-pointer" data-value="3">☆</span>
                        <span class="star text-3xl cursor-pointer" data-value="4">☆</span>
                        <span class="star text-3xl cursor-pointer" data-value="5">☆</span>
                    </div>
                    <input type="hidden" id="ratingValue" name="rating" required>
                </div>

                <div>
                    <label for="reviewComment" class="block text-textPrimary mb-2">Comentario</label>
                    <textarea id="reviewComment" name="comentario" rows="4" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-secondary" placeholder="Cuéntanos tu experiencia..." required></textarea>
                </div>

                <button type="submit" class="bg-secondary text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition flex items-center">
                    <i class="material-icons mr-2">rate_review</i>
                    <span id="submitButtonText">Enviar Reseña</span>
                </button>
                <button type="button" id="cancelEditBtn" class="ml-4 px-4 py-2 bg-gray-300 rounded-lg hidden" onclick="cancelEdit()">Cancelar</button>
            </form>
        </div>

        <!-- Listado de reseñas -->
        <div>
            <h3 class="text-xl font-semibold text-primary mb-4">Reseñas Recientes</h3>
            <div id="reviewsContainer" class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                <?php if (empty($latestReviews)): ?>
                    <p class="text-textSecondary">No hay reseñas aún.</p>
                <?php else: ?>
                    <?php foreach ($latestReviews as $review): ?>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-start">
                                <h4 class="font-semibold"><?= htmlspecialchars($review['foodtruck_nombre'] ?? 'Food Truck') ?></h4>
                                <div class="flex text-orange-400">
                                    <?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?>
                                </div>
                            </div>
                            <p class="mt-2 text-textSecondary"><?= nl2br(htmlspecialchars($review['comentario'])) ?></p>
                            <div class="flex justify-between items-center mt-3 text-sm text-gray-500">
                                <span>Por: <?= htmlspecialchars($review['user_name']) ?></span>
                                <span><?= date('d M Y H:i', strtotime($review['fecha'])) ?></span>
                            </div>
                            <?php if ($review['usuario_id'] == $userId): ?>
                                <div class="mt-2 flex gap-2">
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que quieres eliminar esta reseña?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                        <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                    </form>
                                    <button type="button" class="text-blue-600 hover:underline" 
                                        onclick="editReview(<?= $review['id'] ?>, <?= $review['rating'] ?>, '<?= htmlspecialchars(addslashes($review['comentario'])) ?>', <?= $review['foodtruck_id'] ?>)">
                                        Editar
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    // Sistema de estrellas interactivo
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            setRating(value);
        });
    });

    function setRating(value) {
        document.querySelectorAll('.star').forEach((s, i) => {
            s.textContent = i < value ? '★' : '☆';
            s.style.color = i < value ? '#F97316' : '#9CA3AF';
        });
        document.getElementById('ratingValue').value = value;
    }

    // Editar reseña: carga los datos en el formulario
    function editReview(id, rating, comentario, foodtruck_id) {
        document.getElementById('formAction').value = 'update';
        document.getElementById('reviewId').value = id;
        document.getElementById('foodtruckSelect').value = foodtruck_id;
        document.getElementById('reviewComment').value = comentario;
        setRating(rating);

        document.getElementById('submitButtonText').textContent = 'Actualizar Reseña';
        document.getElementById('cancelEditBtn').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Cancelar edición
    function cancelEdit() {
        document.getElementById('formAction').value = 'create';
        document.getElementById('reviewId').value = '';
        document.getElementById('foodtruckSelect').value = '';
        document.getElementById('reviewComment').value = '';
        setRating(0);

        document.getElementById('submitButtonText').textContent = 'Enviar Reseña';
        document.getElementById('cancelEditBtn').classList.add('hidden');
    }

    // Inicializar rating en 0 al cargar
    setRating(0);
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
