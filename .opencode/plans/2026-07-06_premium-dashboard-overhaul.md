# Plan: Premium Dashboard Overhaul

## Condiciones aceptadas
- **Dark mode**: Botón sol/luna en header, persiste en `localStorage('darkMode')`, Tailwind `darkMode: 'class'`
- **CRUDs**: Tablas con datos mock (sin modales funcionales aún)
- **Canvas stretching**: YA corregido (wrappers + `maintainAspectRatio: false` en todos)

---

## Archivo 1 — `app/Http/Controllers/Admin/DashboardController.php`

### 1a. Agregar mock data de Agenda

Insertar ANTES de `return view(...)`:

```php
        // ── 5. Agenda de Reservas ──
        $agendaReservations = [
            [
                'id' => 1, 'mesa' => 'Balinesa #3 - Playa', 'guest' => 'Carlos Rivera',
                'room' => 'Suite 204', 'time' => '10:00 - 13:00', 'pax' => 4,
                'status' => 'confirmado', 'status_color' => 'emerald', 'phone' => '+52 998 123 4567',
            ],
            [
                'id' => 2, 'mesa' => 'The Grotto - Mesa VIP', 'guest' => 'Ana Sofía García',
                'room' => 'Master 101', 'time' => '14:00 - 16:30', 'pax' => 2,
                'status' => 'confirmado', 'status_color' => 'emerald', 'phone' => '+52 998 234 5678',
            ],
            [
                'id' => 3, 'mesa' => 'Balinesa #7 - Alberca', 'guest' => 'Roberto Méndez',
                'room' => 'Junior 305', 'time' => '11:00 - 14:00', 'pax' => 3,
                'status' => 'pendiente', 'status_color' => 'amber', 'phone' => '+52 998 345 6789',
            ],
            [
                'id' => 4, 'mesa' => 'Terraza Mar - Mesa 5', 'guest' => 'María Fernanda López',
                'room' => 'Suite 110', 'time' => '19:00 - 21:30', 'pax' => 6,
                'status' => 'confirmado', 'status_color' => 'emerald', 'phone' => '+52 998 456 7890',
            ],
            [
                'id' => 5, 'mesa' => 'Balinesa #1 - Playa', 'guest' => 'José Luis Martínez',
                'room' => 'Penthouse 501', 'time' => '09:00 - 12:00', 'pax' => 2,
                'status' => 'no-show', 'status_color' => 'red', 'phone' => '+52 998 567 8901',
            ],
            [
                'id' => 6, 'mesa' => 'The Grotto - Mesa 8', 'guest' => 'Patricia Vega',
                'room' => 'Deluxe 208', 'time' => '13:00 - 15:30', 'pax' => 4,
                'status' => 'completado', 'status_color' => 'gray', 'phone' => '+52 998 678 9012',
            ],
            [
                'id' => 7, 'mesa' => 'Pool Club - Camastro 2', 'guest' => 'Fernando Castillo',
                'room' => 'Suite 402', 'time' => '10:30 - 17:00', 'pax' => 2,
                'status' => 'pendiente', 'status_color' => 'amber', 'phone' => '+52 998 789 0123',
            ],
            [
                'id' => 8, 'mesa' => 'Balinesa #5 - Sunset', 'guest' => 'Laura Jiménez',
                'room' => 'Master 103', 'time' => '15:00 - 18:30', 'pax' => 4,
                'status' => 'confirmado', 'status_color' => 'emerald', 'phone' => '+52 998 890 1234',
            ],
        ];

        $agendaPeriods = [
            ['key' => 'today', 'label' => 'Hoy', 'count' => 8],
            ['key' => 'tomorrow', 'label' => 'Mañana', 'count' => 12],
            ['key' => 'week', 'label' => 'Esta Semana', 'count' => 47],
        ];

        $agendaStats = [
            ['label' => 'Confirmadas', 'count' => 4, 'color' => '#065F46'],
            ['label' => 'Pendientes', 'count' => 2, 'color' => '#D4A853'],
            ['label' => 'No-Show', 'count' => 1, 'color' => '#DC2626'],
            ['label' => 'Completadas', 'count' => 1, 'color' => '#9CA3AF'],
        ];
```

