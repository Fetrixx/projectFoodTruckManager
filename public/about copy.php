<?php
define('CHECK_ACCESS', true);
require_once __DIR__ . '/../src/auth/session.php';
checkAuth();

require_once __DIR__ . '/../src/config/Database.php';
require_once __DIR__ . '/../src/DAO/AlumnoDAO.php';

$pageTitle = "Acerca de Mí";

$developerData = null;
$developerCedula = '5908655'; 

try {
    $db = new \Src\Config\Database();
    $alumnoDAO = new \Src\DAO\AlumnoDAO($db);
    $developerData = $alumnoDAO->getAlumnoByCedula($developerCedula);

    if (!$developerData) {
        $developerData = [
            'nombres' => 'Desarrollador Desconocido',
            'correo' => 'info@example.com',
            'cedula' => $developerCedula
        ];
        error_log("Desarrollador con cédula {$developerCedula} no encontrado en la base de datos.");
    }

} catch (\Exception $e) {
    error_log("Error al obtener datos del desarrollador: " . $e->getMessage());
    $developerData = [
        'nombres' => 'Error al Cargar Datos',
        'correo' => 'error@example.com',
        'cedula' => $developerCedula
    ];
}

include __DIR__ . '/../templates/header.php';
?>

<!-- Contenedor para partículas -->
<!-- <canvas id="particle-canvas" class="fixed top-0 left-0 w-full h-full pointer-events-none z-0"></canvas> -->

<main class="container mx-auto px-6 py-12 relative z-10">
    <button id="darkModeToggle"
        class="fixed right-6 bg-secondary text-white px-4 py-2 rounded-lg shadow-lg hover:bg-orange-600 transition-colors z-50">
        Modo Oscuro
    </button>

    <section class="max-w-4xl mx-auto bg-white rounded-lg custom-shadow p-8 mb-12 animate-fade-in reveal">
        <h2 class="font-heading text-3xl text-primary mb-6 text-center">Acerca de mi, <?php echo PRESENTADO_POR; ?></h2>

        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="md:w-1/3 flex justify-center relative group cursor-pointer" title="Haz clic para ampliar">
                <img src="https://cdn-icons-png.flaticon.com/512/6840/6840541.png"
                    alt="Foto de Perfil del Desarrollador"
                    class="w-48 h-48 rounded-full object-cover shadow-lg border-4 border-secondary transition-transform duration-300 group-hover:scale-105 group-hover:shadow-xl">
                <div
                    class="absolute inset-0 bg-primary bg-opacity-0 group-hover:bg-opacity-20 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <p class="text-white text-lg font-bold">¡Haz clic!</p>
                </div>
            </div>
            <div class="md:w-2/3 text-center md:text-left space-y-4">
                <h3 class="font-heading text-2xl text-textPrimary">¡Hola! Soy <span id="developer-name"></span></h3>
                <p class="text-textSecondary leading-relaxed">
                    Soy el desarrollador principal detrás de <strong class="text-primary">FoodTruck Park
                        Manager</strong>, una aplicación diseñada para optimizar la gestión de food trucks. Mi pasión es
                    transformar ideas en soluciones tecnológicas que simplifiquen la vida y potencien los negocios.
                </p>
                <p class="text-textSecondary leading-relaxed">
                    Mi nombre completo es:
                    <strong><?php echo htmlspecialchars($developerData['nombres'] ?? 'No Disponible'); ?></strong>.<br>
                    Puedes contactarme en:
                    <strong><?php echo htmlspecialchars($developerData['correo'] ?? 'No Disponible'); ?></strong>.<br>
                    Mi cédula es:
                    <strong><?php echo htmlspecialchars($developerData['cedula'] ?? 'No Disponible'); ?></strong>.
                </p>
                <p class="text-textSecondary leading-relaxed">
                    Con un enfoque en el <strong>desarrollo web full-stack</strong>, he liderado la creación de esta
                    plataforma utilizando tecnologías modernas para asegurar una experiencia de usuario fluida y un
                    rendimiento robusto. Mi objetivo es proporcionar herramientas intuitivas y eficientes que ayuden a
                    los emprendedores de food trucks a prosperar.
                </p>
                <p class="text-textSecondary leading-relaxed">
                    ¡Gracias por explorar la aplicación! Si tienes alguna pregunta o sugerencia, no dudes en contactarme
                    a través de los canales de comunicación de la página de contacto.
                </p>

                <div class="flex justify-center md:justify-start gap-4 mt-6">
                    <a href="https://www.linkedin.com/in/eliasamaurimedinaorrego" target="_blank"
                        class="text-secondary hover:text-primary transition-colors transform hover:scale-110"
                        aria-label="LinkedIn">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/LinkedIn_icon.svg/1024px-LinkedIn_icon.svg.png"
                            alt="LinkedIn" class="w-8 h-8">
                    </a>
                    <a href="https://github.com/" target="_blank"
                        class="text-secondary hover:text-primary transition-colors transform hover:scale-110"
                        aria-label="GitHub">
                        <img src="https://cdn.worldvectorlogo.com/logos/github-icon-2.svg" alt="GitHub" class="w-8 h-8">
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <strong>Años de experiencia:</strong> <span id="experience-counter"
                class="text-4xl text-primary font-bold">0</span>
        </div>
    </section>

    <section class="max-w-4xl mx-auto mt-12 bg-white rounded-lg custom-shadow p-8 mb-12 reveal">
        <h3 class="font-heading text-2xl text-primary mb-6 text-center">Mis Habilidades y Tecnologías</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 text-center" id="skills-grid"
            aria-label="Lista de habilidades">
        </div>
        <div class="text-center mt-6">
            <button id="toggle-skills-btn"
                class="bg-secondary text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-all duration-300 transform hover:scale-105">
                Mostrar Más Habilidades
            </button>
        </div>
    </section>

    <section class="max-w-4xl mx-auto mt-12 bg-white rounded-lg custom-shadow p-8 reveal">
        <h3 class="font-heading text-2xl text-primary mb-6 text-center">Intereses y Pasatiempos</h3>
        <div class="flex flex-wrap justify-center gap-4 text-textSecondary" id="interests-container"
            aria-label="Lista de intereses y pasatiempos">
        </div>
    </section>
