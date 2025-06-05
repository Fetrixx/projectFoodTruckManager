<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';

// Obtener datos del usuario actual
$username = $_SESSION['username'];
$email = $_SESSION['email'] ?? '';
?>

<script>
// Inicializar datos en localStorage si no existen
document.addEventListener('DOMContentLoaded', function() {
    // Cargar foodtrucks
    if (!localStorage.getItem('foodtrucks')) {
        localStorage.setItem('foodtrucks', JSON.stringify([
            {
                id: 1,
                nombre: "Tacos El Güero",
                descripcion: "Los mejores tacos de la ciudad con receta familiar",
                rating: 4.5,
                ubicacion: "Frente al edificio principal",
                lat: -0.22985,
                lng: -78.52495,
                horarios: ["10:00", "10:30", "11:00", "11:30", "12:00"],
                menu: [
                    { nombre: "Taco Especial", precio: 3.99, descripcion: "Taco de pastor con piña" },
                    { nombre: "Quesadilla", precio: 4.50, descripcion: "Quesadilla de flor de calabaza" }
                ]
            },
            {
                id: 2,
                nombre: "Burger Paradise",
                descripcion: "Hamburguesas gourmet con ingredientes premium",
                rating: 4.7,
                ubicacion: "Junto a la fuente central",
                lat: -0.23000,
                lng: -78.52510,
                horarios: ["11:00", "11:30", "12:00", "12:30", "13:00"],
                menu: [
                    { nombre: "Clásica", precio: 6.99, descripcion: "Hamburguesa con queso y tocino" },
                    { nombre: "Veggie", precio: 7.50, descripcion: "Hamburguesa vegetariana con portobello" }
                ]
            },
            {
                id: 3,
                nombre: "Sushi Express",
                descripcion: "Sushi fresco preparado al momento",
                rating: 4.2,
                ubicacion: "Área de comida internacional",
                lat: -0.22970,
                lng: -78.52480,
                horarios: ["12:00", "12:30", "13:00", "13:30", "14:00"],
                menu: [
                    { nombre: "Roll California", precio: 8.99, descripcion: "Roll de cangrejo, pepino y aguacate" },
                    { nombre: "Sashimi Variado", precio: 12.50, descripcion: "Selección de 10 piezas de sashimi" }
                ]
            }
        ]));
    }

    // Cargar usuarios
    if (!localStorage.getItem('users')) {
        localStorage.setItem('users', JSON.stringify([
            {
                username: "<?= $username ?>",
                email: "<?= $email ?>",
                favoritos: [1, 2],
                reservas: []
            }
        ]));
    }

    // Cargar reservas
    if (!localStorage.getItem('reservas')) {
        const reservasEjemplo = [
            {
                id: 1001,
                foodtruckId: 1,
                foodtruckName: "Tacos El Güero",
                userId: "<?= $username ?>",
                date: new Date().toLocaleString(),
                items: [
                    { nombre: "Taco Especial", cantidad: 3, precio: 3.99, descripcion: "Taco de pastor con piña" },
                    { nombre: "Quesadilla", cantidad: 2, precio: 4.50, descripcion: "Quesadilla de flor de calabaza" }
                ],
                total: 21.47,
                status: "confirmada"
            },
            {
                id: 1002,
                foodtruckId: 2,
                foodtruckName: "Burger Paradise",
                userId: "usuario2",
                date: new Date().toLocaleString(),
                items: [
                    { nombre: "Clásica", cantidad: 1, precio: 6.99, descripcion: "Hamburguesa con queso y tocino" }
                ],
                total: 6.99,
                status: "pendiente"
            }
        ];
        localStorage.setItem('reservas', JSON.stringify(reservasEjemplo));
    }
    
    // Cargar y mostrar reservas
    loadReservas();
});

