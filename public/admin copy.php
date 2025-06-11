<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();
requireAdmin();

// Incluir DAOs
require_once __DIR__ . '/../src/DAO/FoodTruckDAO.php';
require_once __DIR__ . '/../src/DAO/MenuDAO.php';
require_once __DIR__ . '/../src/Config/Database.php';

// Inicializar DAOs
$database = new Src\Config\Database();
$foodTruckDAO = new Src\DAO\FoodTruckDAO($database);
$menuDAO = new Src\DAO\MenuDAO($database);

// Procesar acciones
$action = $_GET['action'] ?? '';
$foodtruck_id = $_GET['id'] ?? 0;
$menu_id = $_GET['menu_id'] ?? 0;

// Mensajes de éxito/error
$message = '';
$message_type = '';

// Procesar acciones de Food Trucks
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_foodtruck'])) {
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $ubicacion = $_POST['ubicacion'] ?? '';
        $lat = $_POST['lat'] ?? 0;
        $lng = $_POST['lng'] ?? 0;
        $horario_apertura = $_POST['horario_apertura'] ?? '';
        $horario_cierre = $_POST['horario_cierre'] ?? '';
        $imagen = $_POST['imagen'] ?? '';

        if ($id) {
            if ($foodTruckDAO->updateFoodTruck($id, $nombre, $descripcion, $ubicacion, $lat, $lng, $horario_apertura, $horario_cierre, $imagen)) {
                $message = 'Food Truck actualizado correctamente';
                $message_type = 'success';
            } else {
                $message = 'Error al actualizar el Food Truck';
                $message_type = 'error';
            }
        } else {
            $new_id = $foodTruckDAO->createFoodTruck($nombre, $descripcion, $ubicacion, $lat, $lng, $horario_apertura, $horario_cierre, $imagen);
            if ($new_id) {
                $message = 'Food Truck creado correctamente';
                $message_type = 'success';
                $foodtruck_id = $new_id;
            } else {
                $message = 'Error al crear el Food Truck';
                $message_type = 'error';
            }
        }
    } elseif (isset($_POST['save_menu_item'])) {
        $foodtruck_id = $_POST['foodtruck_id'] ?? 0;
        $menu_id = $_POST['menu_id'] ?? 0;
        $nombre = $_POST['nombre_menu'] ?? '';
        $descripcion = $_POST['descripcion_menu'] ?? '';
        $precio = $_POST['precio'] ?? 0;
        $imagen = $_POST['imagen_menu'] ?? '';

        if ($menu_id) {
            if ($menuDAO->updateMenuItem($menu_id, $nombre, $descripcion, $precio, $imagen)) {
                $message = 'Ítem de menú actualizado correctamente';
                $message_type = 'success';
            } else {
                $message = 'Error al actualizar el ítem de menú';
                $message_type = 'error';
            }
        } else {
            if ($menuDAO->createMenuItem($foodtruck_id, $nombre, $descripcion, $precio, $imagen)) {
                $message = 'Ítem de menú creado correctamente';
                $message_type = 'success';
            } else {
                $message = 'Error al crear el ítem de menú';
                $message_type = 'error';
            }
        }
    }
}

// Procesar acciones GET
if ($action === 'delete' && $foodtruck_id) {
    if ($foodTruckDAO->deleteFoodTruck($foodtruck_id)) {
        $message = 'Food Truck eliminado correctamente';
        $message_type = 'success';
        $foodtruck_id = 0;
    } else {
        $message = 'Error al eliminar el Food Truck';
        $message_type = 'error';
    }
} elseif ($action === 'delete_menu' && $menu_id) {
    if ($menuDAO->deleteMenuItem($menu_id)) {
        $message = 'Ítem de menú eliminado correctamente';
        $message_type = 'success';
    } else {
        $message = 'Error al eliminar el ítem de menú';
        $message_type = 'error';
    }
} elseif ($action === 'edit' && $foodtruck_id) {
    $currentFoodTruck = $foodTruckDAO->getFoodTruckById($foodtruck_id);
} elseif ($action === 'edit_menu' && $menu_id) {
    $currentMenuItem = $menuDAO->getMenuItemById($menu_id);
    if ($currentMenuItem) {
        $foodtruck_id = $currentMenuItem['foodtruck_id'];
        $currentFoodTruck = $foodTruckDAO->getFoodTruckById($foodtruck_id);
    }
} elseif ($action === 'new') {
    // Preparar food truck vacío para nuevo registro
    $currentFoodTruck = [
        'id' => '',
        'nombre' => '',
        'descripcion' => '',
        'ubicacion' => '',
        'lat' => '',
        'lng' => '',
        'horario_apertura' => '08:00',
        'horario_cierre' => '22:00',
        'imagen' => ''
    ];
}

