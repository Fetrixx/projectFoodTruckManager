<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Principal - Elias Medina</title>
    <link rel="stylesheet" href="styles.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">

</head>
<body>
    <header>
        <h1>Tema 4. Sistema de gestión de reservas - Elias</h1>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#sobre-mi">Sobre mi</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
			<div class="mensaje-bienvenida">
				Bienvenido <?= htmlspecialchars($_SESSION['username']) ?>!<br>
				<?= htmlspecialchars($_SESSION['email']) ?>
				<a href="logout.php" style="color: white; margin-left: 15px;">(Salir)</a>
			</div>
        </nav>
    </header>
    
    <main class="mainContent">
        <!-- Mantenemos las mismas secciones -->
        <section id="sobre-mi">
            <h2>Sobre mi</h2>
            <p>Soy Elias Medina.</p>
        </section>
        
        <section id="pasatiempos">
            <h2>Mis Pasatiempos</h2>
            <p>Jugar videojuegos y programar.</p>
        </section>
        
        <section id="servicios">
            <h2>Servicios</h2>
            <ul>
                <li>Desarrollo Web</li>
                <li>Soporte Técnico</li>
                <li>Consultoría</li>
            </ul>
        </section>
        
        <section id="contacto">
            <h2>Contacto</h2>
            <p>Email: <a href="mailto:elias.medina@mail.com">elias.medina@mail.com</a></p>
        </section>
    </main>
    
    <footer class="footerContent">
        <p>© 2024 Elias Medina - UA</p>
    </footer>
</body>
</html>