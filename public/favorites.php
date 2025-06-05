<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';
?>

<section class="max-w-6xl mx-auto bg-white rounded-lg custom-shadow p-8">
    <h2 class="font-heading text-3xl text-primary mb-8 text-center">Tus Food Trucks Favoritos</h2>
    
    <div id="favoritesContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Los favoritos se cargarán aquí dinámicamente -->
        <div class="text-center py-12 hidden" id="noFavoritesMessage">
            <i class="material-icons text-5xl text-gray-300 mb-4">favorite_border</i>
            <p class="text-textSecondary">Aún no tienes food trucks favoritos</p>
            <p class="mt-2 text-sm text-gray-500">Visita la página de reservas y marca tus favoritos</p>
        </div>
    </div>
</section>

<script>
    // Cargar favoritos desde localStorage
    function loadFavorites() {
        const user = "<?= $_SESSION['username'] ?>";
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const currentUser = users.find(u => u.username === user);
        const favorites = currentUser?.favoritos || [];
        const container = document.getElementById('favoritesContainer');
        const noFavMessage = document.getElementById('noFavoritesMessage');
        
        if (favorites.length === 0) {
            noFavMessage.classList.remove('hidden');
            return;
        }
        
        noFavMessage.classList.add('hidden');
        container.innerHTML = '';
        
        // Obtener todos los food trucks
        const foodtrucks = JSON.parse(localStorage.getItem('foodtrucks')) || [];
        
        favorites.forEach(favId => {
            const foodtruck = foodtrucks.find(ft => ft.id === favId);
            if (foodtruck) {
                const favEl = document.createElement('div');
                favEl.className = 'bg-white border rounded-lg p-4 hover:shadow-md transition';
                favEl.innerHTML = `
                    <div class="flex items-center mb-3">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16" />
                        <div class="ml-4">
                            <h3 class="font-semibold">${foodtruck.nombre}</h3>
                            <div class="flex text-orange-400">
                                ${'★'.repeat(foodtruck.rating)}${'☆'.repeat(5 - foodtruck.rating)}
                            </div>
                        </div>
                    </div>
                    <p class="text-textSecondary text-sm mb-4">${foodtruck.descripcion || 'Sin descripción'}</p>
                    <div class="flex justify-between">
                        <button onclick="removeFavorite(${foodtruck.id})" class="text-red-500 hover:text-red-700 flex items-center">
                            <i class="material-icons mr-1">delete</i> Eliminar
                        </button>
                        <a href="booking.php?foodtruck=${foodtruck.id}" class="bg-secondary text-white px-3 py-1 rounded-lg text-sm hover:bg-orange-600 transition">
                            Reservar
                        </a>
                    </div>
                `;
                container.appendChild(favEl);
            }
        });
    }

    // Eliminar favorito
    function removeFavorite(foodtruckId) {
        const user = "<?= $_SESSION['username'] ?>";
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const userIndex = users.findIndex(u => u.username === user);
        
        if (userIndex !== -1) {
            users[userIndex].favoritos = users[userIndex].favoritos.filter(id => id !== foodtruckId);
            localStorage.setItem('users', JSON.stringify(users));
            loadFavorites();
        }
    }

    // Cargar favoritos al iniciar
    loadFavorites();
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>