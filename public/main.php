<?php
define('CHECK_ACCESS', true);

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../src/auth/session.php';
require_once __DIR__ . '/../src/config/Database.php';
require_once __DIR__ . '/../src/DAO/FoodTruckDAO.php';
require_once __DIR__ . '/../src/DAO/ReviewDAO.php';
require_once __DIR__ . '/../src/DAO/FavoritoDAO.php';

// Verificar que el usuario esté autenticado
checkAuth();

$db = new Src\Config\Database();
$foodtruckDAO = new Src\DAO\FoodTruckDAO($db);
$reviewDAO = new Src\DAO\ReviewDAO($db);
$favoritoDAO = new Src\DAO\FavoritoDAO($db);

// Obtener ID del usuario actual
$usuarioId = $_SESSION['user_id'] ?? null;

// Procesar toggle favorito si se envió formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_favorito'], $_POST['foodtruck_id']) && $usuarioId !== null) {
    $foodtruckId = (int) $_POST['foodtruck_id'];

    if ($favoritoDAO->isFavorito($usuarioId, $foodtruckId)) {
        $favoritoDAO->deleteFavorito($usuarioId, $foodtruckId);
        $isFavorite = false;
    } else {
        $favoritoDAO->addFavorito($usuarioId, $foodtruckId);
        $isFavorite = true;
    }

    // Responder con JSON para peticiones AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'is_favorite' => $isFavorite]);
        exit;
    }

    // Redirigir para peticiones normales
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Obtener todos los food trucks
$foodtrucks = $foodtruckDAO->getAllFoodTrucks();

// Obtener IDs de food trucks para traer reseñas
$foodtruckIds = (is_array($foodtrucks) && !empty($foodtrucks)) ? array_column($foodtrucks, 'id') : [];

// Obtener reseñas de esos food trucks
$reviews = $reviewDAO->getReviewsByFoodtruckIds($foodtruckIds);

$favoritosIds = [];
// Obtener favoritos del usuario actual
if ($usuarioId !== null) {
    $favoritos = $favoritoDAO->getFavoritosByUsuarioId($usuarioId, true);
    // echo '<pre>';
// var_dump($favoritos);
// echo '</pre>';
    $favoritosIds = (is_array($favoritos) && !empty($favoritos)) ? array_column($favoritos, 'foodtruck_id') : [];
} else {
    $favoritosIds = [];
}
// $favoritosIds = [1, 2, 3];


$pageTitle = "Inicio";

// Incluir plantilla header
include __DIR__ . '/../templates/header.php';
?>

