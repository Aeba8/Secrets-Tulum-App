        <div id="crud-balinesas" class="crud-panel hidden">
            <div
                class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl overflow-hidden">
                <div
                    class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-charcoal-500">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Camas Balinesas</h3>
                    <button onclick="openBalinesaModal()"
                        class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Nueva
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr
                                class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-500">
                                <th class="text-left px-5 py-3.5 font-medium">Nombre</th>
                                <th class="text-left px-5 py-3.5 font-medium">Descripción</th>
                                <th class="text-right px-5 py-3.5 font-medium">Precio / Día</th>
                                <th class="text-center px-5 py-3.5 font-medium">Capacidad</th>
                                <th class="text-center px-5 py-3.5 font-medium">Horario</th>
                                <th class="text-center px-5 py-3.5 font-medium">Botella</th>
                                <th class="text-right px-5 py-3.5 font-medium">Costo Op.</th>
                                <th class="text-center px-5 py-3.5 font-medium">Imágenes</th>
                                <th class="text-center px-5 py-3.5 font-medium">Estado</th>
                                <th class="text-center px-5 py-3.5 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($balinesas as $ba)
                                <tr
                                    class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                    <td class="px-5 py-4 text-gray-900 dark:text-gray-100 font-medium">
                                        {{ $ba->Nombre }}</td>
                                    <td class="px-5 py-4 text-gray-500 dark:text-gray-400 max-w-[180px] truncate">
                                        {{ $ba->Descripcion ?? '—' }}</td>
                                    <td class="px-5 py-4 text-gray-900 dark:text-gray-100 text-right font-mono">
                                        ${{ number_format($ba->Precio, 2) }}</td>
                                    <td class="px-5 py-4 text-gray-900 dark:text-gray-100 text-center font-mono">
                                        {{ $ba->capacidad_maxima ?? '—' }}</td>
                                    <td
                                        class="px-5 py-4 text-gray-500 dark:text-gray-400 text-center text-sm max-w-[130px] truncate">
                                        {{ $ba->Dias ?? '—' }}</td>
                                    <td
                                        class="px-5 py-4 text-gray-500 dark:text-gray-400 text-center text-sm max-w-[160px] truncate">
                                        {{ $ba->ficha_tecnica ?? '—' }}</td>
                                    <td
                                        class="px-5 py-4 text-gray-500 dark:text-gray-400 text-right font-mono text-sm">
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
                                            {{ $ba->Estado ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div
                                            class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
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
                                                    'activo' => $ba->Is_Active,
                                                ]) }})'
                                                class="hover:text-gold-500 transition-colors">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </button>
                                            @if ($ba->Is_Active)
                                                <form method="POST"
                                                    action="{{ route('admin.balinesas.destroy', $ba->Id) }}"
                                                    class="inline"
                                                    onsubmit="return confirm('¿Desactivar esta balinesa?')">
                                                    @csrf @method('DELETE')
                                                    <button class="hover:text-red-500 transition-colors"><i
                                                            class="fa-solid fa-toggle-on text-xs"></i></button>
                                                </form>
                                            @else
                                                <form method="POST"
                                                    action="{{ route('admin.balinesas.activate', $ba->Id) }}"
                                                    class="inline">
                                                    @csrf @method('PATCH')
                                                    <button class="hover:text-sapphire-500 transition-colors"><i
                                                            class="fa-solid fa-toggle-off text-xs"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10"
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
                <form id="balinesaForm" method="POST" enctype="multipart/form-data" class="p-6">
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
                            <input type="text" id="balinesa_nombre" name="nombre" required
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Descripción</label>
                            <textarea id="balinesa_descripcion" name="descripcion" rows="2"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition resize-none"></textarea>
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
                                        min="0" required
                                        class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Capacidad</label>
                                <input type="number" id="balinesa_capacidad" name="capacidad" min="1"
                                    value="2"
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
                                        step="0.01" min="0"
                                        class="w-full pl-7 pr-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Horario</label>
                                <input type="text" id="balinesa_horario" name="horario"
                                    placeholder="Ej: 10:00 - 18:00 hrs"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Botella
                                    Incluida</label>
                                <input type="text" id="balinesa_botella_incluida" name="botella_incluida"
                                    placeholder="Ej: 1 botella de Moët & Chandon"
                                    class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Alimentos
                                o Bebidas</label>
                            <textarea id="balinesa_alimentos_bebidas" name="alimentos_bebidas" rows="2"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition resize-none"></textarea>
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

        @push('scripts')
            <script>
                function openBalinesaModal(data) {
                    const modal = document.getElementById('balinesaModal');
                    const form = document.getElementById('balinesaForm');
                    const title = document.getElementById('balinesaModalTitle');
                    const method = document.getElementById('balinesa_method');

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
                        document.getElementById('balinesa_activo').checked = data.activo === true || data.activo === 1;
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
                    }

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                function closeBalinesaModal() {
                    const modal = document.getElementById('balinesaModal');
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
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
            </script>
        @endpush
