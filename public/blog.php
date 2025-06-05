<!DOCTYPE html>
<html lang="es" class="scroll-smooth">

<head>
    <!-- Mismo header que templates/header.php -->
</head>

<body class="bg-background min-h-screen flex flex-col">
    <header class="bg-primary text-white shadow-lg">
        <!-- Misma navegación -->
    </header>

    <main class="container mx-auto px-6 py-12">
        <section class="max-w-4xl mx-auto bg-white rounded-lg custom-shadow p-8">
            <h1 class="font-heading text-4xl text-primary mb-8">Últimas Noticias</h1>

            <!-- Artículo destacado -->
            <article class="mb-8">
                <h2 class="text-2xl font-semibold text-primary mb-2">Nuevo Sistema de Reservas</h2>
                <p class="text-textSecondary mb-4">Publicado el 15 de Mayo por Elias Medina</p>
                <img src="/projectFoodTruckManager/public/assets/img/blog1.jpg" alt="Nuevo sistema"
                    class="rounded-lg mb-4 custom-shadow">
                <p class="text-textSecondary leading-relaxed">
                    Descripción detallada de las nuevas funcionalidades...
                </p>
            </article>

            <!-- Listado de artículos -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Repetir artículos -->
            </div>
        </section>


        <!-- En el blog -->
        <div class="author-badges">
            <?php
            $autores = ['Elias Medina', 'German Lares', /*...*/];
            foreach ($autores as $autor): ?>
                <span class="bg-secondary text-white px-3 py-1 rounded-full text-sm">
                    <?= $autor ?>
                </span>
            <?php endforeach; ?>
        </div>

        <article class="blog-post">
            <div class="author-info">
                <img src="elias.jpg" class="author-image">
                <div>
                    <h4>Elias Medina</h4>
                    <p>15 de Mayo, 2024</p>
                </div>
            </div>
            <!-- Contenido del post -->
        </article>
    </main>

    <!-- Mismo footer -->
</body>

</html>