// Obtener lista de todos los food trucks
$foodTrucks = $foodTruckDAO->getAllFoodTrucks();

include __DIR__ . '/../templates/header.php';
?>

<main class="container mx-auto px-4 py-8">
    <section class="max-w-6xl mx-auto">
        <h2 class="text-primary text-3xl font-heading mb-6">Panel de Administración</h2>

        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?= $message_type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <!-- Lista de Food Trucks -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Food Trucks Existentes</h3>
                <a href="admin.php?action=new" class="btn-primary">
                    <i class="material-icons mr-1">add</i> Nuevo Food Truck
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border py-2 px-4">ID</th>
                            <th class="border py-2 px-4">Nombre</th>
                            <th class="border py-2 px-4">Ubicación</th>
                            <th class="border py-2 px-4">Horario</th>
                            <th class="border py-2 px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($foodTrucks as $ft): ?>
                            <tr>
                                <td class="border py-2 px-4 text-center"><?= $ft['id'] ?></td>
                                <td class="border py-2 px-4"><?= htmlspecialchars($ft['nombre']) ?></td>
                                <td class="border py-2 px-4"><?= htmlspecialchars($ft['ubicacion']) ?></td>
                                <td class="border py-2 px-4 text-center"><?= substr($ft['horario_apertura'], 0, 5) ?> - <?= substr($ft['horario_cierre'], 0, 5) ?></td>
                                <td class="border py-2 px-4 text-center">
                                    <a href="admin.php?action=edit&id=<?= $ft['id'] ?>" class="text-blue-500 hover:text-blue-700 mr-3">
                                        <i class="material-icons text-sm">edit</i> Editar
                                    </a>
                                    <a href="admin.php?action=delete&id=<?= $ft['id'] ?>" 
                                       class="text-red-500 hover:text-red-700"
                                       onclick="return confirm('¿Estás seguro de eliminar este Food Truck?')">
                                        <i class="material-icons text-sm">delete</i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Formulario de Food Truck -->
        <?php if (($action === 'edit' || $action === 'new') && isset($currentFoodTruck)): ?>
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h3 class="text-xl font-semibold mb-4">
                    <?= $action === 'edit' ? 'Editar Food Truck' : 'Nuevo Food Truck' ?>
                </h3>
                
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $currentFoodTruck['id'] ?? '' ?>">
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block mb-2 font-medium">Nombre*</label>
                            <input type="text" name="nombre" value="<?= htmlspecialchars($currentFoodTruck['nombre'] ?? '') ?>" 
                                   class="w-full p-2 border rounded" required>
                        </div>
                        
                        <div>
                            <label class="block mb-2 font-medium">Imagen URL</label>
                            <input type="text" name="imagen" value="<?= htmlspecialchars($currentFoodTruck['imagen'] ?? '') ?>" 
                                   class="w-full p-2 border rounded">
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block mb-2 font-medium">Descripción</label>
                        <textarea name="descripcion" rows="3" class="w-full p-2 border rounded"><?= htmlspecialchars($currentFoodTruck['descripcion'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block mb-2 font-medium">Ubicación*</label>
                            <input type="text" name="ubicacion" value="<?= htmlspecialchars($currentFoodTruck['ubicacion'] ?? '') ?>" 
                                   class="w-full p-2 border rounded" required>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 font-medium">Latitud</label>
                                <input type="text" name="lat" value="<?= htmlspecialchars($currentFoodTruck['lat'] ?? '') ?>" 
                                       class="w-full p-2 border rounded">
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-medium">Longitud</label>
                                <input type="text" name="lng" value="<?= htmlspecialchars($currentFoodTruck['lng'] ?? '') ?>" 
                                       class="w-full p-2 border rounded">
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block mb-2 font-medium">Horario Apertura*</label>
                            <input type="time" name="horario_apertura" value="<?= $currentFoodTruck['horario_apertura'] ?? '08:00' ?>" 
                                   class="w-full p-2 border rounded" required>
                        </div>
                        
                        <div>
                            <label class="block mb-2 font-medium">Horario Cierre*</label>
                            <input type="time" name="horario_cierre" value="<?= $currentFoodTruck['horario_cierre'] ?? '22:00' ?>" 
                                   class="w-full p-2 border rounded" required>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <a href="admin.php" class="btn-secondary">Cancelar</a>
                        <button type="submit" name="save_foodtruck" class="btn-primary flex justify-center align-center">
                            <i class="material-icons mr-1">save</i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Gestión de Menú -->
        <?php if (($action === 'edit' || $action === 'edit_menu') && isset($currentFoodTruck)): ?>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Menú del Food Truck</h3>
                
                <!-- Lista de ítems del menú -->
                <div class="mb-8">
                    <h4 class="text-lg font-medium mb-3">Ítems del Menú</h4>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border py-2 px-4">Nombre</th>
                                    <th class="border py-2 px-4">Descripción</th>
                                    <th class="border py-2 px-4">Precio</th>
                                    <th class="border py-2 px-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $menuItems = $menuDAO->getMenuByFoodTruckId($currentFoodTruck['id']);
                                foreach ($menuItems as $item): ?>
                                    <tr>
                                        <td class="border py-2 px-4"><?= htmlspecialchars($item['nombre']) ?></td>
                                        <td class="border py-2 px-4"><?= htmlspecialchars($item['descripcion']) ?></td>
                                        <td class="border py-2 px-4 text-right"><?= number_format($item['precio'], 2) ?> €</td>
                                        <td class="border py-2 px-4 text-center">
                                            <a href="admin.php?action=edit_menu&id=<?= $currentFoodTruck['id'] ?>&menu_id=<?= $item['id'] ?>" 
                                               class="text-blue-500 hover:text-blue-700 mr-3">
                                                <i class="material-icons text-sm">edit</i>
                                            </a>
                                            <a href="admin.php?action=delete_menu&menu_id=<?= $item['id'] ?>" 
                                               class="text-red-500 hover:text-red-700"
                                               onclick="return confirm('¿Estás seguro de eliminar este ítem?')">
                                                <i class="material-icons text-sm">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Formulario para nuevo/editar ítem de menú -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold mb-4">
                        <?= ($action === 'edit_menu' && isset($currentMenuItem)) ? 'Editar Ítem de Menú' : 'Nuevo Ítem de Menú' ?>
                    </h4>
                    
                    <form method="POST">
                        <input type="hidden" name="foodtruck_id" value="<?= $currentFoodTruck['id'] ?>">
                        <input type="hidden" name="menu_id" value="<?= $currentMenuItem['id'] ?? '' ?>">
                        
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block mb-2 font-medium">Nombre*</label>
                                <input type="text" name="nombre_menu" value="<?= htmlspecialchars($currentMenuItem['nombre'] ?? '') ?>" 
                                       class="w-full p-2 border rounded" required>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-medium">Precio*</label>
                                <input type="number" name="precio" step="0.01" min="0" 
                                       value="<?= htmlspecialchars($currentMenuItem['precio'] ?? '') ?>" 
                                       class="w-full p-2 border rounded" required>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block mb-2 font-medium">Descripción</label>
                            <textarea name="descripcion_menu" rows="2" class="w-full p-2 border rounded"><?= htmlspecialchars($currentMenuItem['descripcion'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block mb-2 font-medium">Imagen URL</label>
                            <input type="text" name="imagen_menu" value="<?= htmlspecialchars($currentMenuItem['imagen'] ?? '') ?>" 
                                   class="w-full p-2 border rounded">
                        </div>
                        
                        <div class="flex justify-end space-x-4">
                            <?php if (isset($currentMenuItem)): ?>
                                <a href="admin.php?action=edit&id=<?= $currentFoodTruck['id'] ?>" class="btn-secondary">
                                    Cancelar
                                </a>
                            <?php endif; ?>
                            <button type="submit" name="save_menu_item" class="btn-primary flex justify-center align-center">
                                <i class="material-icons mr-1">save</i> 
                                <?= isset($currentMenuItem) ? 'Actualizar' : 'Agregar' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>