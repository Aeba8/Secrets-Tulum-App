<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SecretsPad Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        serif: ['Cinzel', 'ui-serif', 'Georgia', 'serif'],
                    },
                    colors: {
                        gold: {
                            50: '#FDF8ED',
                            100: '#F9EDD0',
                            200: '#F2D89F',
                            300: '#EBC46E',
                            400: '#E3AF3D',
                            500: '#C5A059',
                            600: '#B38F4B',
                            700: '#9A7835',
                            800: '#7A5F2A',
                            900: '#5A4620',
                        },
                        sand: {
                            50: '#FAF7F4',
                            100: '#F5F0EB',
                            200: '#EBE3D9',
                            300: '#DDD0C0',
                            400: '#CBB8A0',
                            500: '#B89F81',
                            600: '#A3866A',
                            700: '#887054',
                            800: '#6F5B45',
                            900: '#5B4A39',
                        },
                        emerald: {
                            deep: '#0D5E3F',
                            dark: '#0A4B32',
                            light: '#E8F5F0',
                        },
                    }
                }
            }
        }
    </script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #F5F0EB; color: #1F2937; overflow: hidden; }
        .dark body { background-color: #0D1B12; color: #E5E7EB; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(197, 160, 89, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(197, 160, 89, 0.5); }
        .sidebar-transition { transition: width 0.3s ease; }
        .nav-item-active { background-color: rgba(197, 160, 89, 0.08); color: #C5A059; border-color: rgba(197, 160, 89, 0.2); }
        .dark .nav-item-active { background-color: rgba(197, 160, 89, 0.12); }
        .nav-item { color: #6B7280; }
        .dark .nav-item { color: #9CA3AF; }
        .nav-item:hover { background-color: #EBE3D9; color: #374151; }
        .dark .nav-item:hover { background-color: rgba(255,255,255,0.05); color: #E5E7EB; }
    </style>
</head>
<body class="">

    <div class="flex h-screen overflow-hidden bg-sand-100 dark:bg-[#0D1B12]">

        {{-- Sidebar --}}
        <aside id="sidebar" class="sidebar-transition flex-shrink-0 h-full bg-white dark:bg-[#16281D] border-r border-sand-200 dark:border-[#2D4A36] flex flex-col z-30 overflow-hidden"
               style="width: 260px;">

            {{-- Logo + toggle --}}
            <div class="flex items-center justify-between h-16 px-5 border-b border-sand-200 dark:border-[#2D4A36] flex-shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    
                    <span class="font-serif text-gold-500 text-lg font-semibold whitespace-nowrap" id="sidebar-brand-text">SecretsPad</span>
                </div>
                <button id="sidebar-toggle" class="w-7 h-7 rounded-lg hover:bg-sand-100 dark:hover:bg-[#2D4A36] flex items-center justify-center text-gray-400 hover:text-gray-600 dark:text-gray-400 transition-colors flex-shrink-0">
                    <i id="sidebar-toggle-icon" class="fa-solid fa-bars text-sm"></i>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">

                {{-- Dashboard General (toggle padre) --}}
                <button id="dashboard-parent-toggle"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 nav-item-active w-full text-left">
                    <i class="fa-solid fa-gauge-high w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label flex-1">Dashboard General</span>
                    <i id="dashboard-chevron" class="fa-solid fa-chevron-down text-xs transition-transform duration-300 nav-label"></i>
                </button>

                {{-- Sub-apartados (colapsable) --}}
                <div id="submenu-container" class="ml-2 border-l-2 border-gold-500/20 dark:border-gold-500/30 pl-2 space-y-0.5 overflow-hidden transition-all duration-300 max-h-[500px] opacity-100">
                    <span class="text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-semibold ml-3 nav-label block mt-2 mb-1">Módulos de Métricas</span>

                    <a href="#bcg" data-section="bcg"
                       class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 nav-item">
                        <i class="fa-solid fa-star w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Matriz BCG</span>
                    </a>

                    <a href="#inventory" data-section="inventory"
                       class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 nav-item">
                        <i class="fa-solid fa-boxes-stacked w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Previsiones Inventario</span>
                    </a>

                    <a href="#financial" data-section="financial"
                       class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 nav-item">
                        <i class="fa-solid fa-chart-line w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Métricas Financieras</span>
                    </a>

                    <a href="#occupancy" data-section="occupancy"
                       class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 nav-item">
                        <i class="fa-solid fa-calendar-day w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Ocupación y Demanda</span>
                    </a>

                    <a href="#operations" data-section="operations"
                       class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 nav-item">
                        <i class="fa-solid fa-gears w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Operación y Logística</span>
                    </a>

                    <a href="#team" data-section="team"
                       class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 nav-item">
                        <i class="fa-solid fa-users w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Rendimiento del Equipo</span>
                    </a>
                </div>

                {{-- Divider --}}
                <div class="border-t border-sand-200 dark:border-[#2D4A36] my-3"></div>

                {{-- Agenda (active) --}}
                <a href="#agenda" data-section="agenda"
                   class="nav-item nav-section-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200">
                    <i class="fa-solid fa-calendar-days w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label">Agenda de Reservas</span>
                </a>

                {{-- Gestión de Servicios (toggle padre) --}}
                <button id="services-parent-toggle"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 w-full text-left">
                    <i class="fa-solid fa-concierge-bell w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label flex-1">Gestión de Servicios</span>
                    <i id="services-chevron" class="fa-solid fa-chevron-down text-xs transition-transform duration-300 nav-label"></i>
                </button>

                {{-- Sub-CRUDs (colapsable) --}}
                <div id="services-submenu" class="ml-2 border-l-2 border-gold-500/20 dark:border-gold-500/30 pl-2 space-y-0.5 overflow-hidden transition-all duration-300 max-h-[500px] opacity-100">
                    <span class="text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-semibold ml-3 nav-label block mt-2 mb-1">Administración de Catálogo</span>

                    <a href="#cenas" data-section="cenas"
                       class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200">
                        <i class="fa-solid fa-utensils w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Cenas Especiales</span>
                    </a>

                    <a href="#paquetes" data-section="paquetes"
                       class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200">
                        <i class="fa-solid fa-gift w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Paquetes de Eventos</span>
                    </a>

                    <a href="#balinesas" data-section="balinesas"
                       class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200">
                        <i class="fa-solid fa-umbrella-beach w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Experiencias VIP (Balinesas)</span>
                    </a>
                </div>
            </nav>

            {{-- Footer --}}
            <div class="px-4 py-3 border-t border-sand-200 dark:border-[#2D4A36] flex-shrink-0">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400 nav-label">v1.0.0</span>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-xs text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                        <span class="nav-label">Salir</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main id="main-content" class="flex-1 flex flex-col h-full overflow-hidden">

            {{-- Top Bar --}}
            <header class="h-16 flex-shrink-0 flex items-center justify-between px-6 border-b border-sand-200 dark:border-[#2D4A36] bg-white dark:bg-[#16281D]">
                <div>
                    <h1 class="text-gray-900 dark:text-gray-100 font-semibold text-base" id="page-title">Dashboard General</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Departamento de Alimentos & Bebidas</p>
                </div>
                <div class="flex items-center gap-3">
                    <div id="current-date" class="text-sm text-gray-500 dark:text-gray-400 font-medium hidden md:block"></div>

                    {{-- Dark mode toggle --}}
                    <button id="darkModeToggle" class="w-8 h-8 rounded-lg hover:bg-sand-100 dark:hover:bg-[#2D4A36] flex items-center justify-center text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gold-500 transition-colors">
                        <i id="darkModeIcon" class="fa-solid fa-moon text-sm"></i>
                    </button>

                    {{-- Profile --}}
                    <div class="flex items-center gap-2.5 pl-3 border-l border-sand-200 dark:border-[#2D4A36]">
                        <div class="text-right hidden sm:block">
                            <p class="text-gray-900 dark:text-gray-100 text-sm font-medium truncate max-w-[140px]">{{ auth()->user()->Nombre ?? 'Admin' }}</p>
                            <p class="text-gold-500 dark:text-gold-400 text-xs font-medium">{{ auth()->user()->Rol ?? 'Admin' }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gold-500 to-gold-700 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0 ring-2 ring-gold-500/20">
                            {{ substr(auth()->user()->Nombre ?? 'A', 0, 1) }}{{ substr(auth()->user()->Nombre ?? 'D', 1, 1) ?? '' }}
                        </div>
                    </div>

                    <button onclick="toggleFullscreen()" class="w-8 h-8 rounded-lg hover:bg-sand-100 dark:hover:bg-[#2D4A36] flex items-center justify-center text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                        <i class="fa-solid fa-expand text-sm"></i>
                    </button>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="flex-1 overflow-y-auto p-6 bg-sand-100 dark:bg-[#0D1B12]">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const sectionNames = {
            'general':   { title: 'Dashboard General',      icon: 'fa-gauge-high' },
            'bcg':       { title: 'Matriz BCG',              icon: 'fa-star' },
            'inventory': { title: 'Previsiones Inventario',  icon: 'fa-boxes-stacked' },
            'financial': { title: 'Métricas Financieras',    icon: 'fa-chart-line' },
            'occupancy': { title: 'Ocupación y Demanda',     icon: 'fa-calendar-day' },
            'operations':{ title: 'Operación y Logística',   icon: 'fa-gears' },
            'team':      { title: 'Rendimiento del Equipo',  icon: 'fa-users' },
            'agenda':    { title: 'Agenda de Reservas',      icon: 'fa-calendar-days' },
            'cenas':     { title: 'Cenas Especiales',        icon: 'fa-utensils' },
            'paquetes':  { title: 'Paquetes de Eventos',     icon: 'fa-gift' },
            'balinesas': { title: 'Experiencias VIP',        icon: 'fa-umbrella-beach' },
        };

        // Sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const toggleIcon = document.getElementById('sidebar-toggle-icon');
        const brandText = document.getElementById('sidebar-brand-text');
        const navLabels = document.querySelectorAll('.nav-label');
        let sidebarCollapsed = false;

        toggleBtn.addEventListener('click', () => {
            sidebarCollapsed = !sidebarCollapsed;
            if (sidebarCollapsed) {
                sidebar.style.width = '70px';
                toggleIcon.className = 'fa-solid fa-chevron-right text-sm';
                brandText.style.display = 'none';
                navLabels.forEach(el => el.style.display = 'none');
            } else {
                sidebar.style.width = '260px';
                toggleIcon.className = 'fa-solid fa-bars text-sm';
                brandText.style.display = '';
                navLabels.forEach(el => el.style.display = '');
                if (dashboardChevron) dashboardChevron.style.display = '';
            }
            if (sidebarCollapsed && dashboardChevron) dashboardChevron.style.display = 'none';
        });

        // Dashboard parent toggle
        const parentToggle = document.getElementById('dashboard-parent-toggle');
        const submenuContainer = document.getElementById('submenu-container');
        const dashboardChevron = document.getElementById('dashboard-chevron');

        if (parentToggle && submenuContainer && dashboardChevron) {
            parentToggle.addEventListener('click', () => {
                const isCollapsed = submenuContainer.style.maxHeight === '0px' || submenuContainer.classList.contains('max-h-0');
                if (isCollapsed) {
                    submenuContainer.style.maxHeight = submenuContainer.scrollHeight + 'px';
                    submenuContainer.style.opacity = '1';
                    submenuContainer.classList.remove('max-h-0');
                    dashboardChevron.style.transform = 'rotate(0deg)';
                } else {
                    submenuContainer.style.maxHeight = '0px';
                    submenuContainer.style.opacity = '0';
                    submenuContainer.classList.add('max-h-0');
                    dashboardChevron.style.transform = 'rotate(-90deg)';
                }
                showSection('general');
            });
        }

        // Services parent toggle (submenu CRUDs)
        const servicesToggle = document.getElementById('services-parent-toggle');
        const servicesSubmenu = document.getElementById('services-submenu');
        const servicesChevron = document.getElementById('services-chevron');

        if (servicesToggle && servicesSubmenu && servicesChevron) {
            servicesToggle.addEventListener('click', () => {
                const isCollapsed = servicesSubmenu.style.maxHeight === '0px' || servicesSubmenu.classList.contains('max-h-0');
                if (isCollapsed) {
                    servicesSubmenu.style.maxHeight = servicesSubmenu.scrollHeight + 'px';
                    servicesSubmenu.style.opacity = '1';
                    servicesSubmenu.classList.remove('max-h-0');
                    servicesChevron.style.transform = 'rotate(0deg)';
                } else {
                    servicesSubmenu.style.maxHeight = '0px';
                    servicesSubmenu.style.opacity = '0';
                    servicesSubmenu.classList.add('max-h-0');
                    servicesChevron.style.transform = 'rotate(-90deg)';
                }
            });
        }

        // Dark mode toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeIcon = document.getElementById('darkModeIcon');
        const htmlEl = document.documentElement;

        function applyDarkMode(isDark) {
            if (isDark) {
                htmlEl.classList.add('dark');
                darkModeIcon.className = 'fa-solid fa-sun text-sm';
                localStorage.setItem('darkMode', 'true');
            } else {
                htmlEl.classList.remove('dark');
                darkModeIcon.className = 'fa-solid fa-moon text-sm';
                localStorage.setItem('darkMode', 'false');
            }
        }

        const savedDark = localStorage.getItem('darkMode');
        if (savedDark === 'true') {
            applyDarkMode(true);
        }

        darkModeToggle.addEventListener('click', () => {
            const isDark = !htmlEl.classList.contains('dark');
            applyDarkMode(isDark);
        });

        // Current date
        const dateEl = document.getElementById('current-date');
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateEl.textContent = now.toLocaleDateString('es-MX', options);

        // Fullscreen
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else {
                document.exitFullscreen().catch(() => {});
            }
        }

        // ── Navigation by hash sections ──
        function showSection(sectionId) {
            // Map CRUD sub-sections to parent section-cenas
            let actualSection = sectionId;
            if (sectionId === 'paquetes' || sectionId === 'balinesas') {
                actualSection = 'cenas';
            }

            // Hide all sections
            document.querySelectorAll('.dashboard-section').forEach(el => el.classList.add('hidden'));

            // Show target
            const target = document.getElementById('section-' + actualSection);
            if (target) target.classList.remove('hidden');

            // Auto-activate CRUD tab if applicable
            if (actualSection === 'cenas' && sectionId !== 'cenas') {
                const crudTab = document.querySelector('.crud-tab[data-crud="' + sectionId + '"]');
                if (crudTab) {
                    document.querySelectorAll('.crud-tab').forEach(t => {
                        t.className = 'crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-sand-100 dark:bg-[#1E3327] text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-[#2D4A36]';
                    });
                    crudTab.className = 'crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-gold-500 text-white';
                    document.querySelectorAll('.crud-panel').forEach(p => p.classList.add('hidden'));
                    const panel = document.getElementById('crud-' + sectionId);
                    if (panel) panel.classList.remove('hidden');
                }
            }

            // Update title
            const titleEl = document.getElementById('page-title');
            if (sectionNames[sectionId]) {
                titleEl.textContent = sectionNames[sectionId].title;
            }

            // Update nav active states
            document.querySelectorAll('.nav-section-link').forEach(el => {
                el.classList.remove('nav-item-active');
                el.classList.add('nav-item');
            });
            const activeLink = document.querySelector('.nav-section-link[data-section="' + sectionId + '"]');
            if (activeLink) {
                activeLink.classList.remove('nav-item');
                activeLink.classList.add('nav-item-active');
            }

            // Re-render charts that might be in the shown section
            if (window.renderChartsForSection && typeof window.renderChartsForSection === 'function') {
                setTimeout(() => window.renderChartsForSection(actualSection), 50);
            }

            // Update URL hash
            history.pushState(null, '', '#' + sectionId);
        }

        // Click handlers for nav links
        document.querySelectorAll('.nav-section-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                showSection(link.dataset.section);
            });
        });

        // On load: read hash or default to general
        window.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.replace('#', '') || 'general';
            if (sectionNames[hash]) {
                showSection(hash);
            } else {
                showSection('general');
            }
        });

        // Handle browser back/forward
        window.addEventListener('popstate', () => {
            const hash = window.location.hash.replace('#', '') || 'general';
            if (sectionNames[hash]) {
                showSection(hash);
            }
        });

        // Alert dismiss
        document.addEventListener('click', (e) => {
            if (e.target.closest('.alert-dismiss')) {
                e.target.closest('.alert-card').remove();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
