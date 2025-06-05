<?php
session_start();

// Mover estas credenciales a un archivo de configuración después
$valid_username = "Elias";
$valid_email = "elias.medina@mail.com";
$valid_password = "12345678";

$error = "";
$username_value = isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : '';
$email_value = isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    if ($username === $valid_username && 
        $email === $valid_email && 
        password_verify($password, password_hash($valid_password, PASSWORD_DEFAULT))) {
        
        // Configurar cookies
        if (isset($_POST['remember'])) {
            setcookie('username', $username, time() + 86400 * 30, "/");
            setcookie('email', $email, time() + 86400 * 30, "/");
        }
        
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        
        header("Location: main.php");
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FoodTruck Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="/projectFoodTruckManager/public/assets/css/styles.css" rel="stylesheet">

    <style>
        :root {
            --primary: #EF4444;
            --secondary: #F97316;
            --accent: #10B981;
            --background: #FFF7ED;
            --textPrimary: #1F2937;
            --textSecondary: #4B5563;
        }
        
        .font-heading { font-family: 'Fredoka One', cursive; }
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-background min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 space-y-6">
        <div class="text-center">
            <h1 class="font-heading text-4xl text-primary mb-2">🍔 FoodTruck Manager</h1>
            <p class="text-textSecondary">Acceso al sistema de gestión</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-textPrimary mb-2">Usuario</label>
                <input type="text" name="username" value="<?= $username_value ?>" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary"
                       required>
            </div>
            
            <div>
                <label class="block text-textPrimary mb-2">Correo electrónico</label>
                <input type="email" name="email" value="<?= $email_value ?>" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary"
                       required>
            </div>
            
            <div>
                <label class="block text-textPrimary mb-2">Contraseña</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary"
                       required>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" 
                       class="w-4 h-4 text-secondary focus:ring-secondary">
                <label for="remember" class="ml-2 text-textSecondary">Recordar mis datos</label>
            </div>
            
            <button type="submit" 
                    class="w-full bg-secondary text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition flex items-center justify-center gap-2">
                <i class="material-icons">login</i>
                <span>Ingresar al sistema</span>
            </button>
        </form>
    </div>
</body>
</html>