<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';
?>

<main class="container mx-auto px-6 py-12">
    <section class="max-w-4xl mx-auto bg-white rounded-lg custom-shadow p-8">
        <h2 class="font-heading text-3xl text-primary mb-6 text-center">Contáctenos</h2>


        <div class="grid ">

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Formulario -->
                <form class="space-y-4">
                    <div>
                        <label class="block text-textPrimary mb-2">Nombre completo</label>
                        <input type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-secondary">
                    </div>

                    <div>
                        <label class="block text-textPrimary mb-2">Correo electrónico</label>
                        <input type="email"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-secondary">
                    </div>

                    <div>
                        <label class="block text-textPrimary mb-2">Mensaje</label>
                        <textarea rows="4"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-secondary"></textarea>
                    </div>

                    <button class="bg-secondary text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
                        Enviar Mensaje
                    </button>
                </form>

                <!-- Información de contacto -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="material-icons text-primary mr-2">phone</i>
                        <p class="text-textSecondary">+593 99 999 9999</p>
                    </div>
                    <div class="flex items-center">
                        <i class="material-icons text-primary mr-2">email</i>
                        <p class="text-textSecondary">contacto@foodtruckmanager.com</p>
                    </div>
                    <div class="flex " style="flex-direction: column;">
                        <div class="flex">
                            <i class="material-icons text-primary mr-2">place</i>
                            <p class="text-textSecondary">Av. Principal 123, Ciudad</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg custom-shadow">
                            <div id="map" style="height: 200px;" class="rounded-lg"></div>
                        </div>
                    </div>





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
                    <!-- 
                <img src="/projectFoodTruckManager/public/assets/img/mapa.jpg" alt="Ubicación"
                    class="rounded-lg mt-4 custom-shadow"> -->
                </div>
            </div>
            <!-- Imagen representativa -->
            <div class="mb-8 text-center">
                <div class="flex flex-row flex-wrap items-center">
                    <div class="bg-white p-4 rounded-lg custom-shadow">
                        <img src="/projectFoodTruckManager/public/assets/img/foodtruck_img.jpg" alt="Food Truck"
                            class="mx-auto rounded-lg shadow-lg max-w-full h-auto" style="height: 200px;">
                    </div>
                    <div class="bg-white p-4 rounded-lg custom-shadow">
                        <img src="https://thehawaiivacationguide.com/wp-content/uploads/2020/01/best-maui-food-truck-parks.jpg" alt="Food Truck"
                            class="mx-auto rounded-lg shadow-lg max-w-full h-auto" style="height: 200px;">
                    </div>
                    <div class="bg-white p-4 rounded-lg custom-shadow">
                        <img src="https://thehawaiivacationguide.com/wp-content/uploads/2020/01/hana-maui-food-truck-park-family-1024x768.jpg" alt="Food Truck"
                            class="mx-auto rounded-lg shadow-lg max-w-full h-auto" style="height: 200px;">
                    </div>
                    <div class="bg-white p-4 rounded-lg custom-shadow">
                        <img src="https://thehawaiivacationguide.com/wp-content/uploads/2022/12/maui-food-trucks-family-dining-1024x768.jpg" alt="Food Truck"
                            class="mx-auto rounded-lg shadow-lg max-w-full h-auto" style="height: 200px;">
                    </div>
                    <div class="bg-white p-4 rounded-lg custom-shadow">
                        <img src="https://thehawaiivacationguide.com/wp-content/uploads/2022/12/maui-food-trucks-family-dining-1024x768.jpg" alt="Food Truck"
                            class="mx-auto rounded-lg shadow-lg max-w-full h-auto" style="height: 200px;">
                    </div>
                    <div class="bg-white p-4 rounded-lg custom-shadow">
                        <img src="https://thehawaiivacationguide.com/wp-content/uploads/2020/01/maui-food-truck-park-kaanapali-1024x546.jpg" 
                        alt="Food Truck"
                            class="mx-auto rounded-lg shadow-lg max-w-full h-auto" style="height: 200px;">
                    </div>
                    <div class="bg-white p-4 rounded-lg custom-shadow">
                        <img src="https://thehawaiivacationguide.com/wp-content/uploads/2022/12/maui-kaanapali-food-truck-park-1024x576.jpg" 
                        alt="Food Truck"
                            class="mx-auto rounded-lg shadow-lg max-w-full h-auto" style="height: 200px;">
                    </div>
                    <div class="bg-white p-4 rounded-lg custom-shadow">
                        <img src="https://thehawaiivacationguide.com/wp-content/uploads/2022/12/maui-kaanapali-food-truck-park-1024x576.jpg" 
                        alt="Food Truck"
                            class="mx-auto rounded-lg shadow-lg max-w-full h-auto" style="height: 200px;">
                    </div>
                    
                </div>
            </div>
        </div>
    </section>


    <!-- En contactenos.php -->
    <div class="grid md:grid-cols-4 gap-6 mt-8">
        <?php
        $integrantes = [
            ['nombre' => 'Elias Medina', 'contacto' => 'emedina@email.com', 'foto' => 'elias.jpg'],
            // ... otros integrantes
        ];

        foreach ($integrantes as $integrante): ?>
            <div class="text-center p-4 bg-primary/10 rounded-lg">
                <img src="<?= $integrante['foto'] ?>" class="w-24 h-24 rounded-full mx-auto mb-4">
                <h4 class="font-semibold"><?= $integrante['nombre'] ?></h4>
                <p class="text-sm"><?= $integrante['contacto'] ?></p>
                <button class="mt-2 bg-secondary text-white px-4 py-1 rounded">
                    Contactar
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</main>



<?php include __DIR__ . '/../templates/footer.php'; ?>