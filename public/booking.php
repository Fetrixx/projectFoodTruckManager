<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

// Incluir DAOs necesarios
require_once __DIR__ . '/../src/config/Database.php';
require_once __DIR__ . '/../src/DAO/FoodTruckDAO.php';
require_once __DIR__ . '/../src/DAO/MenuDAO.php';
require_once __DIR__ . '/../src/DAO/FavoritoDAO.php';
require_once __DIR__ . '/../src/DAO/ReservaDAO.php';
// require_once __DIR__ . '/../src/DAO/ReservaItemDAO.php';

$db = new Src\Config\Database();
$foodtruckDAO = new Src\DAO\FoodTruckDAO($db);
$menuDAO = new Src\DAO\MenuDAO($db);
$favoritoDAO = new Src\DAO\FavoritoDAO($db);
$reservaDAO = new Src\DAO\ReservaDAO($db);
$reservaItemDAO = new Src\DAO\ReservaDAO($db);

// Obtener usuario actual
$usuarioId = $_SESSION['user_id'] ?? null;

// Obtener foodtrucks desde la base de datos
$foodtrucks = $foodtruckDAO->getAllFoodTrucks();

// Obtener favoritos del usuario
$favoritos = $favoritoDAO->getFavoritosByUsuarioId($usuarioId, true);
$favoritosIds = array_column($favoritos, 'foodtruck_id');

// Obtener menús para cada foodtruck
foreach ($foodtrucks as &$foodtruck) {
    $foodtruck['menu'] = $menuDAO->getMenuByFoodTruckId($foodtruck['id']);
}

include __DIR__ . '/../templates/header.php';
?>

<style>
/* Estilos mejorados para responsividad y diseño */
.card-foodtruck {
    min-height: 120px;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.card-foodtruck:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: #f97316;
}

.menu-item {
    padding: 1rem 0;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.menu-item:last-child {
    border-bottom: none;
}

.resumen-pedido {
    position: relative;
    background: #f9fafb;
    border-radius: 8px;
    padding: 1.5rem;
}

.step-container {
    display: none;
}

.step-container.active {
    display: block;
}

.step-indicator {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
    position: relative;
}

.step-indicator::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: #e5e7eb;
    z-index: 0;
}

.step {
    position: relative;
    z-index: 1;
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    border: 2px solid #e5e7eb;
    font-weight: 500;
}

.step.active {
    border-color: #f97316;
    color: #f97316;
    font-weight: 700;
}

.quantity-input {
    width: 70px;
    text-align: center;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 4px;
}

.confirmation-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
    margin-top: 1rem;
}

.confirmation-buttons button {
    flex: 1;
    min-width: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.fav-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    color: #9ca3af;
    transition: all 0.2s ease;
}

.fav-icon:hover {
    color: #ef4444;
}

.fav-icon.active {
    color: #ef4444;
}

.foodtruck-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.menu-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 1024px) {
    .menu-grid {
        grid-template-columns: 1fr 1fr;
    }
}

.qr-container {
    display: flex;
    justify-content: center;
    margin: 1.5rem 0;
}

.empty-cart {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}
</style>

<script>
// Pasamos los datos desde PHP a JavaScript
const foodtrucks = <?= json_encode($foodtrucks) ?>;
const userFavorites = <?= json_encode($favoritosIds) ?>;

