<div id="crud-espacios">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
        <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Espacios</h2>
    </div>
    {{-- Tabs fuera del card --}}
    <div class="flex gap-2 mb-4">
        <button class="espacio-tab px-5 py-2 rounded-full text-sm font-medium bg-gold-500 text-white shadow-sm transition-all"
            data-espacio-tipo="Balinesa">Balinesas</button>
        <button
            class="espacio-tab px-5 py-2 rounded-full text-sm font-medium bg-sand-100 dark:bg-charcoal-500 text-gray-500 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500 transition-all"
            data-espacio-tipo="Mesa">Mesas</button>
    </div>

    <div
        class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl overflow-visible">
        {{-- Toolbar (🌟 Corregido: z-20 para asegurar el orden de capas) --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-charcoal-500 relative z-20">
            <div>
                {{-- Filter dropdown --}}
                <div class="relative" id="estadoFilterDropdown">
                    <button id="estadoFilterToggle"
                        class="text-xs px-3 py-1.5 rounded-lg bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500 transition-colors"
                        title="Filtrar por estado">
                        <i class="fa-solid fa-filter mr-1"></i>
                        <span id="estadoFilterLabel">Todos</span>
                        <i class="fa-solid fa-chevron-down ml-1 text-[10px]"></i>
                    </button>
                    {{-- 🌟 CÓDIGO CORREGIDO: Se cambia z-10 por z-50 para que flote sobre la cabecera sticky de la tabla --}}
                    <div id="estadoFilterMenu"
                        class="absolute left-0 mt-1 w-48 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-xl shadow-lg z-50 hidden overflow-hidden">
                        <div class="p-1 space-y-0.5">
                            <button
                                class="estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg bg-gold-500 text-white"
                                data-filtro="all">Todos</button>
                            <button
                                class="estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500"
                                data-filtro="activo">Activo</button>
                            <button
                                class="estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500"
                                data-filtro="inactivo">Inactivo</button>
                        </div>
                    </div>
                </div>
            </div>
            <button id="btn-nuevo-espacio" onclick="openEspacioModal('Balinesa', null, activeZonaFiltro)"
                class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                <i class="fa-solid fa-plus mr-1"></i> <span id="btn-nuevo-texto">Nueva Balinesa</span>
            </button>
        </div>

        {{-- Filtros de Zona (dinámicos según categoría activa) --}}
        <div id="filtros-zona"
            class="flex gap-2 px-5 py-2 border-b border-sand-200 dark:border-charcoal-500 flex-wrap items-center">
            <span
                class="text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wider mr-1">Zona:</span>
            <button class="zona-chip px-3 py-1 rounded-lg text-xs font-medium bg-gold-500 text-white"
                data-grupo="zona" data-filtro="all">Todas</button>
            @foreach ($espacios->where('Tipo', 'Balinesa')->pluck('Zona')->unique()->values() as $zona)
                <button
                    class="zona-chip px-3 py-1 rounded-lg text-xs font-medium bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500"
                    data-grupo="zona" data-tipo="Balinesa"
                    data-filtro="{{ $zona }}">{{ $zona }}</button>
            @endforeach
            @foreach ($espacios->where('Tipo', 'Mesa')->pluck('Zona')->unique()->values() as $zona)
                <button
                    class="zona-chip px-3 py-1 rounded-lg text-xs font-medium bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500"
                    data-grupo="zona" data-tipo="Mesa"
                    data-filtro="{{ $zona }}">{{ $zona }}</button>
            @endforeach
        </div>

        {{-- ========== Panel Balinesas ========== --}}
        <div id="espacios-panel-Balinesa" class="espacio-panel">
            @php $todosB = $espacios->where('Tipo', 'Balinesa'); @endphp
            <div class="overflow-auto max-h-[500px]">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead class="sticky top-0 z-10">
                        <tr
                            class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-500">
                            <th class="text-left px-5 py-3 font-medium">Nombre</th>
                            <th class="text-left px-5 py-3 font-medium">Zona</th>
                            <th class="text-center px-5 py-3 font-medium">Capacidad</th>
                            <th class="text-center px-5 py-3 font-medium">Activo</th>
                            <th class="text-center px-5 py-3 font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($todosB as $e)
                            <tr data-id="{{ $e->Id }}" data-estado="{{ $e->Estado }}" data-activo="{{ $e->Is_Active }}"
                                data-zona="{{ $e->Zona }}"
                                class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $e->Nombre }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400">{{ $e->Zona }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">
                                    {{ $e->Capacidad }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span
                                        class="text-xs px-2 py-0.5 rounded-md {{ $e->Is_Active ? 'bg-sapphire-50 dark:bg-sapphire-900/20 text-sapphire-600 dark:text-sapphire-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500' }}">
                                        {{ $e->Is_Active ? 'Sí' : 'No' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div
                                        class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button onclick="reordenar('espacios', {{ $e->Id }}, 'up')" class="hover:text-gold-500 transition-colors" title="Subir"><i class="fa-solid fa-chevron-up text-xs"></i></button>
                                        <button onclick="reordenar('espacios', {{ $e->Id }}, 'down')" class="hover:text-gold-500 transition-colors" title="Bajar"><i class="fa-solid fa-chevron-down text-xs"></i></button>
                                        <button
                                            onclick='openEspacioModal("{{ $e->Tipo }}", {!! json_encode([
                                                'id' => $e->Id,
                                                'nombre' => $e->Nombre,
                                                'tipo' => $e->Tipo,
                                                'zona' => $e->Zona,
                                                'capacidad' => $e->Capacidad,
                                                'activo' => (bool) $e->Is_Active,
                                            ], JSON_HEX_APOS | JSON_HEX_QUOT) !!})'
                                            class="hover:text-gold-500 transition-colors">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        @if ($e->Is_Active)
                                            <form method="POST"
                                                action="{{ route('admin.espacios.destroy', $e->Id) }}"
                                                class="inline form-confirm"
                                                data-message="¿Desactivar este espacio?">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-sapphire-500 hover:text-red-500 transition-colors"><i
                                                        class="fa-solid fa-toggle-on text-xs"></i></button>
                                            </form>
                                        @else
                                            <form method="POST"
                                                action="{{ route('admin.espacios.activate', $e->Id) }}"
                                                class="inline form-confirm"
                                                data-message="¿Activar este espacio?">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="text-gray-400 hover:text-sapphire-500 transition-colors"><i
                                                        class="fa-solid fa-toggle-off text-xs"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="fila-vacia">
                                <td colspan="5"
                                    class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">No
                                    hay balinesas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========== Panel Mesas ========== --}}
        <div id="espacios-panel-Mesa" class="espacio-panel hidden">
            @php $todosM = $espacios->where('Tipo', 'Mesa'); @endphp
            <div class="overflow-auto max-h-[500px]">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead class="sticky top-0 z-10">
                        <tr
                            class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-500">
                            <th class="text-left px-5 py-3 font-medium">Nombre</th>
                            <th class="text-left px-5 py-3 font-medium">Restaurante</th>
                            <th class="text-center px-5 py-3 font-medium">Capacidad</th>
                            <th class="text-center px-5 py-3 font-medium">Activo</th>
                            <th class="text-center px-5 py-3 font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($todosM as $e)
                            <tr data-id="{{ $e->Id }}" data-estado="{{ $e->Estado }}" data-activo="{{ $e->Is_Active }}"
                                data-zona="{{ $e->Zona }}"
                                class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $e->Nombre }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400">{{ $e->Zona }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">
                                    {{ $e->Capacidad }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span
                                        class="text-xs px-2 py-0.5 rounded-md {{ $e->Is_Active ? 'bg-sapphire-50 dark:bg-sapphire-900/20 text-sapphire-600 dark:text-sapphire-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500' }}">
                                        {{ $e->Is_Active ? 'Sí' : 'No' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div
                                        class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button onclick="reordenar('espacios', {{ $e->Id }}, 'up')" class="hover:text-gold-500 transition-colors" title="Subir"><i class="fa-solid fa-chevron-up text-xs"></i></button>
                                        <button onclick="reordenar('espacios', {{ $e->Id }}, 'down')" class="hover:text-gold-500 transition-colors" title="Bajar"><i class="fa-solid fa-chevron-down text-xs"></i></button>
                                        <button
                                            onclick='openEspacioModal("{{ $e->Tipo }}", {!! json_encode([
                                                'id' => $e->Id,
                                                'nombre' => $e->Nombre,
                                                'tipo' => $e->Tipo,
                                                'zona' => $e->Zona,
                                                'capacidad' => $e->Capacidad,
                                                'activo' => (bool) $e->Is_Active,
                                            ], JSON_HEX_APOS | JSON_HEX_QUOT) !!})'
                                            class="hover:text-gold-500 transition-colors">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        @if ($e->Is_Active)
                                            <form method="POST"
                                                action="{{ route('admin.espacios.destroy', $e->Id) }}"
                                                class="inline form-confirm"
                                                data-message="¿Desactivar esta mesa?">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-sapphire-500 hover:text-red-500 transition-colors"><i
                                                        class="fa-solid fa-toggle-on text-xs"></i></button>
                                            </form>
                                        @else
                                            <form method="POST"
                                                action="{{ route('admin.espacios.activate', $e->Id) }}"
                                                class="inline form-confirm"
                                                data-message="¿Activar esta mesa?">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="text-gray-400 hover:text-sapphire-500 transition-colors"><i
                                                        class="fa-solid fa-toggle-off text-xs"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="fila-vacia">
                                <td colspan="5"
                                    class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">No
                                    hay mesas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Crear/Editar Espacio --}}
