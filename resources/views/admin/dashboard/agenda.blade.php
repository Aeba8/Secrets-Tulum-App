@php
    $hoyJs = \Carbon\Carbon::today()->format('Y-m-d');
    $mananaJs = \Carbon\Carbon::tomorrow()->format('Y-m-d');
    $semanaInicioJs = \Carbon\Carbon::today()->startOfWeek()->format('Y-m-d');
    $semanaFinJs = \Carbon\Carbon::today()->endOfWeek()->format('Y-m-d');
    $mesFinJs = \Carbon\Carbon::today()->endOfMonth()->format('Y-m-d');
    $agendaMinJs = $inicioConsulta->format('Y-m-d');
    $agendaMaxJs = $finConsulta->format('Y-m-d');
    $mesInicioJs = \Carbon\Carbon::today()->startOfMonth()->format('Y-m-d');
@endphp

<div class="flex items-center gap-3 mb-6">
    <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
    <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Agenda de Reservas</h2>
</div>

{{-- Toolbar --}}
<div class="flex items-center justify-between gap-3 mb-4">
    <div class="flex items-center gap-2 flex-1 flex-wrap">
        {{-- Search --}}
        <div class="relative flex-1 max-w-xs min-w-[160px]">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text"
                class="agenda-search w-full pl-8 pr-3 py-2 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-600 text-gray-900 dark:text-gray-100 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition"
                placeholder="Buscar por servicio, habitación, espacio&hellip;">
        </div>

        {{-- Tipo filter --}}
        <div class="agenda-filter-dropdown relative" data-filter="tipo" data-filtro="all">
            <button
                class="agenda-filter-toggle text-xs px-3 py-2 rounded-lg bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500 transition-colors whitespace-nowrap">
                <i class="fa-solid fa-tag mr-1"></i>
                <span class="agenda-filter-label">Todos</span>
                <i class="fa-solid fa-chevron-down ml-1 text-[10px]"></i>
            </button>
            <div
                class="agenda-filter-menu fixed w-44 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-xl shadow-lg z-50 hidden overflow-hidden">
                <div class="p-1 space-y-0.5">
                    <button
                        class="agenda-filter-option w-full text-left px-3 py-2 text-xs rounded-lg bg-gold-500 text-white"
                        data-filtro="all">Todos</button>
                    <button
                        class="agenda-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500"
                        data-filtro="Balinesa">Balinesas</button>
                    <button
                        class="agenda-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500"
                        data-filtro="CenaEspecial">Cenas Especiales</button>
                    <button
                        class="agenda-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500"
                        data-filtro="Experiencia">Experiencias VIP</button>
                </div>
            </div>
        </div>

        {{-- Estado filter --}}
        <div class="agenda-filter-dropdown relative" data-filter="estado" data-filtro="all">
            <button
                class="agenda-filter-toggle text-xs px-3 py-2 rounded-lg bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500 transition-colors whitespace-nowrap">
                <i class="fa-solid fa-circle mr-1 text-[8px]"></i>
                <span class="agenda-filter-label">Todos</span>
                <i class="fa-solid fa-chevron-down ml-1 text-[10px]"></i>
            </button>
            <div
                class="agenda-filter-menu fixed w-44 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-xl shadow-lg z-50 hidden overflow-hidden">
                <div class="p-1 space-y-0.5">
                    <button
                        class="agenda-filter-option w-full text-left px-3 py-2 text-xs rounded-lg bg-gold-500 text-white"
                        data-filtro="all">Todos</button>
                    <button
                        class="agenda-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500"
                        data-filtro="Confirmado">Confirmado</button>
                    <button
                        class="agenda-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500"
                        data-filtro="Completado">Completado</button>
                    <button
                        class="agenda-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500"
                        data-filtro="Cancelado">Cancelado</button>
                </div>
            </div>
        </div>
    </div>

    <button id="btn-nueva-reserva"
        class="text-xs px-3 py-2 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors whitespace-nowrap">
        <i class="fa-solid fa-plus mr-1"></i> Nueva Reserva
    </button>