// Objeto para manejar el estado de la aplicación
const bookingApp = {
    currentStep: 1,
    selectedFoodTruck: null,
    cart: [],
    reservationId: null,
    
    init() {
        // Si hay un foodtruck en la URL, seleccionarlo automáticamente
        const urlParams = new URLSearchParams(window.location.search);
        const foodtruckId = urlParams.get('foodtruck');
        if (foodtruckId) {
            const ft = foodtrucks.find(f => f.id == foodtruckId);
            if (ft) {
                this.selectFoodTruck(ft);
            }
        }
        
        // Renderizar el paso inicial
        this.renderStep1();
    },
    
    renderStep1() {
        this.updateStepIndicator();
        
        const container = document.getElementById('step1-container');
        container.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Selecciona tu Food Truck</h3>
                <button id="btn-dashboard" class="text-primary flex items-center">
                    <span class="flex justify-center align-center">
                        <i class="material-icons mr-1">dashboard</i> 
                        <span class="flex justify-center align-center hover:underline">
                            Ver Dashboard
                        </span>
                    </span>
                </button>
            </div>
            <div class="foodtruck-grid" id="foodtrucks-container"></div>
        `;
        
        const foodtrucksContainer = document.getElementById('foodtrucks-container');
        foodtrucksContainer.innerHTML = '';
        
        foodtrucks.forEach(foodtruck => {
            const isFavorite = userFavorites.includes(parseInt(foodtruck.id));
            console.log(foodtruck);
            const stars = '★'.repeat(Math.round(foodtruck.avg_rating)) + '☆'.repeat(5 - Math.round(foodtruck.avg_rating));
            
            const card = document.createElement('div');
            card.className = 'card-foodtruck relative';
            card.innerHTML = `
                <i class="material-icons fav-icon ${isFavorite ? 'active' : ''}" 
                data-id="${foodtruck.id}">${isFavorite ? 'favorite' : 'favorite_border'}</i>
                <div class="flex items-start">
                    ${
                        foodtruck.imagen
                        ? `<img src="${foodtruck.imagen}" alt="${foodtruck.nombre}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0" />`
                        : `<div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex-shrink-0 flex items-center justify-center text-gray-400 text-xs">Sin imagen</div>`
                    }
                    <div class="ml-4 flex-grow">
                        <h4 class="font-semibold text-lg">${foodtruck.nombre}</h4>
                        <p class="text-gray-600 text-sm mt-1">${foodtruck.descripcion}</p>
                        <div class="flex items-center mt-2">
                            <div class="text-orange-400">${stars}</div>
                            <span class="text-gray-600 ml-2">${Number(foodtruck.avg_rating).toFixed(2)}</span>
                            <span class="text-gray-600 ml-2">(${foodtruck.review_count})</span>
                        </div>
                    </div>
                </div>
            `;

            /*
            card.innerHTML = `
                <i class="material-icons fav-icon ${isFavorite ? 'active' : ''}" 
                   data-id="${foodtruck.id}">${isFavorite ? 'favorite' : 'favorite_border'}</i>
                <div class="flex items-start">
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex-shrink-0"></div>
                    <div class="ml-4 flex-grow">
                        <h4 class="font-semibold text-lg">${foodtruck.nombre}</h4>
                        <p class="text-gray-600 text-sm mt-1">${foodtruck.descripcion}</p>
                        <div class="flex items-center mt-2">
                            <div class="text-orange-400">${stars}</div>
                            <span class="text-gray-600 ml-2">${Number(foodtruck.avg_rating).toFixed(2)}</span>
                            <span class="text-gray-600 ml-2">(${foodtruck.review_count})</span>
                        </div>
                    </div>
                </div>
            `;*/
            
            // Evento para seleccionar foodtruck
            card.addEventListener('click', (e) => {
                if (!e.target.classList.contains('fav-icon')) {
                    this.selectFoodTruck(foodtruck);
                }
            });
            
            // Evento para favoritos
            const favIcon = card.querySelector('.fav-icon');
            favIcon.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleFavorite(foodtruck.id, favIcon);
            });
            
            foodtrucksContainer.appendChild(card);
        });
        
        // Configurar evento para dashboard
        document.getElementById('btn-dashboard').addEventListener('click', () => {
            window.location.href = 'reservas.php';
        });
    },
    
    renderStep2() {
    this.updateStepIndicator();

    const container = document.getElementById('step2-container');
    container.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-center flex-grow">Realiza tu pedido</h3>
        </div>
        <div class="menu-grid">
            <div class="menu-items space-y-4" id="menu-items-container">
                ${this.selectedFoodTruck.menu.map(item => `
                    <div class="menu-item border rounded-lg p-4 flex items-center space-x-4" style="padding:16px">
                        <img src="${item.imagen || 'ruta/por/defecto.jpg'}" alt="${item.nombre}" class="w-16 h-16 object-cover rounded-lg" />
                        <div class="flex-grow">
                            <h4 class="font-semibold">${item.nombre}</h4>
                            <p class="text-textSecondary text-sm">${item.descripcion}</p>
                            <p class="text-secondary font-semibold mt-1">$${item.precio}</p>
                        </div>
                        <input type="number" min="0" class="quantity-input w-16" 
                               data-item='${JSON.stringify(item).replace(/'/g, "\\'")}'
                               value="${this.getQuantity(item.id)}" />
                    </div>
                `).join('')}
            </div>
            <div class="resumen-pedido">
                <h4 class="text-lg font-semibold mb-4">Tu orden</h4>
                <div class="space-y-3 max-h-96 overflow-y-auto" id="cart-items-container"></div>
                <div class="mt-6 pt-4 border-t">
                    <div class="flex justify-between font-semibold text-lg">
                        <span>Total:</span>
                        <span id="cart-total">$0.00</span>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button id="btn-back-step1-2" class="flex-1 bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300 transition">
                            <span class="flex justify-center align-center">
                                <i class="material-icons mr-1">arrow_back</i> Atrás
                            </span
                        </button>
                        <button id="btn-confirm-reservation" class="flex-1 bg-secondary text-white py-2 rounded-lg hover:bg-orange-600 transition flex items-center justify-center">
                            <span class="flex justify-center align-center">
                                Confirmar <i class="material-icons ml-1">check</i>
                            </span
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Configurar eventos para inputs de cantidad
    const inputs = container.querySelectorAll('.quantity-input');
    inputs.forEach(input => {
        input.addEventListener('change', (e) => {
            try {
                const itemData = JSON.parse(e.target.dataset.item.replace(/\\'/g, "'"));
                this.updateQuantity(itemData, parseInt(e.target.value) || 0);
                this.renderCart();
            } catch (error) {
                console.error('Error parsing item data:', error);
            }
        });
    });

    // Configurar botones
    document.getElementById('btn-back-step1-2')?.addEventListener('click', () => {
        this.currentStep = 1;
        this.renderStep1();
    });

    document.getElementById('btn-confirm-reservation')?.addEventListener('click', () => {
        this.saveReservation();
    });

    // Renderizar carrito
    this.renderCart();
},
    
    renderStep3() {
        this.updateStepIndicator();
        
        const container = document.getElementById('step3-container');
        container.innerHTML = `
            <h3 class="text-xl font-semibold mb-6 text-center">¡Reserva Confirmada!</h3>
            <div class="bg-gray-50 rounded-lg p-6 max-w-2xl mx-auto">
                <p class="mb-4 text-center">Gracias por tu reserva en <span class="font-semibold text-secondary">${this.selectedFoodTruck.nombre}</span></p>
                
                <div class="mb-6 bg-white rounded-lg p-4 shadow-sm">
                    <p class="font-semibold text-lg mb-3 text-center">Detalles de tu pedido:</p>
                    <ul class="text-left" id="confirmation-items"></ul>
                    <div class="flex justify-between border-t mt-3 pt-3 font-bold text-lg">
                        <span>Total:</span>
                        <span id="confirmation-total">$${this.cartTotal().toFixed(2)}</span>
                    </div>
                </div>
                
                <div class="qr-container">
                    <div id="qrcode"></div>
                </div>
                
                <p class="text-sm text-textSecondary mb-6 text-center">Presenta este código QR al food truck para reclamar tu pedido</p>
                
                <div class="confirmation-buttons">
                    <button id="btn-print" class="bg-primary text-white hover:bg-red-700 transition flex items-center">
                        <i class="material-icons mr-1">print</i> Imprimir
                    </button>
                    <button id="btn-new-reservation" class="bg-secondary text-white hover:bg-orange-600 transition flex items-center">
                        <i class="material-icons mr-1">add</i> Nueva Reserva
                    </button>
                    <button id="btn-close" class="bg-gray-600 text-white hover:bg-gray-700 transition flex items-center">
                        <i class="material-icons mr-1">close</i> Cerrar
                    </button>
                </div>
            </div>
        `;
        
        // Renderizar items en la confirmación
        const itemsContainer = document.getElementById('confirmation-items');
        itemsContainer.innerHTML = '';
        
        this.cart.forEach(item => {
            const li = document.createElement('li');
            li.className = 'flex justify-between py-2 border-b border-gray-100';
            li.innerHTML = `
                <div>
                    <span>${item.quantity} x ${item.nombre}</span>
                    <p class="text-sm text-gray-500">${item.descripcion}</p>
                </div>
                <span class="font-semibold">$${(item.precio * item.quantity).toFixed(2)}</span>
            `;
            itemsContainer.appendChild(li);
        });
        
        // Generar QR
        this.generateQR(this.reservationId);
        
        // Configurar eventos
        document.getElementById('btn-print').addEventListener('click', () => {
            window.print();
        });
        
        document.getElementById('btn-new-reservation').addEventListener('click', () => {
            this.currentStep = 1;
            this.cart = [];
            this.renderStep1();
        });
        
        document.getElementById('btn-close').addEventListener('click', () => {
            window.location.href = 'reservas.php';
        });
    },
    
    renderMenuItems() {
        const container = document.getElementById('menu-items-container');
        container.innerHTML = '';
        
        this.selectedFoodTruck.menu.forEach(item => {
            const menuItem = document.createElement('div');
            menuItem.className = 'menu-item';
            menuItem.innerHTML = `
                <div class="flex items-start flex-grow">
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex-shrink-0"></div>
                    <div class="ml-4 flex-grow">
                        <h4 class="font-semibold">${item.nombre}</h4>
                        <p class="text-textSecondary text-sm mt-1">${item.descripcion}</p>
                        <p class="text-secondary font-semibold mt-2">$${item.precio}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 ml-4">
                    <input type="number" min="0" class="quantity-input" 
                           data-item='${JSON.stringify(item).replace(/'/g, "\\'")}'
                           value="${this.getQuantity(item.id)}">
                </div>
            `;
            
            // Evento para actualizar cantidad
            const input = menuItem.querySelector('.quantity-input');
            input.addEventListener('change', (e) => {
                try {
                    const itemData = JSON.parse(e.target.dataset.item.replace(/\\'/g, "'"));
                    this.updateQuantity(itemData, parseInt(e.target.value) || 0);
                    this.renderCart();
                } catch (error) {
                    console.error('Error parsing item data:', error);
                }
            });
            
            container.appendChild(menuItem);
        });
    },
    
    renderCart() {
        const container = document.getElementById('cart-items-container');
        const totalContainer = document.getElementById('cart-total');
        
        if (this.cart.length === 0) {
            container.innerHTML = '<div class="empty-cart">No hay items en tu pedido</div>';
            totalContainer.textContent = '$0.00';
            return;
        }
        
        container.innerHTML = '';
        
        this.cart.forEach(item => {
            const cartItem = document.createElement('div');
            cartItem.className = 'flex justify-between items-start pb-2 border-b border-gray-200';
            cartItem.innerHTML = `
                <div style="display: flex; flex-direction: row; align-items: center;">
                    <p class="font-medium">${item.nombre}</p> &nbsp; 
                    <p class="text-sm text-textSecondary">x ${item.quantity}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold">$${(item.precio * item.quantity).toFixed(2)}</p>
                    <p class="text-xs text-textSecondary">$${item.precio.toFixed(2)} c/u</p>
                </div>
            `;
            container.appendChild(cartItem);
        });
        
        totalContainer.textContent = `$${this.cartTotal().toFixed(2)}`;
    },
    
    updateStepIndicator() {
        const indicator = document.getElementById('step-indicator');
        indicator.innerHTML = '';
        
        [1, 2, 3].forEach(step => {
            const stepEl = document.createElement('div');
            stepEl.className = `step ${this.currentStep === step ? 'active' : ''}`;
            stepEl.textContent = `${step}. ${step === 1 ? 'Selección' : step === 2 ? 'Pedido' : 'Confirmación'}`;
            indicator.appendChild(stepEl);
        });
        
        // Mostrar solo el contenedor activo
        document.querySelectorAll('.step-container').forEach(container => {
            container.classList.remove('active');
        });
        document.getElementById(`step${this.currentStep}-container`).classList.add('active');
    },
    
    selectFoodTruck(foodtruck) {
        this.selectedFoodTruck = foodtruck;
        this.currentStep = 2;
        this.cart = [];
        this.renderStep2();
    },
    
    getQuantity(itemId) {
        const cartItem = this.cart.find(i => i.id == itemId);
        return cartItem ? cartItem.quantity : 0;
    },
    
    updateQuantity(item, quantity) {
        if (quantity <= 0) {
            this.cart = this.cart.filter(i => i.id != item.id);
            return;
        }
        
        const index = this.cart.findIndex(i => i.id == item.id);
        
        if (index !== -1) {
            this.cart[index].quantity = quantity;
        } else {
            this.cart.push({
                id: item.id,
                nombre: item.nombre,
                descripcion: item.descripcion,
                precio: parseFloat(item.precio),
                quantity: quantity
            });
        }
    },
    
    cartTotal() {
        return this.cart.reduce((total, item) => total + (item.precio * item.quantity), 0);
    },
    
    toggleFavorite(foodtruckId, iconElement) {
        const isFavorite = iconElement.textContent === 'favorite';
        
        fetch('toggle_favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                foodtruck_id: foodtruckId,
                action: isFavorite ? 'remove' : 'add'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                iconElement.textContent = isFavorite ? 'favorite_border' : 'favorite';
                iconElement.classList.toggle('active', !isFavorite);
            } else {
                alert('Error al actualizar favoritos');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    },
    
    saveReservation() {
        if (this.cart.length === 0) {
            alert('Por favor selecciona al menos un ítem');
            return;
        }
        
        const reservationData = {
            foodtruck_id: this.selectedFoodTruck.id,
            items: this.cart,
            total: this.cartTotal()
        };
        
        fetch('create_reserva.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(reservationData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.reservationId = data.reserva_id;
                this.currentStep = 3;
                this.renderStep3();
            } else {
                alert('Error al crear la reserva: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al crear la reserva');
        });
    },
    
    generateQR(reservationId) {
        const qrContainer = document.getElementById('qrcode');
        qrContainer.innerHTML = '';
        
        new QRCode(qrContainer, {
            text: `RESERVA-${reservationId}`,
            width: 180,
            height: 180,
            colorDark: "#2d3748",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }
};

// Iniciar la aplicación
function initBookingApp() {
    bookingApp.init();
}

// Iniciar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initBookingApp);
</script>

<section class="max-w-6xl mx-auto bg-white rounded-lg custom-shadow p-6 md:p-8">
    <h2 class="font-heading text-3xl text-primary mb-6 text-center">Reserva tu Turno</h2>
    
    <div class="step-indicator" id="step-indicator">
        <!-- Los indicadores de paso se generan dinámicamente -->
    </div>

    <!-- Paso 1: Selección -->
    <div class="step-container" id="step1-container">
        <!-- Contenido generado dinámicamente -->
    </div>

    <!-- Paso 2: Pedido -->
    <div class="step-container" id="step2-container">
        <!-- Contenido generado dinámicamente -->
    </div>

    <!-- Paso 3: Confirmación -->
    <div class="step-container" id="step3-container">
        <!-- Contenido generado dinámicamente -->
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script>
// Cargar QRCode.js si no está disponible
if (typeof QRCode === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js';
    script.onload = initBookingApp;
    document.head.appendChild(script);
} else {
    // Iniciar la aplicación después de un pequeño retraso para asegurar que el DOM esté listo
    setTimeout(initBookingApp, 100);
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>