<div id="espacioModal"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div
        class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <form id="espacioForm" method="POST" class="p-6"
              onsubmit="if(this.submitting)return false;this.submitting=true;var b=this.querySelector('button[type=submit]');b&&(b.disabled=true,b.innerHTML='<i class=&quot;fa-solid fa-spinner fa-spin mr-1&quot;></i> Guardando...');">
            @csrf
            <input type="hidden" id="espacio_id" name="id">
            <input type="hidden" id="espacio_method" name="_method" value="POST">
            <input type="hidden" id="espacio_tipo" name="tipo">

            <div class="flex items-center justify-between mb-6">
                <h3 id="espacioModalTitle" class="text-gray-900 dark:text-gray-100 font-semibold text-base">
                    Nuevo Espacio</h3>
                <button type="button" onclick="closeEspacioModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors text-xl leading-none">&times;</button>
            </div>

            <div class="space-y-4">
                <div>
                    <label
                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nombre
                        *</label>
                    <input type="text" id="espacio_nombre" name="nombre" required maxlength="50"
                        class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                    @error('nombre')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label
                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Zona
                        / Restaurante *</label>
                    <select id="espacio_zona" name="zona" required
                        class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                    </select>
                    <input type="text" id="espacio_zona_nueva" name="zona_nueva" placeholder="Escribe el nombre de la nueva zona"
                        class="w-full mt-2 px-3 py-2.5 rounded-xl border border-gold-500 dark:border-gold-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 hidden transition">
                    @error('zona')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label
                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Capacidad
                        *</label>
                    <input type="number" id="espacio_capacidad" name="capacidad" required min="1"
                        max="100"
                        class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                    @error('capacidad')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="espacio_activo" name="activo" value="1"
                            class="sr-only peer">
                        <div
                            class="w-10 h-5 bg-gray-200 dark:bg-charcoal-500 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-gold-500/40 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                        </div>
                    </label>
                    <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Activo</span>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-sand-200 dark:border-charcoal-500">
                <button type="button" onclick="closeEspacioModal()"
                    class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
                <button type="submit"
                    class="px-5 py-2 rounded-xl text-sm font-medium bg-gold-500 text-white hover:bg-gold-600 transition-colors">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Confirmación --}}
