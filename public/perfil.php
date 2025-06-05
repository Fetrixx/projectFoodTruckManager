<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

include __DIR__ . '/../templates/header.php';
?>

<main class="container mx-auto px-6 py-12">
    <section class="max-w-4xl mx-auto bg-white rounded-lg custom-shadow p-8">
        <div class="tabs">
            <button class="tab active" data-target="historial">Historial</button>
            <button class="tab" data-target="favoritos">Favoritos</button>
            <button class="tab" data-target="reseñas">Reseñas</button>
        </div>

        <div class="tab-content active" id="historial">
            <template x-for="reserva in user.reservas">
                <div class="reserva-item">
                    <p x-text="reserva.fecha"></p>
                    <p x-text="reserva.total"></p>
                </div>
            </template>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const userData = JSON.parse(localStorage.getItem('users'))
                    .find(u => u.username === "<?= $_SESSION['username'] ?>");

                Alpine.data('profile', () => ({
                    user: userData,
                    activeTab: 'historial'
                }));
            });
        </script>
    </section>
</main>


<?php include __DIR__ . '/../templates/footer.php'; ?>