</div>

{{-- Period tabs + Calendar --}}
<div class="flex items-center gap-2 mb-6 flex-wrap">
    <div class="flex gap-1 bg-sand-100 dark:bg-charcoal-500 rounded-xl p-1">
        @foreach ($agendaPeriods as $i => $ap)
            <button
                class="agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ $i === 0 ? 'bg-white dark:bg-charcoal-600 text-gray-900 dark:text-gray-100 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 bg-transparent' }}"
                data-period="{{ $ap['key'] }}">
                {{ $ap['label'] }} <span class="font-mono ml-1">({{ $ap['count'] }})</span>
            </button>
        @endforeach
    </div>
    <div class="flex items-center gap-1">
        <input type="date" id="agenda-fecha-desde"
            class="w-[140px] px-3 py-2 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-600 text-gray-900 dark:text-gray-100 text-xs focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition"
            min="{{ $inicioConsulta->format('Y-m-d') }}" max="{{ $finConsulta->format('Y-m-d') }}">
        <span class="text-gray-400 text-xs">—</span>
        <input type="date" id="agenda-fecha-hasta"
            class="w-[140px] px-3 py-2 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-600 text-gray-900 dark:text-gray-100 text-xs focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition"
            min="{{ $inicioConsulta->format('Y-m-d') }}" max="{{ $finConsulta->format('Y-m-d') }}">
        <button id="btn-aplicar-fecha"
            class="text-xs px-3 py-2 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">Aplicar</button>
    </div>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl overflow-hidden">
    <div class="overflow-auto max-h-[500px]">
        <table class="w-full text-sm whitespace-nowrap">
            <thead class="sticky top-0 z-10">
                <tr
                    class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-500">
                    <th class="text-left px-5 py-3 font-medium">Mesa / Lugar</th>
                    <th class="text-left px-5 py-3 font-medium">Servicio</th>
                    <th class="text-center px-5 py-3 font-medium">Tipo</th>
                    <th class="text-left px-5 py-3 font-medium col-restaurante hidden">Restaurante</th>
                    <th class="text-left px-5 py-3 font-medium">Habitación</th>
                    <th class="text-center px-5 py-3 font-medium">Fecha</th>
                    <th class="text-center px-5 py-3 font-medium">Estado</th>
                    <th class="text-center px-5 py-3 font-medium">Colaborador</th>
                    <th class="text-center px-5 py-3 font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agendaReservations as $res)
                    @php
                        $tipoLabel = match ($res->serviciable_type) {
                            'App\Models\Balinesa' => 'Balinesa',
                            'App\Models\CenaEspecial' => 'Cena Especial',
                            'App\Models\Experiencia' => 'Experiencia',
                            default => class_basename($res->serviciable_type),
                        };
                        $tipoKey = match ($res->serviciable_type) {
                            'App\Models\Balinesa' => 'Balinesa',
                            'App\Models\CenaEspecial' => 'CenaEspecial',
                            'App\Models\Experiencia' => 'Experiencia',
                            default => 'other',
                        };
                        $servicioNombre = $res->serviciable?->Nombre ?? '—';
                        $espacioNombre = $res->espacio?->Nombre ?? '—';
                        $estado = $res->Estado ?? '—';
                        $restauranteNombre =
                            $tipoKey === 'CenaEspecial' && $res->serviciable
                                ? $res->serviciable->restaurant ?? '—'
                                : '—';
                        $statusBadge = match ($estado) {
                            'Confirmado'
                                => 'bg-sapphire-50 dark:bg-sapphire-900/20 text-sapphire-600 dark:text-sapphire-400 border-sapphire-200 dark:border-sapphire-800',
                            'Pendiente'
                                => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                            'No-Show'
                                => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800',
                            'Completado'
                                => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                            'Cancelado'
                                => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800',
                            default => 'bg-gray-50 text-gray-500 border-gray-200',
                        };
                    @endphp
                    <tr class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors"
                        data-tipo="{{ $tipoKey }}" data-estado="{{ $estado }}"
                        data-fecha="{{ $res->Dia->format('Y-m-d') }}">
                        <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $espacioNombre }}</td>
                        <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100">{{ $servicioNombre }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <span
                                class="text-xs px-2 py-0.5 rounded-md bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400">{{ $tipoLabel }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-xs col-restaurante hidden">
                            {{ $restauranteNombre }}</td>
                        <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 font-mono text-xs">
                            {{ $res->Habitacion ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300 text-center text-xs">
                            {{ \Carbon\Carbon::parse($res->Dia)->format('d/m/Y') }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <span
                                class="text-xs px-2.5 py-1 rounded-md border font-medium {{ $statusBadge }}">{{ $estado }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-400 dark:text-gray-500 text-center text-xs font-mono">
                            {{ $res->Numero_de_colaborador_vendedor ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                <button onclick='openAgendaModal({!! json_encode(
                                    [
                                        'id' => $res->Id,
                                        'serviciable_type' => $res->serviciable_type,
                                        'serviciable_id' => $res->serviciable_id,
                                        'id_espacio' => $res->id_espacio,
                                        'fecha' => $res->Dia ? \Carbon\Carbon::parse($res->Dia)->format('Y-m-d') : '',
                                        'habitacion' => $res->Habitacion ?? '',
                                        'estado' => $res->Estado ?? '',
                                        'colaborador' => $res->Numero_de_colaborador_vendedor ?? '',
                                        'observaciones' => $res->Observaciones ?? '',
                                    ],
                                    JSON_HEX_APOS | JSON_HEX_QUOT,
                                ) !!})'
                                    class="hover:text-gold-500 transition-colors">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                @if ($estado !== 'Cancelado')
                                    <form method="POST" action="{{ route('admin.agenda.destroy', $res->Id) }}"
                                        class="inline form-agenda-confirm" data-message="¿Cancelar esta reserva?">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-sapphire-500 hover:text-red-500 transition-colors">
                                            <i class="fa-solid fa-toggle-on text-xs"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.agenda.activate', $res->Id) }}"
                                        class="inline form-agenda-confirm" data-message="¿Reactivar esta reserva?">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-gray-400 hover:text-sapphire-500 transition-colors">
                                            <i class="fa-solid fa-toggle-off text-xs"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="fila-vacia">
                        <td colspan="9" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">No
                            hay reservas en este período.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Crear/Editar Reserva --}}
<div id="agendaModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div
        class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <form id="agendaForm" method="POST" class="p-6"
              onsubmit="if(this.submitting)return false;this.submitting=true;var b=this.querySelector('button[type=submit]');b&&(b.disabled=true,b.innerHTML='<i class=&quot;fa-solid fa-spinner fa-spin mr-1&quot;></i> Guardando...');">
            @csrf
            <input type="hidden" id="agenda_id" name="id">
            <input type="hidden" id="agenda_method" name="_method" value="POST">

            <div class="flex items-center justify-between mb-6">
                <h3 id="agendaModalTitle" class="text-gray-900 dark:text-gray-100 font-semibold text-base">Nueva
                    Reserva</h3>
                <button type="button" onclick="closeAgendaModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors text-xl leading-none">&times;</button>
            </div>

            <div class="space-y-4">
                <div>
                    <label
                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tipo
                        de Servicio *</label>
                    <select id="agenda_serviciable_type" name="serviciable_type" required
                        class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                        <option value="">Seleccionar&hellip;</option>
                        <option value="App\Models\Balinesa">Balinesa</option>
                        <option value="App\Models\CenaEspecial">Cena Especial</option>
                        <option value="App\Models\Experiencia">Experiencia VIP</option>
                    </select>
                </div>

                <div>
                    <label
                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Servicio
                        *</label>
                    <select id="agenda_serviciable_id" name="serviciable_id" required
                        class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                        <option value="">Primero selecciona un tipo</option>
                    </select>
                </div>

                <div>
                    <label
                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Espacio</label>
                    <select id="agenda_id_espacio" name="id_espacio"
                        class="w-full px-3 py-2.5 rounded-xl text-sm transition bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 focus:outline-none @error('id_espacio') border-red-500 dark:border-red-500 ring-2 ring-red-500/40 @else border border-sand-200 dark:border-charcoal-500 focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 @enderror">
                        <option value="">Sin espacio</option>
                    </select>
                    @error('id_espacio')
                        <p id="agenda-error-id_espacio" class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Fecha
                            *</label>
                        <input type="date" id="agenda_fecha" name="fecha" required
                            class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Habitación
                            *</label>
                        <input type="text" id="agenda_habitacion" name="habitacion" required maxlength="4"
                            pattern="\d{4}" inputmode="numeric"
                            class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Estado
                            *</label>
                        <select id="agenda_estado" name="estado" required
                            class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                            <option value="Confirmado">Confirmado</option>
                            <option value="Completado">Completado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Colaborador</label>
                        <input type="text" id="agenda_colaborador" name="colaborador" maxlength="6"
                            pattern="\d{6}" inputmode="numeric"
                            class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition">
                    </div>
                </div>

                <div>
                    <label
                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Observaciones</label>
                    <textarea id="agenda_observaciones" name="observaciones" rows="3"
                        class="w-full px-3 py-2.5 rounded-xl border border-sand-200 dark:border-charcoal-500 bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500 transition resize-none @error('observaciones') border-red-500 dark:border-red-500 ring-2 ring-red-500/40 @enderror"></textarea>
                    @error('observaciones')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <div id="agenda-word-count" class="text-xs text-gray-400 dark:text-gray-500 mt-1 text-right">0/100
                        palabras</div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-sand-200 dark:border-charcoal-500">
                <button type="button" onclick="closeAgendaModal()"
                    class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
                <button type="submit"
                    class="px-5 py-2 rounded-xl text-sm font-medium bg-gold-500 text-white hover:bg-gold-600 transition-colors">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Confirmación --}}
