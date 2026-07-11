        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Métricas Financieras y de Rendimiento</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-4">
            <div class="lg:col-span-2 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Ingresos por Tipo</h3>
                <div class="relative h-72 w-full"><canvas id="revenueByTypeChart"></canvas></div>
            </div>

            <div class="lg:col-span-3 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Ticket Promedio</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-3">
                    @foreach ($ticketAverages as $ta)
                    <div class="bg-sand-50 dark:bg-charcoal-500 rounded-xl p-5 text-center hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-gold-500/10 flex items-center justify-center text-gold-500 mx-auto mb-3">
                            <i class="fa-solid {{ $ta['icon'] }}"></i>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ $ta['label'] }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gold-400 font-mono">${{ number_format($ta['value']) }}</p>
                    </div>
                    @endforeach
                </div>
                @if (!empty($ticketByCategory))
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($ticketByCategory as $tc)
                    <div class="bg-sand-50 dark:bg-charcoal-500 rounded-xl p-4 text-center hover:bg-sand-100 dark:hover:bg-charcoal-500 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-gold-500/10 flex items-center justify-center text-gold-500 mx-auto mb-2">
                            <i class="fa-solid {{ $tc['icon'] }}"></i>
                        </div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-0.5">{{ $tc['label'] }}</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-gold-400 font-mono">${{ number_format($tc['value']) }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
                <div class="mt-3 text-xs text-gray-400 text-center">Total ingresos: <span class="text-gray-900 dark:text-gold-400 font-medium">${{ number_format(array_sum(array_column($revenueByType, 'amount'))) }}</span></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-4">
            <div class="lg:col-span-3 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Ingresos Mensuales</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">Últimos 6 meses</span>
                </div>
                <div class="relative h-72 w-full"><canvas id="monthlyRevenueChart2"></canvas></div>
            </div>

            <div class="lg:col-span-2 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Paquetes Más Rentables</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500">
                                <th class="text-left pb-2 font-medium whitespace-nowrap">#</th>
                                <th class="text-left pb-2 font-medium whitespace-nowrap">Paquete</th>
                                <th class="text-left pb-2 font-medium whitespace-nowrap">Tipo</th>
                                <th class="text-right pb-2 font-medium whitespace-nowrap">Ingresos</th>
                                <th class="text-right pb-2 font-medium whitespace-nowrap">Margen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topPackages as $pkg)
                            <tr class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                <td class="py-2.5 text-gray-400 dark:text-gray-500 text-xs whitespace-nowrap">{{ $pkg['position'] }}</td>
                                <td class="py-2.5 text-gray-900 dark:text-gray-100 font-medium whitespace-nowrap">{{ $pkg['name'] }}</td>
                                <td class="py-2.5 whitespace-nowrap">
                                    @php
                                        $badgeColor = match($pkg['type']) {
                                            'Balinesa' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                            'Cena' => 'bg-sapphire-50 dark:bg-sapphire-900/20 text-sapphire-600 dark:text-sapphire-400 border-sapphire-200 dark:border-sapphire-800',
                                            'Experiencia' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800',
                                            default => 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700',
                                        };
                                    @endphp
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $badgeColor }}">{{ $pkg['type'] }}</span>
                                </td>
                                <td class="py-2.5 text-gray-900 dark:text-gold-400 text-right font-mono whitespace-nowrap">${{ number_format($pkg['revenue']) }}</td>
                                <td class="py-2.5 text-right whitespace-nowrap">
                                    <span class="text-sapphire-600 font-mono text-xs whitespace-nowrap">{{ $pkg['margin'] }}%</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Revenue por Día de la Semana</h3>
                <div class="relative h-48 w-full"><canvas id="revenueByDayChart"></canvas></div>
            </div>
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Top 5 por Cantidad de Reservas</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500">
                                <th class="text-left pb-2 font-medium">#</th>
                                <th class="text-left pb-2 font-medium">Paquete</th>
                                <th class="text-left pb-2 font-medium">Tipo</th>
                                <th class="text-right pb-2 font-medium">Reservas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topByCount as $tbc)
                            <tr class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                <td class="py-2.5 text-gray-400 text-xs">{{ $tbc['position'] }}</td>
                                <td class="py-2.5 text-gray-900 dark:text-gray-100 font-medium">{{ $tbc['name'] }}</td>
                                <td class="py-2.5">
                                    <span class="text-xs px-1.5 py-0.5 rounded-md border {{ $tbc['type'] === 'Balinesa' ? 'bg-amber-50 text-amber-600 border-amber-200' : ($tbc['type'] === 'Cena' ? 'bg-sapphire-50 text-sapphire-600 border-sapphire-200' : 'bg-blue-50 text-blue-600 border-blue-200') }}">
                                        {{ $tbc['type'] }}
                                    </span>
                                </td>
                                <td class="py-2.5 text-right font-mono text-gray-900 dark:text-gray-100">{{ $tbc['count'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Evolución Ticket Promedio</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">Últimos 6 meses</span>
                </div>
                <div class="relative h-48 w-full"><canvas id="ticketEvolutionChart"></canvas></div>
            </div>
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Distribución de Márgenes</h3>
                <div class="relative h-48 w-full"><canvas id="marginDistributionChart"></canvas></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Comparativa Semanal</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">Ingresos</span>
                </div>
                <div class="relative h-40 w-full"><canvas id="weeklyComparisonChart"></canvas></div>
            </div>
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-4xl font-light text-gray-900 dark:text-gold-400 mb-1">${{ number_format($weeklyComparison[1]['revenue'] - $weeklyComparison[0]['revenue']) }}</div>
                    <div class="{{ $crecimientoMensual >= 0 ? 'text-sapphire-600' : 'text-red-500' }} text-sm font-medium flex items-center justify-center gap-1">
                        <i class="fa-solid fa-{{ $crecimientoMensual >= 0 ? 'arrow-up' : 'arrow-down' }} text-xs"></i>
                        <span>{{ $crecimientoMensual >= 0 ? '+' : '' }}{{ $crecimientoMensual }}% vs. mes anterior</span>
                    </div>
                    <p class="text-gray-400 text-xs mt-2">Proyección de cierre mensual: <span class="text-gray-900 dark:text-gold-400 font-medium">${{ number_format($proyeccionMensual) }}</span></p>
                </div>
            </div>
        </div>
