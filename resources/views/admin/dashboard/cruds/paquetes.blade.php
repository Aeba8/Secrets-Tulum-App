        <div id="crud-experiencias" class="crud-panel hidden">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-charcoal-500">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Experiencias VIP</h3>
                    <button onclick="openExperienciaModal()"
                        class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Nueva
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-500">
                                <th class="text-left px-5 py-3 font-medium">Nombre</th>
                                <th class="text-left px-5 py-3 font-medium">Descripción</th>
                                <th class="text-right px-5 py-3 font-medium">Precio</th>
                                <th class="text-center px-5 py-3 font-medium">Tipo</th>
                                <th class="text-center px-5 py-3 font-medium">Imágenes</th>
                                <th class="text-center px-5 py-3 font-medium">Estado</th>
                                <th class="text-center px-5 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paquetesEventos as $pe)
                            <tr class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $pe->Nombre ?? $pe->name }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-[200px] truncate">{{ $pe->Descripcion ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-right font-mono">${{ number_format($pe->Precio ?? $pe->price ?? 0) }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-center text-xs">{{ $pe->Tipo ?? $pe->tipo ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    @if(!empty($pe->imagenes))
                                        <div class="flex items-center justify-center gap-1">
                                            @foreach(array_slice($pe->imagenes, 0, 3) as $img)
                                                <img src="{{ $img }}" class="w-8 h-8 rounded object-cover border border-sand-200 dark:border-charcoal-500" alt="">
                                            @endforeach
                                            @if(count($pe->imagenes) > 3)
                                                <span class="text-xs text-gray-400 ml-1">+{{ count($pe->imagenes) - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ ($pe->Is_Active ?? $pe->is_active) ? 'bg-sapphire-50 dark:bg-sapphire-900/20 text-sapphire-600 dark:text-sapphire-400 border-sapphire-200 dark:border-sapphire-800' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700' }}">
                                        {{ $pe->Estado ?? (($pe->Is_Active ?? $pe->is_active) ? 'Activo' : 'Inactivo') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button onclick='openExperienciaModal({{ json_encode([
                                            "id" => $pe->Id,
                                            "nombre" => $pe->Nombre ?? $pe->name,
                                            "descripcion" => $pe->Descripcion,
                                            "precio" => $pe->Precio ?? $pe->price,
                                            "tipo" => $pe->Tipo ?? $pe->tipo,
                                            "lugar" => $pe->Lugar ?? $pe->lugar,
                                            "duracion" => $pe->Duracion ?? $pe->duracion,
                                            "horario" => $pe->Horario ?? $pe->horario,
                                            "numero_personas" => $pe->Numero_Personas ?? $pe->numero_personas,
                                            "costo_operativo" => $pe->Costo_Operativo ?? $pe->costo_operativo,
                                            "activo" => $pe->Is_Active ?? $pe->is_active,
                                        ]) }})'
                                            class="hover:text-gold-500 transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                                        @if($pe->Is_Active ?? $pe->is_active)
                                        <form method="POST" action="{{ route('admin.experiencias.destroy', $pe->Id) }}" class="inline" onsubmit="return confirm('¿Desactivar esta experiencia?')">
                                            @csrf @method('DELETE')
                                            <button class="hover:text-red-500 transition-colors"><i class="fa-solid fa-toggle-on text-xs"></i></button>
                                        </form>
                                        @else
                                        <form method="POST" action="{{ route('admin.experiencias.activate', $pe->Id) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button class="hover:text-sapphire-500 transition-colors"><i class="fa-solid fa-toggle-off text-xs"></i></button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">No hay experiencias registradas. ¡Crea la primera!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Crear/Editar Experiencia --}}
        <div id="experienciaModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
                <form id="experienciaForm" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <input type="hidden" id="experiencia_id" name="id">
                    <input type="hidden" id="experiencia_method" name="_method" value="POST">

                    <div class="flex items-center justify-between mb-6">
                        <h3 id="experienciaModalTitle" class="text-gray-900 dark:text-gray-100 font-semibold text-base">Nueva Experiencia</h3>
                        <button type="button" onclick="closeExperienciaModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors text-xl leading-none">&times;</button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nombre *</label>
                            <input type="text" id="experiencia_nombre" name="nombre" required
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Descripción</label>
                            <textarea id="experiencia_descripcion" name="descripcion" rows="2"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Precio *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                    <input type="number" id="experiencia_precio" name="precio" step="0.01" min="0" required
                                        class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tipo</label>
                                <input type="text" id="experiencia_tipo" name="tipo" placeholder="Ej: Spa, Gastronomía"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Lugar</label>
                                <input type="text" id="experiencia_lugar" name="lugar"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Duración</label>
                                <input type="text" id="experiencia_duracion" name="duracion" placeholder="Ej: 2 horas"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Horario</label>
                                <input type="text" id="experiencia_horario" name="horario" placeholder="Ej: 18:00"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Personas</label>
                                <input type="number" id="experiencia_numero_personas" name="numero_personas" min="1"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Costo Op.</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                    <input type="number" id="experiencia_costo_operativo" name="costo_operativo" step="0.01" min="0"
                                        class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Imágenes</label>
                            <div class="relative">
                                <input type="file" id="experiencia_imagenes" name="imagenes[]" multiple accept="image/*"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-sm text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gold-500 file:text-white hover:file:bg-gold-600 transition file:cursor-pointer">
                            </div>
                            <div id="experiencia_preview" class="flex gap-2 mt-2 flex-wrap"></div>
                            <p class="text-xs text-gray-400 mt-1">Formatos: JPEG, PNG, WebP. Máx 5MB c/u.</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="experiencia_activo" name="activo" value="1" checked class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 dark:bg-charcoal-500 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-gold-500/40 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500"></div>
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

        @push('scripts')
        <script>
            function openExperienciaModal(data) {
                const modal = document.getElementById('experienciaModal');
                const form = document.getElementById('experienciaForm');
                const title = document.getElementById('experienciaModalTitle');
                const method = document.getElementById('experiencia_method');

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
                    document.getElementById('experiencia_activo').checked = data.activo === true || data.activo === 1;
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
                    document.getElementById('experiencia_activo').checked = true;
                    document.getElementById('experiencia_preview').innerHTML = '';
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeExperienciaModal() {
                const modal = document.getElementById('experienciaModal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }

            document.getElementById('experiencia_imagenes')?.addEventListener('change', function(e) {
                const preview = document.getElementById('experiencia_preview');
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
                    if (clean) { this.value = clean; }
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
            document.querySelectorAll('#experienciaModal textarea, #experienciaModal input[type="text"]').forEach(function(input) {
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
        </script>
        @endpush
