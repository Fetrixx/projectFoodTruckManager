<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php'; 
?>
<!--
<header class="bg-primary text-white shadow-lg">
    <div class="container mx-auto px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between">
        <h1 class="font-heading text-3xl mb-3 md:mb-0 select-none">🍔 FoodTruck Manager</h1>
        <nav>
            <ul class="flex space-x-8 font-semibold text-lg">
                <li><a href="#sobre-mi" class="hover:text-secondary transition">Sobre Nosotros</a></li>
                <li><a href="#servicios" class="hover:text-secondary transition">Servicios</a></li>
                <li><a href="#contacto" class="hover:text-secondary transition">Contacto</a></li>
            </ul>
        </nav>
        <div class="mt-4 md:mt-0 text-sm text-white/90">
            Bienvenido <span class="font-semibold"><?= htmlspecialchars($_SESSION['username']) ?></span>!<br>
            <span class="text-white/70"><?= htmlspecialchars($_SESSION['email']) ?></span>
            <a href="/projectFoodTruckManager/src/auth/logout.php" class="ml-4 underline hover:text-secondary">(Salir)</a>
        </div>
    </div>
</header>-->
<!-- 
<header class="bg-primary text-white shadow-lg">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">

            <div class="flex items-center">
                <h1 class="font-heading text-2xl md:text-3xl select-none">🍔 FoodTruck Manager</h1>
                <button id="mobileMenu" class="md:hidden ml-4">
                    <i class="material-icons">menu</i>
                </button>
            </div>


            <nav class="hidden md:flex space-x-6 lg:space-x-8 items-center">
                <a href="main.php" class="hover:text-secondary transition">Inicio</a>
                <a href="#servicios" class="hover:text-secondary transition">Servicios</a>
                <a href="contacto.php" class="hover:text-secondary transition">Contacto</a>
                <a href="blog.html" target="_blank" class="hover:text-secondary transition flex items-center">
                    <i class="material-icons mr-1">article</i> Blog
                </a>
                <div class="flex items-center space-x-4 ml-4">
                    <span class="text-sm"><?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="/projectFoodTruckManager/src/auth/logout.php" 
                       class="bg-secondary px-4 py-2 rounded-lg hover:bg-orange-600 transition flex items-center">
                        <i class="material-icons mr-1">logout</i> Salir
                    </a>
                </div>
            </nav>
        </div>


        <div id="mobileNav" class="md:hidden hidden mt-4 space-y-4">
            <a href="main.php" class="block hover:text-secondary">Inicio</a>
            <a href="#servicios" class="block hover:text-secondary">Servicios</a>
            <a href="contacto.php" class="block hover:text-secondary">Contacto</a>
            <a href="blog.html" target="_blank" class="block hover:text-secondary">Blog</a>
        </div>
    </div>
</header>
-->

<script>
    document.getElementById('mobileMenu').addEventListener('click', function() {
        const mobileNav = document.getElementById('mobileNav');
        mobileNav.classList.toggle('hidden');
    });
</script>

<!-- 
<main class="container mx-auto px-6 py-12 flex-grow space-y-20">
    <section id="sobre-mi" class="max-w-4xl mx-auto text-center">
        <h2 class="text-4xl font-heading text-primary mb-4">Sistema de Gestión para Food Trucks</h2>
        <p class="text-lg text-textSecondary max-w-xl mx-auto leading-relaxed">
            Nuestra plataforma te permite reservar turnos y hacer pedidos anticipados en ferias gastronómicas y eventos.
        </p>
    </section>

    <section id="servicios" class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
    </section>

    <section id="contacto" class="max-w-4xl mx-auto bg-white rounded-lg custom-shadow p-8">
    </section>
