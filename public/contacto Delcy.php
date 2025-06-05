<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';
?>

<main class="container mx-auto px-6 py-12">
    <!-- Sección principal con diseño dividido -->
    <section class="max-w-7xl mx-auto bg-gradient-to-r from-primary/5 to-secondary/5 rounded-2xl custom-shadow overflow-hidden">
        <div class="grid md:grid-cols-2 gap-12 p-10">
            <!-- Columna izquierda - Formulario mejorado -->
            <div class="space-y-8">
                <div class="text-center md:text-left">
                    <h2 class="font-heading text-4xl text-primary mb-3">¿Cómo podemos ayudarte?</h2>
                    <p class="text-textSecondary text-lg">
                        Propuesta XXX
                    </p>
                    <p class="text-textSecondary text-lg">Completa el formulario y te responderemos en menos de 24h</p>
                </div>

                <form class="space-y-6">
                    <div class="relative">
                        <input type="text" id="nombre" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl peer focus:border-secondary focus:ring-2 focus:ring-secondary/30"
                               placeholder=" ">
                        <label for="nombre" 
                               class="absolute left-4 top-3 bg-white px-1 transition-all duration-200 
                                      peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400
                                      -translate-y-5 text-textPrimary font-medium">Nombre completo</label>
                    </div>

                    <div class="relative">
                        <input type="email" id="email"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl peer focus:border-secondary focus:ring-2 focus:ring-secondary/30"
                               placeholder=" ">
                        <label for="email" 
                               class="absolute left-4 top-3 bg-white px-1 transition-all duration-200 
                                      peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400
                                      -translate-y-5 text-textPrimary font-medium">Correo electrónico</label>
                    </div>

                    <div class="relative">
                        <textarea id="mensaje" rows="4"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl peer focus:border-secondary focus:ring-2 focus:ring-secondary/30"
                                  placeholder=" "></textarea>
                        <label for="mensaje" 
                               class="absolute left-4 top-4 bg-white px-1 transition-all duration-200 
                                      peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400
                                      -translate-y-5 text-textPrimary font-medium">Tu mensaje</label>
                    </div>

                    <button class="w-full bg-secondary text-white px-8 py-3 rounded-xl font-semibold
                                 hover:bg-orange-600 transition-all duration-300 shadow-lg hover:shadow-secondary/30">
                        Enviar Mensaje ➔
                    </button>
                </form>
            </div>

            <!-- Columna derecha - Info de contacto interactiva -->
            <div class="space-y-8">
                <div class="bg-white p-8 rounded-2xl custom-shadow">
                    <h3 class="font-heading text-2xl text-primary mb-6">Información de contacto</h3>
                    
                    <div class="space-y-5">
                        <a href="tel:+595999999999" class="flex items-center group">
                            <div class="bg-primary/10 p-3 rounded-lg group-hover:bg-secondary/10 transition">
                                <i class="material-icons text-primary group-hover:text-secondary">phone_in_talk</i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Llámanos las 24h</p>
                                <p class="text-textPrimary font-medium">+595 99 999 9999</p>
                            </div>
                        </a>

                        <a href="mailto:contacto@foodtruckmanager.com" class="flex items-center group">
                            <div class="bg-primary/10 p-3 rounded-lg group-hover:bg-secondary/10 transition">
                                <i class="material-icons text-primary group-hover:text-secondary">alternate_email</i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Escríbenos a</p>
                                <p class="text-textPrimary font-medium">contacto@foodtruckmanager.com</p>
                            </div>
                        </a>

                        <div class="flex items-center group">
                            <div class="bg-primary/10 p-3 rounded-lg group-hover:bg-secondary/10 transition">
                                <i class="material-icons text-primary group-hover:text-secondary">place</i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Visítanos en</p>
                                <p class="text-textPrimary font-medium">Av. Principal 123, Ciudad</p>
                            </div>
                        </div>
                    </div>

                    <!-- Mapa interactivo -->
                    <div class="mt-8 bg-white p-4 rounded-xl custom-shadow">
                        <div id="map" class="h-64 rounded-lg overflow-hidden"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Galería de imágenes mejorada -->
        <div class="px-10 pb-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php
                $imagenes = [
                    "/projectFoodTruckManager/public/assets/img/foodtruck_img.jpg",
                    "https://thehawaiivacationguide.com/wp-content/uploads/2020/01/best-maui-food-truck-parks.jpg",
                    "https://thehawaiivacationguide.com/wp-content/uploads/2020/01/hana-maui-food-truck-park-family-1024x768.jpg",
                    "https://thehawaiivacationguide.com/wp-content/uploads/2022/12/maui-food-trucks-family-dining-1024x768.jpg",
                ];

                foreach ($imagenes as $index => $src): ?>
                    <div class="relative group cursor-pointer transform hover:scale-105 transition duration-300">
                        <img src="<?= $src ?>" alt="Food Truck"
                             class="w-full h-48 object-cover rounded-xl shadow-lg"
                             onclick="openModal(<?= $index ?>)">
                        <div class="absolute inset-0 bg-black/30 rounded-xl opacity-0 group-hover:opacity-100 transition"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Equipo en cards horizontales -->
    <section class="max-w-7xl mx-auto mt-20">
        <h2 class="font-heading text-4xl text-primary mb-8 text-center">Nuestro Equipo</h2>
        
        <div class="grid md:grid-cols-3 gap-6">
            <?php
            $integrantes = [
                [
                    'nombre' => 'Elias Medina',
                    'rol' => 'Soporte Técnico',
                    'contacto' => 'emedina@email.com',
                    'foto' => 'user.svg',
                    'redes' => ['phone', 'email', 'chat']
                ],
                // ... otros integrantes
            ];

            foreach ($integrantes as $integrante): ?>
                <div class="bg-white p-6 rounded-2xl custom-shadow hover:shadow-xl transition-shadow">
                    <div class="flex items-center gap-4">
                        <img src="<?= $integrante['foto'] ?>" 
                             class="w-20 h-20 rounded-full border-4 border-secondary/20">
                        <div>
                            <h4 class="font-heading text-xl"><?= $integrante['nombre'] ?></h4>
                            <p class="text-sm text-textSecondary"><?= $integrante['rol'] ?></p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="material-icons text-primary text-sm">email</i>
                            <span class="text-sm"><?= $integrante['contacto'] ?></span>
                        </div>
                        
                        <div class="flex gap-3 mt-4">
                            <?php foreach ($integrante['redes'] as $icono): ?>
                                <button class="p-2 bg-primary/10 rounded-lg hover:bg-secondary/10 transition">
                                    <i class="material-icons text-primary text-xl"><?= $icono ?></i>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<!-- Script del mapa (similar al original) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([-0.22985, -78.52495], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([-0.22985, -78.52495]).addTo(map).bindPopup('¡Te esperamos!');
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>