<!-- Script para manejar favoritos con AJAX -->
<script>
    async function toggleFavorito(foodtruckId, button) {
        const icon = button.querySelector('i');
        const originalIcon = icon.textContent;
        const originalColor = icon.classList.contains('text-red-500') ? 'red' : 'gray';

        // Deshabilitar botón durante la petición
        button.disabled = true;

        try {
            // Mostrar indicador de carga
            icon.textContent = 'hourglass_top';
            icon.classList.remove('text-red-500', 'text-gray-400');
            icon.classList.add('text-blue-500');

            const response = await fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'toggle_favorito': '1',
                    'foodtruck_id': foodtruckId
                })
            });

            if (response.ok) {
                const data = await response.json();

                if (data.success) {
                    // Actualizar visualmente con la respuesta del servidor
                    icon.textContent = data.is_favorite ? 'favorite' : 'favorite_border';
                    icon.classList.remove('text-blue-500');

                    if (data.is_favorite) {
                        icon.classList.add('text-red-500');
                        icon.classList.remove('text-gray-400');
                    } else {
                        icon.classList.add('text-gray-400');
                        icon.classList.remove('text-red-500');
                    }

                    // Animación de feedback
                    button.classList.add('animate-ping');
                    setTimeout(() => button.classList.remove('animate-ping'), 500);
                }
            } else {
                // Restaurar estado original si hay error
                icon.textContent = originalIcon;
                icon.classList.remove('text-blue-500');
                if (originalColor === 'red') {
                    icon.classList.add('text-red-500');
                } else {
                    icon.classList.add('text-gray-400');
                }
                alert('Error al actualizar favoritos');
            }
        } catch (error) {
            console.error('Error al actualizar favoritos:', error);
            // Restaurar estado original
            icon.textContent = originalIcon;
            icon.classList.remove('text-blue-500');
            if (originalColor === 'red') {
                icon.classList.add('text-red-500');
            } else {
                icon.classList.add('text-gray-400');
            }
        } finally {
            button.disabled = false;
        }
    }

    function showFoodTruckLocationOnMap(foodtruckId) {
        const ft = foodtrucks.find(f => f.id === String(foodtruckId));
        if (!ft || !ft.lat || !ft.lng) return;

        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg custom-shadow w-full max-w-2xl max-h-[80vh] overflow-y-auto">
                <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
                    <h3 class="text-xl font-semibold">Ubicación del Food Truck</h3>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-gray-500 hover:text-gray-700">
                        <i class="material-icons">close</i>
                    </button>
                </div>
                <div class="p-6">
                    <div id="map-modal" style="height: 400px;" class="rounded-lg"></div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        // Inicializar el mapa en el modal
        const mapModal = L.map('map-modal').setView([ft.lat, ft.lng], 17);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(mapModal);

        L.marker([ft.lat, ft.lng]).addTo(mapModal)
            .bindPopup(`<b>${ft.nombre}</b><br>${ft.ubicacion}`)
            .openPopup();
    }

    function showReviews(foodtruckId) {
        const reviews = <?= json_encode($reviews) ?>;
        const ftReviews = reviews.filter(r => r.foodtruck_id == foodtruckId);

        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg custom-shadow w-full max-w-2xl max-h-[80vh] overflow-hidden flex flex-col">
                <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center gap-4">
                    <h3 class="text-xl font-semibold flex-1">Reseñas</h3>
                    <a href="reviews.php" class="bg-secondary text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition flex items-center cursor-pointer">
                        <i class="material-icons mr-2">rate_review</i>
                        <span>Agregar Reseña</span>
                    </a>
                    <button onclick="this.closest('div.fixed').remove()" class="text-gray-500 hover:text-gray-700 transition cursor-pointer" aria-label="Cerrar modal">
                        <i class="material-icons text-2xl">close</i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    ${ftReviews.length === 0 ? `
                        <div class="text-center py-8">
                            <i class="material-icons text-5xl text-gray-300 mb-4">reviews</i>
                            <p class="text-textSecondary text-lg">No hay reseñas para este food truck</p>
                        </div>
                    ` : ftReviews.map(review => `
                        <div class="border-b pb-4 last:border-b-0 last:pb-0">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-semibold text-lg">${review.user_name}</h4>
                                <span class="text-sm text-gray-500">${review.fecha}</span>
                            </div>
                            <div class="flex text-orange-400 mb-2" aria-label="Calificación: ${review.rating} de 5 estrellas">
                                ${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}
                            </div>
                            <p class="text-textSecondary text-base leading-relaxed">${review.comentario}</p>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    // Cerrar modal al hacer clic fuera del contenido
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('fixed') && e.target.classList.contains('bg-black')) {
            e.target.remove();
        }
    });
</script>

<main class="container mx-auto px-6 py-12">
    <!-- Sección Integrantes -->
    <section class="mb-20 text-center">
        <h2 class="font-heading text-4xl text-primary mb-8">Equipo de Desarrollo</h2>
        <p class="text-lg text-textSecondary leading-relaxed mb-6" style="text-align: center; font-weight: bold;">
            Grupo 8 - Tema 4:
            <span class="text-lg text-textSecondary leading-relaxed mb-6"
                style="font-style: italic; font-weight: normal;">
                "Sistema de gestión de reservas y pedidos para food trucks o ferias gastronómicas"
            </span>
        </p>
        <div class="grid md:grid-cols-3 gap-6">
            <?php
            $integrantes = [
                ['nombre' => 'Elias Medina', 'rol' => ''],
                ['nombre' => 'German Lares', 'rol' => ''],
                ['nombre' => 'Hugo Silguero', 'rol' => ''],
                ['nombre' => 'Delcy Mendoza', 'rol' => ''],
                ['nombre' => 'Noelia Apodaca', 'rol' => '']
            ];
            foreach ($integrantes as $integrante): ?>
                <div class="bg-white p-6 rounded-lg custom-shadow hover:scale-105 transition-transform">
                    <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg"
                        class="w-32 h-32 rounded-full mx-auto mb-4" alt="<?= htmlspecialchars($integrante['nombre']) ?>">
                    <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($integrante['nombre']) ?></h3>
                    <p class="text-textSecondary"><?= htmlspecialchars($integrante['rol']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Food Trucks Disponibles -->
    <section class="mb-20">
        <h2 class="font-heading text-3xl text-primary mb-6 text-center">Food Trucks Disponibles</h2>
        <div id="foodtrucksContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($foodtrucks)): ?>
                <div class="col-span-full text-center py-12">
                    <i class="material-icons text-5xl text-gray-300 mb-4">fastfood</i>
                    <p class="text-textSecondary">No hay food trucks registrados todavía</p>
                    <p class="mt-2 text-sm text-gray-500">Visita el panel de administración para agregar nuevos</p>
                </div>
            <?php else: ?>
                <?php foreach ($foodtrucks as $ft):
                    // Filtrar reseñas para este foodtruck
                    $ftReviews = array_filter($reviews, fn($r) => $r['foodtruck_id'] == $ft['id']);
                    $ratings = array_column($ftReviews, 'rating');
                    $avgRating = count($ratings) ? array_sum($ratings) / count($ratings) : 0;
                    $isFavorite = in_array($ft['id'], $favoritosIds);
                    ?>
                    <div class="bg-white rounded-lg custom-shadow overflow-hidden hover:shadow-xl transition-all">
                        <div class="relative">
                            <?php if (!empty($ft['imagen'])): ?>
                                <img src="<?= htmlspecialchars($ft['imagen']) ?>" alt="<?= htmlspecialchars($ft['nombre']) ?>"
                                    class="w-full h-48 object-cover" />
                            <?php else: ?>
                                <div
                                    class="bg-gray-200 border-2 border-dashed w-full h-48 flex items-center justify-center text-gray-400">
                                    Sin imagen
                                </div>
                            <?php endif; ?>
                            <!-- <div class="bg-gray-200 border-2 border-dashed w-full h-48"></div> -->
                            <button onclick="toggleFavorito(<?= $ft['id'] ?>, this)"
                                class="absolute top-2 right-2 bg-white rounded-full p-2 shadow-md flex items-center justify-center"
                                aria-label="<?= $isFavorite ? 'Quitar de favoritos' : 'Agregar a favoritos' ?>">
                                <i class="material-icons <?= $isFavorite ? 'text-red-500' : 'text-gray-400' ?>">
                                    <?= $isFavorite ? 'favorite' : 'favorite_border' ?>
                                </i>
                            </button>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <h3 class="text-xl font-semibold"><?= htmlspecialchars($ft['nombre']) ?></h3>
                                <span
                                    class="bg-<?= $avgRating >= 4 ? 'green' : ($avgRating >= 3 ? 'yellow' : 'red') ?>-100 text-<?= $avgRating >= 4 ? 'green' : ($avgRating >= 3 ? 'yellow' : 'red') ?>-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                    <?= number_format($avgRating, 1) ?>
                                </span>
                            </div>

                            <div class="flex items-center mt-1 mb-2">
                                <div class="flex text-orange-400">
                                    <?php
                                    $fullStars = floor($avgRating);
                                    $halfStar = ($avgRating - $fullStars) >= 0.5;
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $fullStars) {
                                            echo '<span>★</span>';
                                        } elseif ($i == $fullStars + 1 && $halfStar) {
                                            echo '<span>½</span>';
                                        } else {
                                            echo '<span>☆</span>';
                                        }
                                    }
                                    ?>
                                </div>
                                <span class="text-sm text-gray-500 ml-2">(<?= count($ftReviews) ?> reseñas)</span>
                            </div>

                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <i class="material-icons mr-1 text-sm">location_on</i>
                                <?= htmlspecialchars($ft['ubicacion'] ?: 'Ubicación no especificada') ?>
                            </div>

                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                <?= htmlspecialchars($ft['descripcion'] ?: 'Sin descripción disponible') ?>
                            </p>

                            <div class="flex flex-wrap gap-2">
                                <a href="menu.php?foodtruck_id=<?= $ft['id'] ?>"
                                    class="flex items-center text-primary hover:text-primary-dark">
                                    <i class="material-icons mr-1 text-sm">menu_book</i> Ver menú
                                </a>
                                <button type="button" onclick="showReviews(<?= $ft['id'] ?>)"
                                    class="flex items-center text-secondary hover:text-orange-700">
                                    <i class="material-icons mr-1 text-sm">reviews</i> Ver reseñas
                                </button>
                                <button type="button" onclick="showFoodTruckLocationOnMap(<?= $ft['id'] ?>)"
                                    class="flex items-center text-accent hover:text-green-700">
                                    <i class="material-icons mr-1 text-sm">map</i> Ver en mapa
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Funcionalidades Principales -->
    <section class="mb-20">
        <div class="bg-white p-8 rounded-lg custom-shadow">
            <h2 class="font-heading text-3xl text-primary mb-6 text-center">Funcionalidades Clave</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-6 border rounded-lg hover:shadow-lg transition">
                    <i class="material-icons text-4xl text-secondary">schedule</i>
                    <h3 class="text-xl font-semibold mt-4">Reserva de Turnos</h3>
                    <p class="text-textSecondary mt-2">Sistema inteligente para evitar filas con horarios programados
                    </p>
                </div>
                <div class="p-6 border rounded-lg hover:shadow-lg transition">
                    <i class="material-icons text-4xl text-secondary">map</i>
                    <h3 class="text-xl font-semibold mt-4">Mapa Interactivo</h3>
                    <p class="text-textSecondary mt-2">Ubicación en tiempo real de todos los food trucks</p>
                </div>
                <div class="p-6 border rounded-lg hover:shadow-lg transition">
                    <i class="material-icons text-4xl text-secondary">menu_book</i>
                    <h3 class="text-xl font-semibold mt-4">Menú Digital</h3>
                    <p class="text-textSecondary mt-2">Catálogo con fotos y precios actualizados</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Funcionalidades Extra -->
    <section class="mb-20 bg-gradient-to-r from-primary to-secondary text-white py-12">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="font-heading text-4xl mb-4">¡Funcionalidades Extra!</h2>
            <div class="grid md:grid-cols-2">
                <div class="p-6 m-4 bg-white/10 hover:shadow-lg transition rounded-lg">
                    <i class="material-icons text-4xl">star</i>
                    <h3 class="text-xl font-semibold mt-4">Sistema de Reseñas</h3>
                    <p>Califica tus food trucks favoritos y comparte tu experiencia</p>
                </div>
                <div class="p-6 m-4 bg-white/10 rounded-lg hover:shadow-lg transition ">
                    <i class="material-icons text-4xl">favorite</i>
                    <h3 class="text-xl font-semibold mt-4">Favoritos</h3>
                    <p>Guarda tus puestos preferidos para acceso rápido</p>
                </div>
            </div>
        </div>
    </section>

</main>

<!-- Carga CSS y JS de Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // Pasamos los food trucks con coordenadas al JS para el mapa
    const foodtrucks = <?= json_encode(array_map(function ($ft) {
        return [
            'id' => $ft['id'],
            'nombre' => $ft['nombre'],
            'lat' => $ft['lat'],
            'lng' => $ft['lng'],
            'ubicacion' => $ft['ubicacion'],
        ];
    }, $foodtrucks)) ?>;

    // Cerrar modal al hacer clic fuera del contenido
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('fixed') && e.target.classList.contains('bg-black')) {
            e.target.remove();
        }
    });
</script>

<style>
    .custom-marker {
        background: transparent;
        border: none;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .animate-ping {
        animation: ping 0.5s cubic-bezier(0, 0, 0.2, 1);
    }

    @keyframes ping {

        75%,
        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>