function loadReservas() {
    try {
        const currentUser = "<?= $_SESSION['username'] ?>";
        const reservasContainer = document.getElementById('reservas-container');
        const emptyState = document.getElementById('empty-state');
        const publicReservations = JSON.parse(localStorage.getItem('reservas')) || [];
        
        // Limpiar contenedor
        reservasContainer.innerHTML = '';
        
        if (publicReservations.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        } else {
            emptyState.classList.add('hidden');
        }
        
        // Ordenar reservas: primero las del usuario actual
        const reservasOrdenadas = publicReservations.sort((a, b) => {
            if (a.userId === currentUser && b.userId !== currentUser) return -1;
            if (a.userId !== currentUser && b.userId === currentUser) return 1;
            return b.id - a.id;
        });
        
        reservasOrdenadas.forEach(reserva => {
            // Completar foodtruckName si falta
            if (!reserva.foodtruckName && reserva.foodtruckId) {
                const foodtrucks = JSON.parse(localStorage.getItem('foodtrucks')) || [];
                const foodtruck = foodtrucks.find(ft => ft.id === reserva.foodtruckId);
                reserva.foodtruckName = foodtruck ? foodtruck.nombre : "Food Truck Desconocido";
            }
            
            const esUsuarioActual = reserva.userId === currentUser;
            
            const reservaElement = document.createElement('div');
            reservaElement.className = `reserva-card p-6 ${esUsuarioActual ? 'user-reserva' : 'other-reserva'}`;
            reservaElement.innerHTML = `
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-semibold text-xl text-gray-800">${reserva.foodtruckName}</h3>
                        <p class="text-gray-500 text-sm mt-1">ID: ${reserva.id}</p>
                    </div>
                    <span class="status-badge badge-${reserva.status}">${reserva.status}</span>
                </div>
                
                <div class="mb-4">
                    <div class="flex items-center text-gray-600 mb-2">
                        <i class="material-icons mr-2 text-sm">person</i>
                        <span>${reserva.userId}</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="material-icons mr-2 text-sm">schedule</i>
                        <span>${reserva.date}</span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="font-medium text-gray-700 mb-2 flex items-center">
                        <i class="material-icons mr-2 text-sm">list</i>
                        Items:
                    </p>
                    <div class="item-list pl-2">
                        <ul class="space-y-2">
                            ${reserva.items.map(item => `
                                <li class="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                    <div>
                                        <span class="font-medium">${item.nombre}</span>
                                        <p class="text-xs text-gray-500 mt-1">${item.descripcion || ''}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="block font-semibold">$${(item.precio * item.cantidad).toFixed(2)}</span>
                                        <span class="text-xs text-gray-500">${item.cantidad} x $${item.precio.toFixed(2)}</span>
                                    </div>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                </div>
                
                <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                    <span class="font-bold text-lg">Total: $${reserva.total.toFixed(2)}</span>
                    ${esUsuarioActual ? '<span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Tu reserva</span>' : ''}
                </div>
            `;
            
            reservasContainer.appendChild(reservaElement);
        });
        
    } catch (error) {
        console.error("Error al cargar reservas:", error);
    }
}
</script>

<style>
.reserva-card {
    transition: all 0.3s ease;
    border-left: 4px solid;
    border-radius: 8px;
    overflow: hidden;
}

.reserva-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.user-reserva {
    border-left-color: #f97316;
    background: linear-gradient(to right, #fff7ed, #ffffff);
}

.other-reserva {
    border-left-color: #d1d5db;
    background: linear-gradient(to right, #f9fafb, #ffffff);
}

.item-list {
    max-height: 200px;
    overflow-y: auto;
    padding-right: 8px;
}

/* Scrollbar personalizada */
.item-list::-webkit-scrollbar {
    width: 6px;
}

.item-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.item-list::-webkit-scrollbar-thumb {
    background: #c5c5c5;
    border-radius: 10px;
}

.item-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: capitalize;
}

.badge-pendiente {
    background-color: #fef3c7;
    color: #d97706;
}

.badge-confirmada {
    background-color: #dcfce7;
    color: #16a34a;
}

.badge-completada {
    background-color: #dbeafe;
    color: #2563eb;
}

.badge-cancelada {
    background-color: #fee2e2;
    color: #dc2626;
}

.empty-state {
    background-color: #f8fafc;
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}

.hidden-item {
    display: none;
}
</style>

<section class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm p-6 md:p-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="font-heading text-3xl text-primary">Dashboard de Reservas</h1>
            <p class="text-gray-600 mt-2">Administra y realiza un seguimiento de todas tus reservas</p>
        </div>
        <a href="booking.php" class="bg-secondary hover:bg-orange-600 text-white font-medium py-3 px-6 rounded-lg transition flex items-center shadow-md">
            <i class="material-icons mr-2">add</i> Nuevo Pedido
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="reservas-container">
        <!-- Las reservas se cargarán aquí dinámicamente -->
    </div>

    <div id="empty-state" class="hidden-item col-span-full empty-state">
        <i class="material-icons">inventory_2</i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay reservas registradas</h3>
        <p class="text-gray-600 mb-4">Cuando realices una reserva, aparecerá aquí</p>
        <a href="booking.php" class="inline-flex items-center text-secondary font-medium hover:underline">
            <i class="material-icons mr-1">add</i> Crear primera reserva
        </a>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>