### 1b. Agregar mock data de CRUDs

Insertar DESPUÉS de agenda data (antes de `return view(...)`):

```php
        // ── 6. CRUD: Cenas Especiales ──
        $cenasEspeciales = [
            ['id' => 1, 'lugar' => 'The Grotto', 'descripcion' => 'Cena romántica en cueva privada con mariachi', 'precio' => 8500, 'capacidad' => 12, 'status' => 'Activo'],
            ['id' => 2, 'lugar' => 'Terraza Mar', 'descripcion' => 'Cena al atardecer frente al mar', 'precio' => 5200, 'capacidad' => 40, 'status' => 'Activo'],
            ['id' => 3, 'lugar' => 'Balcón Colonial', 'descripcion' => 'Cena privada en mirador colonial', 'precio' => 6800, 'capacidad' => 8, 'status' => 'Activo'],
            ['id' => 4, 'lugar' => 'Jardín Secreto', 'descripcion' => 'Cena rodeada de vegetación exótica', 'precio' => 4800, 'capacidad' => 20, 'status' => 'Inactivo'],
            ['id' => 5, 'lugar' => 'Pool Club Noche', 'descripcion' => 'Cena iluminada junto a la alberca infinita', 'precio' => 6100, 'capacidad' => 30, 'status' => 'Activo'],
        ];

        // ── 6b. CRUD: Paquetes de Eventos ──
        $paquetesEventos = [
            ['id' => 1, 'nombre' => 'Paquete Mundial', 'descripcion' => 'Evento con pantalla gigante y buffet', 'precio' => 35000, 'fecha' => '2026-07-15', 'disponible' => 50, 'status' => 'Activo'],
            ['id' => 2, 'nombre' => 'Cata de Tequila Premium', 'descripcion' => 'Degustación guiada con sommelier', 'precio' => 2800, 'fecha' => '2026-07-20', 'disponible' => 20, 'status' => 'Activo'],
            ['id' => 3, 'nombre' => 'Noche de Jazz & Vinos', 'descripcion' => 'Música en vivo y maridaje', 'precio' => 4200, 'fecha' => '2026-07-28', 'disponible' => 35, 'status' => 'Activo'],
            ['id' => 4, 'nombre' => 'Fiesta de Año Nuevo', 'descripcion' => 'Cena de gala y cotillón incluido', 'precio' => 12000, 'fecha' => '2026-12-31', 'disponible' => 100, 'status' => 'Activo'],
            ['id' => 5, 'nombre' => 'Brunch Dominical', 'descripcion' => 'Bufer dominical con estaciones gourmet', 'precio' => 1800, 'fecha' => '2026-07-14', 'disponible' => 60, 'status' => 'Inactivo'],
        ];

        // ── 6c. CRUD: Experiencias VIP (Balinesas) ──
        $balinesas = [
            ['id' => 1, 'ubicacion' => 'Playa Norte', 'tarifa' => 3200, 'capacidad' => 4, 'tipo' => 'Sombrilla', 'vistas' => 'Mar', 'status' => 'Activo'],
            ['id' => 2, 'ubicacion' => 'Playa Central', 'tarifa' => 2800, 'capacidad' => 2, 'tipo' => 'Sol', 'vistas' => 'Mar', 'status' => 'Activo'],
            ['id' => 3, 'ubicacion' => 'Alberca Infinity', 'tarifa' => 2500, 'capacidad' => 4, 'tipo' => 'Sombrilla', 'vistas' => 'Jardín', 'status' => 'Activo'],
            ['id' => 4, 'ubicacion' => 'Sunset Deck', 'tarifa' => 3800, 'capacidad' => 6, 'tipo' => 'VIP', 'vistas' => 'Atardecer', 'status' => 'Activo'],
            ['id' => 5, 'ubicacion' => 'Playa Privada', 'tarifa' => 4500, 'capacidad' => 6, 'tipo' => 'VIP', 'vistas' => 'Mar Abierto', 'status' => 'Inactivo'],
            ['id' => 6, 'ubicacion' => 'Pool Club', 'tarifa' => 2200, 'capacidad' => 2, 'tipo' => 'Sol', 'vistas' => 'Alberca', 'status' => 'Activo'],
        ];
```

