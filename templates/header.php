<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


if (!defined('CHECK_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Acceso directo prohibido');
}

require_once __DIR__ . '/../src/auth/session.php';
require_once __DIR__ . '/../src/config/constants.php'; // Ajusta la ruta según la ubicación del archivo

?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodTruck Park Manager</title>

    <!-- Tailwind CSS CDN con configuración -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@300;400;600&display=swap"
        rel="stylesheet">
    <!-- CSS Personalizado -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="/projectFoodTruckManager/public/assets/css/styles.css" rel="stylesheet">
    <script src="/projectFoodTruckManager/public/assets/js/storage.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#EF4444',
                        secondary: '#F97316',
                        accent: '#10B981',
                        background: '#FFF7ED',
                        textPrimary: '#1F2937',
                        textSecondary: '#4B5563'
                    },
                    fontFamily: {
                        heading: ['Fredoka One', 'cursive'],
                        sans: ['Poppins', 'sans-serif']

                    }
                }
            }
        }
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            <?php if (!isset($_SESSION['storage_init'])): ?>
                localStorage.clear();
                initStorage();
                <?php $_SESSION['storage_init'] = true; ?>
            <?php endif; ?>
        });
    </script>
</head>

<!--
<body class="min-h-screen flex flex-col bg-background font-[Poppins]">
    -->

<body class="bg-background min-h-screen flex flex-col">
    <?php /* include __DIR__ . '/navbar.php'; */ ?>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <?php include __DIR__ . '/navbar.php'; ?>
    <?php endif; ?>