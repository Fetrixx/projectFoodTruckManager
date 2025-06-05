<!--
<footer class="bg-primary text-white py-6 text-center text-sm select-none">
        © <?= date('Y') ?> Elias Medina - UA
    </footer>
</body>
</html>
-->
<!-- Botón flotante -->


<footer class="bg-primary text-white py-6 text-center text-sm mt-auto">
    <div class="d-flex flex-row">
        <div class="container mx-auto px-3">
            <p>© <?= date('Y') ?> FoodTruck Park Manager - Presentado por <?php echo PRESENTADO_POR; ?></p>
            <p class="mt-2">Ing. Informatica - Universidad Americana</p>
        </div>
        <div class="container mx-auto px-3">
            Integrantes:
            <ul>
                <li>
                    Elias Medina
                </li>
                <li>
                    German Lares
                </li>
                <li>
                    Hugo Silguero
                </li>
                <li>
                    Delcy Mendoza
                </li>
                <li>
                    Noelia Apodaca
                </li>

            </ul>
        </div>
    </div>
</footer>

<button id="scrollToTop"
    class="fixed bottom-8 right-8 bg-secondary text-white p-4 rounded-full shadow-lg hover:bg-orange-600 transition-all duration-300 opacity-0 invisible z-[999]"
    style="height: 56px; width: 56px;" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
    aria-label="Volver al inicio">
    <i class="material-icons">arrow_upward</i>
</button>

<script>
    // Control de visibilidad del botón
    window.addEventListener('scroll', () => {
        const btn = document.getElementById('scrollToTop');
        if (window.scrollY > 300) {
            btn.classList.remove('opacity-0', 'invisible');
            btn.classList.add('opacity-100');
        } else {
            btn.classList.remove('opacity-100');
            btn.classList.add('opacity-0', 'invisible');
        }
    });
</script>
</body>

</html>