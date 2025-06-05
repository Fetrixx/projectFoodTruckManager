<?php if (!defined('CHECK_ACCESS'))
    exit('Acceso directo prohibido'); ?>

<nav class="bg-primary text-white shadow-lg">
    <div class="container mx-auto px-4 py-3">
        <!-- Contenedor principal -->
        <div class="flex items-center justify-between">
            <!-- Logo y botón móvil -->
            <div class="flex items-center flex-1">
                <button id="mobileMenuButton" class="md:hidden mr-2">
                    <i class="material-icons text-3xl">menu</i>
                </button>
                <a href="main.php" class="font-heading text-xl md:text-2xl whitespace-nowrap">
                    🍔 FoodTruck Park Manager
                </a>
            </div>

            <!-- Navegación desktop -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="main.php" class="hover:text-secondary transition-colors duration-200">Inicio</a>
                <!-- <a href="#servicios" class="hover:text-secondary transition-colors duration-200">Servicios</a> -->
                <a href="contacto.php" class="hover:text-secondary transition-colors duration-200">Contacto</a>
                <a href="https://foodtruckmanager.blogspot.com" target="_blank"
                    class="flex items-center hover:text-secondary transition-colors duration-200">
                    <i class="material-icons mr-1">article</i> Blog
                </a>

                <a href="reviews.php" class="hover:text-secondary transition-colors duration-200">Reseñas</a>
                <a href="favorites.php" class="hover:text-secondary transition-colors duration-200">Favoritos</a>
                <a href="reservas.php" class="hover:text-secondary transition-colors duration-200">Reservas</a>
<!-- 
                <a href="reservas.php" class="hover:text-secondary">Reservas</a>

                <a href="reseñas.php" class="hover:text-secondary transition-colors duration-200">Reseñas</a>
                <a href="favoritos.php" class="hover:text-secondary transition-colors duration-200">Favoritos</a> -->

                <!-- <a href="perfil.php" class="hover:text-secondary">Perfil</a> -->
                <?php if ($_SESSION['username'] === 'admin'): ?>
                    <a href="admin.php" class="hover:text-secondary" style="font-weight: bold; font-style: italic;">Admin Panel</a>
                <?php endif; ?>

                <!-- Perfil usuario -->
                <div class="flex items-center ml-4 space-x-3">
                    <a href="perfil.php" class="hover:text-secondary">
                            <?= htmlspecialchars($_SESSION['username'] ?? 'Invitado') ?>
                    </a>
                    <a href="/projectFoodTruckManager/public/logout.php"
                        class="bg-secondary px-3 py-1.5 rounded-md hover:bg-orange-600 transition-colors flex items-center"
                        style="border-radius: 20px;">
                        <i class="material-icons text-sm">logout</i> 
                    </a>
                        <!-- <a href="/projectFoodTruckManager/public/logout.php"
                            class="bg-secondary p-2 rounded-md hover:bg-orange-600 transition-colors flex items-center">
                            <i class="material-icons text-sm">logout</i>
                        </a> -->
                    <!-- <a href="/projectFoodTruckManager/public/logout.php"
                        class="bg-secondary px-3 py-1.5 rounded-md hover:bg-orange-600 transition-colors flex items-center">
                        <i class="material-icons mr-1 text-sm">logout</i> Salir
                    </a> -->
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Menú móvil lateral -->
<div id="mobileMenu"
    class="fixed inset-y-0 left-0 w-64 bg-primary transform -translate-x-full shadow-xl z-50 transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full p-4" style="color: white;">
        <!-- Encabezado menú -->
        <div class="flex justify-between items-center mb-6">
            <span class="font-heading text-lg">Menú</span>
            <button id="closeMenuButton" class="text-white hover:text-secondary">
                <i class="material-icons text-2xl">close</i>
            </button>
        </div>

        <!-- Enlaces -->
        <nav class="flex-1 space-y-3">
            <a href="main.php" class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Inicio</a>
            <!-- <a href="#servicios" class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Servicios</a> -->
            <a href="contacto.php" class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Contacto</a>
            <a href="https://foodtruckmanager.blogspot.com" target="_blank"
                class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Blog</a>
            
            <!-- <a href="reservas.php" class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Reservas</a>
            <a href="reseñas.php" class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Reseñas</a>
            <a href="favoritos.php" class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Favoritos</a> -->

            <a href="reviews.php" class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Reseñas</a>
            <a href="favorites.php" class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Favoritos</a>
            <a href="reservas.php" class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Reservas</a>

            <a href="perfil.php" class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Perfil</a>
            <?php if ($_SESSION['username'] === 'admin'): ?>
                <a href="admin.php" class="block px-3 py-2 hover:bg-white/10 rounded-lg transition-colors">Admin</a>
            <?php endif; ?>
        </nav>

        <!-- Pie del menú -->
        <div class="border-t pt-4">
            <div class="text-sm mb-2"><?= htmlspecialchars($_SESSION['username'] ?? 'Invitado') ?></div>
            <a href="/projectFoodTruckManager/public/logout.php"
                class="inline-flex items-center text-sm hover:text-secondary">
                <i class="material-icons mr-2 text-base">logout</i> Cerrar sesión
            </a>
        </div>
    </div>
</div>

<!-- Overlay -->
<div id="mobileOverlay" class="hidden fixed inset-0 bg-black/50 z-40 backdrop-blur-sm"></div>

<script>
    // Elementos del DOM
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const closeMenuButton = document.getElementById('closeMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('mobileOverlay');

    // Funcionalidad del menú
    function toggleMenu() {
        mobileMenu.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    // Event Listeners
    mobileMenuButton.addEventListener('click', toggleMenu);
    closeMenuButton.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);

    // Cerrar menú en resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            mobileMenu.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });
</script>

<style>
    /* Animaciones personalizadas */
    #mobileMenu {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #mobileMenu:not(.-translate-x-full) {
        transform: translateX(0);
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
    }
</style>