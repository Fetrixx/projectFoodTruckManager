<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';
?>

<style>
/* Estilos mejorados para responsividad y diseño */
.card-foodtruck {
    min-height: 120px;
    transition: all 0.3s ease;
}

.card-foodtruck:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.menu-item {
    padding: 1rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.menu-item:last-child {
    border-bottom: none;
}

.resumen-pedido {
    position: relative;
}

@media (max-width: 768px) {
    .resumen-pedido {
        border-left: none;
        border-top: 1px solid #e5e7eb;
        padding-left: 0;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .steps {
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    
    .step {
        width: 100%;
        text-align: center;
        padding: 0.5rem;
    }
}

.step {
    position: relative;
    padding-bottom: 0.5rem;
    flex: 1;
    text-align: center;
}

.step:not(:last-child):after {
    content: "";
    position: absolute;
    top: 50%;
    right: 0;
    width: 100%;
    height: 2px;
    background: #e5e7eb;
    z-index: -1;
}

.quantity-input {
    width: 70px;
    text-align: center;
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
}
</style>

<script>
// Inicializar datos en localStorage si no existen
document.addEventListener('DOMContentLoaded', function() {
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

    if (!localStorage.getItem('users')) {
        localStorage.setItem('users', JSON.stringify([
            {
                username: "<?= $_SESSION['username'] ?>",
                email: "<?= $_SESSION['email'] ?? '' ?>",
                favoritos: [1, 2],
                reservas: []
            }
        ]));
    }

    if (!localStorage.getItem('reservas')) {
        localStorage.setItem('reservas', JSON.stringify([
            {
                id: 1001,
                foodtruckId: 1,
                userId: "<?= $_SESSION['username'] ?>",
                fecha: "2024-05-20",
                hora: "12:00",
                items: [
                    { nombre: "Taco Especial", cantidad: 3, precio: 3.99 },
                    { nombre: "Quesadilla", cantidad: 2, precio: 4.50 }
                ],
                total: 21.47,
                estado: "confirmada"
            },
            {
                id: 1002,
                foodtruckId: 2,
                userId: "usuario2",
                fecha: "2024-05-21",
                hora: "13:00",
                items: [
                    { nombre: "Clásica", cantidad: 1, precio: 6.99 }
                ],
                total: 6.99,
                estado: "pendiente"
            }
        ]));
    }
});
</script>

<section class="max-w-6xl mx-auto bg-white rounded-lg custom-shadow p-6 md:p-8" x-data="bookingApp()" x-init="init()">
    <h2 class="font-heading text-3xl text-primary mb-6 text-center">Reserva tu Turno</h2>
    
    <div class="steps mb-8 flex justify-between relative">
        <div class="step z-10 bg-white px-4" :class="{'text-secondary font-bold': currentStep === 1}">1. Selección</div>
        <div class="step z-10 bg-white px-4" :class="{'text-secondary font-bold': currentStep === 2}">2. Pedido</div>
        <div class="step z-10 bg-white px-4" :class="{'text-secondary font-bold': currentStep === 3}">3. Confirmación</div>
    </div>

    <!-- Paso 1: Selección -->
    <div x-show="currentStep === 1" class="transition-all duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Selecciona tu Food Truck</h3>
            <button onclick="location.href='reservas.php'" class="text-primary hover:underline flex items-center">
                <i class="material-icons mr-1">dashboard</i> Ver Dashboard
            </button>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <template x-for="foodtruck in foodtrucks" :key="foodtruck.id">
                <div class="card-foodtruck relative border rounded-lg p-4 hover:shadow-md cursor-pointer flex flex-col" @click="selectFoodTruck(foodtruck)">
                    <i class="material-icons absolute top-2 right-2 cursor-pointer fav-icon text-gray-400 hover:text-red-500" 
                       :data-id="foodtruck.id"
                       @click.stop="toggleFavorite(foodtruck.id)"
                       :class="{'text-red-500': userFavorites.includes(foodtruck.id)}"
                       x-text="userFavorites.includes(foodtruck.id) ? 'favorite' : 'favorite_border'">
                    </i>
                    <div class="flex items-start">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl flex-shrink-0" />
                        <div class="ml-4 flex-grow">
                            <h4 class="font-semibold text-lg" x-text="foodtruck.nombre"></h4>
                            <p class="text-gray-600 text-sm mt-1" x-text="foodtruck.descripcion"></p>
                            <div class="flex text-orange-400 mt-2">
                                <span x-text="'★'.repeat(Math.round(foodtruck.rating)) + '☆'.repeat(5 - Math.round(foodtruck.rating))"></span>
                                <span class="text-gray-600 ml-2" x-text="foodtruck.rating.toFixed(1)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Paso 2: Pedido -->
    <div x-show="currentStep === 2" class="space-y-6 transition-all duration-300">
        <div class="flex justify-between items-center">
            <button @click="currentStep = 1" class="text-primary hover:underline flex items-center">
                <i class="material-icons">arrow_back</i> Volver
            </button>
            <h3 class="text-xl font-semibold text-center flex-grow">Realiza tu pedido</h3>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="menu-items space-y-4">
                <template x-for="item in selectedFoodTruck.menu" :key="item.nombre">
                    <div class="menu-item flex items-center justify-between">
                        <div class="flex items-start flex-grow w-auto">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl  flex-shrink-0" />
                            <div class="ml-4 flex-grow">
                                <h4 class="font-semibold" x-text="item.nombre"></h4>
                                <p class="text-textSecondary text-sm mt-1" x-text="item.descripcion"></p>
                                <p class="text-secondary font-semibold mt-2" x-text="'$' + item.precio.toFixed(2)"></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <input type="number" min="0" class="quantity-input w-16 p-2 border rounded" 
                                   @change="updateQuantity(item, $event.target.value)" 
                                   :value="getQuantity(item)">
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="resumen-pedido bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Tu orden</h4>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    <template x-for="item in cart" :key="item.nombre">
                        <div class="flex justify-between items-start pb-2 border-b border-gray-200">
                            <div>
                                <p class="font-medium" x-text="item.nombre"></p>
                                <p class="text-sm text-textSecondary" x-text="'x' + item.quantity"></p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold" x-text="'$' + (item.precio * item.quantity).toFixed(2)"></p>
                                <p class="text-xs text-textSecondary" x-text="'$' + item.precio.toFixed(2) + ' c/u'"></p>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="cart.length === 0" class="text-center py-4 text-gray-500">
                        No hay items en tu pedido
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t">
                    <div class="flex justify-between font-semibold text-lg">
                        <span>Total:</span>
                        <span x-text="'$' + cartTotal.toFixed(2)"></span>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button @click="currentStep = 1" class="flex-1 bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300 transition">
                            <i class="material-icons mr-1">arrow_back</i> Atrás
                        </button>
                        <button @click="saveReservation()" class="flex-1 bg-secondary text-white py-2 rounded-lg hover:bg-orange-600 transition flex items-center justify-center">
                            Confirmar <i class="material-icons ml-1">check</i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paso 3: Confirmación -->
    <div x-show="currentStep === 3" class="text-center transition-all duration-300">
        <h3 class="text-xl font-semibold mb-6">¡Reserva Confirmada!</h3>
        <div class="bg-gray-50 rounded-lg p-6 max-w-2xl mx-auto">
            <p class="mb-4">Gracias por tu reserva en <span class="font-semibold text-secondary" x-text="selectedFoodTruck.nombre"></span></p>
            
            <div class="mb-6 bg-white rounded-lg p-4 shadow-sm">
                <p class="font-semibold text-lg mb-3">Detalles de tu pedido:</p>
                <ul class="text-left">
                    <template x-for="item in cart">
                        <li class="flex justify-between py-2 border-b border-gray-100">
                            <div>
                                <span x-text="item.quantity + ' x ' + item.nombre"></span>
                                <p class="text-sm text-gray-500" x-text="item.descripcion"></p>
                            </div>
                            <span class="font-semibold" x-text="'$' + (item.precio * item.quantity).toFixed(2)"></span>
                        </li>
                    </template>
                </ul>
                <div class="flex justify-between border-t mt-3 pt-3 font-bold text-lg">
                    <span>Total:</span>
                    <span x-text="'$' + cartTotal.toFixed(2)"></span>
                </div>
            </div>
            
            <div class="flex justify-center mb-6">
                <div id="qrcode"></div>
            </div>
            
            <p class="text-sm text-textSecondary mb-6">Presenta este código QR al food truck para reclamar tu pedido</p>
            
            <div class="confirmation-buttons">
                <button onclick="window.print()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center">
                    <i class="material-icons mr-1">print</i> Imprimir
                </button>
                <button @click="currentStep = 1; cart = [];" class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition flex items-center">
                    <i class="material-icons mr-1">add</i> Nueva Reserva
                </button>
                <button onclick="location.href='reservas.php'" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                    <i class="material-icons mr-1">close</i> Cerrar
                </button>
            </div>
        </div>
    </div>
</section>

<script>
function bookingApp() {
    return {
        currentStep: 1,
        foodtrucks: [],
        selectedFoodTruck: null,
        cart: [],
        userFavorites: [],
        reservationId: null,
        
        init() {
            // Cargar foodtrucks desde localStorage
            this.foodtrucks = JSON.parse(localStorage.getItem('foodtrucks')) || [];
            
            // Obtener favoritos del usuario
            const user = "<?= $_SESSION['username'] ?>";
            const users = JSON.parse(localStorage.getItem('users')) || [];
            const currentUser = users.find(u => u.username === user);
            this.userFavorites = currentUser?.favoritos || [];
            
            // Si hay un foodtruck en la URL, seleccionarlo automáticamente
            const urlParams = new URLSearchParams(window.location.search);
            const foodtruckId = urlParams.get('foodtruck');
            if (foodtruckId) {
                const ft = this.foodtrucks.find(f => f.id == foodtruckId);
                if (ft) {
                    this.selectFoodTruck(ft);
                }
            }
        },
        
        selectFoodTruck(ft) {
            this.selectedFoodTruck = ft;
            this.currentStep = 2;
            this.cart = [];
        },
        
        getQuantity(item) {
            const cartItem = this.cart.find(i => i.nombre === item.nombre);
            return cartItem ? cartItem.quantity : 0;
        },
        
        updateQuantity(item, quantity) {
            quantity = parseInt(quantity) || 0;
            
            if (quantity <= 0) {
                this.cart = this.cart.filter(i => i.nombre !== item.nombre);
                return;
            }
            
            const index = this.cart.findIndex(i => i.nombre === item.nombre);
            
            if (index !== -1) {
                this.cart[index].quantity = quantity;
            } else {
                this.cart.push({
                    ...item,
                    quantity: quantity
                });
            }
        },
        
        get cartTotal() {
            return this.cart.reduce((total, item) => total + (item.precio * item.quantity), 0);
        },
        
        toggleFavorite(foodtruckId) {
            const user = "<?= $_SESSION['username'] ?>";
            const users = JSON.parse(localStorage.getItem('users')) || [];
            let userIndex = users.findIndex(u => u.username === user);
            
            if (userIndex === -1) {
                users.push({
                    username: user,
                    favoritos: [],
                    reservas: []
                });
                userIndex = users.length - 1;
            }
            
            const favIndex = users[userIndex].favoritos.indexOf(foodtruckId);
            
            if (favIndex === -1) {
                users[userIndex].favoritos.push(foodtruckId);
            } else {
                users[userIndex].favoritos.splice(favIndex, 1);
            }
            
            localStorage.setItem('users', JSON.stringify(users));
            this.userFavorites = users[userIndex].favoritos;
        },
        
        saveReservation() {
            if (this.cart.length === 0) {
                alert('Por favor selecciona al menos un ítem');
                return;
            }
            
            const user = "<?= $_SESSION['username'] ?>";
            const users = JSON.parse(localStorage.getItem('users')) || [];
            let userIndex = users.findIndex(u => u.username === user);
            
            if (userIndex === -1) {
                users.push({
                    username: user,
                    favoritos: [],
                    reservas: []
                });
                userIndex = users.length - 1;
            }
            
            // Crear reserva
            const reservation = {
                id: Date.now(),
                foodtruckId: this.selectedFoodTruck.id,
                foodtruckName: this.selectedFoodTruck.nombre,
                userId: user,
                date: new Date().toLocaleString(),
                items: this.cart.map(item => ({
                    nombre: item.nombre,
                    precio: item.precio,
                    cantidad: item.quantity,
                    descripcion: item.descripcion
                })),
                total: this.cartTotal,
                status: 'pending'
            };
            
            // Guardar en el usuario
            if (!users[userIndex].reservas) {
                users[userIndex].reservas = [];
            }
            users[userIndex].reservas.push(reservation.id);
            localStorage.setItem('users', JSON.stringify(users));
            
            // Guardar en reservas públicas
            const publicReservations = JSON.parse(localStorage.getItem('reservas')) || [];
            publicReservations.push(reservation);
            localStorage.setItem('reservas', JSON.stringify(publicReservations));
            
            // Generar QR
            this.generateQR(reservation.id);
            this.reservationId = reservation.id;
            this.currentStep = 3;
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
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<?php include __DIR__ . '/../templates/footer.php'; ?>