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
                    <span>vs. mes pasado: <span class="text-sapphire-600">{{ $crecimientoMensual >= 0 ? '+' : '' }}{{ $crecimientoMensual }}%</span></span>
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


        {{-- Demand Calendar --}}
        <div class="mb-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Calendario de Demanda — Próximos 30 Días</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-400">Reservas activas por día</span>
                </div>
                @php
                    $demandColors = [1 => 'bg-gold-100 text-gold-700', 2 => 'bg-gold-200 text-gold-700', 3 => 'bg-gold-400 text-white', 4 => 'bg-gold-500 text-white'];
                    $weeks = array_chunk($demandCalendar, 7);
                @endphp
                <div class="space-y-2">
                    @foreach ($weeks as $week)
                    <div class="grid grid-cols-7 gap-1.5">
                        @foreach ($week as $day)
                        <div class="flex flex-col items-center gap-0.5 p-1.5 rounded-lg {{ $demandColors[$day['intensity']] ?? 'bg-sand-50 text-gray-400' }}">
                            <span class="text-[10px] font-medium">{{ $day['dayName'] }}</span>
                            <span class="text-sm font-bold">{{ $day['day'] }}</span>
                            <span class="text-[10px] opacity-75">{{ $day['count'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
                <div class="flex items-center justify-center gap-3 mt-3 text-xs text-gray-400">
                    <span>Baja</span>
                    <div class="flex gap-1">
                        @foreach ([1,2,3,4] as $i)
                        <div class="w-4 h-4 rounded {{ $demandColors[$i] }}"></div>
                        @endforeach
                    </div>
                    <span>Alta</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Tasa de Cancelaciones</h3>
                    <span class="text-2xl font-bold text-red-500 font-mono">{{ $cancellationRate }}%</span>
                </div>
            </div>
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Reincidencia de Huéspedes</h3>
                    <span class="text-2xl font-bold text-gold-500 font-mono">{{ $repeatGuestRate }}%</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Habitaciones que repiten este mes</p>
                <div class="space-y-2">
                    @foreach ($topSpenders as $ts)
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-sand-50 dark:bg-charcoal-500">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-door-open text-gray-400 text-xs"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">#{{ $ts['habitacion'] }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400">{{ $ts['reservations'] }} res.</span>
                            <span class="text-xs font-mono text-gold-600 dark:text-gold-400">${{ number_format($ts['revenue']) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