### 1c. Agregar al `compact()`:

Agregar estos nombres al compact:
```php
'agendaReservations', 'agendaPeriods', 'agendaStats',
'cenasEspeciales', 'paquetesEventos', 'balinesas',
```

---

## Archivo 2 — `resources/views/admin/layouts/dashboard.blade.php`

### 2a. Tailwind config: darkMode + new colors + sand palette

Reemplazar TODO el bloque `tailwind.config = { ... }`:

**oldString:**
```js
        tailwind.config = {
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
                    }
                }
            }
        }
```

**newString:**
```js
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
```

### 2b. CSS: dark mode styles + new palette

Reemplazar TODO el bloque `<style>...</style>`:

**oldString:**
```css
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #F9FAFB; color: #111827; overflow: hidden; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(197, 160, 89, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(197, 160, 89, 0.5); }
        .sidebar-transition { transition: width 0.3s ease; }
        .nav-item-active { background-color: rgba(197, 160, 89, 0.08); color: #C5A059; border-color: rgba(197, 160, 89, 0.2); }
        .nav-item-sub-active { background-color: rgba(197, 160, 89, 0.06); color: #C5A059; }
        .nav-item { color: #6B7280; }
        .nav-item:hover { background-color: #F3F4F6; color: #374151; }
```

**newString:**
```css
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
```

### 2c. Body: add dark class and new bg

Replace `<body>` line:
**oldString:** `<body>`
**newString:** `<body class="">` *(empty class, JS will add 'dark')*

### 2d. Main container div: new bg + dark mode

**oldString:** `<div class="flex h-screen overflow-hidden bg-gray-50">`
**newString:** `<div class="flex h-screen overflow-hidden bg-sand-100 dark:bg-[#0D1B12]">`

### 2e. Sidebar: new bg colors + remove profile section

**oldString:** `<aside id="sidebar" class="sidebar-transition flex-shrink-0 h-full bg-white border-r border-gray-200 flex flex-col z-30 overflow-hidden"`

**newString:** `<aside id="sidebar" class="sidebar-transition flex-shrink-0 h-full bg-white dark:bg-[#16281D] border-r border-sand-200 dark:border-[#2D4A36] flex flex-col z-30 overflow-hidden"`

### 2f. Logo bar: new border color

**oldString:** `<div class="flex items-center justify-between h-16 px-5 border-b border-gray-100 flex-shrink-0">`
**newString:** `<div class="flex items-center justify-between h-16 px-5 border-b border-sand-200 dark:border-[#2D4A36] flex-shrink-0">`

### 2g. Toggle button hover colors

**oldString:** `<button id="sidebar-toggle" class="w-7 h-7 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0">`
**newString:** `<button id="sidebar-toggle" class="w-7 h-7 rounded-lg hover:bg-sand-100 dark:hover:bg-[#2D4A36] flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0">`

### 2h. REMOVE profile section (lines 78-89)

**oldString:**
```
            {{-- Profile --}}
            <div class="px-4 py-4 border-b border-gray-100 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gold-500 to-gold-700 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                        {{ substr(auth()->user()->Nombre ?? 'A', 0, 1) }}{{ substr(auth()->user()->Nombre ?? 'D', 1, 1) ?? '' }}
                    </div>
                    <div class="overflow-hidden" id="sidebar-profile-text">
                        <p class="text-gray-900 text-sm font-medium truncate">{{ auth()->user()->Nombre ?? 'Admin' }}</p>
                        <p class="text-gold-500 text-xs font-medium">{{ auth()->user()->Rol ?? 'Admin' }}</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
```
**newString:** `{{-- Navigation --}}`