<div id="espacioConfirmModal"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
    <div
        class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-sm shadow-2xl p-6 text-center">
        <div
            class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
            <i class="fa-solid fa-exclamation-triangle text-red-500 text-xl"></i>
        </div>
        <p id="espacioConfirmMessage" class="text-gray-900 dark:text-gray-100 font-medium text-base mb-6"></p>
        <div class="flex gap-3 justify-center">
            <button type="button" onclick="cerrarEspacioConfirmModal()"
                class="px-5 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
            <button type="button" id="espacioConfirmAccept"
                class="px-5 py-2 rounded-xl text-sm font-medium bg-red-500 text-white hover:bg-red-600 transition-colors">Confirmar</button>
        </div>
    </div>
</div>

@if ($errors->has('nombre') || $errors->has('zona') || $errors->has('capacidad'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('edit_id'))
            openEspacioModal('{{ old('tipo', 'Balinesa') }}', {
                id: {{ session('edit_id') }},
                nombre: '{{ old('nombre', '') }}',
                tipo: '{{ old('tipo', 'Balinesa') }}',
                zona: '{{ old('zona', '') }}',
                capacidad: '{{ old('capacidad', '') }}',
                activo: {{ old('activo', '1') === '1' ? 'true' : 'false' }},
            });
            @else
            openEspacioModal('{{ old('tipo', 'Balinesa') }}', {
                nombre: '{{ old('nombre', '') }}',
                tipo: '{{ old('tipo', 'Balinesa') }}',
                zona: '{{ old('zona', '') }}',
                capacidad: '{{ old('capacidad', '') }}',
                activo: {{ old('activo', '1') === '1' ? 'true' : 'false' }},
            });
            @endif
        });
    </script>
