<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión de Reservas - Elias Medina</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
    <link href="/projectFoodTruckManager/public/assets/css/styles.css" rel="stylesheet">

</head>
<body class="min-h-screen flex flex-col">

    <header class="bg-[var(--primary)] text-white shadow-lg">
        <div class="container mx-auto px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between">
            <h1 class="font-heading text-3xl mb-3 md:mb-0 select-none">🍔 FoodTruck Manager</h1>
            <nav>
                <ul class="flex space-x-8 font-semibold text-lg">
                    <li><a href="#sobre-mi" class="hover:text-[var(--secondary)] transition">Sobre Nosotros</a></li>
                    <li><a href="#servicios" class="hover:text-[var(--secondary)] transition">Servicios</a></li>
                    <li><a href="#contacto" class="hover:text-[var(--secondary)] transition">Contacto</a></li>
                </ul>
            </nav>
            <div class="mt-4 md:mt-0 text-sm text-white/90">
                Bienvenido <span class="font-semibold"><?= htmlspecialchars($_SESSION['username']) ?></span>!<br />
                <span class="text-white/70"><?= htmlspecialchars($_SESSION['email']) ?></span>
                <a href="logout.php" class="ml-4 underline hover:text-[var(--secondary)]">(Salir)</a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 py-12 flex-grow space-y-20">

        <section id="sobre-mi" class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl font-heading text-[var(--primary)] mb-4">Sistema de Gestión para Food Trucks</h2>
            <p class="text-lg text-[var(--textSecondary)] max-w-xl mx-auto leading-relaxed">
                Nuestra plataforma te permite reservar turnos y hacer pedidos anticipados en ferias gastronómicas y eventos de comida. ¡Olvídate de las filas y disfruta más de tus comidas favoritas!
            </p>
        </section>

        <section id="servicios" class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <article class="bg-white rounded-lg custom-shadow p-6 hover:shadow-xl transition cursor-default">
                <h3 class="text-2xl font-semibold mb-3 text-[var(--primary)]">Menú Digital</h3>
                <p class="text-[var(--textSecondary)]">Consulta el menú de cada puesto con imágenes, descripciones y precios actualizados al instante.</p>
            </article>
            <article class="bg-white rounded-lg custom-shadow p-6 hover:shadow-xl transition cursor-default">
                <h3 class="text-2xl font-semibold mb-3 text-[var(--primary)]">Mapa Interactivo</h3>
                <p class="text-[var(--textSecondary)]">Ubica fácilmente cada food truck en el evento para que no pierdas tiempo buscando tu comida favorita.</p>
            </article>
            <article class="bg-white rounded-lg custom-shadow p-6 hover:shadow-xl transition cursor-default">
                <h3 class="text-2xl font-semibold mb-3 text-[var(--primary)]">Opiniones y Favoritos</h3>
                <p class="text-[var(--textSecondary)]">Lee reseñas de otros clientes y guarda tus puestos favoritos para reservar rápido la próxima vez.</p>
            </article>
        </section>

        <section id="contacto" class="max-w-4xl mx-auto bg-white rounded-lg custom-shadow p-8">
            <h2 class="text-3xl font-heading text-[var(--primary)] mb-6 text-center">Contacto</h2>
            <p class="text-center text-[var(--textSecondary)] mb-4">¿Tienes dudas o quieres más información? Escríbenos:</p>
            <p class="text-center text-[var(--secondary)] font-semibold text-lg">
                <a href="mailto:elias.medina@mail.com" class="hover:underline">elias.medina@mail.com</a>
            </p>
        </section>

    </main>

    <footer class="bg-[var(--primary)] text-white py-6 text-center text-sm select-none">
        © 2024 Elias Medina - UA
    </footer>

</body>
</html>