### 2i. Navigation submenu: new border color

**oldString:** `<div id="submenu-container" class="ml-2 border-l-2 border-gray-100 pl-2 space-y-0.5 overflow-hidden transition-all duration-300 max-h-[500px] opacity-100">`
**newString:** `<div id="submenu-container" class="ml-2 border-l-2 border-gold-500/20 dark:border-gold-500/30 pl-2 space-y-0.5 overflow-hidden transition-all duration-300 max-h-[500px] opacity-100">`

### 2j. Submenu label: new text color

**oldString:** `<span class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold ml-3 nav-label block mt-2 mb-1">Módulos de Métricas</span>`
**newString:** `<span class="text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-semibold ml-3 nav-label block mt-2 mb-1">Módulos de Métricas</span>`

### 2k. Replace disabled Agenda with active link

**oldString:**
```
                {{-- Agenda (disabled) --}}
                <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-300 cursor-not-allowed relative group">
                    <i class="fa-solid fa-calendar-days w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label">Agenda</span>
                    <span class="ml-auto text-[10px] uppercase tracking-wider text-gray-300 nav-label">Próximamente</span>
                    <div class="absolute left-0 -top-8 bg-gray-800 text-white text-xs px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-50">
                        Módulo en desarrollo
                    </div>
                </div>
```

**newString:**
```
                {{-- Agenda (active) --}}
                <a href="#agenda" data-section="agenda"
                   class="nav-item nav-section-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200">
                    <i class="fa-solid fa-calendar-days w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label">Agenda de Reservas</span>
                </a>
```

### 2l. Replace disabled Servicios with active toggle + sub-items

**oldString:**
```
                {{-- Servicios (disabled) --}}
                <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-300 cursor-not-allowed relative group">
                    <i class="fa-solid fa-concierge-bell w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label">Servicios</span>
                    <span class="ml-auto text-[10px] uppercase tracking-wider text-gray-300 nav-label">Próximamente</span>
                    <div class="absolute left-0 -top-8 bg-gray-800 text-white text-xs px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-50">
                        CRUDs de servicios en desarrollo
                    </div>
                </div>
```

**newString:**
```
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
```

### 2m. Footer: new border + dark

**oldString:** `<div class="px-4 py-3 border-t border-gray-100 flex-shrink-0">`
**newString:** `<div class="px-4 py-3 border-t border-sand-200 dark:border-[#2D4A36] flex-shrink-0">`

### 2n. Main: remove bg from main

**oldString:** `<main id="main-content" class="flex-1 flex flex-col h-full overflow-hidden">`
**newString:** `<main id="main-content" class="flex-1 flex flex-col h-full overflow-hidden">` — no change needed

### 2o. Header: new colors + add profile section + dark toggle

Replace entire header block:

**oldString:**
```
            {{-- Top Bar --}}
            <header class="h-16 flex-shrink-0 flex items-center justify-between px-6 border-b border-gray-200 bg-white">
                <div>
                    <h1 class="text-gray-900 font-semibold text-base" id="page-title">Dashboard General</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Departamento de Alimentos & Bebidas</p>
                </div>
                <div class="flex items-center gap-4">
                    <div id="current-date" class="text-sm text-gray-500 font-medium"></div>
                    <button onclick="toggleFullscreen()" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-expand text-sm"></i>
                    </button>
                </div>
            </header>
```

**newString:**
```
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
```

### 2o5. Content area: dark bg

**oldString:** `<div class="flex-1 overflow-y-auto p-6">`
**newString:** `<div class="flex-1 overflow-y-auto p-6 bg-sand-100 dark:bg-[#0D1B12]">`

### 2p. JS sectionNames: add new sections

Replace the `sectionNames` object:

