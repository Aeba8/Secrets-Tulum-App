<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SecretsPad Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Cinzel:wght@400;600;700&display=swap"
        rel="stylesheet">
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
                            40: '#FDFBF7',
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
                        navy: {
                            50: '#F0F4FA',
                            100: '#D6E0F0',
                            200: '#ADC0E0',
                            300: '#84A0D0',
                            400: '#5B80C0',
                            500: '#3B6BB5',
                            600: '#2A5A9F',
                            700: '#1A4280',
                            800: '#0F3B6E',
                            900: '#0A2D55',
                            deep: '#0F4C75',
                            dark: '#0A3D6E',
                            light: '#E8F0FE',
                        },
                        sapphire: {
                            50: '#EFF4FA',
                            100: '#D6E2F2',
                            200: '#ADC5E6',
                            300: '#84A8D9',
                            400: '#5B8BCC',
                            500: '#2F5F9F',
                            600: '#254E85',
                            700: '#1B3C6B',
                            800: '#112B51',
                            900: '#081A37',
                        },
                        'sidebar-light': '#1C2B3E',
                        'sidebar-dark': '#1C2B3E',
                        charcoal: {
                            50: '#F0F0F1',
                            100: '#D6D7DA',
                            200: '#ABADB3',
                            300: '#80838C',
                            400: '#555965',
                            500: '#2A2E36',
                            600: '#1E232A',
                            700: '#12161A',
                            800: '#121418',
                            900: '#0A0B0D',
                        },
                    }
                }
            }
        }
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #FDFBF7;
            color: #1F2937;
            overflow: hidden;
        }

        .dark body {
            background-color: #1A1D23;
            color: #E5E7EB;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar:horizontal {
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(197, 160, 89, 0.5);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(197, 160, 89, 0.7);
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(197, 160, 89, 0.5) transparent;
        }

        .sidebar-transition {
            transition: width 0.3s ease;
        }

        *:focus-visible {
            outline: 2px solid rgba(47, 95, 159, 0.5);
            outline-offset: 2px;
            border-radius: 4px;
        }

        .dark *:focus-visible {
            outline-color: rgba(197, 160, 89, 0.5);
        }

        /* Sidebar Navy (light mode) */
        .sidebar-navy {
            background-color: #1C2B3E;
            border-right-color: #182535;
        }

        .sidebar-navy .nav-label,
        .sidebar-navy .nav-item,
        .sidebar-navy a:not(.nav-item-active),
        .sidebar-navy button:not(.nav-item-active) {
            color: rgba(255, 255, 255, 0.75);
        }

        .sidebar-navy .nav-item:hover,
        .sidebar-navy a:not(.nav-item-active):hover,
        .sidebar-navy button:not(.nav-item-active):hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .sidebar-navy .nav-item-active {
            background-color: rgba(197, 160, 89, 0.12);
            border-left: 2px solid #C5A880;
            border-radius: 10px;
            color: #C5A880;
        }

        .sidebar-navy .nav-item-active i {
            color: #C5A880;
        }

        .sidebar-navy .nav-item-active .nav-label {
            color: #C5A880;
        }

        .sidebar-navy .sidebar-border {
            border-color: rgba(255, 255, 255, 0.08);
        }

        .sidebar-navy .sidebar-text-muted {
            color: rgba(255, 255, 255, 0.45);
        }

        .sidebar-navy .sidebar-toggle-hover:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-navy .sidebar-submenu-border {
            border-color: rgba(197, 160, 89, 0.25);
        }

        .sidebar-navy #sidebar-brand-text {
            color: #C5A880;
        }

        /* Dark mode sidebar */
        .dark .sidebar-dark {
            background-color: #1C2B3E;
            border-right-color: #1E232A;
        }

        .dark .sidebar-dark .sidebar-border {
            border-color: rgba(255, 255, 255, 0.06);
        }

        .dark .sidebar-dark .nav-label,
        .dark .sidebar-dark .nav-item,
        .dark .sidebar-dark a:not(.nav-item-active),
        .dark .sidebar-dark button:not(.nav-item-active) {
            color: rgba(255, 255, 255, 0.65);
        }

        .dark .sidebar-dark .nav-item:hover,
        .dark .sidebar-dark a:not(.nav-item-active):hover,
        .dark .sidebar-dark button:not(.nav-item-active):hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .dark .sidebar-dark .nav-item-active {
            background-color: rgba(197, 160, 89, 0.08);
            border-left: 2px solid #C5A880;
            border-radius: 10px;
            color: #C5A880;
        }

        .dark .sidebar-dark .nav-item-active i {
            color: #C5A880;
        }

        .dark .sidebar-dark .nav-item-active .nav-label {
            color: #C5A880;
        }

        .dark .sidebar-dark #sidebar-brand-text {
            color: #C5A880;
        }

        .dark .sidebar-dark #sidebar-logo-svg {
            color: #C5A880;
        }

        .dark .sidebar-dark .sidebar-text-muted {
            color: rgba(255, 255, 255, 0.3);
        }

        .dark .sidebar-dark .sidebar-submenu-border {
            border-color: rgba(255, 255, 255, 0.06);
        }
    </style>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/images/icon-192.png">
    <link rel="manifest" href="/manifest.json">
