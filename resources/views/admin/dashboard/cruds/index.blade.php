        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Catálogo — Gestión de Servicios</h2>
        </div>

        {{-- CRUD tabs --}}
        <div class="flex gap-2 mb-4 flex-wrap">
            <button class="crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-gold-500 text-white" data-crud="cenas">Cenas Especiales</button>
            <button class="crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500" data-crud="balinesas">Balinesas</button>
            <button class="crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500" data-crud="experiencias">Experiencias VIP</button>
        </div>

        @include('admin.dashboard.cruds.cenas')
        @include('admin.dashboard.cruds.balinesas')
        @include('admin.dashboard.cruds.paquetes')
