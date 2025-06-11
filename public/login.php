<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
require_once __DIR__ . '/../src/config/constants.php';

require_once __DIR__ . '/../src/config/Database.php';
require_once __DIR__ . '/../src/DAO/UserDAO.php';

use Src\Config\Database;
use Src\DAO\UserDAO;

if (isLoggedIn()) {
    header('Location: /projectFoodTruckManager/public/main.php');
    exit;
}

$error = "";
$username_value = isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : '';
$email_value = isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    $db = new Database();
    $userDAO = new UserDAO($db);

    $user = $userDAO->login($email, $password);

    if ($user) {
        if (isset($_POST['remember'])) {
            setcookie('username', $user['nombre'], time() + 86400 * 30, "/");
            setcookie('email', $email, time() + 86400 * 30, "/");
        }

        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];          // Guardar ID usuario
        $_SESSION['username'] = $user['nombre'];
        // $_SESSION['admin'] = $user['admin'];
        $_SESSION['admin'] = (bool) $user['admin'];
        $_SESSION['email'] = $email;
        session_regenerate_id(true);

        header("Location: /projectFoodTruckManager/public/main.php");
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}

$pageTitle = "Iniciar Sesion";

require_once __DIR__ . '/../templates/header.php';
?>

<main class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl custom-shadow p-8 space-y-6">
        <div class="text-center">
            <h1 class="font-heading text-4xl text-primary mb-2">🍔 FoodTruck Park Manager</h1>
            <p class="text-textSecondary">Acceso al sistema de gestión</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-textPrimary mb-2">Correo electrónico</label>
                <input type="email" name="email" value="<?= $email_value ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-secondary" required>
            </div>

            <div>
                <label class="block text-textPrimary mb-2">Contraseña</label>
                <input type="password" name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-secondary" required>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-secondary">
                <label for="remember" class="ml-2 text-textSecondary">Recordar mis datos</label>
            </div>

            <button type="submit"
                class="w-full bg-secondary text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition flex items-center justify-center gap-2">
                <i class="material-icons">login</i>
                <span>Ingresar al sistema</span>
            </button>
        </form>
    </div>
</main>

<?php
/*
 include __DIR__ . '/../templates/footer.php'; 
 */
?>