@endif

@push('scripts')
    <script>
        const zonasPorTipo = @json($zonasPorTipo);

        let espacioConfirmCallback = null;

        function mostrarEspacioConfirm(mensaje, cb) {
            document.getElementById('espacioConfirmMessage').textContent = mensaje;
            espacioConfirmCallback = cb;
            const modal = document.getElementById('espacioConfirmModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function cerrarEspacioConfirmModal() {
            const modal = document.getElementById('espacioConfirmModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            espacioConfirmCallback = null;
        }

        document.getElementById('espacioConfirmAccept')?.addEventListener('click', function() {
            if (espacioConfirmCallback) {
                espacioConfirmCallback();
                espacioConfirmCallback = null;
            }
            cerrarEspacioConfirmModal();
        });

        document.querySelectorAll('#crud-espacios .form-confirm').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const msg = this.getAttribute('data-message') || '¿Confirmar acción?';
                const self = this;
                mostrarEspacioConfirm(msg, function() {
                    self.submit();
                });
            });
        });

        let activeEstadoFiltroEsp = 'all';
        let activeZonaFiltro = 'all';

        function aplicarFiltros() {
            const panel = document.querySelector('.espacio-panel:not(.hidden)');
            if (!panel) return;
            const rows = panel.querySelectorAll('tbody tr[data-activo]');
            rows.forEach(function(row) {
                let estadoOk = activeEstadoFiltroEsp === 'all' || row.dataset.activo === (activeEstadoFiltroEsp ===
                    'activo' ? '1' : '0');
                let zonaOk = activeZonaFiltro === 'all' || row.dataset.zona === activeZonaFiltro;
                row.style.display = (estadoOk && zonaOk) ? '' : 'none';
            });
        }

        function mostrarZonas(tipo) {
            document.querySelectorAll('.zona-chip').forEach(function(c) {
                if (c.dataset.tipo) {
                    c.style.display = c.dataset.tipo === tipo ? '' : 'none';
                }
            });
        }

        function resetZonaChips() {
            document.querySelectorAll('.zona-chip').forEach(function(c) {
                c.className = 'zona-chip px-3 py-1 rounded-lg text-xs font-medium bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500';
            });
        }

        function activarChipTodos() {
            const todos = document.querySelector('.zona-chip[data-filtro="all"]');
            if (todos) todos.className = 'zona-chip px-3 py-1 rounded-lg text-xs font-medium bg-gold-500 text-white';
        }

        // Exposed so sidebar nav can switch sub-tabs programmatically
        window.activarSubTabEspacios = function(tipo) {
            const btn = document.querySelector('.espacio-tab[data-espacio-tipo="' + tipo + '"]');
            if (btn) btn.click();
        };

        // Sub-tabs
        document.querySelectorAll('.espacio-tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.espacio-tab').forEach(t => {
                    t.className =
                        'espacio-tab px-5 py-2 rounded-full text-sm font-medium bg-sand-100 dark:bg-charcoal-500 text-gray-500 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500 transition-all';
                });
                this.className =
                    'espacio-tab px-5 py-2 rounded-full text-sm font-medium bg-gold-500 text-white shadow-sm transition-all';
                document.querySelectorAll('.espacio-panel').forEach(p => p.classList.add('hidden'));
                const tipo = this.dataset.espacioTipo;
                const panel = document.getElementById('espacios-panel-' + tipo);
                if (panel) panel.classList.remove('hidden');
                mostrarZonas(tipo);
                // Reset both filters
                activeEstadoFiltroEsp = 'all';
                activeZonaFiltro = 'all';
                document.getElementById('estadoFilterLabel').textContent = 'Todos';
                // Reset dropdown visual state
                document.querySelectorAll('.estado-filter-option').forEach(function(o) {
                    o.className = 'estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500';
                });
                var allOpt = document.querySelector('.estado-filter-option[data-filtro="all"]');
                if (allOpt) allOpt.className = 'estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg bg-gold-500 text-white';
                resetZonaChips();
                activarChipTodos();
                aplicarFiltros();

                // Actualizar botón contextual según pestaña activa
                const btnNuevo = document.getElementById('btn-nuevo-espacio');
                const btnTexto = document.getElementById('btn-nuevo-texto');
                if (tipo === 'Balinesa') {
                    btnNuevo.className = 'text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors';
                    btnTexto.textContent = 'Nueva Balinesa';
                    btnNuevo.setAttribute('onclick', "openEspacioModal('Balinesa', null, activeZonaFiltro)");
                } else {
                    btnNuevo.className = 'text-xs px-3 py-1.5 rounded-lg bg-sapphire-500 text-white font-medium hover:bg-sapphire-600 transition-colors';
                    btnTexto.textContent = 'Nueva Mesa';
                    btnNuevo.setAttribute('onclick', "openEspacioModal('Mesa', null, activeZonaFiltro)");
                }

                // Sincronizar hash con la pestaña activa del panel
                const sectionId = tipo === 'Balinesa' ? 'espacios-balinesas' : 'espacios-mesas';
                history.pushState(null, '', '#' + sectionId);

                // Sincronizar highlight del sidebar
                document.querySelectorAll('.nav-section-link, #dashboard-parent-toggle, #services-parent-toggle, #espacios-parent-toggle')
                    .forEach(el => { el.classList.remove('nav-item-active'); el.classList.add('nav-item'); });
                const activeLink = document.querySelector('.nav-section-link[data-section="' + sectionId + '"]');
                if (activeLink) { activeLink.classList.remove('nav-item'); activeLink.classList.add('nav-item-active'); }
                const espaciosParent = document.getElementById('espacios-parent-toggle');
                if (espaciosParent) { espaciosParent.classList.remove('nav-item'); espaciosParent.classList.add('nav-item-active'); }
            });
        });

        // Estado/Activo dropdown
        document.getElementById('estadoFilterToggle').addEventListener('click', function(e) {
            e.stopPropagation();
            const menu = document.getElementById('estadoFilterMenu');
            menu.classList.toggle('hidden');
        });

        document.querySelectorAll('.estado-filter-option').forEach(function(opt) {
            opt.addEventListener('click', function() {
                const filtro = this.dataset.filtro;
                const labels = {
                    'all': 'Todos',
                    'activo': 'Activo',
                    'inactivo': 'Inactivo'
                };
                document.getElementById('estadoFilterLabel').textContent = labels[filtro] || 'Todos';
                document.getElementById('estadoFilterMenu').classList.add('hidden');
                document.querySelectorAll('.estado-filter-option').forEach(o => {
                    o.className =
                        'estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500';
                });
                this.className =
                    'estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg bg-gold-500 text-white';
                activeEstadoFiltroEsp = filtro;
                aplicarFiltros();
            });
        });

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function() {
            const menu = document.getElementById('estadoFilterMenu');
            if (!menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        });

        // Zona filter chips
        document.querySelectorAll('.zona-chip').forEach(function(chip) {
            chip.addEventListener('click', function() {
                resetZonaChips();
                this.className =
                    'zona-chip px-3 py-1 rounded-lg text-xs font-medium bg-gold-500 text-white';
                activeZonaFiltro = this.dataset.filtro;
                aplicarFiltros();
            });
        });

        // Bloquear 'e', '-', '+' en inputs number
        document.querySelectorAll('#espacioModal input[type="number"]').forEach(function(input) {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'e' || e.key === 'E' || e.key === '-' || e.key === '+') {
                    e.preventDefault();
                }
            });
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const text = (e.clipboardData || window.clipboardData).getData('text');
                const clean = text.replace(/[^0-9]/g, '');
                if (clean) {
                    this.value = clean;
                }
                this.dispatchEvent(new Event('input'));
            });
            const enforceLimits = function() {
                const max = parseFloat(this.max);
                const min = parseFloat(this.min);
                let val = parseFloat(this.value);
                if (this.value !== '' && !isNaN(val)) {
                    if (!isNaN(max) && val > max) this.value = max;
                    if (!isNaN(min) && val < min) this.value = min;
                }
            };
            input.addEventListener('input', enforceLimits);
            input.addEventListener('blur', enforceLimits);
        });

        // Paste sanitizado en text inputs
        document.querySelectorAll('#espacioModal input[type="text"]').forEach(function(input) {
            input.addEventListener('paste', function(e) {
                const text = (e.clipboardData || window.clipboardData).getData('text');
                e.preventDefault();
                const start = this.selectionStart;
                const end = this.selectionEnd;
                this.value = this.value.substring(0, start) + text + this.value.substring(end);
                this.selectionStart = this.selectionEnd = start + text.length;
                this.dispatchEvent(new Event('input'));
            });
        });

        function llenarSelectZona(tipo, selected) {
            const select = document.getElementById('espacio_zona');
            select.innerHTML = '';
            const input = document.getElementById('espacio_zona_nueva');
            const zonas = zonasPorTipo[tipo] || [];
            let found = false;
            zonas.forEach(function(z) {
                const opt = document.createElement('option');
                opt.value = z;
                opt.textContent = z;
                if (z === selected) { opt.selected = true; found = true; }
                select.appendChild(opt);
            });
            if (selected && !found) {
                const opt = document.createElement('option');
                opt.value = selected;
                opt.textContent = selected;
                opt.selected = true;
                select.appendChild(opt);
            }
            const newOpt = document.createElement('option');
            newOpt.value = '__new__';
            newOpt.textContent = '➕ Otra zona...';
            select.appendChild(newOpt);

            input.classList.add('hidden');
            input.value = '';
            select.onchange = function() {
                if (this.value === '__new__') {
                    input.classList.remove('hidden');
                    input.focus();
                } else {
                    input.classList.add('hidden');
                    input.value = '';
                }
            };
        }

        function openEspacioModal(tipo, data, zonaPorDefecto) {
            const modal = document.getElementById('espacioModal');
            const form = document.getElementById('espacioForm');
            const title = document.getElementById('espacioModalTitle');
            const method = document.getElementById('espacio_method');

            document.getElementById('espacio_tipo').value = tipo;

            if (data && data.id) {
                title.textContent = (tipo === 'Balinesa' ? 'Editar Balinesa' : 'Editar Mesa');
                form.action = '{{ route('admin.espacios.update', ':id') }}'.replace(':id', data.id);
                method.value = 'PUT';
                document.getElementById('espacio_id').value = data.id;
                document.getElementById('espacio_nombre').value = data.nombre || '';
                document.getElementById('espacio_capacidad').value = data.capacidad || '';
                document.getElementById('espacio_activo').checked = data.activo === true;
                llenarSelectZona(tipo, data.zona || '');
            } else {
                title.textContent = (tipo === 'Balinesa' ? 'Nueva Balinesa' : 'Nueva Mesa');
                form.action = '{{ route('admin.espacios.store') }}';
                method.value = 'POST';
                document.getElementById('espacio_id').value = '';
                document.getElementById('espacio_nombre').value = '';
                document.getElementById('espacio_capacidad').value = '';
                document.getElementById('espacio_activo').checked = true;
                const zonaInicial = zonaPorDefecto && zonaPorDefecto !== 'all' ? zonaPorDefecto : '';
                llenarSelectZona(tipo, zonaInicial);
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEspacioModal() {
            const modal = document.getElementById('espacioModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        // Initialize zona chips for default active tab (Balinesa)
        document.addEventListener('DOMContentLoaded', function() {
            mostrarZonas('Balinesa');
        });
    </script>
@endpush