<div id="agendaConfirmModal"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
    <div
        class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl w-full max-w-sm shadow-2xl p-6 text-center">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
            <i class="fa-solid fa-exclamation-triangle text-red-500 text-xl"></i>
        </div>
        <p id="agendaConfirmMessage" class="text-gray-900 dark:text-gray-100 font-medium text-base mb-6"></p>
        <div class="flex gap-3 justify-center">
            <button type="button" onclick="closeAgendaConfirmModal()"
                class="px-5 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">Cancelar</button>
            <button type="button" id="agendaConfirmAccept"
                class="px-5 py-2 rounded-xl text-sm font-medium bg-red-500 text-white hover:bg-red-600 transition-colors">Confirmar</button>
        </div>
    </div>
</div>

<script>
    // ── Datos embebidos para selects dependientes ──
    const agendaServicios = {
        'App\\Models\\Balinesa': {!! $balinesas->map(function ($b) {
                return ['id' => $b->Id, 'nombre' => $b->Nombre];
            })->toJson() !!},
        'App\\Models\\CenaEspecial': {!! $cenasEspeciales->map(function ($c) {
                return ['id' => $c->Id, 'nombre' => $c->Nombre, 'restaurant' => $c->restaurant];
            })->toJson() !!},
        'App\\Models\\Experiencia': {!! $paquetesEventos->map(function ($e) {
                return ['id' => $e->Id, 'nombre' => $e->Nombre];
            })->toJson() !!},
    };

    const agendaEspacios = {
        'App\\Models\\Balinesa': {!! $espacios->where('Tipo', 'Balinesa')->where('Is_Active', 1)->map(function ($e) {
                return ['id' => $e->Id, 'nombre' => $e->Nombre];
            })->values()->toJson() !!},
        'App\\Models\\CenaEspecial': {!! $espacios->where('Tipo', 'Mesa')->where('Is_Active', 1)->map(function ($e) {
                return ['id' => $e->Id, 'nombre' => $e->Nombre, 'zona' => $e->Zona];
            })->values()->toJson() !!},
        'App\\Models\\Experiencia': @json($espacios->where('nombre', 'Sin Espacio Físico')->map(fn($e) => ['id' => $e->id, 'nombre' => $e->nombre])->values()),
    };

    // ── Confirm dialog ──
    let agendaConfirmCallback = null;

    function showAgendaConfirm(msg, cb) {
        document.getElementById('agendaConfirmMessage').textContent = msg;
        agendaConfirmCallback = cb;
        const modal = document.getElementById('agendaConfirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeAgendaConfirmModal() {
        const modal = document.getElementById('agendaConfirmModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        agendaConfirmCallback = null;
    }

    document.getElementById('agendaConfirmAccept')?.addEventListener('click', function() {
        if (agendaConfirmCallback) {
            agendaConfirmCallback();
            agendaConfirmCallback = null;
        }
        closeAgendaConfirmModal();
    });

    document.querySelectorAll('#section-agenda .form-agenda-confirm').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const msg = this.getAttribute('data-message') || '¿Confirmar acción?';
            const self = this;
            showAgendaConfirm(msg, function() {
                self.submit();
            });
        });
    });

    // ── Dependent selects ──
    function poblarAgendaServicios(tipo) {
        const sel = document.getElementById('agenda_serviciable_id');
        sel.innerHTML = '<option value="">Seleccionar&hellip;</option>';
        const items = agendaServicios[tipo] || [];
        items.forEach(function(item) {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.nombre;
            sel.appendChild(opt);
        });
    }

    function poblarAgendaEspacios(tipo, restauranteFiltro) {
        const sel = document.getElementById('agenda_id_espacio');
        sel.innerHTML = '<option value="">Sin espacio</option>';
        const items = agendaEspacios[tipo] || [];
        items.forEach(function(item) {
            if (restauranteFiltro && item.zona !== restauranteFiltro) return;
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.nombre;
            sel.appendChild(opt);
        });
        if (items.length === 1) {
            sel.value = items[0].id;
        }
    }

    document.getElementById('agenda_serviciable_type')?.addEventListener('change', function() {
        const tipo = this.value;
        poblarAgendaServicios(tipo);
        poblarAgendaEspacios(tipo);
    });

    document.getElementById('agenda_serviciable_id')?.addEventListener('change', function() {
        const tipo = document.getElementById('agenda_serviciable_type').value;
        if (tipo !== 'App\\Models\\CenaEspecial') return;
        const cenas = agendaServicios['App\\Models\\CenaEspecial'] || [];
        const cena = cenas.find(function(c) {
            return c.id == this.value;
        }.bind(this));
        poblarAgendaEspacios(tipo, cena ? cena.restaurant : null);
    });

    // ── Open / Close modal ──
    function openAgendaModal(data) {
        // ── Limpiar errores de validación previos ──
        const oldError = document.getElementById('agenda-error-id_espacio');
        if (oldError) oldError.remove();
        document.getElementById('agenda_id_espacio').className =
            'w-full px-3 py-2.5 rounded-xl text-sm transition bg-white dark:bg-charcoal-700 text-gray-900 dark:text-gray-100 focus:outline-none border border-sand-200 dark:border-charcoal-500 focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500';

        // Asegurar que 'data' siempre sea un objeto válido para evitar errores si se pasa null
        data = data || {};
        
        const modal = document.getElementById('agendaModal');
        const form = document.getElementById('agendaForm');
        const title = document.getElementById('agendaModalTitle');
        const method = document.getElementById('agenda_method');

        const isEdit = data && data.id;

        if (isEdit) {
            title.textContent = 'Editar Reserva';
            form.action = '{{ route('admin.agenda.update', ':id') }}'.replace(':id', data.id);
            method.value = 'PUT';
            document.getElementById('agenda_id').value = data.id;

            document.getElementById('agenda_serviciable_type').value = data.serviciable_type || '';
            poblarAgendaServicios(data.serviciable_type);
            document.getElementById('agenda_serviciable_id').value = data.serviciable_id || '';
            poblarAgendaEspacios(data.serviciable_type);
            if (data.id_espacio) {
                document.getElementById('agenda_id_espacio').value = data.id_espacio;
            }
            document.getElementById('agenda_fecha').value = data.fecha || '';
            document.getElementById('agenda_habitacion').value = data.habitacion || '';
            document.getElementById('agenda_estado').value = data.estado || 'Confirmado';
            document.getElementById('agenda_colaborador').value = data.colaborador || '';
            document.getElementById('agenda_observaciones').value = data.observaciones || '';
        } else {
            title.textContent = 'Nueva Reserva';
            form.action = '{{ route('admin.agenda.store') }}';
            method.value = 'POST';
            document.getElementById('agenda_id').value = '';

            document.getElementById('agenda_serviciable_type').value = data.serviciable_type || '';
            poblarAgendaServicios(data.serviciable_type || '');
            document.getElementById('agenda_serviciable_id').value = data.serviciable_id || '';
            poblarAgendaEspacios(data.serviciable_type || '');
            document.getElementById('agenda_id_espacio').value = data.id_espacio || '';
            document.getElementById('agenda_fecha').value = data.fecha || '{{ $hoyJs }}';
            document.getElementById('agenda_habitacion').value = data.habitacion || '';
            document.getElementById('agenda_estado').value = data.estado || 'Confirmado';
            document.getElementById('agenda_colaborador').value = data.colaborador || '';
            document.getElementById('agenda_observaciones').value = data.observaciones || '';
        }

        // Disparar manualmente el evento 'input' para que se actualice el contador de palabras al abrirse
        const obsTextarea = document.getElementById('agenda_observaciones');
        if (obsTextarea) {
            obsTextarea.dispatchEvent(new Event('input'));
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeAgendaModal() {
        const modal = document.getElementById('agendaModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // ── Botón Nueva Reserva ──
    document.getElementById('btn-nueva-reserva')?.addEventListener('click', function() {
        openAgendaModal(null);
    });

    // ── Filtros de agenda ──
    let activePeriodoAgenda = 'today';
    let activeTipoFiltro = 'all';
    let activeEstadoFiltro = 'all';

    const hoyAgenda = '{{ $hoyJs }}';
    const mananaAgenda = '{{ $mananaJs }}';
    const semanaInicioAgenda = '{{ $semanaInicioJs }}';
    const semanaFinAgenda = '{{ $semanaFinJs }}';
    const mesFinAgenda = '{{ $mesFinJs }}';
    const agendaMinFecha = '{{ $agendaMinJs }}';
    const agendaMaxFecha = '{{ $agendaMaxJs }}';
    const mesInicioAgenda = '{{ $mesInicioJs }}';

    // Initial: set date range to this week + filter
    document.getElementById('agenda-fecha-desde').value = hoyAgenda;
    document.getElementById('agenda-fecha-hasta').value = semanaFinAgenda;
    aplicarFiltrosAgenda();

    // ── Dropdown filter (tipo) toggle & select ──
    document.addEventListener('click', function(e) {
        const toggle = e.target.closest('.agenda-filter-toggle');
        if (toggle) {
            e.stopPropagation();
            const menu = toggle.parentElement.querySelector('.agenda-filter-menu');
            if (menu) {
                document.querySelectorAll('.agenda-filter-menu:not(.hidden)').forEach(function(m) {
                    if (m !== menu) m.classList.add('hidden');
                });
                const rect = toggle.getBoundingClientRect();
                menu.style.top = (rect.bottom + 4) + 'px';
                menu.style.left = rect.left + 'px';
                menu.style.minWidth = rect.width + 'px';
                menu.classList.toggle('hidden');
            }
            return;
        }

        const option = e.target.closest('.agenda-filter-option');
        if (option) {
            const menu = option.closest('.agenda-filter-menu');
            const container = menu.closest('.agenda-filter-dropdown');
            const label = container.querySelector('.agenda-filter-label');
            const value = option.dataset.filtro;
            const filterType = container.dataset.filter;

            label.textContent = option.textContent.trim();
            container.dataset.filtro = value;
            menu.classList.add('hidden');

            menu.querySelectorAll('.agenda-filter-option').forEach(function(o) {
                o.className = 'agenda-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500';
            });
            option.className = 'agenda-filter-option w-full text-left px-3 py-2 text-xs rounded-lg bg-gold-500 text-white';

            if (filterType === 'tipo') {
                activeTipoFiltro = value;
                toggleRestauranteColumn(value);
            }
            if (filterType === 'estado') activeEstadoFiltro = value;
            aplicarFiltrosAgenda();
            return;
        }

        document.querySelectorAll('.agenda-filter-menu:not(.hidden)').forEach(function(m) {
            m.classList.add('hidden');
        });
    });

    // ── Period tabs — set range + highlight + auto-filter ──
    document.querySelectorAll('.agenda-period-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.agenda-period-tab').forEach(function(t) {
                t.className = 'agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 bg-transparent';
            });
            this.className = 'agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 bg-white dark:bg-charcoal-600 text-gray-900 dark:text-gray-100 shadow-sm';

            activePeriodoAgenda = this.dataset.period;
            if (activePeriodoAgenda === 'today') {
                document.getElementById('agenda-fecha-desde').value = hoyAgenda;
                document.getElementById('agenda-fecha-hasta').value = hoyAgenda;
            } else if (activePeriodoAgenda === 'tomorrow') {
                document.getElementById('agenda-fecha-desde').value = mananaAgenda;
                document.getElementById('agenda-fecha-hasta').value = mananaAgenda;
            } else if (activePeriodoAgenda === 'week') {
                document.getElementById('agenda-fecha-desde').value = semanaInicioAgenda;
                document.getElementById('agenda-fecha-hasta').value = semanaFinAgenda;
            } else if (activePeriodoAgenda === 'month') {
                document.getElementById('agenda-fecha-desde').value = mesInicioAgenda;
                document.getElementById('agenda-fecha-hasta').value = mesFinAgenda;
            }
            aplicarFiltrosAgenda();
        });
    });

    // ── Date range manual: deselect tabs (no auto-filter) ──
    document.getElementById('agenda-fecha-desde').addEventListener('change', function() {
        document.querySelectorAll('.agenda-period-tab').forEach(function(t) {
            t.className = 'agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 bg-transparent';
        });
    });
    document.getElementById('agenda-fecha-hasta').addEventListener('change', function() {
        document.querySelectorAll('.agenda-period-tab').forEach(function(t) {
            t.className = 'agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 bg-transparent';
        });
    });

    // ── Aplicar button ──
    document.getElementById('btn-aplicar-fecha').addEventListener('click', function() {
        activePeriodoAgenda = 'range';
        aplicarFiltrosAgenda();
    });

    // ── Search input ──
    document.querySelector('.agenda-search')?.addEventListener('input', function() {
        aplicarFiltrosAgenda();
    });

    // ── Main filter function ──
    function aplicarFiltrosAgenda() {
        const rows = document.querySelectorAll('#section-agenda table tbody tr');
        const q = (document.querySelector('.agenda-search')?.value || '').toLowerCase().trim();
        const desde = document.getElementById('agenda-fecha-desde').value;
        const hasta = document.getElementById('agenda-fecha-hasta').value;

        rows.forEach(function(row) {
            if (row.classList.contains('fila-vacia')) return;

            const fecha = row.dataset.fecha || '';
            const tipo = row.dataset.tipo || '';
            const estado = row.dataset.estado || '';

            // Tipo filter
            const tipoOk = activeTipoFiltro === 'all' || tipo === activeTipoFiltro;

            // Date filter
            let fechaOk = false;
            if (activePeriodoAgenda === 'today') {
                fechaOk = fecha === hoyAgenda;
            } else if (activePeriodoAgenda === 'tomorrow') {
                fechaOk = fecha === mananaAgenda;
            } else if (activePeriodoAgenda === 'week') {
                fechaOk = fecha >= semanaInicioAgenda && fecha <= semanaFinAgenda;
            } else if (activePeriodoAgenda === 'month') {
                fechaOk = fecha >= mesInicioAgenda && fecha <= mesFinAgenda;
            } else {
                // 'range' — manual date range
                fechaOk = (!desde || fecha >= desde) && (!hasta || fecha <= hasta);
            }

            // Estado filter
            const estadoOk = activeEstadoFiltro === 'all' || estado === activeEstadoFiltro;

            // Search text
            const textoOk = !q || row.textContent.toLowerCase().includes(q);

            row.style.display = (tipoOk && fechaOk && estadoOk && textoOk) ? '' : 'none';
        });
    }

    // ── Toggle Restaurante column visibility ──
    function toggleRestauranteColumn(tipo) {
        document.querySelectorAll('.col-restaurante').forEach(function(el) {
            el.classList.toggle('hidden', tipo !== 'CenaEspecial');
        });
    }

    // Initial: hide restaurante column (default tipo = 'all')
    toggleRestauranteColumn('all');

    // Handle validation errors: re-open modal safely if errors exist
    @if ($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        @foreach ($errors->get('id_espacio') as $msg)
        showToast('{{ $msg }}', 'error');
        @endforeach
        @foreach ($errors->get('observaciones') as $msg)
        showToast('{{ $msg }}', 'error');
        @endforeach
        
        // Uso de json_encode para pasar variables limpias y con escapes de barras/comillas correctos a JS
        openAgendaModal({
            id: {!! json_encode(old('id')) !!},
            serviciable_type: {!! json_encode(old('serviciable_type')) !!},
            serviciable_id: {!! json_encode(old('serviciable_id')) !!},
            id_espacio: {!! json_encode(old('id_espacio')) !!},
            fecha: {!! json_encode(old('fecha', $hoyJs)) !!},
            habitacion: {!! json_encode(old('habitacion')) !!},
            estado: {!! json_encode(old('estado', 'Confirmado')) !!},
            colaborador: {!! json_encode(old('colaborador')) !!},
            observaciones: {!! json_encode(old('observaciones') ?? '') !!},
        });
    });
    @endif

    // ── Word counter + block at 100 words ──
    const obsTextarea = document.getElementById('agenda_observaciones');

    obsTextarea?.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' || e.key === 'Delete') return;
        if (e.ctrlKey || e.metaKey) return;
        const words = this.value.trim() ? this.value.trim().split(/\s+/) : [];
        if (words.length >= 100) {
            e.preventDefault();
        }
    });

    obsTextarea?.addEventListener('paste', function(e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        const current = this.value.trim() ? this.value.trim().split(/\s+/) : [];
        const incoming = pasted.trim().split(/\s+/);
        const allowed = Math.max(0, 100 - current.length);
        if (allowed > 0) {
            const start = this.selectionStart;
            const end = this.selectionEnd;
            const before = this.value.substring(0, start);
            const after = this.value.substring(end);
            this.value = before + incoming.slice(0, allowed).join(' ') + after;
        }
        this.dispatchEvent(new Event('input'));
    });

    obsTextarea?.addEventListener('input', function() {
        let words = this.value.trim() ? this.value.trim().split(/\s+/) : [];
        if (words.length > 100) {
            words = words.slice(0, 100);
            this.value = words.join(' ');
        }
        const el = document.getElementById('agenda-word-count');
        el.textContent = words.length + '/100 palabras';
        el.className = 'text-xs mt-1 text-right ' + (words.length >= 100 ? 'text-red-500 font-medium' : 'text-gray-400 dark:text-gray-500');
    });
</script>
