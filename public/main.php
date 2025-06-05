<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadFoodTrucks();
        initMap();
        setupReviewModals();
    });

    function loadFoodTrucks() {
        const foodtrucks = JSON.parse(localStorage.getItem('foodtrucks')) || [];
        const container = document.getElementById('foodtrucksContainer');
        container.innerHTML = '';
        
        if (foodtrucks.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i class="material-icons text-5xl text-gray-300 mb-4">fastfood</i>
                    <p class="text-textSecondary">No hay food trucks registrados todavía</p>
                    <p class="mt-2 text-sm text-gray-500">Visita el panel de administración para agregar nuevos</p>
                </div>
            `;
            return;
        }
        
        foodtrucks.forEach(ft => {
            // Calcular promedio de reseñas
            const reviews = JSON.parse(localStorage.getItem('reviews')) || [];
            const ftReviews = reviews.filter(r => r.foodtruckId === ft.id);
            const avgRating = ftReviews.length > 0 ? 
                (ftReviews.reduce((sum, r) => sum + parseInt(r.rating), 0) / ftReviews.length) : 0;
            
            const card = document.createElement('div');
            card.className = 'bg-white rounded-lg custom-shadow overflow-hidden hover:shadow-xl transition-all';
            card.innerHTML = `
                <div class="relative">
                    <div class="bg-gray-200 border-2 border-dashed w-full h-48"></div>
                    <button class="absolute top-2 right-2 bg-white rounded-full p-2 shadow-md" style="display: flex"
                            onclick="toggleFavorite(${ft.id}, this)">
                        <i class="material-icons text-${isFavorite(ft.id) ? 'red-500' : 'gray-400'}">
                            ${isFavorite(ft.id) ? 'favorite' : 'favorite_border'}
                        </i>
                    </button>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <h3 class="text-xl font-semibold">${ft.nombre}</h3>
                        <span class="bg-${avgRating >= 4 ? 'green' : avgRating >= 3 ? 'yellow' : 'red'}-100 text-${avgRating >= 4 ? 'green' : avgRating >= 3 ? 'yellow' : 'red'}-800 text-sm font-medium px-2.5 py-0.5 rounded">
                            ${avgRating.toFixed(1)}
                        </span>
                    </div>
                    
                    <div class="flex items-center mt-1 mb-2">
                        <div class="flex text-orange-400">
                            ${renderStars(avgRating)}
                        </div>
                        <span class="text-sm text-gray-500 ml-2">(${ftReviews.length} reseñas)</span>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <i class="material-icons mr-1 text-sm">location_on</i>
                        ${ft.ubicacion || 'Ubicación no especificada'}
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">${ft.descripcion || 'Sin descripción disponible'}</p>
                    
                    <div class="flex flex-wrap gap-2">
                        <button onclick="showOnMap(${ft.id})" class="flex items-center text-primary hover:text-primary-dark">
                            <i class="material-icons mr-1 text-sm">map</i> Ver en mapa
                        </button>
                        <button onclick="showReviews(${ft.id})" class="flex items-center text-secondary hover:text-orange-700">
                            <i class="material-icons mr-1 text-sm">reviews</i> Ver reseñas
                        </button>
                        <button onclick="showMenu(${ft.id})" class="flex items-center text-accent hover:text-green-700">
                            <i class="material-icons mr-1 text-sm">menu_book</i> Ver menú
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    }

    function renderStars(rating) {
        const fullStars = Math.floor(rating);
        const halfStar = rating % 1 >= 0.5;
        let stars = '';
        
        for (let i = 1; i <= 5; i++) {
            if (i <= fullStars) {
                stars += '★';
            } else if (i === fullStars + 1 && halfStar) {
                stars += '½';
            } else {
                stars += '☆';
            }
        }
        return stars;
    }

    function isFavorite(foodtruckId) {
        const user = "<?= $_SESSION['username'] ?>";
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const currentUser = users.find(u => u.username === user);
        return currentUser?.favoritos?.includes(foodtruckId) || false;
    }

    function toggleFavorite(foodtruckId, element) {
        const user = "<?= $_SESSION['username'] ?>";
        const users = JSON.parse(localStorage.getItem('users')) || [];
        let userIndex = users.findIndex(u => u.username === user);
        
        if (userIndex === -1) {
            users.push({
                username: user,
                favoritos: [foodtruckId],
                reservas: []
            });
            userIndex = users.length - 1;
        } else {
            const favIndex = users[userIndex].favoritos.indexOf(foodtruckId);
            if (favIndex === -1) {
                users[userIndex].favoritos.push(foodtruckId);
            } else {
                users[userIndex].favoritos.splice(favIndex, 1);
            }
        }
        
        localStorage.setItem('users', JSON.stringify(users));
        
        // Actualizar icono
        const icon = element.querySelector('i');
        if (icon) {
            const isFav = users[userIndex].favoritos.includes(foodtruckId);
            icon.textContent = isFav ? 'favorite' : 'favorite_border';
            icon.classList.toggle('text-red-500', isFav);
            icon.classList.toggle('text-gray-400', !isFav);
        }
    }

    function showReviews(foodtruckId) {
        const foodtrucks = JSON.parse(localStorage.getItem('foodtrucks')) || [];
        const foodtruck = foodtrucks.find(ft => ft.id === foodtruckId);
        const reviews = JSON.parse(localStorage.getItem('reviews')) || [];
        const ftReviews = reviews.filter(r => r.foodtruckId === foodtruckId);
        
        // Crear modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg custom-shadow w-full max-w-2xl max-h-[80vh] overflow-y-auto">
                <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
                    <h3 class="text-xl font-semibold">Reseñas de ${foodtruck.nombre}</h3>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-gray-500 hover:text-gray-700">
                        <i class="material-icons">close</i>
                    </button>
                </div>
                <div class="p-6">
                    ${ftReviews.length === 0 ? `
                        <div class="text-center py-8">
                            <i class="material-icons text-4xl text-gray-300 mb-4">reviews</i>
                            <p class="text-textSecondary">No hay reseñas para este food truck</p>
                        </div>
                    ` : ftReviews.map(review => `
                        <div class="border-b pb-4 mb-4">
                            <div class="flex justify-between items-start">
                                <h4 class="font-semibold">${review.user}</h4>
                                <span class="text-sm text-gray-500">${review.date}</span>
                            </div>
                            <div class="flex text-orange-400 my-2">
                                ${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}
                            </div>
                            <p class="text-textSecondary">${review.comment}</p>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
    }

    function showOnMap(foodtruckId) {
        const foodtrucks = JSON.parse(localStorage.getItem('foodtrucks')) || [];
        const foodtruck = foodtrucks.find(ft => ft.id === foodtruckId);
        
        if (foodtruck && map && foodtruck.lat && foodtruck.lng) {
            map.setView([foodtruck.lat, foodtruck.lng], 17);
            
            // Destacar el marcador
            if (window.currentMarker) {
                map.removeLayer(window.currentMarker);
            }
            
            window.currentMarker = L.marker([foodtruck.lat, foodtruck.lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `<div class="bg-primary text-white p-2 rounded-full shadow-lg">${foodtruck.nombre}</div>`,
                    iconSize: [40, 40],
                    iconAnchor: [20, 40]
                })
            }).addTo(map)
            .bindPopup(`<b>${foodtruck.nombre}</b><br>${foodtruck.ubicacion}`)
            .openPopup();
        }
    }

    function showMenu(foodtruckId) {
        // Guardar el foodtruckId en sessionStorage para la próxima página
        sessionStorage.setItem('currentFoodtruck', foodtruckId);
        window.location.href = 'menu.php'; // Crear esta página
    }

    function setupReviewModals() {
        // Cerrar modal al hacer clic fuera del contenido
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('fixed') && e.target.classList.contains('bg-black')) {
                e.target.remove();
            }
        });
    }
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
</style>

<main class="container mx-auto px-6 py-12">
    <!-- Sección Integrantes -->
    <section class="mb-20 text-center">
        <h2 class="font-heading text-4xl text-primary mb-8">Equipo de Desarrollo</h2>
        <p class="text-lg text-textSecondary leading-relaxed mb-6" style="text-align: center; font-weight: bold;">
            Grupo 8 - Tema 4:
            <span class="text-lg text-textSecondary leading-relaxed mb-6" style="font-style: italic; text-align: center; font-weight: normal;">
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
                        class="w-32 h-32 rounded-full mx-auto mb-4" alt="<?= $integrante['nombre'] ?>">
                    <h3 class="text-xl font-semibold mb-2"><?= $integrante['nombre'] ?></h3>
                    <p class="text-textSecondary"><?= $integrante['rol'] ?></p>
                </div>
            <?php endforeach; ?>
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
                    <p class="text-textSecondary mt-2">Sistema inteligente para evitar filas con horarios programados</p>
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

    <!-- Food Trucks Registrados -->
    <section class="mb-20">
        <h2 class="font-heading text-3xl text-primary mb-6 text-center">Food Trucks Disponibles</h2>
        <div id="foodtrucksContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Food trucks se cargarán aquí dinámicamente -->
        </div>
    </section>

    <!-- Mapa Interactivo -->
    <section class="mb-20">
        <h2 class="font-heading text-3xl text-primary mb-6 text-center">Ubicación en Tiempo Real</h2>
        <div class="bg-white p-4 rounded-lg custom-shadow">
            <div id="map" style="height: 500px;" class="rounded-lg"></div>
        </div>
    </section>

    <!-- Carga CSS de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Contenedor del mapa -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        let map;
        function initMap() {
            map = L.map('map').setView([-0.22985, -78.52495], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            
            // Agregar marcadores de food trucks
            const foodtrucks = JSON.parse(localStorage.getItem('foodtrucks')) || [];
            foodtrucks.forEach(ft => {
                if (ft.lat && ft.lng) {
                    L.marker([ft.lat, ft.lng]).addTo(map)
                        .bindPopup(`<b>${ft.nombre}</b><br>${ft.ubicacion}`);
                }
            });
        }
    </script>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>