</main>

<!-- Modal para foto ampliada -->
<div id="profileModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden"
    role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDesc">
    <div class="bg-white p-6 rounded-lg shadow-xl relative animate-zoom-in max-w-lg max-h-[80vh] overflow-auto">
        <button onclick="closeProfileModal()" aria-label="Cerrar modal"
            class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-2xl font-bold">&times;</button>
        <img src="/projectFoodTruckManager/public/assets/img/developer_profile_large.jpg" alt="Foto de Perfil Ampliada"
            class="max-w-full rounded-lg">
        <p id="modalDesc" class="mt-4 text-center text-textPrimary">¡Un vistazo más de cerca!</p>
    </div>
</div>

<!-- Botón volver arriba -->
<button id="scrollTopBtn" title="Volver arriba" aria-label="Volver arriba"
    class="fixed bottom-6 right-6 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-secondary transition-colors opacity-0 pointer-events-none z-50">
    ↑
</button>

<script>
    // === Modo Oscuro / Claro ===
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;

    function setDarkMode(enabled) {
        if (enabled) {
            body.classList.add('dark');
            darkModeToggle.textContent = 'Modo Claro';
            localStorage.setItem('darkMode', 'true');
        } else {
            body.classList.remove('dark');
            darkModeToggle.textContent = 'Modo Oscuro';
            localStorage.setItem('darkMode', 'false');
        }
    }

    darkModeToggle.addEventListener('click', () => {
        setDarkMode(!body.classList.contains('dark'));
    });

    // Carga preferencia
    if (localStorage.getItem('darkMode') === 'true') {
        setDarkMode(true);
    }

    // === Animación Máquina de Escribir para Nombre ===
    const developerNameElement = document.getElementById('developer-name');
    const fullDeveloperName = "<?php echo htmlspecialchars($developerData['nombres'] ?? 'Desconocido'); ?>";
    let charIndex = 0;

    function typeWriterEffect() {
        if (charIndex < fullDeveloperName.length) {
            developerNameElement.textContent += fullDeveloperName.charAt(charIndex);
            charIndex++;
            setTimeout(typeWriterEffect, 100);
        } else {
            developerNameElement.classList.add('underline', 'decoration-secondary', 'decoration-wavy');
        }
    }

    setTimeout(() => {
        developerNameElement.textContent = '';
        typeWriterEffect();
    }, 1000);

    // === Contador animado de experiencia ===
    const experienceCounter = document.getElementById('experience-counter');
    const experienceYears = 4; // Cambia según tu experiencia real
    let currentExp = 0;

    function animateCounter() {
        if (currentExp < experienceYears) {
            currentExp++;
            experienceCounter.textContent = currentExp;
            setTimeout(animateCounter, 200);
        } else {
            experienceCounter.textContent = experienceYears + '+';
        }
    }
    setTimeout(animateCounter, 2500);

    // === Habilidades y tooltips ===
    const allSkills = [
        { name: "PHP", desc: "Lenguaje de programación backend." },
        { name: "JavaScript", desc: "Lenguaje para desarrollo web frontend y backend." },
        { name: "Tailwind CSS", desc: "Framework CSS para diseño rápido y responsivo." },
        { name: "MySQL", desc: "Sistema de gestión de bases de datos relacional." },
        { name: "HTML5", desc: "Estructura básica de páginas web." },
        { name: "CSS3", desc: "Estilos y diseño para páginas web." },
        { name: "Git & GitHub", desc: "Control de versiones y repositorios remotos." },
        { name: "Leaflet JS", desc: "Biblioteca para mapas interactivos." },
        { name: "Node.js", desc: "Entorno de ejecución para JavaScript en servidor." },
        { name: "React", desc: "Biblioteca para construir interfaces de usuario." },
        { name: "RESTful APIs", desc: "Diseño de servicios web escalables." },
        { name: "Diseño Responsive", desc: "Adaptación de interfaces a diferentes dispositivos." },
        { name: "Firebase", desc: "Backend como servicio para aplicaciones." },
        { name: "Metodologías Ágiles", desc: "Procesos iterativos para desarrollo eficiente." },
        { name: "Angular", desc: "Framework para aplicaciones web SPA con TypeScript." },
        { name: "Material UI", desc: "Componentes UI basados en Material Design para Angular y React." },
        { name: "TypeScript", desc: "Superset de JavaScript con tipado estático." },
        { name: "Manejo de flujos y estados", desc: "Gestión de estados en aplicaciones frontend (Redux, Context API, NgRx)." },
        { name: "C", desc: "Programación estructurada, scripts y aplicaciones de bajo nivel." },
        { name: "C++", desc: "Programación orientada a objetos, aplicaciones de escritorio y sistemas." },
        { name: "C#", desc: "Desarrollo de aplicaciones de escritorio, web y APIs con .NET." },
        { name: "Python", desc: "Scripts, automatización, desarrollo web y análisis de datos." },
        { name: "Java", desc: "Aplicaciones empresariales, móviles, web y APIs." }
    ];

    const skillsGrid = document.getElementById('skills-grid');
    const toggleSkillsBtn = document.getElementById('toggle-skills-btn');
    const initialSkillsToShow = 8;
    let showingAllSkills = false;

    function createTooltip(text) {
        const tooltip = document.createElement('div');
        tooltip.className = 'absolute bg-gray-800 text-white text-sm rounded px-2 py-1 z-50 pointer-events-none opacity-0 transition-opacity duration-200';
        tooltip.textContent = text;
        document.body.appendChild(tooltip);
        return tooltip;
    }

    let activeTooltip = null;

    function renderSkills() {
        skillsGrid.innerHTML = '';
        const skillsToRender = showingAllSkills ? allSkills : allSkills.slice(0, initialSkillsToShow);

        let hideTooltipTimeout = null; // Variable para controlar el timeout

        skillsToRender.forEach((skill, index) => {
            const skillDiv = document.createElement('div');
            skillDiv.className = 'relative flex justify-center items-center p-3 bg-gray-100 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-1 cursor-default opacity-0 animate-pop-in';
            skillDiv.style.animationDelay = `${index * 0.05}s`;
            skillDiv.innerHTML = `<p class="font-semibold text-textPrimary">${skill.name}</p>`;

            let isTooltipHovered = false;
            let isSkillDivHovered = false;

            function showTooltip(e) {
                // Si hay timeout para ocultar tooltip, cancelarlo
                if (hideTooltipTimeout) {
                    clearTimeout(hideTooltipTimeout);
                    hideTooltipTimeout = null;
                }

                if (activeTooltip) activeTooltip.remove();
                activeTooltip = createTooltip(skill.desc);
                positionTooltip(e, activeTooltip);
                activeTooltip.style.opacity = '1';

                activeTooltip.addEventListener('mouseenter', () => {
                    isTooltipHovered = true;
                    if (hideTooltipTimeout) {
                        clearTimeout(hideTooltipTimeout);
                        hideTooltipTimeout = null;
                    }
                });
                activeTooltip.addEventListener('mouseleave', () => {
                    isTooltipHovered = false;
                    if (!isSkillDivHovered) hideTooltip();
                });
            }

            function hideTooltip() {
                if (activeTooltip) {
                    activeTooltip.style.opacity = '0';
                    // Programar la eliminación con timeout
                    hideTooltipTimeout = setTimeout(() => {
                        activeTooltip?.remove();
                        activeTooltip = null;
                        hideTooltipTimeout = null;
                    }, 200);
                }
            }

            skillDiv.addEventListener('mouseenter', (e) => {
                isSkillDivHovered = true;

                // Si hay un tooltip visible, eliminarlo inmediatamente para evitar conflictos
                if (activeTooltip) {
                    if (hideTooltipTimeout) {
                        clearTimeout(hideTooltipTimeout);
                        hideTooltipTimeout = null;
                    }
                    activeTooltip.remove();
                    activeTooltip = null;
                }

                showTooltip(e);
            });

            skillDiv.addEventListener('mousemove', (e) => {
                if (activeTooltip) positionTooltip(e, activeTooltip);
            });

            skillDiv.addEventListener('mouseleave', () => {
                isSkillDivHovered = false;
                if (!isTooltipHovered) hideTooltip();
            });

            skillsGrid.appendChild(skillDiv);
        });

        toggleSkillsBtn.textContent = showingAllSkills ? 'Mostrar Menos Habilidades' : 'Mostrar Más Habilidades';
    }



    function positionTooltip(event, tooltip) {
        const padding = 10;
        let x = event.pageX + padding;
        let y = event.pageY + padding;

        const tooltipRect = tooltip.getBoundingClientRect();
        const windowWidth = window.innerWidth;
        const windowHeight = window.innerHeight;

        if (x + tooltipRect.width > windowWidth) {
            x = event.pageX - tooltipRect.width - padding;
        }
        if (y + tooltipRect.height > windowHeight) {
            y = event.pageY - tooltipRect.height - padding;
        }

        tooltip.style.left = `${x}px`;
        tooltip.style.top = `${y}px`;
    }

    toggleSkillsBtn.addEventListener('click', () => {
        showingAllSkills = !showingAllSkills;
        renderSkills();
    });

    document.addEventListener('DOMContentLoaded', renderSkills);

    // === Intereses y Pasatiempos ===
    const interests = [
        "Desarrollo de Videojuegos", "Lectura de Ciencia Ficción", "Aprender Idiomas"
    ];
    const interestsContainer = document.getElementById('interests-container');

    interests.forEach((interest, index) => {
        const span = document.createElement('span');
        span.className = 'px-4 py-2 bg-accent/10 rounded-full text-accent font-medium shadow-sm cursor-pointer hover:bg-accent/20 transition-all duration-300 transform hover:scale-105 opacity-0 animate-pop-in';
        span.style.animationDelay = `${(allSkills.length * 0.05) + (index * 0.1)}s`;
        span.textContent = interest;
        span.setAttribute('data-interest', interest);

        span.addEventListener('mouseenter', () => {
            span.textContent = `¡Me encanta ${interest.toLowerCase()}!`;
        });
        span.addEventListener('mouseleave', () => {
            span.textContent = interest;
        });

        interestsContainer.appendChild(span);
    });

    // === Modal foto perfil ===
    const profileImage = document.querySelector('.group img');
    const profileModal = document.getElementById('profileModal');

    profileImage.addEventListener('click', () => {
        profileModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    function closeProfileModal() {
        profileModal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    profileModal.addEventListener('click', (e) => {
        if (e.target === profileModal) {
            closeProfileModal();
        }
    });

    // === Scroll Reveal para secciones ===
    const revealElements = document.querySelectorAll('.reveal');

    function revealOnScroll() {
        const windowHeight = window.innerHeight;
        revealElements.forEach(el => {
            const elementTop = el.getBoundingClientRect().top;
            const revealPoint = 150;

            if (elementTop < windowHeight - revealPoint) {
                el.classList.add('animate-slide-up');
            }
        });
    }

    window.addEventListener('scroll', revealOnScroll);
    window.addEventListener('load', revealOnScroll);

    // === Botón Volver Arriba ===
    const scrollTopBtn = document.getElementById('scrollTopBtn');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollTopBtn.style.opacity = '1';
            scrollTopBtn.style.pointerEvents = 'auto';
        } else {
            scrollTopBtn.style.opacity = '0';
            scrollTopBtn.style.pointerEvents = 'none';
        }
    });

    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // === Partículas animadas en canvas ===
    const canvas = document.getElementById('particle-canvas');
    const ctx = canvas.getContext('2d');
    let particlesArray = [];

    function initCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    window.addEventListener('resize', () => {
        initCanvas();
    });

    class Particle {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 2 + 1;
            this.speedX = (Math.random() - 0.5) * 0.5;
            this.speedY = (Math.random() - 0.5) * 0.5;
            this.opacity = Math.random() * 0.5 + 0.1;
        }
        update() {
            this.x += this.speedX;
            this.y += this.speedY;

            if (this.x < 0 || this.x > canvas.width) this.speedX = -this.speedX;
            if (this.y < 0 || this.y > canvas.height) this.speedY = -this.speedY;
        }
        draw() {
            ctx.fillStyle = `rgba(255, 165, 0, ${this.opacity})`; // naranja suave
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    function initParticles() {
        particlesArray = [];
        const numberOfParticles = 80;
        for (let i = 0; i < numberOfParticles; i++) {
            particlesArray.push(new Particle());
        }
    }

    function animateParticles() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particlesArray.forEach(p => {
            p.update();
            p.draw();
        });
        requestAnimationFrame(animateParticles);
    }

    initCanvas();
    initParticles();
    animateParticles();

