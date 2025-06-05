<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';
?>

<main class="container mx-auto px-6 py-12">
    <section class="max-w-4xl mx-auto bg-white rounded-lg custom-shadow p-8">
        <h2 class="font-heading text-3xl text-primary mb-6 text-center">Contáctenos</h2>
        <h4 class="font-headingtext-primary mb-6 text-center" style="font-style: italic;">

            (Propuesta Elias Medina) </h4>

        <div class="grid">
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
                        <p class="text-textSecondary">+595 99 999 9999</p>
                    </div>
                    <div class="flex items-center">
                        <i class="material-icons text-primary mr-2">email</i>
                        <p class="text-textSecondary">contacto@foodtruckmanager.com</p>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center">
                            <i class="material-icons text-primary mr-2">place</i>
                            <p class="text-textSecondary">Av. Principal 123, Ciudad</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg custom-shadow mt-2">
                            <div id="map" style="height: 200px; z-index: 0;" class="rounded-lg"></div>
                        </div>
                    </div>

                    <!-- Leaflet CSS y JS -->
                    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                    <script>
                        var map = L.map('map').setView([-0.22985, -78.52495], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(map);
                        L.marker([-0.22985, -78.52495]).addTo(map)
                            .bindPopup('Tacos El Güero').openPopup();
                    </script>
                </div>
            </div>

            <!-- Carrusel compacto -->
            <div class="max-w-6xl mx-auto mt-8 relative">
                <!-- Botón anterior -->
                <button id="prevBtn" aria-label="Anterior"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-secondary bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center shadow hover:bg-orange-600 hover:bg-opacity-80 transition z-20"
                    style="margin-left: -20px;">
                    &#10094;
                </button>

                <!-- <button id="prevBtn" aria-label="Anterior"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-secondary 
                    text-white rounded-full p-2 shadow hover:bg-orange-600 transition z-20">
                    &#10094;
                </button> -->

                <!-- Contenedor scrollable -->
                <div id="carousel" class="overflow-x-auto scrollbar-hide flex space-x-4 py-2 px-6 scroll-smooth">
                    <?php
                    $imagenes = [
                        "/projectFoodTruckManager/public/assets/img/foodtruck_img.jpg",
                        "https://thehawaiivacationguide.com/wp-content/uploads/2020/01/best-maui-food-truck-parks.jpg",
                        "https://thehawaiivacationguide.com/wp-content/uploads/2020/01/hana-maui-food-truck-park-family-1024x768.jpg",
                        "https://thehawaiivacationguide.com/wp-content/uploads/2022/12/maui-food-trucks-family-dining-1024x768.jpg",
                        "https://thehawaiivacationguide.com/wp-content/uploads/2022/12/maui-food-trucks-family-dining-1024x768.jpg",
                        "https://thehawaiivacationguide.com/wp-content/uploads/2020/01/maui-food-truck-park-kaanapali-1024x546.jpg",
                        "https://thehawaiivacationguide.com/wp-content/uploads/2022/12/maui-kaanapali-food-truck-park-1024x576.jpg",
                        "https://thehawaiivacationguide.com/wp-content/uploads/2022/12/maui-kaanapali-food-truck-park-1024x576.jpg",
                    ];

                    foreach ($imagenes as $index => $src): ?>
                        <img src="<?= $src ?>" alt="Food Truck"
                            class="h-[150px] rounded-lg shadow-lg cursor-pointer flex-shrink-0" style="width: auto;"
                            onclick="openModal(<?= $index ?>)">
                    <?php endforeach; ?>
                </div>

                <!-- Botón siguiente -->
                <button id="nextBtn" aria-label="Siguiente"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-secondary bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center shadow hover:bg-orange-600 hover:bg-opacity-80 transition z-20"
                    style="margin-right: -20px;">
                    &#10095;
                </button>

                <!-- <button id="nextBtn" aria-label="Siguiente"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-secondary text-white rounded-full p-2 shadow hover:bg-orange-600 transition z-20">
                    &#10095;
                </button> -->
            </div>
        </div>
    </section>






    <section class="mb-20 text-center mt-20">
        <h2 class="font-heading text-4xl text-primary mb-8">Contactos a Encargados</h2>
        <div class="grid">
            <!-- Sección integrantes -->
            <div class="grid md:grid-cols-3 gap-6 mt-8">
                <?php
                $integrantes = [
                    ['nombre' => 'Elias Medina', 'contacto' => 'emedina@email.com', 'foto' => 'user.svg'],
                    ['nombre' => 'German Lares', 'contacto' => 'glares@email.com', 'foto' => 'user.svg'],
                    ['nombre' => 'Hugo Silguero', 'contacto' => 'hsilguero@email.com', 'foto' => 'user.svg'],
                    ['nombre' => 'Delcy Mendoza', 'contacto' => 'dmendoza@email.com', 'foto' => 'user.svg'],
                    ['nombre' => 'Noelia Apodaca', 'contacto' => 'napodaca@email.com', 'foto' => 'user.svg'],
                    // ... otros integrantes
                ];

                foreach ($integrantes as $integrante): ?>
                    <div class="text-center p-4 bg-primary/10 rounded-lg">
                        <img src="<?= $integrante['foto'] ?>" class="w-24 h-24 rounded-full mx-auto mb-4"
                            alt="<?= $integrante['nombre'] ?>">
                        <h4 class="font-semibold"><?= $integrante['nombre'] ?></h4>
                        <p class="text-sm"><?= $integrante['contacto'] ?></p>
                        <button class="mt-2 bg-secondary text-white px-4 py-1 rounded hover:bg-orange-600 transition">
                            Contactar
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
    </section>

    </div>
</main>

<!-- Modal fullscreen con navegación -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center p-4 z-50 hidden">
    <button onclick="closeModal()"
        class="absolute top-4 right-4 text-white text-3xl font-bold hover:text-gray-300 focus:outline-none">&times;</button>

    <!-- Botón anterior -->
    <button onclick="prevImage()"
        class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-4xl font-bold hover:text-gray-300 focus:outline-none select-none">
        &#10094;
    </button>

    <!-- Imagen ampliada -->
    <img id="modalImage" src="" alt="Imagen ampliada" class="max-w-full max-h-full rounded-lg shadow-lg mx-auto" />

    <!-- Botón siguiente -->
    <button onclick="nextImage()"
        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-4xl font-bold hover:text-gray-300 focus:outline-none select-none">
        &#10095;
    </button>
</div>

<script>
    // Array de imágenes (coincide con PHP)
    const imagenes = <?= json_encode($imagenes) ?>;

    // Carrusel scroll
    const carousel = document.getElementById('carousel');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    // Scroll amount: ajusta según tamaño imagen + margen (aprox 150px altura + 16px margen)
    const scrollAmount = 170;

    prevBtn.addEventListener('click', () => {
        carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    });

    nextBtn.addEventListener('click', () => {
        carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    });

    // Modal y navegación
    let currentIndex = 0;

    function openModal(index) {
        currentIndex = index;
        updateModalImage();
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function updateModalImage() {
        const modalImg = document.getElementById('modalImage');
        modalImg.src = imagenes[currentIndex];
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + imagenes.length) % imagenes.length;
        updateModalImage();
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % imagenes.length;
        updateModalImage();
    }

    // Cerrar modal al hacer clic fuera de la imagen
    document.getElementById('imageModal').addEventListener('click', function (e) {
        if (e.target.id === 'imageModal') {
            closeModal();
        }
    });

    // Navegación con teclado: ESC, flechas
    document.addEventListener('keydown', function (e) {
        if (document.getElementById('imageModal').classList.contains('hidden')) return;

        if (e.key === "Escape") {
            closeModal();
        } else if (e.key === "ArrowLeft") {
            prevImage();
        } else if (e.key === "ArrowRight") {
            nextImage();
        }
    });
</script>

<style>
    /* Oculta scrollbar en navegadores modernos */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>