**oldString:**
```js
        const sectionNames = {
            'general':   { title: 'Dashboard General',   icon: 'fa-gauge-high' },
            'bcg':       { title: 'Matriz BCG',           icon: 'fa-star' },
            'inventory': { title: 'Previsiones Inventario', icon: 'fa-boxes-stacked' },
            'financial': { title: 'Métricas Financieras',  icon: 'fa-chart-line' },
            'occupancy': { title: 'Ocupación y Demanda',   icon: 'fa-calendar-day' },
            'operations':{ title: 'Operación y Logística',  icon: 'fa-gears' },
            'team':      { title: 'Rendimiento del Equipo', icon: 'fa-users' },
        };
```

**newString:**
```js
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
```

### 2q. Remove profileText from sidebar toggle JS

In sidebar toggle, remove references to `profileText`:
- Remove `const profileText = document.getElementById('sidebar-profile-text');`
- Remove `profileText.style.display = 'none';`
- Remove `profileText.style.display = '';`

**oldString:**
```js
        const brandText = document.getElementById('sidebar-brand-text');
        const profileText = document.getElementById('sidebar-profile-text');
        const navLabels = document.querySelectorAll('.nav-label');
        let sidebarCollapsed = false;

        toggleBtn.addEventListener('click', () => {
            sidebarCollapsed = !sidebarCollapsed;
            if (sidebarCollapsed) {
                sidebar.style.width = '70px';
                toggleIcon.className = 'fa-solid fa-chevron-right text-sm';
                brandText.style.display = 'none';
                profileText.style.display = 'none';
                navLabels.forEach(el => el.style.display = 'none');
            } else {
                sidebar.style.width = '260px';
                toggleIcon.className = 'fa-solid fa-bars text-sm';
                brandText.style.display = '';
                profileText.style.display = '';
                navLabels.forEach(el => el.style.display = '');
```

**newString:**
```js
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
```

### 2r. Add dark mode toggle JS + services parent toggle JS

Insert AFTER the block that ends with `showSection('general');` (the dashboard parent toggle JS):

**oldString:**
```js
            }
        }

        // Current date
        const dateEl = document.getElementById('current-date');
```

**newString:**
```js
            }
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

        // Initialize dark mode from localStorage
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
```

---

## Archivo 3 — `resources/views/admin/dashboard.blade.php`

### 3a. Add dark mode classes to section wrappers

Replace the outer container:
**oldString:** `<div class="max-w-7xl mx-auto space-y-8">`
**newString:** `<div class="max-w-7xl mx-auto space-y-8 dark:text-gray-200">`

### 3b. Add dark mode to KPI cards

Replace the KPI card div:
**oldString:** `<div class="bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-md hover:border-gold-500/30 transition-all duration-300 group">`
**newString:** `<div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5 hover:shadow-md hover:border-gold-500/30 dark:hover:border-gold-500/40 transition-all duration-300 group">`

### 3c. KPI label text

**oldString:** `<span class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ $kpi['label'] }}</span>`
**newString:** `<span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</span>`

### 3d. KPI value

**oldString:** `<div class="text-3xl font-bold text-gray-900 mb-1 font-light tracking-tight">{{ $kpi['value'] }}</div>`
**newString:** `<div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1 font-light tracking-tight">{{ $kpi['value'] }}</div>`

### 3e. Quick links section title

**oldString:** `<h3 class="text-gray-900 font-semibold text-base mb-4">Acceso Rápido a Módulos</h3>`
**newString:** `<h3 class="text-gray-900 dark:text-gray-100 font-semibold text-base mb-4">Acceso Rápido a Módulos</h3>`

### 3f. Quick links cards

**oldString:**
```
                <a href="#{{ $ql['section'] }}" data-section="{{ $ql['section'] }}"
                   class="nav-section-link flex items-center gap-3 p-4 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-gold-500/30 transition-all duration-200 nav-item">
```
**newString:**
```
                <a href="#{{ $ql['section'] }}" data-section="{{ $ql['section'] }}"
                   class="nav-section-link flex items-center gap-3 p-4 bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-xl hover:shadow-md hover:border-gold-500/30 transition-all duration-200 nav-item">
```

