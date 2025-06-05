<?php 
if (!defined('CHECK_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Acceso directo prohibido');
}
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodTruck Manager</title>
    
    <!-- Tailwind CSS CDN con configuración -->
    <script src="https://cdn.tailwindcss.com"></script>
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
              heading: ['Fredoka One', 'cursive']
            }
          }
        }
      }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- CSS Personalizado -->
    <link href="/projectFoodTruckManager/public/assets/css/styles.css" rel="stylesheet">
</head>
<body class="min-h-screen flex flex-col bg-background font-[Poppins]">