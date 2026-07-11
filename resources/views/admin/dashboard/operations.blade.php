        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Métricas de Operación y Logística</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gold-100 flex items-center justify-center text-gold-600 flex-shrink-0">
                    <i class="fa-solid fa-clock text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-0.5">Anticipación Promedio</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 font-mono">{{ $avgLeadTime }} <span class="text-base font-normal text-gray-400">días</span></p>
                </div>
            </div>
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                    <i class="fa-solid fa-gauge-high text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-0.5">Alertas Activas</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 font-mono">{{ count($alerts) }} <span class="text-base font-normal text-gray-400">notificaciones</span></p>
                </div>
            </div>
        </div>

        {{-- Space Utilization --}}
        <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Utilización de Espacios (30d)</h3>
                <span class="text-xs text-gray-400 dark:text-gray-400">Top espacios por ocupación</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500">
                            <th class="text-left pb-2 font-medium">#</th>
                            <th class="text-left pb-2 font-medium">Espacio</th>
                            <th class="text-left pb-2 font-medium">Tipo</th>
                            <th class="text-left pb-2 font-medium">Zona</th>
                            <th class="text-right pb-2 font-medium">Días Ocup.</th>
                            <th class="text-right pb-2 font-medium">Utilización</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($spaceUtilization as $i => $su)
                        <tr class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                            <td class="py-2.5 pr-2 text-gray-400 text-xs">{{ $i + 1 }}</td>
                            <td class="py-2.5 pr-2 text-gray-900 dark:text-gray-100 font-medium">{{ $su['nombre'] }}</td>
                            <td class="py-2.5 pr-2">
                                <span class="text-xs px-1.5 py-0.5 rounded-md border {{ $su['tipo'] === 'Balinesa' ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-sapphire-50 text-sapphire-600 border-sapphire-200' }}">
                                    {{ $su['tipo'] }}
                                </span>
                            </td>
                            <td class="py-2.5 pr-2 text-xs text-gray-500">{{ $su['zona'] }}</td>
                            <td class="py-2.5 pr-2 text-right font-mono text-xs text-gray-700 dark:text-gray-300">{{ $su['dias_ocupados'] }}/{{ $su['total_dias'] }}</td>
                            <td class="py-2.5 pr-2 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-20 h-1.5 rounded-full bg-sand-100 dark:bg-charcoal-500 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500 {{ $su['utilizacion'] >= 70 ? 'bg-sapphire-500' : ($su['utilizacion'] >= 40 ? 'bg-gold-500' : 'bg-red-400') }}" style="width: {{ $su['utilizacion'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-mono {{ $su['utilizacion'] >= 70 ? 'text-sapphire-600' : ($su['utilizacion'] >= 40 ? 'text-gold-600' : 'text-red-500') }}">{{ $su['utilizacion'] }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-6 text-center text-gray-400 text-sm">No hay datos de espacios disponibles</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Panel de Alertas Operativas</h3>
                <div class="flex items-center gap-2 text-xs">
                    <span class="inline-block w-2 h-2 rounded-full bg-sapphire-500"></span>
                    <span class="text-gray-500">OK</span>
                    <span class="inline-block w-2 h-2 rounded-full bg-amber-500"></span>
                    <span class="text-gray-500">Atención</span>
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500"></span>
                    <span class="text-gray-500">Crítico</span>
                </div>
            </div>
            <div id="alertsContainer" class="space-y-2">
                @foreach ($alerts as $alert)
                @php
                    $severityStyles = [
                        'danger' => 'border-red-200 bg-red-50',
                        'warning' => 'border-amber-200 bg-amber-50',
                        'info' => 'border-sapphire-200 bg-sapphire-50',
                    ];
                    $style = $severityStyles[$alert['severity']] ?? $severityStyles['info'];
                @endphp
                <div class="alert-card flex items-start gap-3 p-4 rounded-xl border {{ $style }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-gray-900 dark:text-gray-100 text-sm font-medium">{{ $alert['message'] }}</p>
                            <span class="text-[10px] uppercase tracking-wider px-1.5 py-0.5 rounded {{ $style }} font-medium">{{ $alert['zone'] }}</span>
                        </div>
                        <p class="text-gray-500 text-xs mt-0.5">{{ $alert['detail'] }}</p>
                    </div>
                    <button class="alert-dismiss text-gray-300 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
