        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @foreach ($kpis as $kpi)
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5 hover:shadow-md hover:border-gold-500/30 dark:hover:border-gold-500/40 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-2">
                    <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</span>
                    @php $isReservas = str_contains($kpi['icon'], 'calendar-check'); @endphp
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors {{ $isReservas ? 'bg-sapphire-500/10 text-sapphire-600 group-hover:bg-sapphire-500/20' : 'bg-gold-500/10 text-gold-500 group-hover:bg-gold-500/20' }}">
                        <i class="{{ $kpi['icon'] }} text-sm"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1 font-light tracking-tight">{{ $kpi['value'] }}</div>
                <div class="flex items-center gap-1.5 text-sm">
                    <span class="{{ $kpi['positive'] ? 'text-sapphire-600' : 'text-red-500' }}">
                        <i class="fa-solid fa-{{ $kpi['positive'] ? 'arrow-up' : 'arrow-down' }} text-xs mr-0.5"></i>
                        {{ $kpi['change'] }}
                    </span>
                    <span class="text-gray-400 dark:text-gray-500 text-xs">vs. semana anterior</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Access quick cards --}}
        <div class="mb-6">
            <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-base mb-4">Acceso Rápido a Módulos</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @php
                    $quickLinks = [
                        ['section' => 'bcg',       'icon' => 'fa-star',           'label' => 'Matriz BCG',              'color' => 'text-gold-500'],
                        ['section' => 'inventory', 'icon' => 'fa-boxes-stacked',  'label' => 'Previsiones Inventario',  'color' => 'text-blue-500'],
                        ['section' => 'financial', 'icon' => 'fa-chart-line',     'label' => 'Métricas Financieras',    'color' => 'text-sapphire-500'],
                        ['section' => 'occupancy', 'icon' => 'fa-calendar-day',   'label' => 'Ocupación y Demanda',     'color' => 'text-violet-500'],
                        ['section' => 'operations','icon' => 'fa-gears',          'label' => 'Operación y Logística',   'color' => 'text-amber-500'],
                        ['section' => 'team',      'icon' => 'fa-users',          'label' => 'Rendimiento del Equipo',  'color' => 'text-rose-500'],
                    ];
                @endphp
                @foreach ($quickLinks as $ql)
                <a href="#{{ $ql['section'] }}" data-section="{{ $ql['section'] }}"
                   class="nav-section-link flex items-center gap-3 p-4 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-xl hover:shadow-md hover:border-gold-500/30 hover:ring-1 hover:ring-sapphire-500/20 dark:hover:ring-sapphire-500/20 transition-all duration-200 nav-item">
                    <div class="w-10 h-10 rounded-xl bg-sand-100 dark:bg-charcoal-500 flex items-center justify-center {{ $ql['color'] }}">
                        <i class="fa-solid {{ $ql['icon'] }}"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $ql['label'] }}</span>
                    <i class="fa-solid fa-arrow-right text-gray-300 ml-auto text-xs"></i>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Summary row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Ingresos Mensuales</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">Últimos 6 meses</span>
                </div>
                <div class="relative h-72 w-full"><canvas id="monthlyRevenueChart"></canvas></div>
            </div>
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Resumen del Mes</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-sand-50 dark:bg-charcoal-500">
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Total Ingresos</span>
                        <span class="text-gray-900 dark:text-gold-400 font-bold text-lg font-mono">${{ number_format(array_sum(array_column($revenueByType, 'amount'))) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-sand-50 dark:bg-charcoal-500">
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Reservas del Mes</span>
                        <span class="text-gray-900 dark:text-gold-400 font-bold text-lg font-mono">{{ $reservationVolume[2]['count'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-sand-50 dark:bg-charcoal-500">
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Ticket Promedio</span>
                        <span class="text-gray-900 dark:text-gold-400 font-bold text-lg font-mono">${{ number_format($ticketAverages[0]['value']) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-sand-50 dark:bg-charcoal-500">
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Ocupación General</span>
                        <span class="text-sapphire-600 font-bold text-lg font-mono">74%</span>
                    </div>
                </div>
            </div>
        </div>