</head>

<body class="">

    <div class="flex h-screen overflow-hidden bg-sand-40 dark:bg-charcoal-700">

        {{-- Sidebar --}}
        <aside id="sidebar"
            class="sidebar-transition flex-shrink-0 h-full sidebar-navy sidebar-dark bg-sidebar-light dark:bg-sidebar-dark border-r border-navy-800 dark:border-charcoal-600 flex flex-col z-30 overflow-hidden"
            style="width: 260px;">

            {{-- Logo + toggle --}}
            <div class="sidebar-border flex items-center justify-between h-16 px-5 border-b flex-shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div
                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-gold-500 to-gold-700 flex items-center justify-center flex-shrink-0">
                        <!-- SVG Detallado de Copa Martini Elegante -->
                        <svg id="sidebar-logo-svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <!-- Cuerpo y silueta de la copa -->
                            <path d="M4 4h16l-8 9.5L4 4z" stroke="currentColor" stroke-width="1.5"
                                stroke-linejoin="round" />
                            <path d="M12 13.5V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M8 20h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />

                            <!-- Palillo de la aceituna -->
                            <path d="M10 11l7-8" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                            <!-- La aceituna (Rellena) -->
                            <circle cx="12.5" cy="8" r="1.8" fill="currentColor" />

                            <!-- Twist / Espiral de limón en el borde izquierdo -->
                            <path d="M3.5 5.5c-0.8-1 0.5-2 1.5-1.5s0.2 2.5-1 2" stroke="currentColor" stroke-width="1.2"
                                fill="none" stroke-linecap="round" />
                        </svg>
                    </div>
                    <span class="font-serif text-gold-400 text-lg font-semibold whitespace-nowrap"
                        id="sidebar-brand-text">SecretsPad</span>
                </div>
                <button id="sidebar-toggle"
                    class="sidebar-toggle-hover w-7 h-7 rounded-lg flex items-center justify-center text-white/50 hover:text-white/80 transition-colors flex-shrink-0">
                    <i id="sidebar-toggle-icon" class="fa-solid fa-bars text-sm"></i>
                </button>
            </div>

            <div class="sidebar-border border-t dark:border-white/5"></div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5 dark:space-y-1">

                {{-- Dashboard General (toggle padre) --}}
                <button id="dashboard-parent-toggle"
                    class="flex items-center gap-3 px-3 py-2.5 dark:px-4 dark:py-3 rounded-xl text-sm font-medium transition-all duration-200 nav-item-active w-full text-left">
                    <i class="fa-solid fa-gauge-high w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label flex-1">Dashboard General</span>
                    <i id="dashboard-chevron"
                        class="fa-solid fa-chevron-down text-xs transition-transform duration-300 nav-label"></i>
                </button>

                {{-- Sub-apartados (colapsable) --}}
                <div id="submenu-container"
                    class="sidebar-submenu-border ml-2 border-l-2 pl-2 space-y-0.5 dark:space-y-1 overflow-hidden transition-all duration-300 max-h-[500px] opacity-100">
                    <span
                        class="sidebar-text-muted text-[10px] uppercase tracking-widest font-semibold ml-3 nav-label block mt-2 mb-1">Módulos
                        de Métricas</span>

                    <a href="#bcg" data-section="bcg"
                        class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 dark:px-3 dark:py-2.5 rounded-lg text-xs font-medium transition-all duration-200 nav-item">
                        <i class="fa-solid fa-star w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Matriz BCG</span>
                    </a>

                    <a href="#financial" data-section="financial"
                        class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 dark:px-3 dark:py-2.5 rounded-lg text-xs font-medium transition-all duration-200 nav-item">
                        <i class="fa-solid fa-chart-line w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Métricas Financieras</span>
                    </a>

                    <a href="#occupancy" data-section="occupancy"
                        class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 dark:px-3 dark:py-2.5 rounded-lg text-xs font-medium transition-all duration-200 nav-item">
                        <i class="fa-solid fa-calendar-day w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Ocupación y Demanda</span>
                    </a>

                    <a href="#operations" data-section="operations"
                        class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 dark:px-3 dark:py-2.5 rounded-lg text-xs font-medium transition-all duration-200 nav-item">
                        <i class="fa-solid fa-gears w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Operación y Logística</span>
                    </a>

                    <a href="#team" data-section="team"
                        class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 dark:px-3 dark:py-2.5 rounded-lg text-xs font-medium transition-all duration-200 nav-item">
                        <i class="fa-solid fa-users w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Rendimiento del Equipo</span>
                    </a>
                </div>

                {{-- Divider --}}
                <div class="sidebar-border border-t my-3"></div>

                {{-- Agenda (active) --}}
                <a href="#agenda" data-section="agenda"
                    class="nav-item nav-section-link flex items-center gap-3 px-3 py-2.5 dark:px-4 dark:py-3 rounded-xl text-sm font-medium transition-all duration-200">
                    <i class="fa-solid fa-calendar-days w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label">Agenda de Reservas</span>
                </a>

                <div class="sidebar-border border-t dark:border-white/5 my-2"></div>

                {{-- Gestión de Servicios (toggle padre) --}}
                <button id="services-parent-toggle"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 dark:px-4 dark:py-3 rounded-xl text-sm font-medium transition-all duration-200 w-full text-left">
                    <i class="fa-solid fa-concierge-bell w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label flex-1">Gestión de Servicios</span>
                    <i id="services-chevron"
                        class="fa-solid fa-chevron-down text-xs transition-transform duration-300 nav-label"></i>
                </button>

                {{-- Sub-CRUDs (colapsable) --}}
                <div id="services-submenu"
                    class="sidebar-submenu-border ml-2 border-l-2 pl-2 space-y-0.5 dark:space-y-1 overflow-hidden transition-all duration-300 max-h-[500px] opacity-100">
                    <span
                        class="sidebar-text-muted text-[10px] uppercase tracking-widest font-semibold ml-3 nav-label block mt-2 mb-1">Administración
                        de Catálogo</span>

                    <a href="#cenas" data-section="cenas"
                        class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 dark:px-3 dark:py-2.5 rounded-lg text-xs font-medium transition-all duration-200">
                        <i class="fa-solid fa-utensils w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Cenas Especiales</span>
                    </a>

                    <a href="#balinesas" data-section="balinesas"
                        class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 dark:px-3 dark:py-2.5 rounded-lg text-xs font-medium transition-all duration-200">
                        <i class="fa-solid fa-umbrella-beach w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Balinesas</span>
                    </a>

                    <a href="#experiencias" data-section="experiencias"
                        class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 dark:px-3 dark:py-2.5 rounded-lg text-xs font-medium transition-all duration-200">
                        <i class="fa-solid fa-gem w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Experiencias VIP</span>
                    </a>

                </div>

                <div class="sidebar-border border-t dark:border-white/5 my-2"></div>

                {{-- Usuarios Operativos (standalone) --}}
                <a href="#usuarios" data-section="usuarios"
                    class="nav-item nav-section-link flex items-center gap-3 px-3 py-2.5 dark:px-4 dark:py-3 rounded-xl text-sm font-medium transition-all duration-200">
                    <i class="fa-solid fa-users-gear w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label">Usuarios Operativos</span>
                </a>

                <div class="sidebar-border border-t dark:border-white/5 my-2"></div>

                {{-- Espacios (parent toggle) --}}
                <button id="espacios-parent-toggle"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 dark:px-4 dark:py-3 rounded-xl text-sm font-medium transition-all duration-200 w-full text-left">
                    <i class="fa-solid fa-table-cells w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label flex-1">Espacios</span>
                    <i id="espacios-chevron"
                        class="fa-solid fa-chevron-down text-xs transition-transform duration-300 nav-label"
                        style="transform: rotate(-90deg)"></i>
                </button>

                {{-- Sub-apartados de Espacios --}}
                <div id="espacios-submenu"
                    class="sidebar-submenu-border ml-2 border-l-2 pl-2 space-y-0.5 dark:space-y-1 overflow-hidden transition-all duration-300 max-h-[500px] opacity-100">
                    <a href="#espacios-balinesas" data-section="espacios-balinesas"
                        class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 dark:px-3 dark:py-2.5 rounded-lg text-xs font-medium transition-all duration-200">
                        <i class="fa-solid fa-umbrella-beach w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Balinesas</span>
                    </a>
                    <a href="#espacios-mesas" data-section="espacios-mesas"
                        class="nav-item nav-section-link flex items-center gap-3 px-3 py-2 dark:px-3 dark:py-2.5 rounded-lg text-xs font-medium transition-all duration-200">
                        <i class="fa-solid fa-chair w-4 text-center flex-shrink-0"></i>
                        <span class="whitespace-nowrap nav-label">Mesas</span>
                    </a>
                </div>
            </nav>

            {{-- Footer --}}
            <div class="sidebar-border px-4 py-3 border-t dark:border-white/5 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <span class="sidebar-text-muted text-xs nav-label">v1.0.0</span>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="sidebar-text-muted hover:text-red-400 transition-colors text-xs flex items-center gap-1.5">
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
            <header
                class="h-16 flex-shrink-0 flex items-center justify-between px-6 border-b border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-600">
                <div>
                    <h1 class="text-gray-900 dark:text-gray-100 font-semibold text-base" id="page-title">Dashboard
                        General</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Departamento de Alimentos & Bebidas</p>
                </div>
                <div class="flex items-center gap-3">
                    <div id="current-date"
                        class="text-sm text-gray-500 dark:text-gray-400 font-medium hidden md:block"></div>

                    {{-- Dark mode toggle --}}
                    <button id="darkModeToggle"
                        class="w-8 h-8 rounded-lg hover:bg-sand-100 dark:hover:bg-charcoal-500 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gold-500 transition-colors">
                        <i id="darkModeIcon" class="fa-solid fa-moon text-sm"></i>
                    </button>

                    {{-- Profile --}}
                    <div class="flex items-center gap-2.5 pl-3 border-l border-sand-200 dark:border-charcoal-500">
                        <div class="text-right hidden sm:block">
                            <p class="text-gray-900 dark:text-gray-100 text-sm font-medium truncate max-w-[140px]">
                                {{ auth()->user()->Nombre ?? 'Admin' }}</p>
                            <p class="text-gold-500 dark:text-gold-400 text-xs font-medium">
                                {{ auth()->user()->Rol ?? 'Admin' }}</p>
                        </div>
                        <div
                            class="w-9 h-9 rounded-full bg-gradient-to-br from-gold-500 to-gold-700 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0 ring-2 ring-gold-500/20">
                            {{ substr(auth()->user()->Nombre ?? 'A', 0, 1) }}{{ substr(auth()->user()->Nombre ?? 'D', 1, 1) ?? '' }}
                        </div>
                    </div>

                    <button onclick="toggleFullscreen()"
                        class="w-8 h-8 rounded-lg hover:bg-sand-100 dark:hover:bg-charcoal-500 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                        <i class="fa-solid fa-expand text-sm"></i>
                    </button>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="flex-1 overflow-y-auto p-6 bg-sand-100 dark:bg-charcoal-700">
                @if (session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showToast('{{ session('success') }}', 'success');
                        });
                    </script>
                @endif
                @if (session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showToast('{{ session('error') }}', 'error');
                        });
                    </script>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <div id="toast-container" class="fixed top-24 right-8 z-[100] flex flex-col gap-3 pointer-events-none"></div>

    <script>
        const sectionNames = {
            'general': {
                title: 'Dashboard General',
                icon: 'fa-gauge-high'
            },
            'bcg': {
                title: 'Matriz BCG',
                icon: 'fa-star'
            },
            'financial': {
                title: 'Métricas Financieras',
                icon: 'fa-chart-line'
            },
            'occupancy': {
                title: 'Ocupación y Demanda',
                icon: 'fa-calendar-day'
            },
            'operations': {
                title: 'Operación y Logística',
                icon: 'fa-gears'
            },
            'team': {
                title: 'Rendimiento del Equipo',
                icon: 'fa-users'
            },
            'agenda': {
                title: 'Agenda de Reservas',
                icon: 'fa-calendar-days'
            },
            'cenas': {
                title: 'Cenas Especiales',
                icon: 'fa-utensils'
            },
            'balinesas': {
                title: 'Balinesas',
                icon: 'fa-umbrella-beach'
            },
            'experiencias': {
                title: 'Experiencias VIP',
                icon: 'fa-gem'
            },
            'usuarios': {
                title: 'Usuarios Operativos',
                icon: 'fa-users-gear'
            },
            'espacios-balinesas': {
                title: 'Espacios — Balinesas',
                icon: 'fa-umbrella-beach'
            },
            'espacios-mesas': {
                title: 'Espacios — Mesas',
                icon: 'fa-chair'
            },
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
                const isCollapsed = submenuContainer.style.maxHeight === '0px' || submenuContainer.classList
                    .contains('max-h-0');
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
                localStorage.setItem('sidebar-collapsed-dashboard', isCollapsed ? 'false' : 'true');
                showSection('general');
            });
        }

        // Services parent toggle (submenu CRUDs)
        const servicesToggle = document.getElementById('services-parent-toggle');
        const servicesSubmenu = document.getElementById('services-submenu');
        const servicesChevron = document.getElementById('services-chevron');

        if (servicesToggle && servicesSubmenu && servicesChevron) {
            servicesToggle.addEventListener('click', () => {
                const isCollapsed = servicesSubmenu.style.maxHeight === '0px' || servicesSubmenu.classList.contains(
                    'max-h-0');
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
                localStorage.setItem('sidebar-collapsed-services', isCollapsed ? 'false' : 'true');

                showSection('cenas');
            });
        }

        // Espacios parent toggle (submenu Balinesas/Mesas)
        const espaciosToggle = document.getElementById('espacios-parent-toggle');
        const espaciosSubmenu = document.getElementById('espacios-submenu');
        const espaciosChevron = document.getElementById('espacios-chevron');

        if (espaciosToggle && espaciosSubmenu && espaciosChevron) {
            espaciosToggle.addEventListener('click', () => {
                const isCollapsed = espaciosSubmenu.style.maxHeight === '0px' || espaciosSubmenu.classList.contains(
                    'max-h-0');
                if (isCollapsed) {
                    espaciosSubmenu.style.maxHeight = espaciosSubmenu.scrollHeight + 'px';
                    espaciosSubmenu.style.opacity = '1';
                    espaciosSubmenu.classList.remove('max-h-0');
                    espaciosChevron.style.transform = 'rotate(0deg)';
                } else {
                    espaciosSubmenu.style.maxHeight = '0px';
                    espaciosSubmenu.style.opacity = '0';
                    espaciosSubmenu.classList.add('max-h-0');
                    espaciosChevron.style.transform = 'rotate(-90deg)';
                }
                localStorage.setItem('sidebar-collapsed-espacios', isCollapsed ? 'false' : 'true');

                showSection('espacios-balinesas');
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
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
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
        let _showSectionBusy = false;

        function showSection(sectionId) {
            if (_showSectionBusy) return;
            _showSectionBusy = true;
            try {

                // Map CRUD sub-sections to parent blade
                let actualSection = sectionId;
                if (sectionId === 'paquetes' || sectionId === 'balinesas' || sectionId === 'experiencias') {
                    actualSection = 'cenas';
                } else if (sectionId === 'usuarios') {
                    actualSection = 'usuarios';
                } else if (sectionId === 'espacios-balinesas' || sectionId === 'espacios-mesas') {
                    actualSection = 'espacios';
                }

                // Hide all sections
                document.querySelectorAll('.dashboard-section').forEach(el => el.classList.add('hidden'));

                // Show target
                const target = document.getElementById('section-' + actualSection);
                if (target) target.classList.remove('hidden');

                // Auto-activate CRUD tab if applicable (services)
                if (actualSection === 'cenas') {
                    const crudTab = document.querySelector('.crud-tab[data-crud="' + sectionId + '"]');
                    if (crudTab) {
                        document.querySelectorAll('.crud-tab').forEach(t => {
                            t.className =
                                'crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500';
                        });
                        crudTab.className = 'crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-gold-500 text-white';
                        document.querySelectorAll('.crud-panel').forEach(p => p.classList.add('hidden'));
                        const panel = document.getElementById('crud-' + sectionId);
                        if (panel) panel.classList.remove('hidden');
                    }
                }

                // Auto-activate sub-tab for espacios
                if (actualSection === 'espacios' && sectionId !== 'espacios') {
                    if (typeof window.activarSubTabEspacios === 'function') {
                        window.activarSubTabEspacios(sectionId === 'espacios-balinesas' ? 'Balinesa' : 'Mesa');
                    }
                }

                // Update title
                const titleEl = document.getElementById('page-title');
                if (sectionNames[sectionId]) {
                    titleEl.textContent = sectionNames[sectionId].title;
                }

                // Update nav active states
                // 1. Quitamos el color dorado de TODOS los enlaces y botones principales
                document.querySelectorAll(
                        '.nav-section-link, #dashboard-parent-toggle, #services-parent-toggle, #espacios-parent-toggle')
                    .forEach(
                        el => {
                            el.classList.remove('nav-item-active');
                            el.classList.add('nav-item');
                        });

                // 2. Iluminamos el sub-enlace exacto que el usuario seleccionó
                const activeLink = document.querySelector('.nav-section-link[data-section="' + sectionId + '"]');
                if (activeLink) {
                    activeLink.classList.remove('nav-item');
                    activeLink.classList.add('nav-item-active');
                }

                // 3. Lógica para mantener los apartados padre en dorado si estamos en sus submenús
                const dashboardParent = document.getElementById('dashboard-parent-toggle');
                const servicesParent = document.getElementById('services-parent-toggle');

                const dashboardSections = ['general', 'bcg', 'financial', 'occupancy', 'operations', 'team'];
                const servicesSections = ['cenas', 'balinesas', 'experiencias'];
                const espaciosSections = ['espacios-balinesas', 'espacios-mesas'];

                if (dashboardSections.includes(sectionId) && dashboardParent) {
                    dashboardParent.classList.remove('nav-item');
                    dashboardParent.classList.add('nav-item-active');
                }

                if (servicesSections.includes(sectionId) && servicesParent) {
                    servicesParent.classList.remove('nav-item');
                    servicesParent.classList.add('nav-item-active');
                }

                const espaciosParent = document.getElementById('espacios-parent-toggle');
                if (espaciosSections.includes(sectionId) && espaciosParent) {
                    espaciosParent.classList.remove('nav-item');
                    espaciosParent.classList.add('nav-item-active');
                }

                // Re-render charts that might be in the shown section
                if (window.renderChartsForSection && typeof window.renderChartsForSection === 'function') {
                    setTimeout(() => window.renderChartsForSection(actualSection), 50);
                }

                // Update URL hash
                history.pushState(null, '', '#' + sectionId);

            } finally {
                _showSectionBusy = false;
            }
        }

        // Click handlers for nav links
        document.querySelectorAll('.nav-section-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                showSection(link.dataset.section);
            });
        });

        // On load: read hash or default to general
        // Restore sidebar submenu collapse states from localStorage
        function restoreSidebarState(key, containerId, chevronId) {
            if (localStorage.getItem(key) === 'true') {
                const container = document.getElementById(containerId);
                const chevron = document.getElementById(chevronId);
                if (container && chevron) {
                    container.style.maxHeight = '0px';
                    container.style.opacity = '0';
                    container.classList.add('max-h-0');
                    chevron.style.transform = 'rotate(-90deg)';
                }
            }
        }
        restoreSidebarState('sidebar-collapsed-dashboard', 'submenu-container', 'dashboard-chevron');
        restoreSidebarState('sidebar-collapsed-services', 'services-submenu', 'services-chevron');
        restoreSidebarState('sidebar-collapsed-espacios', 'espacios-submenu', 'espacios-chevron');

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

        // NUEVO: Sincronizar clics en las pestañas internas del CRUD con el menú lateral
        document.addEventListener('click', (e) => {
            const crudTab = e.target.closest('.crud-tab');
            if (crudTab) {
                // Obtenemos qué sección quiere ver (cenas, paquetes o balinesas) basado en su data-crud
                const section = crudTab.dataset.crud;
                if (section) {
                    // Llamamos a showSection para que sincronice la vista, el título y el sidebar
                    showSection(section);
                }
            }
        });

        // Toast notifications
        function showToast(message, type) {
            const container = document.getElementById('toast-container');
            if (!container) return;
            const toast = document.createElement('div');
            const colors = type === 'success' ?
                'bg-sapphire-50 dark:bg-sapphire-900/20 border-sapphire-200 dark:border-sapphire-800 text-sapphire-700 dark:text-sapphire-300' :
                'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-300';
            const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
            toast.className = 'pointer-events-auto flex items-center gap-3 px-5 py-3 rounded-xl border shadow-lg ' +
                colors + ' transform transition-all duration-300 translate-x-full opacity-0';
            toast.innerHTML = '<i class="fa-solid ' + icon + ' text-sm"></i><span class="text-sm font-medium">' + message +
                '</span>';
            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            });
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Alert dismiss
        document.addEventListener('click', (e) => {
            if (e.target.closest('.alert-dismiss')) {
                e.target.closest('.alert-card').remove();
            }
        });
    </script>

    @stack('scripts')

    {{-- Floating Export Button --}}
    <button id="exportBtn"
        class="fixed bottom-6 right-6 z-[60] w-12 h-12 rounded-full bg-gold-500 text-white shadow-lg flex items-center justify-center text-lg hover:bg-gold-600 transition-all cursor-pointer active:scale-90"
        title="Exportar sección actual a Excel">
        <i class="fa-solid fa-file-excel"></i>
    </button>

    <script>
        let exportando = false;
        document.getElementById('exportBtn')?.addEventListener('click', function(e) {
            if (exportando) return;
            exportando = true;
            e.preventDefault();
            const visible = document.querySelector('.dashboard-section:not(.hidden)');
            const section = visible ? visible.id.replace('section-', '') : 'general';
            window.location.href = '{{ route('admin.dashboard.export', '_SECTION_') }}'.replace('_SECTION_',
                section);
            setTimeout(function() { exportando = false; }, 3000);
        });
    </script>
    <script>
    if ('serviceWorker' in navigator) { navigator.serviceWorker.register('/sw.js'); }
    </script>
</body>

</html>
