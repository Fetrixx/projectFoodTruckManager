<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

// Incluir DAOs necesarios
require_once __DIR__ . '/../src/config/Database.php';
require_once __DIR__ . '/../src/DAO/FoodTruckDAO.php';
require_once __DIR__ . '/../src/DAO/MenuDAO.php';

$db = new Src\Config\Database();
$foodtruckDAO = new Src\DAO\FoodTruckDAO($db);
$menuDAO = new Src\DAO\MenuDAO($db);

$foodtruckId = $_GET['foodtruck_id'] ?? null;

if (!$foodtruckId) {
    header('Location: main.php');
    exit;
}

// Obtener información del food truck
$foodtruck = $foodtruckDAO->getFoodTruckById($foodtruckId);
if (!$foodtruck) {
    header('Location: main.php');
    exit;
}

// Obtener menú del food truck
$menu = $menuDAO->getMenuByFoodTruckId($foodtruckId);

include __DIR__ . '/../templates/header.php';
?>

<section class="max-w-4xl mx-auto bg-white rounded-lg custom-shadow p-8">
    <h2 class="font-heading text-3xl text-primary mb-6">Menú de <?= htmlspecialchars($foodtruck['nombre']) ?></h2>

    <div class="grid md:grid-cols-2 gap-6" id="menuContainer">
        <?php if (!empty($menu)): ?>
            <?php foreach ($menu as $item): ?>
                <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-semibold"><?= htmlspecialchars($item['nombre']) ?></h3>
                        <span class="text-secondary font-semibold">$<?= number_format($item['precio'], 2) ?></span>
                    </div>
                    <p class="text-textSecondary text-sm mb-4"><?= htmlspecialchars($item['descripcion']) ?></p>
                    <?php if (!empty($item['imagen'])): ?>
                        <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>"
                            class="w-full h-32 object-cover rounded-xl mb-2" />
                    <?php else: ?>
                        <div
                            class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-32 flex items-center justify-center text-gray-400">
                            Sin imagen
                        </div>
                    <?php endif; ?>
                </div>

                <!-- <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-semibold"><?= htmlspecialchars($item['nombre']) ?></h3>
                        <span class="text-secondary font-semibold">$<?= number_format($item['precio'], 2) ?></span>
                    </div>
                    <p class="text-textSecondary text-sm mb-4"><?= htmlspecialchars($item['descripcion']) ?></p>
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-32"></div>
                </div> -->
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full text-center py-12">
                <i class="material-icons text-5xl text-gray-300 mb-4">fastfood</i>
                <p class="text-textSecondary">Este food truck no tiene menú disponible</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>