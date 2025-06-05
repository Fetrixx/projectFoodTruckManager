<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';
?>

<script>
    document.getElementById('mobileMenu').addEventListener('click', function () {
        const mobileNav = document.getElementById('mobileNav');
        mobileNav.classList.toggle('hidden');
    });
</script>

<main class="container mx-auto px-6 py-12">
    <!-- Sección Integrantes -->
    <section class="mb-20 text-center">
        <!-- <h2 class="font-heading text-4xl text-primary mb-8">Equipo de Desarrollo</h2> -->
        <h2 class="font-heading text-4xl text-primary mb-8">Equipo de Desarrollo</h2>
        <p class="text-lg text-textSecondary leading-relaxed mb-6" style="text-align: center; font-weight: bold;">
            Grupo 8 - Tema 4:
            <span class="text-lg text-textSecondary leading-relaxed mb-6"
                style="font-style: italic; text-align: center; font-weight: normal;">
                "Sistema de gestión de reservas y pedidos para food trucks o ferias gastronómicas"
            </span>
        </p>
        <div class="grid md:grid-cols-3 gap-6">
            <?php
            $integrantes = [
                ['nombre' => 'Elias Medina', 'rol' => ''], // Líder de Proyecto
                ['nombre' => 'German Lares', 'rol' => ''], // Desarrollador Backend
                ['nombre' => 'Hugo Silguero', 'rol' => ''], // Diseñador UI/UX
                ['nombre' => 'Delcy Mendoza', 'rol' => ''], // Analista de Datos
                ['nombre' => 'Noelia Apodaca', 'rol' => ''] // Desarrollador Frontend
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

    <section class="mb-20 text-center">

        <!-- Sección Integrantes -->
        <!-- 
        <h2 class="font-heading text-4xl text-primary mb-8">Equipo de Desarrollo</h2>
        <p class="text-lg text-textSecondary leading-relaxed mb-6" style="text-align: center; font-weight: bold;">
            Grupo 8 - Tema 4:
            <span class="text-lg text-textSecondary leading-relaxed mb-6"
                style="font-style: italic; text-align: center; font-weight: normal;">
                "Sistema de gestión de reservas y pedidos para food trucks o ferias gastronómicas"
            </span>
        </p>

       
        <div class="grid md:grid-cols-3 gap-8">
            
                  
            <div class="bg-white p-6 rounded-lg custom-shadow">
                <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg"
                    class="w-32 h-32 rounded-full mx-auto mb-4" alt="Elias Medina">
                <h3 class="text-xl font-semibold mb-2">Elias Medina</h3>
            </div>
            <div class="bg-white p-6 rounded-lg custom-shadow">
                <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg"
                    class="w-32 h-32 rounded-full mx-auto mb-4" alt="German Lares">
                <h3 class="text-xl font-semibold mb-2">German Lares</h3>
            </div>
            <div class="bg-white p-6 rounded-lg custom-shadow">
                <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg"
                    class="w-32 h-32 rounded-full mx-auto mb-4" alt="Hugo Silguero">
                <h3 class="text-xl font-semibold mb-2">Hugo Silguero</h3>
            </div>
            <div class="bg-white p-6 rounded-lg custom-shadow">
                <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg"
                    class="w-32 h-32 rounded-full mx-auto mb-4" alt="Delcy Mendoza">
                <h3 class="text-xl font-semibold mb-2">Delcy Mendoza</h3>
                <p class="text-textSecondary">Desarrollador Full Stack</p>
            </div>
            <div class="bg-white p-6 rounded-lg custom-shadow">
                <img src="/projectFoodTruckManager/public/assets/img/user_placeholder.svg"
                    class="w-32 h-32 rounded-full mx-auto mb-4" alt="Noelia Apodaca">
                <h3 class="text-xl font-semibold mb-2">Noelia Apodaca</h3>
            </div>
        </div>
        
        -->
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

    <!-- Descripción del Proyecto -->
     <!-- 
    <section class="mb-20 max-w-4xl mx-auto">
        <h2 class="font-heading text-4xl text-primary mb-6 text-center">Gestión Inteligente para Food Trucks</h2>
        <div class="bg-white p-8 rounded-lg custom-shadow">
            </p>
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
     -->



    <!-- Funcionalidades -->
    <section class="mb-20 bg-gradient-to-r from-primary to-secondary text-white py-12">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="font-heading text-4xl mb-4">¡Funcionalidades Extra!</h2>
            <div class="grid md:grid-cols-2"> <!-- gap-6-->
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
                <!-- 
                <div class="p-6 m-4 bg-white/10 rounded-lg hover:shadow-lg transition ">
                    <i class="material-icons text-4xl">schedule</i>
                    <h3 class="text-xl font-semibold mt-4">Pedidos Anticipados</h3>
                    <p>Realiza tu pedido con horas de anticipación</p>
                </div>
                 -->
            </div>
        </div>
    </section>

    <!-- 
    <section class="mb-20">
        <h2 class="font-heading text-3xl text-primary mb-6 text-center">Ubicación en Tiempo Real</h2>
        <div class="bg-white p-4 rounded-lg custom-shadow">
            <div id="map" class="h-96 rounded-lg"></div>
        </div>
    </section>
    -->
    <section class="mb-20">
        <h2 class="font-heading text-3xl text-primary mb-6 text-center">Ubicación en Tiempo Real</h2>
        <div class="bg-white p-4 rounded-lg custom-shadow">
            <div id="map" style="height: 400px;" class="rounded-lg"></div>
        </div>
    </section>

    <!-- Carga CSS de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Contenedor del mapa -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inicializar el mapa centrado en las coordenadas deseadas
        var map = L.map('map').setView([-0.22985, -78.52495], 15);

        // Cargar mapas desde OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Agregar marcador con popup
        L.marker([-0.22985, -78.52495]).addTo(map)
            .bindPopup('Tacos El Güero')
            .openPopup();
    </script>

</main>


<?php include __DIR__ . '/../templates/footer.php'; ?>