</script>

<style>
    /* Tailwind extend para modo oscuro */
    body.dark {
        background-color: #121212;
        color: #e0e0e0;
    }

    body.dark .bg-white {
        background-color: #1e1e1e !important;
    }

    body.dark .text-primary {
        color: #ffa500 !important;
        /* naranja */
    }

    body.dark .text-textSecondary {
        color: #bbbbbb !important;
    }

    body.dark .bg-gray-100 {
        background-color: #2a2a2a !important;
    }

    body.dark .bg-secondary {
        background-color: #ff7f50 !important;
    }

    body.dark .border-secondary {
        border-color: #ff7f50 !important;
    }

    /* Animaciones Tailwind CSS personalizadas */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .animate-fade-in {
        animation: fadeIn 1s ease-out forwards;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-up {
        animation: slideUp 0.8s ease-out forwards;
        animation-delay: 0.3s;
    }

    @keyframes popIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-pop-in {
        animation: popIn 0.5s ease-out forwards;
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .animate-zoom-in {
        animation: zoomIn 0.3s ease-out forwards;
    }

    .decoration-wavy {
        text-decoration-style: wavy;
    }

    /* Botón volver arriba */
    #scrollTopBtn {
        transition: opacity 0.3s ease;
        font-size: 1.5rem;
        line-height: 1;
    }

    /* Tooltip estilos */
    /* Ya definidos en JS como inline styles, pero puedes agregar si quieres */
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>