### 3g. Quick links icon bg

**oldString:** `<div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center {{ $ql['color'] }}">`
**newString:** `<div class="w-10 h-10 rounded-xl bg-sand-50 dark:bg-[#2D4A36] flex items-center justify-center {{ $ql['color'] }}">`

### 3h. Quick links label

**oldString:** `<span class="text-sm font-medium text-gray-700">{{ $ql['label'] }}</span>`
**newString:** `<span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $ql['label'] }}</span>`

### 3i. Summary cards (monthly revenue + summary)

**oldString:** `<div class="bg-white border border-gray-200 rounded-2xl p-5">` (6 occurrences for summary cards in general section)
**newString:** `<div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">`

### 3j. Summary card titles

**oldString:** `<h3 class="text-gray-900 font-semibold text-sm">Ingresos Mensuales</h3>`
**newString:** `<h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Ingresos Mensuales</h3>`

**oldString:** `<h3 class="text-gray-900 font-semibold text-sm">Resumen del Mes</h3>`
**newString:** `<h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Resumen del Mes</h3>`

### 3k. Summary row bg

**oldString:** `class="flex items-center justify-between p-3 rounded-xl bg-gray-50"`
**newString:** `class="flex items-center justify-between p-3 rounded-xl bg-sand-50 dark:bg-[#1E3327]"`

### 3l. Summary text colors

**oldString:** `<span class="text-gray-600 text-sm">Total Ingresos</span>`
**newString:** `<span class="text-gray-600 dark:text-gray-400 text-sm">Total Ingresos</span>`

**oldString:** `<span class="text-gray-900 font-bold text-lg font-mono">`
**newString:** `<span class="text-gray-900 dark:text-gray-100 font-bold text-lg font-mono">`

This pattern repeats across ALL sections. For brevity, the full plan for dashboard.blade.php will be:
1. Replace all `bg-white` card containers with `bg-white dark:bg-[#16281D] border-sand-200 dark:border-[#2D4A36]`
2. Replace all `text-gray-900` with `text-gray-900 dark:text-gray-100`
3. Replace all `text-gray-500/600/700` with appropriate dark variants
4. Replace `border-gray-200/100` with `border-sand-200`
5. Replace `bg-gray-50/100` with `bg-sand-50` or `dark:bg-[#1E3327]`

### 3m. NEW section: Agenda

Insert AFTER section-team closing `</div>` (line 594) and BEFORE the outer `</div>`:

