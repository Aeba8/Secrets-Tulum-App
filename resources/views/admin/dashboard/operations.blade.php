        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Métricas de Operación y Logística</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
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
                <div class="w-14 h-14 rounded-2xl bg-sapphire-100 flex items-center justify-center text-sapphire-600 flex-shrink-0">
                    <i class="fa-solid fa-users text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-0.5">PAX Promedio por Evento</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 font-mono">{{ $avgPax }} <span class="text-base font-normal text-gray-400">personas</span></p>
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
