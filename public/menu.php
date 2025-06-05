<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';
?>

<section class="max-w-4xl mx-auto bg-white rounded-lg custom-shadow p-8">
    <h2 class="font-heading text-3xl text-primary mb-6" id="menuTitle"></h2>
    
    <div class="grid md:grid-cols-2 gap-6" id="menuContainer">
        <!-- Menú se cargará aquí dinámicamente -->
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const foodtruckId = sessionStorage.getItem('currentFoodtruck');
        if (!foodtruckId) {
            window.location.href = 'main.php';
            return;
        }
        
        const foodtrucks = JSON.parse(localStorage.getItem('foodtrucks')) || [];
        const foodtruck = foodtrucks.find(ft => ft.id == foodtruckId);
        
        if (foodtruck) {
            document.getElementById('menuTitle').textContent = `Menú de ${foodtruck.nombre}`;
            const container = document.getElementById('menuContainer');
            
            if (foodtruck.menu && foodtruck.menu.length > 0) {
                foodtruck.menu.forEach(item => {
                    const menuItem = document.createElement('div');
                    menuItem.className = 'border rounded-lg p-4 hover:shadow-md transition';
                    menuItem.innerHTML = `
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold">${item.nombre}</h3>
                            <span class="text-secondary font-semibold">$${item.precio.toFixed(2)}</span>
                        </div>
                        <p class="text-textSecondary text-sm mb-4">${item.descripcion || 'Sin descripción'}</p>
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-32"></div>
                    `;
                    container.appendChild(menuItem);
                });
            } else {
                container.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class="material-icons text-5xl text-gray-300 mb-4">fastfood</i>
                        <p class="text-textSecondary">Este food truck no tiene menú disponible</p>
                    </div>
                `;
            }
        }
    });
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>