        <div id="crud-balinesas" class="crud-panel hidden">
            <div
                class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl overflow-hidden">
                <div
                    class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-charcoal-500">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Camas Balinesas</h3>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fa-solid fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" class="search-servicios pl-7 pr-2.5 py-1.5 rounded-lg border border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-xs focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition w-40" placeholder="Buscar...">
                        </div>
                        <div class="relative estado-filter-dropdown">
                            <button class="estado-filter-toggle text-xs px-3 py-1.5 rounded-lg bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500 transition-colors whitespace-nowrap" title="Filtrar por estado">
                                <i class="fa-solid fa-filter mr-1"></i>
                                <span class="estado-filter-label">Todos</span>
                                <i class="fa-solid fa-chevron-down ml-1 text-[10px]"></i>
                            </button>
                            <div class="estado-filter-menu fixed w-44 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-xl shadow-lg z-50 hidden overflow-hidden">
                                <div class="p-1 space-y-0.5">
                                    <button class="estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg bg-gold-500 text-white" data-filtro="todos">Todos</button>
                                    <button class="estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500" data-filtro="Activo">Activo</button>
                                    <button class="estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500" data-filtro="Inactivo">Inactivo</button>
                                </div>
                            </div>
                        </div>
                        <button onclick="openBalinesaModal()"
                            class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                            <i class="fa-solid fa-plus mr-1"></i> Nueva
                        </button>
                    </div>
                </div>
                <div class="overflow-auto max-h-[500px]">
                        <table class="w-full text-sm whitespace-nowrap">
                            <thead class="sticky top-0 z-10">
                            <tr
                                class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-500">
                                <th class="text-left px-5 py-3.5 font-medium">Nombre</th>
                                <th class="text-left px-5 py-3.5 font-medium">Descripción</th>
                                <th class="text-left px-5 py-3.5 font-medium">Precio </th>
                                <th class="text-center px-5 py-3.5 font-medium">Botella</th>
                                <th class="text-right px-5 py-3.5 font-medium">Costo Op.</th>
                                <th class="text-center px-5 py-3.5 font-medium">Imágenes</th>
                                <th class="text-center px-5 py-3.5 font-medium">Estado</th>
                                <th class="text-center px-5 py-3.5 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($balinesas as $ba)
                                <tr data-id="{{ $ba->Id }}"
                                    class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                    <td class="px-5 py-4 text-gray-900 dark:text-gray-100 font-medium">
                                        {{ $ba->Nombre }}</td>
                                    <td class="px-5 py-4 text-gray-500 dark:text-gray-400 max-w-[180px] truncate">
                                        {{ $ba->Descripcion ?? '—' }}</td>
                                    <td class="px-5 py-4 text-gray-900 dark:text-gray-100 text-right font-mono">
                                        ${{ number_format($ba->Precio, 2) }}</td>
                                    <td
                                        class="px-5 py-4 text-gray-500 dark:text-gray-400 text-center text-sm max-w-[160px] truncate">
                                        {{ $ba->ficha_tecnica ?? '—' }}</td>
                                    <td class="px-5 py-4 text-gray-500 dark:text-gray-400 text-right font-mono text-sm">
                                        ${{ $ba->Costo_Operativo ? number_format($ba->Costo_Operativo, 2) : '—' }}</td>
                                    <td class="px-5 py-4 text-center">
                                        @if (!empty($ba->imagenes))
                                            <div class="flex items-center justify-center gap-1">
                                                @foreach (array_slice($ba->imagenes, 0, 3) as $img)
                                                    <img src="{{ $img }}"
                                                        class="w-8 h-8 rounded object-cover border border-sand-200 dark:border-charcoal-500"
                                                        alt="">
                                                @endforeach
                                                @if (count($ba->imagenes) > 3)
                                                    <span
                                                        class="text-xs text-gray-400 ml-1">+{{ count($ba->imagenes) - 3 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-md border {{ $ba->Estado === 'Activo' ? 'bg-sapphire-50 dark:bg-sapphire-900/20 text-sapphire-600 dark:text-sapphire-400 border-sapphire-200 dark:border-sapphire-800' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700' }}">
                                            {{ $ba->Estado ?? 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div
                                            class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                            <button onclick="reordenar('balinesas', {{ $ba->Id }}, 'up')" class="hover:text-gold-500 transition-colors" title="Subir"><i class="fa-solid fa-chevron-up text-xs"></i></button>
                                            <button onclick="reordenar('balinesas', {{ $ba->Id }}, 'down')" class="hover:text-gold-500 transition-colors" title="Bajar"><i class="fa-solid fa-chevron-down text-xs"></i></button>
                                            <button
                                                onclick='openBalinesaModal({{ json_encode([
                                                    'id' => $ba->Id,
                                                    'nombre' => $ba->Nombre,
                                                    'descripcion' => $ba->Descripcion,
                                                    'precio' => $ba->Precio,
                                                    'capacidad' => $ba->capacidad_maxima,
                                                    'horario' => $ba->Dias,
                                                    'botella_incluida' => $ba->ficha_tecnica,
                                                    'alimentos_bebidas' => $ba->Productos,
                                                    'costo_operativo' => $ba->Costo_Operativo,
                                                    'activo' => $ba->Estado === 'Activo',
                                                    'imagenes' => $ba->imagenes,
                                                ]) }})'
                                                class="hover:text-gold-500 transition-colors">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </button>
                                            @if ($ba->Estado === 'Activo')
                                                <form method="POST"
                                                    action="{{ route('admin.balinesas.destroy', $ba->Id) }}"
                                                    class="inline form-confirm"
                                                    data-message="¿Desactivar esta balinesa?">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-sapphire-500 hover:text-red-500 transition-colors"><i
                                                            class="fa-solid fa-toggle-on text-xs"></i></button>
                                                </form>
                                            @else
                                                <form method="POST"
                                                    action="{{ route('admin.balinesas.activate', $ba->Id) }}"
                                                    class="inline form-confirm" data-message="¿Activar esta balinesa?">
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
                                <tr>
                                    <td colspan="8"
                                        class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">No hay
                                        balinesas registradas. ¡Crea la primera!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Crear/Editar Balinesa --}}
        <div id="balinesaModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div
                class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl">
                <form id="balinesaForm" method="POST" enctype="multipart/form-data" class="p-6"
                      onsubmit="if(this.submitting)return false;this.submitting=true;var b=this.querySelector('button[type=submit]');b&&(b.disabled=true,b.innerHTML='<i class=&quot;fa-solid fa-spinner fa-spin mr-1&quot;></i> Guardando...');">
                    @csrf
                    <input type="hidden" id="balinesa_id" name="id">
                    <input type="hidden" id="balinesa_method" name="_method" value="POST">

                    <div class="flex items-center justify-between mb-6">
                        <h3 id="balinesaModalTitle" class="text-gray-900 dark:text-gray-100 font-semibold text-base">
                            Nueva Balinesa</h3>
                        <button type="button" onclick="closeBalinesaModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors text-xl leading-none">&times;</button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nombre
                                *</label>
                            <input type="text" id="balinesa_nombre" name="nombre" required maxlength="100"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">

                            <p class="validation-msg text-xs text-red-500 mt-1 hidden">Letras, números, espacios y . & -
                                ,</p>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Descripción</label>
                            <textarea id="balinesa_descripcion" name="descripcion" rows="2" maxlength="500"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition resize-none"></textarea>
                            <p class="validation-msg text-xs text-red-500 mt-1 hidden">Letras, números, espacios y . , :
                                ; ! ? ( ) & -</p>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Precio
                                    *</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                    <input type="number" id="balinesa_precio" name="precio" step="0.01"
                                        min="0" max="999999.99" required
                                        class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Capacidad</label>
                                <input type="number" id="balinesa_capacidad" name="capacidad" min="1"
                                    max="20" value="2"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Costo
                                    Operativo</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                    <input type="number" id="balinesa_costo_operativo" name="costo_operativo"
                                        step="0.01" min="0" max="999999.99"
                                        class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Horario</label>
                                <input type="text" id="balinesa_horario" name="horario" maxlength="50"
                                    placeholder="Ej: 10:00 - 18:00 hrs"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                <p class="validation-msg text-xs text-red-500 mt-1 hidden">Letras, números, espacios, :
                                    y -</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Botella
                                    Incluida</label>
                                <input type="text" id="balinesa_botella_incluida" name="botella_incluida"
                                    maxlength="150" placeholder="Ej: 1 botella de Moet & Chandon"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                <p class="validation-msg text-xs text-red-500 mt-1 hidden">Letras, números, espacios y
                                    . & - ,</p>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Alimentos
                                o Bebidas</label>

                            <textarea id="balinesa_alimentos_bebidas" name="alimentos_bebidas" rows="2" maxlength="500"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition resize-none"></textarea>
                            <p class="validation-msg text-xs text-red-500 mt-1 hidden">Letras, números, espacios y . ,
                                : ; ! ? ( ) & -</p>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Imágenes</label>
                            <div class="relative">
                                <input type="file" id="balinesa_imagenes" name="imagenes[]" multiple
                                    accept="image/*"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-sm text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gold-500 file:text-white hover:file:bg-gold-600 transition file:cursor-pointer">
                            </div>
                            <div id="balinesa_preview" class="flex gap-2 mt-2 flex-wrap"></div>
                            <div id="balinesa_imagenes_existentes" class="flex gap-2 mt-2 flex-wrap"></div>
                            <input type="hidden" id="balinesa_imagenes_eliminar" name="imagenes_eliminar"
                                value="">
                            <p class="text-xs text-gray-400 mt-1">Formatos: JPEG, PNG, WebP. Máx 5MB c/u.</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="balinesa_activo" name="activo" value="1" checked
                                    class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-gray-200 dark:bg-charcoal-500 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-gold-500/40 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                                </div>
                            </label>
                            <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Activo</span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-sand-200 dark:border-charcoal-500">
                        <button type="button" onclick="closeBalinesaModal()"
                            class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
                        <button type="submit"
                            class="px-5 py-2 rounded-xl text-sm font-medium bg-gold-500 text-white hover:bg-gold-600 transition-colors">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="confirmModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
            <div
                class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-sm shadow-2xl p-6 text-center">
                <div
                    class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                    <i class="fa-solid fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <p id="confirmMessage" class="text-gray-900 dark:text-gray-100 font-medium text-base mb-6"></p>
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="cerrarConfirmModal()"
                        class="px-5 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
                    <button type="button" id="confirmAccept"
                        class="px-5 py-2 rounded-xl text-sm font-medium bg-red-500 text-white hover:bg-red-600 transition-colors">Confirmar</button>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                let confirmCallback = null;

                function mostrarConfirm(mensaje, cb) {
                    document.getElementById('confirmMessage').textContent = mensaje;
                    confirmCallback = cb;
                    const modal = document.getElementById('confirmModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                function cerrarConfirmModal() {
                    const modal = document.getElementById('confirmModal');
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    confirmCallback = null;
                }

                document.getElementById('confirmAccept')?.addEventListener('click', function() {
                    if (confirmCallback) {
                        confirmCallback();
                        confirmCallback = null;
                    }
                    cerrarConfirmModal();
                });

                // Bloquear 'e', '-', '+' en inputs number y forzar límites
                document.querySelectorAll('input[type="number"]').forEach(function(input) {
                    // Evitar teclas inválidas
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'e' || e.key === 'E' || e.key === '-' || e.key === '+') {
                            e.preventDefault();
                        }
                    });

                    // Limpiar al pegar
                    input.addEventListener('paste', function(e) {
                        e.preventDefault();
                        const text = (e.clipboardData || window.clipboardData).getData('text');
                        const clean = text.replace(/[^0-9.]/g, '');
                        if (clean) {
                            this.value = clean;
                        }
                        this.dispatchEvent(new Event('input'));
                    });

                    // Validar al escribir (en tiempo real) y al perder el foco
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

                function openBalinesaModal(data) {
                    const modal = document.getElementById('balinesaModal');
                    const form = document.getElementById('balinesaForm');
                    const title = document.getElementById('balinesaModalTitle');
                    const method = document.getElementById('balinesa_method');

                    const existentes = document.getElementById('balinesa_imagenes_existentes');
                    const eliminarInput = document.getElementById('balinesa_imagenes_eliminar');
                    eliminarInput.value = '';

                    if (data) {
                        title.textContent = 'Editar Balinesa';
                        form.action = '{{ route('admin.balinesas.update', ':id') }}'.replace(':id', data.id);
                        method.value = 'PUT';
                        document.getElementById('balinesa_id').value = data.id;
                        document.getElementById('balinesa_nombre').value = data.nombre || '';
                        document.getElementById('balinesa_descripcion').value = data.descripcion || '';
                        document.getElementById('balinesa_precio').value = data.precio || '';
                        document.getElementById('balinesa_capacidad').value = data.capacidad || '';
                        document.getElementById('balinesa_horario').value = data.horario || '';
                        document.getElementById('balinesa_botella_incluida').value = data.botella_incluida || '';
                        document.getElementById('balinesa_alimentos_bebidas').value = data.alimentos_bebidas || '';
                        document.getElementById('balinesa_costo_operativo').value = data.costo_operativo || '';
                        document.getElementById('balinesa_activo').checked = data.activo === true;

                        existentes.innerHTML = '';
                        if (data.imagenes && data.imagenes.length > 0) {
                            data.imagenes.forEach(function(url, index) {
                                const wrapper = document.createElement('div');
                                wrapper.className = 'relative group';
                                wrapper.id = 'img_existente_' + index;
                                wrapper.innerHTML =
                                    '<img src="' + url +
                                    '" class="w-20 h-20 rounded-lg object-cover border border-sand-200 dark:border-charcoal-500">' +
                                    '<button type="button" onclick="confirmEliminarImagen(' + index + ', \'' + url.replace(
                                        /'/g, "\\'") +
                                    '\')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 shadow">&times;</button>';
                                existentes.appendChild(wrapper);
                            });
                        }
                    } else {
                        title.textContent = 'Nueva Balinesa';
                        form.action = '{{ route('admin.balinesas.store') }}';
                        method.value = 'POST';
                        document.getElementById('balinesa_id').value = '';
                        document.getElementById('balinesa_nombre').value = '';
                        document.getElementById('balinesa_descripcion').value = '';
                        document.getElementById('balinesa_precio').value = '';
                        document.getElementById('balinesa_capacidad').value = '2';
                        document.getElementById('balinesa_horario').value = '';
                        document.getElementById('balinesa_botella_incluida').value = '';
                        document.getElementById('balinesa_alimentos_bebidas').value = '';
                        document.getElementById('balinesa_costo_operativo').value = '';
                        document.getElementById('balinesa_activo').checked = true;
                        document.getElementById('balinesa_preview').innerHTML = '';
                        existentes.innerHTML = '';
                    }

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                function closeBalinesaModal() {
                    const modal = document.getElementById('balinesaModal');
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }

                function confirmEliminarImagen(index, url) {
                    mostrarConfirm('¿Eliminar esta imagen?', function() {
                        const wrapper = document.getElementById('img_existente_' + index);
                        if (wrapper) {
                            wrapper.classList.add('opacity-40', 'pointer-events-none');
                            wrapper.querySelector('button')?.remove();
                            const marca = document.createElement('div');
                            marca.className =
                                'absolute inset-0 flex items-center justify-center text-red-500 text-xs font-bold';
                            marca.textContent = 'Eliminada';
                            wrapper.appendChild(marca);
                        }
                        const input = document.getElementById('balinesa_imagenes_eliminar');
                        const urls = input.value ? input.value.split(',') : [];
                        urls.push(url);
                        input.value = urls.join(',');
                    });
                }

                document.getElementById('balinesa_imagenes')?.addEventListener('change', function(e) {
                    const preview = document.getElementById('balinesa_preview');
                    preview.innerHTML = '';
                    for (const file of e.target.files) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            const img = document.createElement('img');
                            img.src = ev.target.result;
                            img.className =
                                'w-16 h-16 rounded-lg object-cover border border-sand-200 dark:border-charcoal-500';
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Bloquear 'e', '-', '+' en inputs number
                document.querySelectorAll('input[type="number"]').forEach(function(input) {
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'e' || e.key === 'E' || e.key === '-' || e.key === '+') {
                            e.preventDefault();
                        }
                    });
                    input.addEventListener('paste', function(e) {
                        e.preventDefault();
                        const text = (e.clipboardData || window.clipboardData).getData('text');
                        const clean = text.replace(/[^0-9.]/g, '');
                        if (clean) {
                            this.value = clean;
                        }
                        this.dispatchEvent(new Event('input'));
                    });
                    input.addEventListener('blur', function() {
                        const max = parseFloat(this.max);
                        const min = parseFloat(this.min);
                        let val = parseFloat(this.value);
                        if (this.value !== '' && !isNaN(val)) {
                            if (!isNaN(max) && val > max) this.value = max;
                            if (!isNaN(min) && val < min) this.value = min;
                        }
                    });
                });

                // Limpiar HTML al pegar en textareas y text inputs
                document.querySelectorAll('textarea, input[type="text"]').forEach(function(input) {
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

                // Validación en tiempo real
                const validations = {
                    balinesa_nombre: /^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ0-9\s\.\&\-,]*$/,
                    balinesa_descripcion: /^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ0-9\s\.\,\:\;\!\?\(\)\&\-]*$/,

                    // 🌟 CÓDIGO ACTUALIZADO: Ya acepta texto, números, espacios, dos puntos y guiones
                    balinesa_horario: /^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ0-9\s\.\:\-]*$/,

                    balinesa_botella_incluida: /^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ0-9\s\.\&\-,]*$/,
                    balinesa_alimentos_bebidas: /^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ0-9\s\.\,\:\;\!\?\(\)\&\-]*$/,
                };

                Object.keys(validations).forEach(function(id) {
                    const input = document.getElementById(id);
                    if (!input) return;
                    const msg = input.parentElement.querySelector('.validation-msg');
                    input.addEventListener('input', function() {
                        const regex = validations[id];
                        const original = this.value;
                        const filtered = original.split('').filter(function(ch) {
                            return regex.test(ch) || ch === '';
                        }).join('');
                        if (filtered !== original) {
                            this.value = filtered;
                            if (msg) msg.classList.remove('hidden');
                        } else {
                            if (msg) msg.classList.add('hidden');
                        }
                    });
                });
            </script>
        @endpush
