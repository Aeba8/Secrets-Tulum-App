        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Métricas de Ocupación y Demanda</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Volumen de Reservas</h3>
                <div class="flex gap-1 mb-4 bg-sand-100 dark:bg-charcoal-500 rounded-xl p-1">
                    @foreach ($reservationVolume as $i => $rv)
                    <button class="period-tab flex-1 py-2 px-3 rounded-lg text-xs font-medium transition-all duration-200 {{ $i === 0 ? 'bg-white dark:bg-charcoal-600 text-gray-900 dark:text-gray-100 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}"
                            data-period="{{ $rv['key'] }}">
                        {{ $rv['period'] }}
                    </button>
                    @endforeach
                </div>
                <div class="space-y-3">
                    @foreach ($reservationVolume as $i => $rv)
                    <div class="period-content flex items-center justify-between p-4 rounded-xl bg-sand-50 dark:bg-charcoal-500 {{ $i === 0 ? '' : 'hidden' }}" data-period="{{ $rv['key'] }}">
                        <div>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Reservas</span>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $rv['period'] }}</div>
                        </div>
                        <span class="text-gray-900 dark:text-gray-100 font-bold text-3xl font-mono">{{ $rv['count'] }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
                    <span>Promedio diario: <span class="text-gray-900 dark:text-gray-100 font-medium">{{ round($reservationVolume[2]['count'] / 30) }}</span></span>
                    <span>vs. mes pasado: <span class="text-sapphire-600">+8%</span></span>
                </div>
            </div>

            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Ocupación por Zona</h3>
                <div class="relative h-72 w-full"><canvas id="occupancyChart"></canvas></div>
            </div>

            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Días de Mayor Demanda</h3>
                <div class="grid grid-cols-7 gap-1.5 mb-4">
                    @php
                        $intensityColors = [
                            1 => 'bg-gold-100 text-gold-600',
                            2 => 'bg-gold-200 text-gold-700',
                            3 => 'bg-gold-300 text-gold-800',
                            4 => 'bg-gold-400 text-white',
                            5 => 'bg-gold-500 text-white',
                        ];
                    @endphp
                    @foreach ($peakDays as $pd)
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $pd['day'] }}</span>
                        <div class="w-full aspect-square rounded-lg flex items-center justify-center text-xs font-bold {{ $intensityColors[$pd['intensity']] ?? 'bg-gold-100 text-gold-600' }}">
                            {{ $pd['intensity'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-400">
                    <span>Baja</span>
                    <div class="flex gap-1">
                        @foreach ([1,2,3,4,5] as $i)
                        <div class="w-4 h-4 rounded {{ $intensityColors[$i] }}"></div>
                        @endforeach
                    </div>
                    <span>Alta</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5 mb-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Horas Pico — Flujo de Solicitudes</h3>
                <span class="text-xs text-gray-400 dark:text-gray-500">Distribución del día</span>
            </div>
            <div class="relative h-40 w-full"><canvas id="peakHoursChart"></canvas></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Tasa de Cancelaciones</h3>
                    <span class="text-2xl font-bold text-red-500 font-mono">{{ $cancellationRate }}%</span>
                </div>
                <div class="space-y-3">
                    @foreach ($cancellationReasons as $cr)
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-700 dark:text-gray-300">{{ $cr['reason'] }}</span>
                            <span class="text-gray-400 font-mono text-xs">{{ $cr['percentage'] }}%</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-sand-100 dark:bg-charcoal-500 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-gold-500 to-gold-400 transition-all duration-500"
                                 style="width: {{ $cr['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-5xl font-bold text-gray-900 dark:text-gray-100 font-mono mb-2">{{ $cancellationRate }}<span class="text-2xl text-gray-400">%</span></div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Tasa de cancelaciones y No-Shows</p>
                    <p class="text-gray-400 text-xs mt-2">Equivale a <span class="text-gray-900 dark:text-gray-100 font-medium">{{ round($reservationVolume[2]['count'] * $cancellationRate / 100) }} reservas</span> este mes</p>
                </div>
            </div>
        </div>
