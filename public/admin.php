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


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Food Trucks</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root {
            --primary: #3498db;
            --secondary: #f39c12;
            --success: #2ecc71;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --border-radius: 12px;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .text-primary {
            color: var(--primary);
        }
        
        .text-secondary {
            color: var(--secondary);
        }
        
        .text-success {
            color: var(--success);
        }
        
        .text-danger {
            color: var(--danger);
        }
        
        .bg-light {
            background-color: var(--light);
        }
        
        .bg-white {
            background-color: #fff;
        }
        
        .rounded-xl {
            border-radius: var(--border-radius);
        }
        
        .shadow-md {
            box-shadow: var(--shadow);
        }
        
        .p-6 {
            padding: 1.5rem;
        }
        
        .mb-6 {
            margin-bottom: 1.5rem;
        }
        
        .mb-8 {
            margin-bottom: 2rem;
        }
        
        .flex {
            display: flex;
        }
        
        .justify-between {
            justify-content: space-between;
        }
        
        .items-center {
            align-items: center;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            border: none;
            font-size: 0.95rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--light-gray);
            color: var(--dark);
        }
        
        .btn-secondary:hover {
            background-color: #d6d8db;
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
        }
        
        .material-icons {
            vertical-align: middle;
            font-size: 1.1rem;
        }
        
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
            margin-bottom: 1.5rem;
        }
        
        .card:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--light-gray);
            background-color: #f8fafc;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0;
        }
        
        .card-subtitle {
            font-size: 0.9rem;
            color: var(--gray);
            margin-top: 0.25rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary);
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
            color: var(--primary);
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #495057;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        thead th {
            background-color: #f1f5f9;
            font-weight: 600;
            color: #4a5568;
        }
        
        tbody tr:hover {
            background-color: rgba(241, 245, 249, 0.5);
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .scroll-target {
            scroll-margin-top: 100px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .badge-active {
            background-color: rgba(46, 204, 113, 0.15);
            color: var(--success);
        }
        
        .badge-inactive {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--danger);
        }
        
        @media (max-width: 768px) {
            .flex-col-mobile {
                flex-direction: column;
            }
            
            .grid-cols-1-mobile {
                grid-template-columns: 1fr;
            }
        }
        
        .highlight-section {
            border-left: 4px solid var(--primary);
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .transition-all {
            transition: var(--transition);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../templates/header.php'; ?>
    
    <main class="container mx-auto px-4 py-8">
        <section class="max-w-6xl mx-auto">
            <h2 class="section-title">
                <i class="material-icons">dashboard</i>
                Panel de Administración - Food Trucks
            </h2>

            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-lg <?= $message_type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?> flex items-center">
                    <i class="material-icons mr-2"><?= $message_type === 'success' ? 'check_circle' : 'error' ?></i>
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <!-- Lista de Food Trucks -->
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="card-title">Food Trucks Existentes</h3>
                            <p class="card-subtitle">Administra los food trucks registrados en el sistema</p>
                        </div>
                        <a href="admin.php?action=new" class="btn btn-primary">
                            <i class="material-icons mr-1">add</i> Nuevo Food Truck
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Ubicación</th>
                                    <th>Horario</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($foodTrucks as $ft): ?>
                                    <tr>
                                        <td><?= $ft['id'] ?></td>
                                        <td class="font-medium"><?= htmlspecialchars($ft['nombre']) ?></td>
                                        <td><?= htmlspecialchars($ft['ubicacion']) ?></td>
                                        <td><?= substr($ft['horario_apertura'], 0, 5) ?> - <?= substr($ft['horario_cierre'], 0, 5) ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="admin.php?action=edit&id=<?= $ft['id'] ?>" 
                                                   class="btn btn-secondary btn-sm">
                                                    <i class="material-icons">edit</i> Editar
                                                </a>
                                                <a href="admin.php?action=delete&id=<?= $ft['id'] ?>" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('¿Estás seguro de eliminar este Food Truck?')">
                                                    <i class="material-icons">delete</i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Formulario de Food Truck -->
            <?php if (($action === 'edit' || $action === 'new' || $action === 'edit_menu') && isset($currentFoodTruck)): ?>
                <div class="card highlight-section">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="material-icons">local_dining</i>
                            <?= $action === 'new' ? 'Crear Nuevo Food Truck' : 'Editar Food Truck: ' . htmlspecialchars($currentFoodTruck['nombre']) ?>
                        </h3>
                        <?php if ($action !== 'new'): ?>
                            <p class="card-subtitle">ID: <?= $currentFoodTruck['id'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $currentFoodTruck['id'] ?? '' ?>">
                            
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                <div class="form-group">
                                    <label class="form-label">Nombre*</label>
                                    <input type="text" name="nombre" value="<?= htmlspecialchars($currentFoodTruck['nombre'] ?? '') ?>" 
                                           class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Imagen URL</label>
                                    <input type="text" name="imagen" value="<?= htmlspecialchars($currentFoodTruck['imagen'] ?? '') ?>" 
                                           class="form-control">
                                    <?php if (!empty($currentFoodTruck['imagen'])): ?>
                                        <div class="mt-2 text-sm text-gray-500">Imagen actual: <a href="<?= $currentFoodTruck['imagen'] ?>" target="_blank" class="text-blue-500">Ver imagen</a></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="form-group mb-6">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" rows="3" class="form-control"><?= htmlspecialchars($currentFoodTruck['descripcion'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                <div class="form-group">
                                    <label class="form-label">Ubicación*</label>
                                    <input type="text" name="ubicacion" value="<?= htmlspecialchars($currentFoodTruck['ubicacion'] ?? '') ?>" 
                                           class="form-control" required>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label">Latitud</label>
                                        <input type="text" name="lat" value="<?= htmlspecialchars($currentFoodTruck['lat'] ?? '') ?>" 
                                               class="form-control">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Longitud</label>
                                        <input type="text" name="lng" value="<?= htmlspecialchars($currentFoodTruck['lng'] ?? '') ?>" 
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                <div class="form-group">
                                    <label class="form-label">Horario Apertura*</label>
                                    <input type="time" name="horario_apertura" value="<?= $currentFoodTruck['horario_apertura'] ?? '08:00' ?>" 
                                           class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Horario Cierre*</label>
                                    <input type="time" name="horario_cierre" value="<?= $currentFoodTruck['horario_cierre'] ?? '22:00' ?>" 
                                           class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-4">
                                <a href="admin.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" name="save_foodtruck" class="btn btn-primary">
                                    <i class="material-icons mr-1">save</i> Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Gestión de Menú -->
            <?php if (($action === 'edit' || $action === 'edit_menu') && isset($currentFoodTruck)): ?>
                <div class="card highlight-section scroll-target" id="menu-section">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="material-icons">restaurant_menu</i>
                            Menú del Food Truck: <?= htmlspecialchars($currentFoodTruck['nombre']) ?>
                        </h3>
                        <p class="card-subtitle">Administra los ítems del menú de este food truck</p>
                    </div>
                    <div class="card-body">
                        <!-- Lista de ítems del menú -->
                        <div class="mb-8">
                            <h4 class="text-lg font-medium mb-4 flex items-center">
                                <i class="material-icons mr-2">list</i>
                                Ítems del Menú
                            </h4>
                            
                            <div class="overflow-x-auto">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Precio</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $menuItems = $menuDAO->getMenuByFoodTruckId($currentFoodTruck['id']);
                                        foreach ($menuItems as $item): ?>
                                            <tr class="<?= ($action === 'edit_menu' && isset($currentMenuItem) && $currentMenuItem['id'] == $item['id']) ? 'bg-blue-50' : '' ?>">
                                                <td class="font-medium"><?= htmlspecialchars($item['nombre']) ?></td>
                                                <td><?= htmlspecialchars($item['descripcion']) ?></td>
                                                <td class="font-semibold"><?= number_format($item['precio'], 2) ?> €</td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="admin.php?action=edit_menu&id=<?= $currentFoodTruck['id'] ?>&menu_id=<?= $item['id'] ?>#menu-section" 
                                                           class="btn btn-secondary btn-sm">
                                                            <i class="material-icons">edit</i>
                                                        </a>
                                                        <a href="admin.php?action=delete_menu&menu_id=<?= $item['id'] ?>" 
                                                           class="btn btn-danger btn-sm"
                                                           onclick="return confirm('¿Estás seguro de eliminar este ítem?')">
                                                            <i class="material-icons">delete</i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($menuItems)): ?>
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-gray-500">
                                                    No hay ítems en el menú de este food truck
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Formulario para nuevo/editar ítem de menú -->
                        <div class="border-t pt-6">
                            <h4 class="text-lg font-semibold mb-4 flex items-center">
                                <i class="material-icons mr-2"><?= ($action === 'edit_menu' && isset($currentMenuItem)) ? 'edit' : 'add' ?></i>
                                <?= ($action === 'edit_menu' && isset($currentMenuItem)) ? 'Editar Ítem de Menú' : 'Agregar Nuevo Ítem de Menú' ?>
                            </h4>
                            
                            <form method="POST">
                                <input type="hidden" name="foodtruck_id" value="<?= $currentFoodTruck['id'] ?>">
                                <input type="hidden" name="menu_id" value="<?= $currentMenuItem['id'] ?? '' ?>">
                                
                                <div class="grid md:grid-cols-2 gap-6 mb-6">
                                    <div class="form-group">
                                        <label class="form-label">Nombre*</label>
                                        <input type="text" name="nombre_menu" value="<?= htmlspecialchars($currentMenuItem['nombre'] ?? '') ?>" 
                                               class="form-control" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Precio*</label>
                                        <input type="number" name="precio" step="0.01" min="0" 
                                               value="<?= htmlspecialchars($currentMenuItem['precio'] ?? '') ?>" 
                                               class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-6">
                                    <label class="form-label">Descripción</label>
                                    <textarea name="descripcion_menu" rows="2" class="form-control"><?= htmlspecialchars($currentMenuItem['descripcion'] ?? '') ?></textarea>
                                </div>
                                
                                <div class="form-group mb-6">
                                    <label class="form-label">Imagen URL</label>
                                    <input type="text" name="imagen_menu" value="<?= htmlspecialchars($currentMenuItem['imagen'] ?? '') ?>" 
                                           class="form-control">
                                    <?php if (isset($currentMenuItem) && !empty($currentMenuItem['imagen'])): ?>
                                        <div class="mt-2 text-sm text-gray-500">Imagen actual: <a href="<?= $currentMenuItem['imagen'] ?>" target="_blank" class="text-blue-500">Ver imagen</a></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex justify-end space-x-4">
                                    <?php if (isset($currentMenuItem)): ?>
                                        <a href="admin.php?action=edit&id=<?= $currentFoodTruck['id'] ?>" class="btn btn-secondary">
                                            Cancelar
                                        </a>
                                    <?php endif; ?>
                                    <button type="submit" name="save_menu_item" class="btn btn-primary">
                                        <i class="material-icons mr-1">save</i> 
                                        <?= isset($currentMenuItem) ? 'Actualizar' : 'Agregar' ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php include __DIR__ . '/../templates/footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($action === 'edit_menu'): ?>
                // Hacer scroll a la sección de menú cuando se edita un ítem
                const menuSection = document.getElementById('menu-section');
                if (menuSection) {
                    menuSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Resaltar la sección momentáneamente
                    menuSection.classList.add('highlight-section');
                    setTimeout(() => {
                        menuSection.classList.remove('highlight-section');
                    }, 3000);
                }
            <?php endif; ?>
            
            // Manejar el resaltado de secciones
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    cards.forEach(c => c.classList.remove('highlight-section'));
                    this.classList.add('highlight-section');
                });
            });
        });
    </script>
</body>
</html>