```
    {{-- ═══════════════════════════════════════════ --}}
    {{-- SECTION: AGENDA DE RESERVAS --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="section-agenda" class="dashboard-section hidden">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Agenda de Reservas</h2>
        </div>

        {{-- Stats pills --}}
        <div class="flex flex-wrap gap-3 mb-6">
            @foreach ($agendaStats as $as)
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36]">
                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $as['color'] }}"></span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $as['label'] }}</span>
                <span class="text-sm font-bold text-gray-900 dark:text-gray-100 font-mono">{{ $as['count'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Period tabs --}}
        <div class="flex gap-1 mb-6 bg-sand-100 dark:bg-[#1E3327] rounded-xl p-1 w-fit">
            @foreach ($agendaPeriods as $i => $ap)
            <button class="agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ $i === 0 ? 'bg-white dark:bg-[#16281D] text-gray-900 dark:text-gray-100 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }}"
                    data-period="{{ $ap['key'] }}">
                {{ $ap['label'] }} <span class="font-mono ml-1">({{ $ap['count'] }})</span>
            </button>
            @endforeach
        </div>

        {{-- Reservation table --}}
        <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-[#2D4A36] bg-sand-50 dark:bg-[#1E3327]">
                            <th class="text-left px-5 py-3 font-medium">Mesa / Lugar</th>
                            <th class="text-left px-5 py-3 font-medium">Huésped</th>
                            <th class="text-left px-5 py-3 font-medium">Habitación</th>
                            <th class="text-center px-5 py-3 font-medium">Hora</th>
                            <th class="text-center px-5 py-3 font-medium">PAX</th>
                            <th class="text-center px-5 py-3 font-medium">Contacto</th>
                            <th class="text-center px-5 py-3 font-medium">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agendaReservations as $res)
                        @php
                            $statusBadge = match($res['status_color']) {
                                'emerald' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200',
                                'amber' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-200',
                                'red' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-200',
                                'gray' => 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200',
                                default => 'bg-gray-50 text-gray-500 border-gray-200',
                            };
                        @endphp
                        <tr class="border-b border-sand-200 dark:border-[#2D4A36] last:border-0 hover:bg-sand-50 dark:hover:bg-[#1E3327] transition-colors">
                            <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $res['mesa'] }}</td>
                            <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100">{{ $res['guest'] }}</td>
                            <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 font-mono text-xs">{{ $res['room'] }}</td>
                            <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300 text-center">{{ $res['time'] }}</td>
                            <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">{{ $res['pax'] }}</td>
                            <td class="px-5 py-3.5 text-gray-400 dark:text-gray-500 text-center text-xs font-mono">{{ $res['phone'] }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-md border font-medium {{ $statusBadge }}">{{ ucfirst($res['status']) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
```

### 3n. NEW section: Cenas Especiales (CRUD)

