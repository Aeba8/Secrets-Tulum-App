        <div id="crud-usuarios">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-charcoal-500">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Usuarios Operativos</h3>
                    <button onclick="openUsuarioModal()"
                        class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Nuevo
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-500">
                                <th class="text-left px-5 py-3 font-medium">Nombre</th>
                                <th class="text-left px-5 py-3 font-medium">Email</th>
                                <th class="text-center px-5 py-3 font-medium">Contraseña</th>
                                <th class="text-center px-5 py-3 font-medium">Estado</th>
                                <th class="text-center px-5 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($usuarios as $u)
                            <tr class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $u->Nombre }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400">{{ $u->Email ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-400 dark:text-gray-500 text-center font-mono text-xs">••••••••</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ ($u->Estado ?? 'Activo') === 'Activo' ? 'bg-sapphire-50 dark:bg-sapphire-900/20 text-sapphire-600 dark:text-sapphire-400 border-sapphire-200 dark:border-sapphire-800' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700' }}">
                                        {{ $u->Estado ?? 'Activo' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button onclick='openUsuarioModal({{ json_encode([
                                            "id" => $u->Id,
                                            "nombre" => $u->Nombre,
                                            "email" => $u->Email,
                                            "activo" => ($u->Estado ?? 'Activo') === 'Activo',
                                        ]) }})'
                                            class="hover:text-gold-500 transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                                        @if (($u->Estado ?? 'Activo') === 'Activo')
                                        <form method="POST" action="{{ route('admin.usuarios.destroy', $u->Id) }}" class="inline form-confirm" data-message="¿Desactivar este usuario?">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-sapphire-500 hover:text-red-500 transition-colors"><i class="fa-solid fa-toggle-on text-xs"></i></button>
                                        </form>
                                        @else
                                        <form method="POST" action="{{ route('admin.usuarios.activate', $u->Id) }}" class="inline form-confirm" data-message="¿Activar este usuario?">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-gray-400 hover:text-sapphire-500 transition-colors"><i class="fa-solid fa-toggle-off text-xs"></i></button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">No hay usuarios operativos registrados. ¡Crea el primero!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Crear/Editar Usuario --}}
        <div id="usuarioModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
                <form id="usuarioForm" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" id="usuario_id" name="id">
                    <input type="hidden" id="usuario_method" name="_method" value="POST">

                    <div class="flex items-center justify-between mb-6">
                        <h3 id="usuarioModalTitle" class="text-gray-900 dark:text-gray-100 font-semibold text-base">Nuevo Usuario Operativo</h3>
                        <button type="button" onclick="closeUsuarioModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors text-xl leading-none">&times;</button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nombre *</label>
                            <input type="text" id="usuario_nombre" name="nombre" required maxlength="100" pattern="^[a-zA-ZÀ-ÿ\s\.]+$"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            @error('nombre')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Email *</label>
                            <input type="email" id="usuario_email" name="email" required maxlength="150"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Núm. Colaborador *</label>
                            <input type="text" id="usuario_numero_colaborador" name="numero_colaborador" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" minlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            @error('numero_colaborador')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                            <p id="colaborador_hint" class="text-xs text-gray-400 mt-1">Este número será usado como contraseña para el inicio de sesión.</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="usuario_activo" name="activo" value="1" checked class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 dark:bg-charcoal-500 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-gold-500/40 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500"></div>
                            </label>
                            <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Activo</span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-sand-200 dark:border-charcoal-500">
                        <button type="button" onclick="closeUsuarioModal()"
                            class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
                        <button type="submit"
                            class="px-5 py-2 rounded-xl text-sm font-medium bg-gold-500 text-white hover:bg-gold-600 transition-colors">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Confirmación --}}
        <div id="usuarioConfirmModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-sm shadow-2xl p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                    <i class="fa-solid fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <p id="usuarioConfirmMessage" class="text-gray-900 dark:text-gray-100 font-medium text-base mb-6"></p>
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="cerrarUsuarioConfirmModal()"
                        class="px-5 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
                    <button type="button" id="usuarioConfirmAccept"
                        class="px-5 py-2 rounded-xl text-sm font-medium bg-red-500 text-white hover:bg-red-600 transition-colors">Confirmar</button>
                </div>
            </div>
        </div>

        @if($errors->has('nombre') || $errors->has('email') || $errors->has('numero_colaborador'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('edit_id'))
                openUsuarioModal({
                    id: {{ session('edit_id') }},
                    nombre: '{{ old('nombre', '') }}',
                    email: '{{ old('email', '') }}',
                    activo: {{ old('activo', '1') === '1' ? 'true' : 'false' }},
                });
                @else
                openUsuarioModal({
                    nombre: '{{ old('nombre', '') }}',
                    email: '{{ old('email', '') }}',
                    activo: {{ old('activo', '1') === '1' ? 'true' : 'false' }},
                });
                @endif
            });
        </script>
        @endif

        @push('scripts')
        <script>
            let usuarioConfirmCallback = null;

            function mostrarUsuarioConfirm(mensaje, cb) {
                document.getElementById('usuarioConfirmMessage').textContent = mensaje;
                usuarioConfirmCallback = cb;
                const modal = document.getElementById('usuarioConfirmModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function cerrarUsuarioConfirmModal() {
                const modal = document.getElementById('usuarioConfirmModal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                usuarioConfirmCallback = null;
            }

            document.getElementById('usuarioConfirmAccept')?.addEventListener('click', function() {
                if (usuarioConfirmCallback) {
                    usuarioConfirmCallback();
                    usuarioConfirmCallback = null;
                }
                cerrarUsuarioConfirmModal();
            });

            // Interceptar form-confirm
            document.querySelectorAll('#crud-usuarios .form-confirm').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const msg = this.getAttribute('data-message') || '¿Confirmar acción?';
                    const self = this;
                    mostrarUsuarioConfirm(msg, function() {
                        self.submit();
                    });
                });
            });

            // Bloquear 'e', '-', '+' en inputs number
            document.querySelectorAll('#usuarioModal input[type="number"]').forEach(function(input) {
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

            // Limpiar HTML al pegar en text inputs
            document.querySelectorAll('#usuarioModal input[type="text"], #usuarioModal input[type="email"]').forEach(function(input) {
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

            function openUsuarioModal(data) {
                const modal = document.getElementById('usuarioModal');
                const form = document.getElementById('usuarioForm');
                const title = document.getElementById('usuarioModalTitle');
                const method = document.getElementById('usuario_method');

                if (data) {
                    title.textContent = 'Editar Usuario Operativo';
                    form.action = '{{ route('admin.usuarios.update', ':id') }}'.replace(':id', data.id);
                    method.value = 'PUT';
                    document.getElementById('usuario_id').value = data.id;
                    document.getElementById('usuario_nombre').value = data.nombre || '';
                    document.getElementById('usuario_email').value = data.email || '';
                    document.getElementById('usuario_numero_colaborador').value = '';
                    document.getElementById('usuario_numero_colaborador').required = false;
                    document.getElementById('colaborador_hint').textContent = 'Dejar vacío para mantener el actual.';
                    document.getElementById('usuario_activo').checked = data.activo === true;
                } else {
                    title.textContent = 'Nuevo Usuario Operativo';
                    form.action = '{{ route('admin.usuarios.store') }}';
                    method.value = 'POST';
                    document.getElementById('usuario_id').value = '';
                    document.getElementById('usuario_nombre').value = '';
                    document.getElementById('usuario_email').value = '';
                    document.getElementById('usuario_numero_colaborador').value = '';
                    document.getElementById('usuario_numero_colaborador').required = true;
                    document.getElementById('colaborador_hint').textContent = 'Este número será usado como contraseña para el inicio de sesión.';
                    document.getElementById('usuario_activo').checked = true;
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeUsuarioModal() {
                const modal = document.getElementById('usuarioModal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        </script>
        @endpush
