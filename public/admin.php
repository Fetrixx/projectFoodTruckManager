<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';
?>


<main class="container mx-auto px-6 py-12">
    <section class="max-w-6xl mx-auto bg-white rounded-lg custom-shadow p-8">
        <h2 class="text-3xl font-heading mb-6">Panel de Administración</h2>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Gestión Food Trucks -->
            <div class="admin-card" x-data="{showForm: false}">
                <h3>Gestión de Food Trucks</h3>
                <button @click="showForm = !showForm" class="btn-secondary">
                    Agregar Nuevo
                </button>

                <form x-show="showForm" @submit.prevent="saveFoodTruck">
                    <!-- Formulario de food truck -->
                </form>
            </div>

            <!-- Horarios -->
            <div class="admin-card">
                <h3>Control de Horarios</h3>
                <div class="time-grid">
                    <template x-for="time in horarios">
                        <div class="time-slot">
                            <span x-text="time"></span>
                            <input type="number" placeholder="Capacidad">
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>