```
    {{-- ═══════════════════════════════════════════ --}}
    {{-- SECTION: CENAS ESPECIALES (CRUD) --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="section-cenas" class="dashboard-section hidden">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Catálogo — Cenas Especiales</h2>
        </div>

        {{-- CRUD tabs --}}
        <div class="flex gap-2 mb-4">
            <button class="crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-gold-500 text-white" data-crud="cenas">Cenas Especiales</button>
            <button class="crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-sand-100 dark:bg-[#1E3327] text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-[#2D4A36]" data-crud="paquetes">Paquetes de Eventos</button>
            <button class="crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-sand-100 dark:bg-[#1E3327] text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-[#2D4A36]" data-crud="balinesas">Experiencias VIP</button>
        </div>

        <div id="crud-cenas" class="crud-panel">
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-[#2D4A36]">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Locaciones de Cenas Especiales</h3>
                    <button class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Nuevo
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-[#2D4A36] bg-sand-50 dark:bg-[#1E3327]">
                                <th class="text-left px-5 py-3 font-medium">Lugar</th>
                                <th class="text-left px-5 py-3 font-medium">Descripción</th>
                                <th class="text-right px-5 py-3 font-medium">Precio</th>
                                <th class="text-center px-5 py-3 font-medium">Capacidad</th>
                                <th class="text-center px-5 py-3 font-medium">Estado</th>
                                <th class="text-center px-5 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cenasEspeciales as $ce)
                            <tr class="border-b border-sand-200 dark:border-[#2D4A36] last:border-0 hover:bg-sand-50 dark:hover:bg-[#1E3327] transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $ce['lugar'] }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-[200px] truncate">{{ $ce['descripcion'] }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-right font-mono">${{ number_format($ce['precio']) }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">{{ $ce['capacidad'] }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $ce['status'] === 'Activo' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200' }}">{{ $ce['status'] }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button class="hover:text-gold-500 transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                                        <button class="hover:text-red-500 transition-colors"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="crud-paquetes" class="crud-panel hidden">
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-[#2D4A36]">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Paquetes de Eventos</h3>
                    <button class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Nuevo
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-[#2D4A36] bg-sand-50 dark:bg-[#1E3327]">
                                <th class="text-left px-5 py-3 font-medium">Nombre</th>
                                <th class="text-left px-5 py-3 font-medium">Descripción</th>
                                <th class="text-right px-5 py-3 font-medium">Precio</th>
                                <th class="text-center px-5 py-3 font-medium">Fecha</th>
                                <th class="text-center px-5 py-3 font-medium">Disponibles</th>
                                <th class="text-center px-5 py-3 font-medium">Estado</th>
                                <th class="text-center px-5 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paquetesEventos as $pe)
                            <tr class="border-b border-sand-200 dark:border-[#2D4A36] last:border-0 hover:bg-sand-50 dark:hover:bg-[#1E3327] transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $pe['nombre'] }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-[200px] truncate">{{ $pe['descripcion'] }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-right font-mono">${{ number_format($pe['precio']) }}</td>
                                <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300 text-center text-xs font-mono">{{ \Carbon\Carbon::parse($pe['fecha'])->format('d/m/Y') }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">{{ $pe['disponible'] }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $pe['status'] === 'Activo' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200' }}">{{ $pe['status'] }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button class="hover:text-gold-500 transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                                        <button class="hover:text-red-500 transition-colors"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="crud-balinesas" class="crud-panel hidden">
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-[#2D4A36]">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Experiencias VIP — Balinesas</h3>
                    <button class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Nueva
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-[#2D4A36] bg-sand-50 dark:bg-[#1E3327]">
                                <th class="text-left px-5 py-3 font-medium">Ubicación</th>
                                <th class="text-right px-5 py-3 font-medium">Tarifa / Día</th>
                                <th class="text-center px-5 py-3 font-medium">Capacidad</th>
                                <th class="text-center px-5 py-3 font-medium">Tipo</th>
                                <th class="text-center px-5 py-3 font-medium">Vistas</th>
                                <th class="text-center px-5 py-3 font-medium">Estado</th>
                                <th class="text-center px-5 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($balinesas as $ba)
                            <tr class="border-b border-sand-200 dark:border-[#2D4A36] last:border-0 hover:bg-sand-50 dark:hover:bg-[#1E3327] transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $ba['ubicacion'] }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-right font-mono">${{ number_format($ba['tarifa']) }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">{{ $ba['capacidad'] }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $ba['tipo'] === 'VIP' ? 'bg-gold-50 dark:bg-gold-900/20 text-gold-600 dark:text-gold-400 border-gold-200' : 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-blue-200' }}">{{ $ba['tipo'] }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-center text-xs">{{ $ba['vistas'] }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $ba['status'] === 'Activo' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200' }}">{{ $ba['status'] }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button class="hover:text-gold-500 transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                                        <button class="hover:text-red-500 transition-colors"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
```

### 3o. ADD CRUD tab switching JS + agenda periods JS at end of script

At the end of the push-script block (before `</script>`), add:

```js
// ── Agenda period tabs ──
document.addEventListener('click', (e) => {
    const agendaTab = e.target.closest('.agenda-period-tab');
    if (agendaTab) {
        document.querySelectorAll('.agenda-period-tab').forEach(t => {
            t.className = 'agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 bg-sand-100 dark:bg-[#1E3327] text-gray-500 dark:text-gray-400 hover:text-gray-700';
        });
        agendaTab.className = 'agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 bg-white dark:bg-[#16281D] text-gray-900 dark:text-gray-100 shadow-sm';
    }
});

// ── CRUD tabs ──
document.addEventListener('click', (e) => {
    const crudTab = e.target.closest('.crud-tab');
    if (crudTab) {
        document.querySelectorAll('.crud-tab').forEach(t => {
            t.className = 'crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-sand-100 dark:bg-[#1E3327] text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-[#2D4A36]';
        });
        crudTab.className = 'crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-gold-500 text-white';
        const target = crudTab.dataset.crud;
        document.querySelectorAll('.crud-panel').forEach(p => p.classList.add('hidden'));
        const panel = document.getElementById('crud-' + target);
        if (panel) panel.classList.remove('hidden');
    }
});
```