</main>
-->
<main class="container mx-auto px-6 py-12">
    <!-- Sección Integrantes -->
    <section class="mb-20 text-center">
        <h2 class="font-heading text-4xl text-primary mb-8">Equipo de Desarrollo</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg custom-shadow">
                <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg" 
                     class="w-32 h-32 rounded-full mx-auto mb-4" 
                     alt="Elias Medina">
                <h3 class="text-xl font-semibold mb-2">Elias Medina</h3>
                <p class="text-textSecondary">Desarrollador Full Stack</p>
            </div>
            <div class="bg-white p-6 rounded-lg custom-shadow">
                <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg" 
                     class="w-32 h-32 rounded-full mx-auto mb-4" 
                     alt="German Lares">
                <h3 class="text-xl font-semibold mb-2">German Lares</h3>
                <p class="text-textSecondary">Desarrollador Full Stack</p>
            </div>
            <div class="bg-white p-6 rounded-lg custom-shadow">
                <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg" 
                     class="w-32 h-32 rounded-full mx-auto mb-4" 
                     alt="Hugo Silguero">
                <h3 class="text-xl font-semibold mb-2">Hugo Silguero</h3>
                <p class="text-textSecondary">Desarrollador Full Stack</p>
            </div>
            <div class="bg-white p-6 rounded-lg custom-shadow">
                <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg" 
                     class="w-32 h-32 rounded-full mx-auto mb-4" 
                     alt="Delcy Mendoza">
                <h3 class="text-xl font-semibold mb-2">Delcy Mendoza</h3>
                <p class="text-textSecondary">Desarrollador Full Stack</p>
            </div>
            <div class="bg-white p-6 rounded-lg custom-shadow">
                <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg" 
                     class="w-32 h-32 rounded-full mx-auto mb-4" 
                     alt="Noelia Apodaca">
                <h3 class="text-xl font-semibold mb-2">Noelia Apodaca</h3>
                <p class="text-textSecondary">Desarrollador Full Stack</p>
            </div>
        </div>
    </section>

    <!-- Descripción del Proyecto -->
    <section class="mb-20 max-w-4xl mx-auto">
        <h2 class="font-heading text-4xl text-primary mb-6 text-center">Gestión Inteligente para Food Trucks</h2>
        <div class="bg-white p-8 rounded-lg custom-shadow">
            <p class="text-lg text-textSecondary leading-relaxed mb-6">
                Plataforma integral para optimizar la experiencia en ferias gastronómicas mediante:
            </p>
            <ul class="list-disc pl-6 space-y-4">
                <li class="text-textSecondary">
                    <span class="font-semibold text-primary">Reservas inteligentes:</span> 
                    Sistema de turnos para evitar aglomeraciones
                </li>
                <li class="text-textSecondary">
                    <span class="font-semibold text-primary">Menú digital:</span> 
                    Visualización de productos con imágenes y precios en tiempo real
                </li>
                <li class="text-textSecondary">
                    <span class="font-semibold text-primary">Mapa interactivo:</span> 
                    Geolocalización precisa de cada puesto
                </li>
            </ul>
        </div>
    </section>

    <!-- Sección de Servicios -->
    <section id="servicios" class="mb-20">
        <!-- Contenido de servicios existente -->
    </section>
</main>


<div style="width: 100%; height: 20px; background-color: red;"></div>


<main class="container mx-auto px-6 py-12">
    <!-- Sección Integrantes -->
    <section class="mb-20 text-center">
        <h2 class="font-heading text-4xl text-primary mb-8">Equipo de Desarrollo</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <?php
            $integrantes = [
                ['nombre' => 'Elias Medina', 'rol' => 'Líder de Proyecto'],
                ['nombre' => 'German Lares', 'rol' => 'Desarrollador Backend'],
                ['nombre' => 'Hugo Silguero', 'rol' => 'Diseñador UI/UX'],
                ['nombre' => 'Delcy Mendoza', 'rol' => 'Analista de Datos'],
                ['nombre' => 'Noelia Apodaca', 'rol' => 'Desarrollador Frontend']
            ];
            
            foreach ($integrantes as $integrante): ?>
            <div class="bg-white p-6 rounded-lg custom-shadow hover:scale-105 transition-transform">
                <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg" 
                     class="w-32 h-32 rounded-full mx-auto mb-4" 
                     alt="<?= $integrante['nombre'] ?>">
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
</main>


<?php include __DIR__ . '/../templates/footer.php'; ?>