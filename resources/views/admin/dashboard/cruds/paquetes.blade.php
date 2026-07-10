        <div id="crud-experiencias" class="crud-panel hidden">
            <div
                class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl overflow-hidden">
                <div
                    class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-charcoal-500">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Experiencias VIP</h3>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <i
                                class="fa-solid fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text"
                                class="search-servicios pl-7 pr-2.5 py-1.5 rounded-lg border border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-xs focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition w-40"
                                placeholder="Buscar...">
                        </div>
                        <div class="relative estado-filter-dropdown">
                            <button
                                class="estado-filter-toggle text-xs px-3 py-1.5 rounded-lg bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500 transition-colors whitespace-nowrap"
                                title="Filtrar por estado">
                                <i class="fa-solid fa-filter mr-1"></i>
                                <span class="estado-filter-label">Todos</span>
                                <i class="fa-solid fa-chevron-down ml-1 text-[10px]"></i>
                            </button>
                            <div
                                class="estado-filter-menu fixed w-44 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-xl shadow-lg z-50 hidden overflow-hidden">
                                <div class="p-1 space-y-0.5">
                                    <button
                                        class="estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg bg-gold-500 text-white"
                                        data-filtro="todos">Todos</button>
                                    <button
                                        class="estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500"
                                        data-filtro="Activo">Activo</button>
                                    <button
                                        class="estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500"
                                        data-filtro="Inactivo">Inactivo</button>
                                </div>
                            </div>
                        </div>
                        <button onclick="openExperienciaModal()"
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
                                <th class="text-left px-5 py-3 font-medium">Nombre</th>
                                <th class="text-left px-5 py-3 font-medium">Descripción</th>
                                <th class="text-right px-5 py-3 font-medium">Precio</th>
                                <th class="text-right px-5 py-3 font-medium">Costo Op.</th>
                                <th class="text-center px-5 py-3 font-medium">Capacidad</th>
                                <th class="text-center px-5 py-3 font-medium">Imágenes</th>
                                <th class="text-center px-5 py-3 font-medium">Estado</th>
                                <th class="text-center px-5 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paquetesEventos as $pe)
                                <tr
                                    class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                    <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">
                                        {{ $pe->Nombre }}</td>
                                    <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-[200px] truncate">
                                        {{ $pe->Descripcion ?? '—' }}</td>
                                    <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-right font-mono">
                                        ${{ number_format($pe->Precio, 2) }}</td>
                                    <td
                                        class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-right font-mono text-sm">
                                        {{ $pe->Costo_Operativo ? '$' . number_format($pe->Costo_Operativo, 2) : '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">
                                        {{ $pe->numero_personas ?? '—' }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        @if (!empty($pe->imagenes))
                                            <div class="flex items-center justify-center gap-1">
                                                @foreach (array_slice($pe->imagenes, 0, 3) as $img)
                                                    <img src="{{ $img }}"
                                                        class="w-8 h-8 rounded object-cover border border-sand-200 dark:border-charcoal-500"
                                                        alt="">
                                                @endforeach
                                                @if (count($pe->imagenes) > 3)
                                                    <span
                                                        class="text-xs text-gray-400 ml-1">+{{ count($pe->imagenes) - 3 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-md border {{ ($pe->Estado ?? 'Inactivo') === 'Activo' ? 'bg-sapphire-50 dark:bg-sapphire-900/20 text-sapphire-600 dark:text-sapphire-400 border-sapphire-200 dark:border-sapphire-800' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700' }}">
                                            {{ $pe->Estado ?? 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <div
                                            class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                            <button
                                                onclick='openExperienciaModal({{ json_encode([
                                                    'id' => $pe->Id,
                                                    'nombre' => $pe->Nombre,
                                                    'descripcion' => $pe->Descripcion,
                                                    'precio' => $pe->Precio,
                                                    'tipo' => $pe->Tipo,
                                                    'lugar' => $pe->Lugar,
                                                    'duracion' => $pe->Duracion,
                                                    'horario' => $pe->Horario,
                                                    'numero_personas' => $pe->numero_personas,
                                                    'costo_operativo' => $pe->Costo_Operativo,
                                                    'productos' => $pe->Productos,
                                                    'ficha_tecnica' => $pe->ficha_tecnica,
                                                    'activo' => ($pe->Estado ?? 'Inactivo') === 'Activo',
                                                    'imagenes' => $pe->imagenes,
                                                ]) }})'
                                                class="hover:text-gold-500 transition-colors"><i
                                                    class="fa-solid fa-pen text-xs"></i></button>
                                            @if (($pe->Estado ?? 'Inactivo') === 'Activo')
                                                <form method="POST"
                                                    action="{{ route('admin.experiencias.destroy', $pe->Id) }}"
                                                    class="inline form-confirm"
                                                    data-message="¿Desactivar esta experiencia?">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-sapphire-500 hover:text-red-500 transition-colors"><i
                                                            class="fa-solid fa-toggle-on text-xs"></i></button>
                                                </form>
                                            @else
                                                <form method="POST"
                                                    action="{{ route('admin.experiencias.activate', $pe->Id) }}"
                                                    class="inline form-confirm"
                                                    data-message="¿Activar esta experiencia?">
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
                                        experiencias registradas. ¡Crea la primera!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Crear/Editar Experiencia --}}
        <div id="experienciaModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div
                class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl">
                <form id="experienciaForm" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <input type="hidden" id="experiencia_id" name="id">
                    <input type="hidden" id="experiencia_method" name="_method" value="POST">

                    <div class="flex items-center justify-between mb-6">
                        <h3 id="experienciaModalTitle" class="text-gray-900 dark:text-gray-100 font-semibold text-base">
                            Nueva Experiencia</h3>
                        <button type="button" onclick="closeExperienciaModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors text-xl leading-none">&times;</button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nombre
                                *</label>
                            <input type="text" id="experiencia_nombre" name="nombre" required maxlength="150"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Descripción</label>
                            <textarea id="experiencia_descripcion" name="descripcion" rows="2" maxlength="1000"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tipo</label>
                                <select id="experiencia_tipo" name="tipo" required
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition cursor-pointer">
                                    <option value="" disabled selected>Selecciona un tipo...</option>
                                    <option value="Spa & Bienestar">Spa & Bienestar</option>
                                    <option value="Talleres & Mixología">Talleres & Mixología</option>
                                    <option value="Gastronomico">Gastronómico</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Lugar</label>
                                <input type="text" id="experiencia_lugar" name="lugar" maxlength="150"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Duración</label>
                                <input type="text" id="experiencia_duracion" name="duracion" maxlength="100"
                                    placeholder="Ej: 2 horas"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Horario</label>
                                <input type="text" id="experiencia_horario" name="horario" maxlength="100"
                                    placeholder="Ej: 18:00"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Precio
                                    *</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                    <input type="number" id="experiencia_precio" name="precio" step="0.01"
                                        min="0" max="999999.99" required
                                        class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Costo
                                    Op.</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                    <input type="number" id="experiencia_costo_operativo" name="costo_operativo"
                                        step="0.01" min="0" max="999999.99"
                                        class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Personas</label>
                                <input type="number" id="experiencia_numero_personas" name="numero_personas"
                                    min="1" max="500"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Productos
                                / Alimentos o Bebidas</label>
                            <textarea id="experiencia_productos" name="productos" rows="2" maxlength="1000"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Botella
                                    incluida</label>
                                <input type="text" id="experiencia_botella" name="botella" maxlength="255"
                                    placeholder="Ej: Casa Madero 2V"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Servicio
                                    extra</label>
                                <input type="text" id="experiencia_servicio_extra" name="servicio_extra"
                                    maxlength="255" placeholder="Ej: Spa 40 minutos"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Imágenes</label>
                            <div class="relative">
                                <input type="file" id="experiencia_imagenes" name="imagenes[]" multiple
                                    accept="image/*"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-sm text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gold-500 file:text-white hover:file:bg-gold-600 transition file:cursor-pointer">
                            </div>
                            <div id="experiencia_preview" class="flex gap-2 mt-2 flex-wrap"></div>
                            <div id="experiencia_imagenes_existentes" class="flex gap-2 mt-2 flex-wrap"></div>
                            <input type="hidden" id="experiencia_imagenes_eliminar" name="imagenes_eliminar"
                                value="">
                            <p class="text-xs text-gray-400 mt-1">Formatos: JPEG, PNG, WebP. Máx 5MB c/u.</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="experiencia_activo" name="activo" value="1" checked
                                    class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-gray-200 dark:bg-charcoal-500 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-gold-500/40 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                                </div>
                            </label>
                            <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Activo</span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-sand-200 dark:border-charcoal-500">
                        <button type="button" onclick="closeExperienciaModal()"
                            class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
                        <button type="submit"
                            class="px-5 py-2 rounded-xl text-sm font-medium bg-gold-500 text-white hover:bg-gold-600 transition-colors">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Confirmación --}}
        <div id="experienciaConfirmModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
            <div
                class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-sm shadow-2xl p-6 text-center">
                <div
                    class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                    <i class="fa-solid fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <p id="experienciaConfirmMessage" class="text-gray-900 dark:text-gray-100 font-medium text-base mb-6">
                </p>
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="cerrarExperienciaConfirmModal()"
                        class="px-5 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
                    <button type="button" id="experienciaConfirmAccept"
                        class="px-5 py-2 rounded-xl text-sm font-medium bg-red-500 text-white hover:bg-red-600 transition-colors">Confirmar</button>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                let experienciaConfirmCallback = null;

                function mostrarExperienciaConfirm(mensaje, cb) {
                    document.getElementById('experienciaConfirmMessage').textContent = mensaje;
                    experienciaConfirmCallback = cb;
                    const modal = document.getElementById('experienciaConfirmModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                function cerrarExperienciaConfirmModal() {
                    const modal = document.getElementById('experienciaConfirmModal');
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    experienciaConfirmCallback = null;
                }

                document.getElementById('experienciaConfirmAccept')?.addEventListener('click', function() {
                    if (experienciaConfirmCallback) {
                        experienciaConfirmCallback();
                        experienciaConfirmCallback = null;
                    }
                    cerrarExperienciaConfirmModal();
                });

                // Interceptar form-confirm para usar modal en lugar de confirm()
                document.querySelectorAll('#crud-experiencias .form-confirm').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const msg = this.getAttribute('data-message') || '¿Confirmar acción?';
                        const self = this;
                        mostrarExperienciaConfirm(msg, function() {
                            self.submit();
                        });
                    });
                });

                // Bloquear 'e', '-', '+' en inputs number
                document.querySelectorAll('#experienciaModal input[type="number"]').forEach(function(input) {
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
                document.querySelectorAll('#experienciaModal textarea, #experienciaModal input[type="text"]').forEach(function(
                    input) {
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

                function openExperienciaModal(data) {
                    const modal = document.getElementById('experienciaModal');
                    const form = document.getElementById('experienciaForm');
                    const title = document.getElementById('experienciaModalTitle');
                    const method = document.getElementById('experiencia_method');

                    const existentes = document.getElementById('experiencia_imagenes_existentes');
                    const eliminarInput = document.getElementById('experiencia_imagenes_eliminar');
                    eliminarInput.value = '';

                    if (data) {
                        title.textContent = 'Editar Experiencia';
                        form.action = '{{ route('admin.experiencias.update', ':id') }}'.replace(':id', data.id);
                        method.value = 'PUT';
                        document.getElementById('experiencia_id').value = data.id;
                        document.getElementById('experiencia_nombre').value = data.nombre || '';
                        document.getElementById('experiencia_descripcion').value = data.descripcion || '';
                        document.getElementById('experiencia_precio').value = data.precio || '';
                        document.getElementById('experiencia_tipo').value = data.tipo || '';
                        document.getElementById('experiencia_lugar').value = data.lugar || '';
                        document.getElementById('experiencia_duracion').value = data.duracion || '';
                        document.getElementById('experiencia_horario').value = data.horario || '';
                        document.getElementById('experiencia_numero_personas').value = data.numero_personas || '';
                        document.getElementById('experiencia_costo_operativo').value = data.costo_operativo || '';
                        document.getElementById('experiencia_productos').value = data.productos || '';
                        document.getElementById('experiencia_activo').checked = data.activo === true;

                        // ficha_tecnica pipe format: "botella|servicio_extra"
                        const ficha = data.ficha_tecnica || '';
                        const partes = ficha.split('|');
                        document.getElementById('experiencia_botella').value = partes[0] || '';
                        document.getElementById('experiencia_servicio_extra').value = partes[1] || '';

                        existentes.innerHTML = '';
                        if (data.imagenes && data.imagenes.length > 0) {
                            data.imagenes.forEach(function(url, index) {
                                const wrapper = document.createElement('div');
                                wrapper.className = 'relative group';
                                wrapper.id = 'exp_img_existente_' + index;
                                wrapper.innerHTML =
                                    '<img src="' + url +
                                    '" class="w-20 h-20 rounded-lg object-cover border border-sand-200 dark:border-charcoal-500">' +
                                    '<button type="button" onclick="expConfirmEliminarImagen(' + index + ', \'' + url
                                    .replace(/'/g, "\\'") +
                                    '\')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 shadow">&times;</button>';
                                existentes.appendChild(wrapper);
                            });
                        }
                    } else {
                        title.textContent = 'Nueva Experiencia';
                        form.action = '{{ route('admin.experiencias.store') }}';
                        method.value = 'POST';
                        document.getElementById('experiencia_id').value = '';
                        document.getElementById('experiencia_nombre').value = '';
                        document.getElementById('experiencia_descripcion').value = '';
                        document.getElementById('experiencia_precio').value = '';
                        document.getElementById('experiencia_tipo').value = '';
                        document.getElementById('experiencia_lugar').value = '';
                        document.getElementById('experiencia_duracion').value = '';
                        document.getElementById('experiencia_horario').value = '';
                        document.getElementById('experiencia_numero_personas').value = '';
                        document.getElementById('experiencia_costo_operativo').value = '';
                        document.getElementById('experiencia_productos').value = '';
                        document.getElementById('experiencia_botella').value = '';
                        document.getElementById('experiencia_servicio_extra').value = '';
                        document.getElementById('experiencia_activo').checked = true;
                        document.getElementById('experiencia_preview').innerHTML = '';
                        existentes.innerHTML = '';
                    }

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                function closeExperienciaModal() {
                    const modal = document.getElementById('experienciaModal');
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }

                function expConfirmEliminarImagen(index, url) {
                    mostrarExperienciaConfirm('¿Eliminar esta imagen?', function() {
                        const wrapper = document.getElementById('exp_img_existente_' + index);
                        if (wrapper) {
                            wrapper.classList.add('opacity-40', 'pointer-events-none');
                            wrapper.querySelector('button')?.remove();
                            const marca = document.createElement('div');
                            marca.className =
                                'absolute inset-0 flex items-center justify-center text-red-500 text-xs font-bold';
                            marca.textContent = 'Eliminada';
                            wrapper.appendChild(marca);
                        }
                        const input = document.getElementById('experiencia_imagenes_eliminar');
                        const urls = input.value ? input.value.split(',') : [];
                        urls.push(url);
                        input.value = urls.join(',');
                    });
                }

                document.getElementById('experiencia_imagenes')?.addEventListener('change', function(e) {
                    const preview = document.getElementById('experiencia_preview');
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
            </script>
        @endpush
