        <div id="crud-cenas" class="crud-panel">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-charcoal-500">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Cenas Especiales</h3>
                    <button onclick="openCenaModal()"
                        class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Nueva
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-500">
                                <th class="text-left px-5 py-3 font-medium">Nombre</th>
                                <th class="text-left px-5 py-3 font-medium">Restaurante</th>
                                <th class="text-left px-5 py-3 font-medium">Menú</th>
                                <th class="text-right px-5 py-3 font-medium">Precio</th>
                                <th class="text-right px-5 py-3 font-medium">Costo Op.</th>
                                <th class="text-center px-5 py-3 font-medium">Imágenes</th>
                                <th class="text-center px-5 py-3 font-medium">Estado</th>
                                <th class="text-center px-5 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cenasEspeciales as $ce)
                            <tr class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $ce->Nombre }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400">{{ $ce->restaurant ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-xs max-w-[220px] truncate">
                                    @php
                                        $menu = array_filter([$ce->Entrada ?? '', $ce->Crema ?? '', $ce->Plato_fuerte ?? '', $ce->Postre ?? '']);
                                    @endphp
                                    {{ !empty($menu) ? implode(' | ', $menu) : '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-right font-mono">${{ number_format($ce->Precio, 2) }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-right font-mono text-sm">{{ $ce->Costo_Operativo ? '$' . number_format($ce->Costo_Operativo, 2) : '—' }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    @if(!empty($ce->imagenes))
                                        <div class="flex items-center justify-center gap-1">
                                            @foreach(array_slice($ce->imagenes, 0, 3) as $img)
                                                <img src="{{ $img }}" class="w-8 h-8 rounded object-cover border border-sand-200 dark:border-charcoal-500" alt="">
                                            @endforeach
                                            @if(count($ce->imagenes) > 3)
                                                <span class="text-xs text-gray-400 ml-1">+{{ count($ce->imagenes) - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ ($ce->Estado ?? 'Inactivo') === 'Activo' ? 'bg-sapphire-50 dark:bg-sapphire-900/20 text-sapphire-600 dark:text-sapphire-400 border-sapphire-200 dark:border-sapphire-800' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700' }}">
                                        {{ $ce->Estado ?? 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button onclick='openCenaModal({{ json_encode([
                                            "id" => $ce->Id,
                                            "nombre" => $ce->Nombre,
                                            "restaurant" => $ce->restaurant,
                                            "entrada" => $ce->Entrada,
                                            "crema" => $ce->Crema,
                                            "plato_fuerte" => $ce->Plato_fuerte,
                                            "postre" => $ce->Postre,
                                            "precio" => $ce->Precio,
                                            "costo_operativo" => $ce->Costo_Operativo,
                                            "numero_personas" => $ce->numero_personas ?? $ce->Numero_Personas,
                                            "ficha_tecnica" => $ce->ficha_tecnica,
                                            "activo" => ($ce->Estado ?? 'Inactivo') === 'Activo',
                                            "imagenes" => $ce->imagenes,
                                        ]) }})'
                                            class="hover:text-gold-500 transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                                        @if (($ce->Estado ?? 'Inactivo') === 'Activo')
                                        <form method="POST" action="{{ route('admin.cenas.destroy', $ce->Id) }}" class="inline form-confirm" data-message="¿Desactivar esta cena?">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-sapphire-500 hover:text-red-500 transition-colors"><i class="fa-solid fa-toggle-on text-xs"></i></button>
                                        </form>
                                        @else
                                        <form method="POST" action="{{ route('admin.cenas.activate', $ce->Id) }}" class="inline form-confirm" data-message="¿Activar esta cena?">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-gray-400 hover:text-sapphire-500 transition-colors"><i class="fa-solid fa-toggle-off text-xs"></i></button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">No hay cenas registradas. ¡Crea la primera!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Crear/Editar Cena --}}
        <div id="cenaModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl">
                <form id="cenaForm" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <input type="hidden" id="cena_id" name="id">
                    <input type="hidden" id="cena_method" name="_method" value="POST">

                    <div class="flex items-center justify-between mb-6">
                        <h3 id="cenaModalTitle" class="text-gray-900 dark:text-gray-100 font-semibold text-base">Nueva Cena</h3>
                        <button type="button" onclick="closeCenaModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors text-xl leading-none">&times;</button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nombre *</label>
                            <input type="text" id="cena_nombre" name="nombre" required maxlength="150"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Restaurante *</label>
                            <div id="cena_restaurante_display" class="hidden flex items-center gap-2">
                                <span id="cena_restaurante_text" class="text-sm text-gray-900 dark:text-gray-100 font-medium"></span>
                                <button type="button" onclick="cenaToggleRestauranteEdit()"
                                    class="text-gold-500 hover:text-gold-600 transition-colors p-1 rounded-lg hover:bg-gold-50 dark:hover:bg-gold-900/20"
                                    title="Cambiar restaurante">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                            </div>
                            <div id="cena_restaurante_edit">
                                <div class="flex gap-2 items-start">
                                    <select id="cena_restaurant" name="restaurant" required
                                        class="flex-1 px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                        <option value="">Seleccionar...</option>
                                        <option value="Terraza Willy's">Terraza Willy's</option>
                                        <option value="Rosewater">Rosewater</option>
                                        <option value="La taqueria">La taqueria</option>
                                        <option value="The Market Cafe">The Market Cafe</option>
                                        <option value="Blue Water">Blue Water</option>
                                        <option value="Jasmine">Jasmine</option>
                                        <option value="Bordeaux">Bordeaux</option>
                                        <option value="The grotto">The grotto</option>
                                        <option value="Portofino">Portofino</option>
                                        <option value="Gazebo">Gazebo</option>
                                    </select>
                                    <button type="button" id="cena_restaurante_cancelar" onclick="cenaCancelRestauranteEdit()"
                                        class="hidden px-3 py-2.5 rounded-xl text-xs font-medium text-gray-500 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors whitespace-nowrap">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Entrada</label>
                                <input type="text" id="cena_entrada" name="entrada" maxlength="255"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Crema</label>
                                <input type="text" id="cena_crema" name="crema" maxlength="255"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Plato fuerte</label>
                                <input type="text" id="cena_plato_fuerte" name="plato_fuerte" maxlength="255"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Postre</label>
                                <input type="text" id="cena_postre" name="postre" maxlength="255"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Precio *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                    <input type="number" id="cena_precio" name="precio" step="0.01" min="0" max="999999.99" required
                                        class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Costo Op.</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                    <input type="number" id="cena_costo_operativo" name="costo_operativo" step="0.01" min="0" max="999999.99"
                                        class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Capacidad</label>
                                <input type="number" id="cena_numero_personas" name="numero_personas" min="1" max="500"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Botella</label>
                            <textarea id="cena_ficha_tecnica" name="ficha_tecnica" rows="2" maxlength="1000"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Imágenes</label>
                            <div class="relative">
                                <input type="file" id="cena_imagenes" name="imagenes[]" multiple accept="image/*"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-sm text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gold-500 file:text-white hover:file:bg-gold-600 transition file:cursor-pointer">
                            </div>
                            <div id="cena_preview" class="flex gap-2 mt-2 flex-wrap"></div>
                            <div id="cena_imagenes_existentes" class="flex gap-2 mt-2 flex-wrap"></div>
                            <input type="hidden" id="cena_imagenes_eliminar" name="imagenes_eliminar" value="">
                            <p class="text-xs text-gray-400 mt-1">Formatos: JPEG, PNG, WebP. Máx 5MB c/u.</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="cena_activo" name="activo" value="1" checked class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 dark:bg-charcoal-500 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-gold-500/40 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500"></div>
                            </label>
                            <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Activo</span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-sand-200 dark:border-charcoal-500">
                        <button type="button" onclick="closeCenaModal()"
                            class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
                        <button type="submit"
                            class="px-5 py-2 rounded-xl text-sm font-medium bg-gold-500 text-white hover:bg-gold-600 transition-colors">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Confirmación --}}
        <div id="cenaConfirmModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-sm shadow-2xl p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                    <i class="fa-solid fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <p id="cenaConfirmMessage" class="text-gray-900 dark:text-gray-100 font-medium text-base mb-6"></p>
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="cerrarCenaConfirmModal()"
                        class="px-5 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
                    <button type="button" id="cenaConfirmAccept"
                        class="px-5 py-2 rounded-xl text-sm font-medium bg-red-500 text-white hover:bg-red-600 transition-colors">Confirmar</button>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            let cenaConfirmCallback = null;

            function mostrarCenaConfirm(mensaje, cb) {
                document.getElementById('cenaConfirmMessage').textContent = mensaje;
                cenaConfirmCallback = cb;
                const modal = document.getElementById('cenaConfirmModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function cerrarCenaConfirmModal() {
                const modal = document.getElementById('cenaConfirmModal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                cenaConfirmCallback = null;
            }

            document.getElementById('cenaConfirmAccept')?.addEventListener('click', function() {
                if (cenaConfirmCallback) {
                    cenaConfirmCallback();
                    cenaConfirmCallback = null;
                }
                cerrarCenaConfirmModal();
            });

            // Interceptar form-confirm para usar modal en lugar de confirm()
            document.querySelectorAll('#crud-cenas .form-confirm').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const msg = this.getAttribute('data-message') || '¿Confirmar acción?';
                    const self = this;
                    mostrarCenaConfirm(msg, function() {
                        self.submit();
                    });
                });
            });

            // Bloquear 'e', '-', '+' en inputs number
            document.querySelectorAll('#cenaModal input[type="number"]').forEach(function(input) {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'e' || e.key === 'E' || e.key === '-' || e.key === '+') {
                        e.preventDefault();
                    }
                });
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const text = (e.clipboardData || window.clipboardData).getData('text');
                    const clean = text.replace(/[^0-9.]/g, '');
                    if (clean) { this.value = clean; }
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

            // Limpiar HTML al pegar en textareas y text inputs
            document.querySelectorAll('#cenaModal textarea, #cenaModal input[type="text"]').forEach(function(input) {
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

            let cenaRestauranteOriginal = '';

            function openCenaModal(data) {
                const modal = document.getElementById('cenaModal');
                const form = document.getElementById('cenaForm');
                const title = document.getElementById('cenaModalTitle');
                const method = document.getElementById('cena_method');

                const display = document.getElementById('cena_restaurante_display');
                const edit = document.getElementById('cena_restaurante_edit');
                const cancelBtn = document.getElementById('cena_restaurante_cancelar');
                const select = document.getElementById('cena_restaurant');

                const existentes = document.getElementById('cena_imagenes_existentes');
                const eliminarInput = document.getElementById('cena_imagenes_eliminar');
                eliminarInput.value = '';

                if (data) {
                    title.textContent = 'Editar Cena';
                    form.action = '{{ route('admin.cenas.update', ':id') }}'.replace(':id', data.id);
                    method.value = 'PUT';
                    document.getElementById('cena_id').value = data.id;
                    document.getElementById('cena_nombre').value = data.nombre || '';
                    document.getElementById('cena_entrada').value = data.entrada || '';
                    document.getElementById('cena_crema').value = data.crema || '';
                    document.getElementById('cena_plato_fuerte').value = data.plato_fuerte || '';
                    document.getElementById('cena_postre').value = data.postre || '';
                    document.getElementById('cena_precio').value = data.precio || '';
                    document.getElementById('cena_costo_operativo').value = data.costo_operativo || '';
                    document.getElementById('cena_numero_personas').value = data.numero_personas || '';
                    document.getElementById('cena_ficha_tecnica').value = data.ficha_tecnica || '';
                    document.getElementById('cena_activo').checked = data.activo === true;

                    // Restaurante: mostrar display mode
                    cenaRestauranteOriginal = data.restaurant || '';
                    document.getElementById('cena_restaurante_text').textContent = cenaRestauranteOriginal;
                    select.value = cenaRestauranteOriginal;
                    display.classList.remove('hidden');
                    edit.classList.add('hidden');
                    cancelBtn.classList.add('hidden');

                    existentes.innerHTML = '';
                    if (data.imagenes && data.imagenes.length > 0) {
                        data.imagenes.forEach(function(url, index) {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'relative group';
                            wrapper.id = 'cena_img_existente_' + index;
                            wrapper.innerHTML =
                                '<img src="' + url +
                                '" class="w-20 h-20 rounded-lg object-cover border border-sand-200 dark:border-charcoal-500">' +
                                '<button type="button" onclick="cenaConfirmEliminarImagen(' + index + ', \'' + url.replace(/'/g, "\\'") +
                                '\')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 shadow">&times;</button>';
                            existentes.appendChild(wrapper);
                        });
                    }
                } else {
                    title.textContent = 'Nueva Cena';
                    form.action = '{{ route('admin.cenas.store') }}';
                    method.value = 'POST';
                    document.getElementById('cena_id').value = '';
                    document.getElementById('cena_nombre').value = '';
                    document.getElementById('cena_entrada').value = '';
                    document.getElementById('cena_crema').value = '';
                    document.getElementById('cena_plato_fuerte').value = '';
                    document.getElementById('cena_postre').value = '';
                    document.getElementById('cena_precio').value = '';
                    document.getElementById('cena_costo_operativo').value = '';
                    document.getElementById('cena_numero_personas').value = '';
                    document.getElementById('cena_ficha_tecnica').value = '';
                    document.getElementById('cena_activo').checked = true;
                    document.getElementById('cena_preview').innerHTML = '';
                    existentes.innerHTML = '';

                    // Restaurante: mostrar select directo
                    cenaRestauranteOriginal = '';
                    select.value = '';
                    display.classList.add('hidden');
                    edit.classList.remove('hidden');
                    cancelBtn.classList.add('hidden');
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function cenaToggleRestauranteEdit() {
                const display = document.getElementById('cena_restaurante_display');
                const edit = document.getElementById('cena_restaurante_edit');
                const cancelBtn = document.getElementById('cena_restaurante_cancelar');
                const select = document.getElementById('cena_restaurant');
                display.classList.add('hidden');
                edit.classList.remove('hidden');
                select.required = true;
                cancelBtn.classList.remove('hidden');
            }

            function cenaCancelRestauranteEdit() {
                const display = document.getElementById('cena_restaurante_display');
                const edit = document.getElementById('cena_restaurante_edit');
                const cancelBtn = document.getElementById('cena_restaurante_cancelar');
                const select = document.getElementById('cena_restaurant');
                select.value = cenaRestauranteOriginal;
                edit.classList.add('hidden');
                cancelBtn.classList.add('hidden');
                display.classList.remove('hidden');
            }

            function closeCenaModal() {
                const modal = document.getElementById('cenaModal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }

            function cenaConfirmEliminarImagen(index, url) {
                mostrarCenaConfirm('¿Eliminar esta imagen?', function() {
                    const wrapper = document.getElementById('cena_img_existente_' + index);
                    if (wrapper) {
                        wrapper.classList.add('opacity-40', 'pointer-events-none');
                        wrapper.querySelector('button')?.remove();
                        const marca = document.createElement('div');
                        marca.className = 'absolute inset-0 flex items-center justify-center text-red-500 text-xs font-bold';
                        marca.textContent = 'Eliminada';
                        wrapper.appendChild(marca);
                    }
                    const input = document.getElementById('cena_imagenes_eliminar');
                    const urls = input.value ? input.value.split(',') : [];
                    urls.push(url);
                    input.value = urls.join(',');
                });
            }

            document.getElementById('cena_imagenes')?.addEventListener('change', function(e) {
                const preview = document.getElementById('cena_preview');
                preview.innerHTML = '';
                for (const file of e.target.files) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const img = document.createElement('img');
                        img.src = ev.target.result;
                        img.className = 'w-16 h-16 rounded-lg object-cover border border-sand-200 dark:border-charcoal-500';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>
        @endpush
