<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';
?>

<section class="max-w-6xl mx-auto bg-white rounded-lg custom-shadow p-8">
    <h2 class="font-heading text-3xl text-primary mb-8 text-center">Sistema de Reseñas</h2>
    
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Formulario de reseña -->
        <div class="space-y-6">
            <h3 class="text-xl font-semibold text-primary">Deja tu reseña</h3>
            <form id="reviewForm" class="space-y-4">
                <div>
                    <label class="block text-textPrimary mb-2">Selecciona un Food Truck</label>
                    <select id="foodtruckSelect" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-secondary" required>
                        <option value="">-- Selecciona --</option>
                        <option value="1">Tacos El Güero</option>
                        <option value="2">Burger Paradise</option>
                        <option value="3">Sushi Express</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-textPrimary mb-2">Calificación</label>
                    <div class="rating flex space-x-1">
                        <span class="star text-3xl cursor-pointer" data-value="1">☆</span>
                        <span class="star text-3xl cursor-pointer" data-value="2">☆</span>
                        <span class="star text-3xl cursor-pointer" data-value="3">☆</span>
                        <span class="star text-3xl cursor-pointer" data-value="4">☆</span>
                        <span class="star text-3xl cursor-pointer" data-value="5">☆</span>
                    </div>
                    <input type="hidden" id="ratingValue" name="rating" required>
                </div>
                
                <div>
                    <label class="block text-textPrimary mb-2">Comentario</label>
                    <textarea id="reviewComment" rows="4" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-secondary" placeholder="Cuéntanos tu experiencia..." required></textarea>
                </div>
                
                <button type="submit" class="bg-secondary text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition flex items-center">
                    <i class="material-icons mr-2">rate_review</i>
                    Enviar Reseña
                </button>
            </form>
        </div>
        
        <!-- Listado de reseñas -->
        <div>
            <h3 class="text-xl font-semibold text-primary mb-4">Reseñas Recientes</h3>
            <div id="reviewsContainer" class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                <!-- Las reseñas se cargarán aquí dinámicamente -->
            </div>
        </div>
    </div>
</section>

<script>
    // Sistema de estrellas interactivo
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            document.querySelectorAll('.star').forEach((s, i) => {
                s.textContent = i < value ? '★' : '☆';
                s.style.color = i < value ? '#F97316' : '#9CA3AF';
            });
            document.getElementById('ratingValue').value = value;
        });
    });

    // Cargar reseñas desde localStorage
    function loadReviews() {
        const reviews = JSON.parse(localStorage.getItem('reviews')) || [];
        const container = document.getElementById('reviewsContainer');
        container.innerHTML = '';
        
        reviews.forEach(review => {
            const reviewEl = document.createElement('div');
            reviewEl.className = 'bg-gray-50 p-4 rounded-lg';
            reviewEl.innerHTML = `
                <div class="flex justify-between items-start">
                    <h4 class="font-semibold">${review.foodtruck}</h4>
                    <div class="flex text-orange-400">
                        ${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}
                    </div>
                </div>
                <p class="mt-2 text-textSecondary">${review.comment}</p>
                <div class="flex justify-between items-center mt-3">
                    <span class="text-sm text-gray-500">Por: ${review.user}&nbsp;</span>
                    <span class="text-sm text-gray-500"> ${review.date}</span>
                </div>
            `;
            container.appendChild(reviewEl);
        });
    }

    // Guardar nueva reseña
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const foodtruckId = document.getElementById('foodtruckSelect').value;
        const foodtruckName = document.getElementById('foodtruckSelect').options[foodtruckSelect.selectedIndex].text;
        const rating = document.getElementById('ratingValue').value;
        const comment = document.getElementById('reviewComment').value;
        const user = "<?= $_SESSION['username'] ?>";
        const date = new Date().toLocaleDateString('es-ES', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        if (!foodtruckId || !rating || !comment) {
            alert('Por favor completa todos los campos');
            return;
        }
        
        const review = {
            foodtruckId,
            foodtruck: foodtruckName,
            rating,
            comment,
            user,
            date
        };
        
        // Guardar en localStorage
        const reviews = JSON.parse(localStorage.getItem('reviews')) || [];
        reviews.unshift(review);
        localStorage.setItem('reviews', JSON.stringify(reviews));
        
        // Recargar lista
        loadReviews();
        
        // Resetear formulario
        this.reset();
        document.querySelectorAll('.star').forEach(s => {
            s.textContent = '☆';
            s.style.color = '#9CA3AF';
        });
    });

    // Cargar reseñas al iniciar
    loadReviews();
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>