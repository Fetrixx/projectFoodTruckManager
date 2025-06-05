<?php if (!defined('CHECK_ACCESS'))
    exit('Acceso directo prohibido'); ?>

<nav class="bg-primary text-white shadow-lg">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo y menú móvil -->
            <div class="flex items-center w-full md:w-auto">
                <button id="mobileMenu" class="md:hidden">
                    <i class="material-icons text-3xl">menu</i>
                </button>
                <a href="main.php" class="font-heading text-2xl md:text-3xl select-none ml-4">
                    🍔 FoodTruck Manager
                </a>
            </div>


            <!-- Navegación desktop -->
            <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                <a href="main.php" class="hover:text-secondary transition">Inicio</a>
                <a href="#servicios" class="hover:text-secondary transition">Servicios</a>
                <a href="contacto.php" class="hover:text-secondary transition">Contacto</a>
                <a href="https://foodtruckmanager.blogspot.com" target="_blank"
                    class="hover:text-secondary transition flex items-center">
                    <i class="material-icons mr-1">article</i> Blog
                </a>

                <!-- Perfil de usuario -->
                <div class="flex items-center space-x-4 ml-4">
                    <span class="text-sm">
                        <?= htmlspecialchars($_SESSION['username'] ?? 'Invitado') ?>
                    </span>
                    <a href="/projectFoodTruckManager/public/logout.php"
                        class="bg-secondary px-4 py-2 rounded-lg hover:bg-orange-600 transition flex items-center">
                        <i class="material-icons mr-1">logout</i> Salir
                    </a>
                </div>
            </div>
        </div>

        <!-- Menú móvil lateral -->
        <div id="mobileNav"
            class="fixed inset-y-0 left-0 w-64 bg-primary transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50">
            <div class="p-6">
                <button id="closeMenu" class="mb-6 text-white hover:text-secondary">
                    <i class="material-icons text-3xl">close</i>
                </button>
                <nav class="space-y-4">
                    <a href="main.php" class="block text-white hover:text-secondary transition">Inicio</a>
                    <a href="#servicios" class="block text-white hover:text-secondary transition">Servicios</a>
                    <a href="contacto.php" class="block text-white hover:text-secondary transition">Contacto</a>
                    <a href="https://foodtruckmanager.blogspot.com" target="_blank"
                        class="block text-white hover:text-secondary transition">Blog</a>
                </nav>
                <div class="mt-8 border-t pt-4">
                    <p class="text-white text-sm">
                        <?= htmlspecialchars($_SESSION['username'] ?? 'Invitado') ?>
                    </p>
                    <a href="/projectFoodTruckManager/public/logout.php"

                        class="mt-2 inline-flex items-center text-white hover:text-secondary">
                        <i class="material-icons mr-2">logout</i> Salir
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Overlay para cerrar menú -->
<div id="mobileOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>

<script>
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMenu = document.getElementById('closeMenu');
    const mobileNav = document.getElementById('mobileNav');
    const overlay = document.getElementById('mobileOverlay');

    function toggleMenu() {
        mobileNav.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    mobileMenu.addEventListener('click', toggleMenu);
    closeMenu.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);
</script>

<style>
    /* Animación personalizada para suavizar el despliegue */
    #mobileNav {
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    }
</style>