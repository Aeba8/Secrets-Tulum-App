@extends('admin.layouts.dashboard')

@section('title', 'Dashboard — SecretsPad Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- ═══════════════════════════════════════════ --}}
    {{-- SECTION: GENERAL --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="section-general" class="dashboard-section">

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @foreach ($kpis as $kpi)
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5 hover:shadow-md hover:border-gold-500/30 dark:hover:border-gold-500/40 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-2">
                    <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</span>
                    <div class="w-9 h-9 rounded-xl bg-gold-500/10 flex items-center justify-center text-gold-500 group-hover:bg-gold-500/20 transition-colors">
                        <i class="{{ $kpi['icon'] }} text-sm"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1 font-light tracking-tight">{{ $kpi['value'] }}</div>
                <div class="flex items-center gap-1.5 text-sm">
                    <span class="{{ $kpi['positive'] ? 'text-emerald-600' : 'text-red-500' }}">
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
                        ['section' => 'financial', 'icon' => 'fa-chart-line',     'label' => 'Métricas Financieras',    'color' => 'text-emerald-500'],
                        ['section' => 'occupancy', 'icon' => 'fa-calendar-day',   'label' => 'Ocupación y Demanda',     'color' => 'text-violet-500'],
                        ['section' => 'operations','icon' => 'fa-gears',          'label' => 'Operación y Logística',   'color' => 'text-amber-500'],
                        ['section' => 'team',      'icon' => 'fa-users',          'label' => 'Rendimiento del Equipo',  'color' => 'text-rose-500'],
                    ];
                @endphp
                @foreach ($quickLinks as $ql)
                <a href="#{{ $ql['section'] }}" data-section="{{ $ql['section'] }}"
                   class="nav-section-link flex items-center gap-3 p-4 bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-xl hover:shadow-md hover:border-gold-500/30 transition-all duration-200 nav-item">
                    <div class="w-10 h-10 rounded-xl bg-sand-100 dark:bg-[#1E3327] flex items-center justify-center {{ $ql['color'] }}">
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
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Ingresos Mensuales</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">Últimos 6 meses</span>
                </div>
                <div class="relative h-72 w-full"><canvas id="monthlyRevenueChart"></canvas></div>
            </div>
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Resumen del Mes</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-sand-50 dark:bg-[#1E3327]">
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Total Ingresos</span>
                        <span class="text-gray-900 dark:text-gray-100 font-bold text-lg font-mono">${{ number_format(array_sum(array_column($revenueByType, 'amount'))) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-sand-50 dark:bg-[#1E3327]">
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Reservas del Mes</span>
                        <span class="text-gray-900 dark:text-gray-100 font-bold text-lg font-mono">{{ $reservationVolume[2]['count'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-sand-50 dark:bg-[#1E3327]">
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Ticket Promedio</span>
                        <span class="text-gray-900 dark:text-gray-100 font-bold text-lg font-mono">${{ number_format($ticketAverages[0]['value']) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-sand-50 dark:bg-[#1E3327]">
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Ocupación General</span>
                        <span class="text-emerald-600 font-bold text-lg font-mono">74%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- SECTION: BCG MATRIX --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="section-bcg" class="dashboard-section hidden">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Análisis Estratégico de Catálogo — Matriz BCG</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            @foreach ($bcgQuadrants as $key => $q)
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5 text-center">
                <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center" style="background-color: {{ $q['color'] }}12; color: {{ $q['color'] }};">
                    <i class="fa-solid {{ $q['icon'] }}"></i>
                </div>
                <div class="text-gray-900 dark:text-gray-100 font-semibold text-sm">{{ $q['label'] }}</div>
                <div class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">{{ $q['desc'] }}</div>
                @php $count = count(array_filter($bcgMatrix, fn($b) => $b['quadrant'] === $key)); @endphp
                <div class="text-2xl font-bold mt-1 font-mono" style="color: {{ $q['color'] }};">{{ $count }}</div>
            </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Matriz de Crecimiento vs. Participación</h3>
                <span class="text-xs text-gray-500 dark:text-gray-400">Tamaño del círculo = Revenue total</span>
            </div>
            <div class="relative h-[420px] w-full">
                <canvas id="bcgChart"></canvas>
            </div>
            <div class="flex items-center justify-center gap-6 mt-4 text-xs">
                @foreach ($bcgQuadrants as $q)
                <div class="flex items-center gap-1.5">
                    <span class="inline-block w-3 h-3 rounded-full" style="background-color: {{ $q['color'] }};"></span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $q['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- SECTION: INVENTORY --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="section-inventory" class="dashboard-section hidden">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Previsiones de Inventario Estimado</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-3 bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Insumos Críticos — Stock vs. Reservas (Próximos 7 días)</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-[#2D4A36]">
                                <th class="text-left pb-3 font-medium whitespace-nowrap">Insumo</th>
                                <th class="text-center pb-3 font-medium whitespace-nowrap">Stock Actual</th>
                                <th class="text-center pb-3 font-medium whitespace-nowrap">Reservado</th>
                                <th class="text-center pb-3 font-medium whitespace-nowrap">Estado</th>
                                <th class="text-right pb-3 font-medium whitespace-nowrap">A Ordenar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $statusMap = [
                                'ok' => ['label' => 'OK', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                                'warning' => ['label' => 'Reordenar', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50'],
                                'danger' => ['label' => 'Crítico', 'color' => 'text-red-600', 'bg' => 'bg-red-50'],
                            ];
                            @endphp
                            @foreach ($inventory as $item)
                            @php
                                $st = $statusMap[$item['status']];
                                $toOrder = max(0, ($item['reserved'] + $item['minStock']) - $item['stock']);
                                $maxBar = max($item['stock'], $item['reserved'] + $item['minStock']);
                            @endphp
                            <tr class="border-b border-sand-200 dark:border-[#2D4A36] last:border-0 hover:bg-sand-50 dark:hover:bg-[#1E3327] transition-colors">
                                <td class="py-3 text-gray-900 dark:text-gray-100 font-medium whitespace-nowrap">{{ $item['item'] }}</td>
                                <td class="py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-20 h-1.5 rounded-full bg-sand-100 dark:bg-[#1E3327] overflow-hidden">
                                            <div class="h-full rounded-full bg-gold-500" style="width: {{ ($item['stock'] / $maxBar) * 100 }}%"></div>
                                        </div>
                                        <span class="text-gray-900 dark:text-gray-100 font-mono text-xs">{{ $item['stock'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-20 h-1.5 rounded-full bg-sand-100 dark:bg-[#1E3327] overflow-hidden">
                                            <div class="h-full rounded-full" style="width: {{ ($item['reserved'] / $maxBar) * 100 }}%; background-color: {{ $item['status'] === 'danger' ? '#EF4444' : ($item['status'] === 'warning' ? '#F59E0B' : '#10B981') }};"></div>
                                        </div>
                                        <span class="text-gray-900 dark:text-gray-100 font-mono text-xs">{{ $item['reserved'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-center whitespace-nowrap">
                                    <span class="text-xs px-2 py-0.5 rounded-md {{ $st['bg'] }} {{ $st['color'] }} font-medium">{{ $st['label'] }}</span>
                                </td>
                                <td class="py-3 text-right whitespace-nowrap">
                                    @if ($toOrder > 0)
                                    <span class="text-gray-900 dark:text-gray-100 font-mono font-bold text-sm whitespace-nowrap">{{ $toOrder }} {{ $item['unit'] }}</span>
                                    @else
                                    <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-xs text-gray-400 text-center">
                    Basado en {{ $reservationVolume[1]['count'] }} reservas activas esta semana
                </div>
            </div>

            <div class="lg:col-span-2 bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Stock Actual vs. Requerido</h3>
                <div class="relative h-72 w-full"><canvas id="inventoryChart"></canvas></div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- SECTION: FINANCIAL --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="section-financial" class="dashboard-section hidden">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Métricas Financieras y de Rendimiento</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-4">
            <div class="lg:col-span-2 bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Ingresos por Tipo</h3>
                <div class="relative h-72 w-full"><canvas id="revenueByTypeChart"></canvas></div>
            </div>

            <div class="lg:col-span-3 bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Ticket Promedio</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($ticketAverages as $ta)
                    <div class="bg-sand-50 dark:bg-[#1E3327] rounded-xl p-5 text-center hover:bg-sand-100 dark:hover:bg-[#2D4A36] transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-gold-500/10 flex items-center justify-center text-gold-500 mx-auto mb-3">
                            <i class="fa-solid {{ $ta['icon'] }}"></i>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ $ta['label'] }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 font-mono">${{ number_format($ta['value']) }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 text-xs text-gray-400 text-center">Total ingresos: <span class="text-gray-900 dark:text-gray-100 font-medium">${{ number_format(array_sum(array_column($revenueByType, 'amount'))) }}</span></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-4">
            <div class="lg:col-span-3 bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Ingresos Mensuales</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">Últimos 6 meses</span>
                </div>
                <div class="relative h-72 w-full"><canvas id="monthlyRevenueChart2"></canvas></div>
            </div>

            <div class="lg:col-span-2 bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Paquetes Más Rentables</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-[#2D4A36]">
                                <th class="text-left pb-2 font-medium whitespace-nowrap">#</th>
                                <th class="text-left pb-2 font-medium whitespace-nowrap">Paquete</th>
                                <th class="text-left pb-2 font-medium whitespace-nowrap">Tipo</th>
                                <th class="text-right pb-2 font-medium whitespace-nowrap">Ingresos</th>
                                <th class="text-right pb-2 font-medium whitespace-nowrap">Margen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topPackages as $pkg)
                            <tr class="border-b border-sand-200 dark:border-[#2D4A36] last:border-0 hover:bg-sand-50 dark:hover:bg-[#1E3327] transition-colors">
                                <td class="py-2.5 text-gray-400 dark:text-gray-500 text-xs whitespace-nowrap">{{ $pkg['position'] }}</td>
                                <td class="py-2.5 text-gray-900 dark:text-gray-100 font-medium whitespace-nowrap">{{ $pkg['name'] }}</td>
                                <td class="py-2.5 whitespace-nowrap">
                                    @php
                                        $badgeColor = match($pkg['type']) {
                                            'Balinesa' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                            'Cena' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                            'Experiencia' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800',
                                            default => 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700',
                                        };
                                    @endphp
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $badgeColor }}">{{ $pkg['type'] }}</span>
                                </td>
                                <td class="py-2.5 text-gray-900 text-right font-mono whitespace-nowrap">${{ number_format($pkg['revenue']) }}</td>
                                <td class="py-2.5 text-right whitespace-nowrap">
                                    <span class="text-emerald-600 font-mono text-xs whitespace-nowrap">{{ $pkg['margin'] }}%</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Comparativa Semanal</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">Ingresos</span>
                </div>
                <div class="relative h-40 w-full"><canvas id="weeklyComparisonChart"></canvas></div>
            </div>
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-4xl font-light text-gray-900 dark:text-gray-100 mb-1">${{ number_format($weeklyComparison[1]['revenue'] - $weeklyComparison[0]['revenue']) }}</div>
                    <div class="text-emerald-600 text-sm font-medium flex items-center justify-center gap-1">
                        <i class="fa-solid fa-arrow-up text-xs"></i>
                        <span>{{ round(($weeklyComparison[1]['revenue'] - $weeklyComparison[0]['revenue']) / $weeklyComparison[0]['revenue'] * 100) }}% vs. semana anterior</span>
                    </div>
                    <p class="text-gray-400 text-xs mt-2">Proyección de cierre mensual: <span class="text-gray-900 dark:text-gray-100 font-medium">${{ number_format(312000) }}</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- SECTION: OCCUPANCY --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="section-occupancy" class="dashboard-section hidden">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Métricas de Ocupación y Demanda</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Volumen de Reservas</h3>
                <div class="flex gap-1 mb-4 bg-sand-100 dark:bg-[#1E3327] rounded-xl p-1">
                    @foreach ($reservationVolume as $i => $rv)
                    <button class="period-tab flex-1 py-2 px-3 rounded-lg text-xs font-medium transition-all duration-200 {{ $i === 0 ? 'bg-white dark:bg-[#16281D] text-gray-900 dark:text-gray-100 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}"
                            data-period="{{ $rv['key'] }}">
                        {{ $rv['period'] }}
                    </button>
                    @endforeach
                </div>
                <div class="space-y-3">
                    @foreach ($reservationVolume as $i => $rv)
                    <div class="period-content flex items-center justify-between p-4 rounded-xl bg-sand-50 dark:bg-[#1E3327] {{ $i === 0 ? '' : 'hidden' }}" data-period="{{ $rv['key'] }}">
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
                    <span>vs. mes pasado: <span class="text-emerald-600">+8%</span></span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Ocupación por Zona</h3>
                <div class="relative h-72 w-full"><canvas id="occupancyChart"></canvas></div>
            </div>

            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
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

        <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5 mb-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Horas Pico — Flujo de Solicitudes</h3>
                <span class="text-xs text-gray-400 dark:text-gray-500">Distribución del día</span>
            </div>
            <div class="relative h-40 w-full"><canvas id="peakHoursChart"></canvas></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
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
                        <div class="w-full h-1.5 rounded-full bg-sand-100 dark:bg-[#1E3327] overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-gold-500 to-gold-400 transition-all duration-500"
                                 style="width: {{ $cr['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-5xl font-bold text-gray-900 dark:text-gray-100 font-mono mb-2">{{ $cancellationRate }}<span class="text-2xl text-gray-400">%</span></div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Tasa de cancelaciones y No-Shows</p>
                    <p class="text-gray-400 text-xs mt-2">Equivale a <span class="text-gray-900 dark:text-gray-100 font-medium">{{ round($reservationVolume[2]['count'] * $cancellationRate / 100) }} reservas</span> este mes</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- SECTION: OPERATIONS --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="section-operations" class="dashboard-section hidden">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Métricas de Operación y Logística</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gold-100 flex items-center justify-center text-gold-600 flex-shrink-0">
                    <i class="fa-solid fa-clock text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-0.5">Anticipación Promedio</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 font-mono">{{ $avgLeadTime }} <span class="text-base font-normal text-gray-400">días</span></p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                    <i class="fa-solid fa-users text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-0.5">PAX Promedio por Evento</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 font-mono">{{ $avgPax }} <span class="text-base font-normal text-gray-400">personas</span></p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                    <i class="fa-solid fa-gauge-high text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-0.5">Alertas Activas</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 font-mono">{{ count($alerts) }} <span class="text-base font-normal text-gray-400">notificaciones</span></p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Panel de Alertas Operativas</h3>
                <div class="flex items-center gap-2 text-xs">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
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
                        'info' => 'border-emerald-200 bg-emerald-50',
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
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- SECTION: TEAM --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="section-team" class="dashboard-section hidden">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Rendimiento del Equipo (Ventas Internas)</h2>
        </div>

        <div class="flex gap-2 mb-4">
            <button class="dept-tab px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200 bg-gold-500 text-white" data-dept="all">
                Todos los Departamentos
            </button>
            @foreach ($departments as $dept)
            <button class="dept-tab px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200 bg-gray-100 dark:bg-[#1E3327] text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-[#2D4A36]" data-dept="{{ strtolower($dept) }}">
                {{ $dept }}
            </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-3 bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Top 5 Colaboradores</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-[#2D4A36]">
                                <th class="text-left pb-2 font-medium whitespace-nowrap">#</th>
                                <th class="text-left pb-2 font-medium whitespace-nowrap">Colaborador (ID)</th>
                                <th class="text-left pb-2 font-medium whitespace-nowrap">Depto.</th>
                                <th class="text-right pb-2 font-medium whitespace-nowrap">Reservas</th>
                                <th class="text-right pb-2 font-medium whitespace-nowrap">Monto Vendido</th>
                                <th class="text-right pb-2 font-medium whitespace-nowrap">Eficiencia</th>
                            </tr>
                        </thead>
                        <tbody id="collaboratorsBody">
                            @foreach ($topCollaborators as $col)
                            <tr class="collab-row border-b border-sand-200 dark:border-[#2D4A36] last:border-0 hover:bg-sand-50 dark:hover:bg-[#1E3327] transition-colors" data-dept="{{ strtolower($col['department']) }}">
                                <td class="py-3 whitespace-nowrap">
                                    <div class="w-6 h-6 rounded-full bg-gold-100 flex items-center justify-center text-xs font-bold text-gold-600">
                                        {{ $col['position'] }}
                                    </div>
                                </td>
                                <td class="py-3 whitespace-nowrap">
                                    <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $col['name'] }}</span>
                                    <span class="text-gray-400 text-xs ml-1 font-mono">({{ $col['id'] }})</span>
                                </td>
                                <td class="py-3 whitespace-nowrap">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $col['department'] }}</span>
                                </td>
                                <td class="py-3 text-gray-900 dark:text-gray-100 text-right font-mono whitespace-nowrap">{{ $col['reservations'] }}</td>
                                <td class="py-3 text-gray-900 dark:text-gray-100 text-right font-mono whitespace-nowrap">${{ number_format($col['amount']) }}</td>
                                <td class="py-3 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="w-16 h-1.5 rounded-full bg-sand-100 dark:bg-[#1E3327] overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-gold-500 to-gold-400" style="width: {{ $col['efficiency'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-mono text-gold-600 dark:text-gold-400">{{ $col['efficiency'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Eficiencia por Turno</h3>
                <div class="relative h-72 w-full"><canvas id="shiftEfficiencyChart"></canvas></div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- SECTION: AGENDA DE RESERVAS --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="section-agenda" class="dashboard-section hidden">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Agenda de Reservas</h2>
        </div>

        {{-- Stats pills --}}
        <div class="flex flex-wrap gap-3 mb-6">
            @foreach ($agendaStats as $as)
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36]">
                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $as['color'] }}"></span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $as['label'] }}</span>
                <span class="text-sm font-bold text-gray-900 dark:text-gray-100 font-mono">{{ $as['count'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Period tabs --}}
        <div class="flex gap-1 mb-6 bg-sand-100 dark:bg-[#1E3327] rounded-xl p-1 w-fit">
            @foreach ($agendaPeriods as $i => $ap)
            <button class="agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ $i === 0 ? 'bg-white dark:bg-[#16281D] text-gray-900 dark:text-gray-100 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}"
                    data-period="{{ $ap['key'] }}">
                {{ $ap['label'] }} <span class="font-mono ml-1">({{ $ap['count'] }})</span>
            </button>
            @endforeach
        </div>

        {{-- Reservation table --}}
        <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-[#2D4A36] bg-sand-50 dark:bg-[#1E3327]">
                            <th class="text-left px-5 py-3 font-medium">Mesa / Lugar</th>
                            <th class="text-left px-5 py-3 font-medium">Huésped</th>
                            <th class="text-left px-5 py-3 font-medium">Habitación</th>
                            <th class="text-center px-5 py-3 font-medium">Hora</th>
                            <th class="text-center px-5 py-3 font-medium">PAX</th>
                            <th class="text-center px-5 py-3 font-medium">Contacto</th>
                            <th class="text-center px-5 py-3 font-medium">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agendaReservations as $res)
                        @php
                            $statusBadge = match($res['status_color']) {
                                'emerald' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                'amber' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                'red' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800',
                                'gray' => 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700',
                                default => 'bg-gray-50 text-gray-500 border-gray-200',
                            };
                        @endphp
                        <tr class="border-b border-sand-200 dark:border-[#2D4A36] last:border-0 hover:bg-sand-50 dark:hover:bg-[#1E3327] transition-colors">
                            <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $res['mesa'] }}</td>
                            <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100">{{ $res['guest'] }}</td>
                            <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 font-mono text-xs">{{ $res['room'] }}</td>
                            <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300 text-center">{{ $res['time'] }}</td>
                            <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">{{ $res['pax'] }}</td>
                            <td class="px-5 py-3.5 text-gray-400 dark:text-gray-500 text-center text-xs font-mono">{{ $res['phone'] }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-md border font-medium {{ $statusBadge }}">{{ ucfirst($res['status']) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- SECTION: CRUDs (CENAS / PAQUETES / BALINESAS) --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="section-cenas" class="dashboard-section hidden">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Catálogo — Gestión de Servicios</h2>
        </div>

        {{-- CRUD tabs --}}
        <div class="flex gap-2 mb-4 flex-wrap">
            <button class="crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-gold-500 text-white" data-crud="cenas">Cenas Especiales</button>
            <button class="crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-sand-100 dark:bg-[#1E3327] text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-[#2D4A36]" data-crud="paquetes">Paquetes de Eventos</button>
            <button class="crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-sand-100 dark:bg-[#1E3327] text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-[#2D4A36]" data-crud="balinesas">Experiencias VIP</button>
        </div>

        <div id="crud-cenas" class="crud-panel">
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-[#2D4A36]">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Locaciones de Cenas Especiales</h3>
                    <button class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Nuevo
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-[#2D4A36] bg-sand-50 dark:bg-[#1E3327]">
                                <th class="text-left px-5 py-3 font-medium">Lugar</th>
                                <th class="text-left px-5 py-3 font-medium">Descripción</th>
                                <th class="text-right px-5 py-3 font-medium">Precio</th>
                                <th class="text-center px-5 py-3 font-medium">Capacidad</th>
                                <th class="text-center px-5 py-3 font-medium">Estado</th>
                                <th class="text-center px-5 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cenasEspeciales as $ce)
                            <tr class="border-b border-sand-200 dark:border-[#2D4A36] last:border-0 hover:bg-sand-50 dark:hover:bg-[#1E3327] transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $ce['lugar'] }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-[200px] truncate">{{ $ce['descripcion'] }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-right font-mono">${{ number_format($ce['precio']) }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">{{ $ce['capacidad'] }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $ce['status'] === 'Activo' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700' }}">{{ $ce['status'] }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button class="hover:text-gold-500 transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                                        <button class="hover:text-red-500 transition-colors"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="crud-paquetes" class="crud-panel hidden">
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-[#2D4A36]">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Paquetes de Eventos</h3>
                    <button class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Nuevo
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-[#2D4A36] bg-sand-50 dark:bg-[#1E3327]">
                                <th class="text-left px-5 py-3 font-medium">Nombre</th>
                                <th class="text-left px-5 py-3 font-medium">Descripción</th>
                                <th class="text-right px-5 py-3 font-medium">Precio</th>
                                <th class="text-center px-5 py-3 font-medium">Fecha</th>
                                <th class="text-center px-5 py-3 font-medium">Disponibles</th>
                                <th class="text-center px-5 py-3 font-medium">Estado</th>
                                <th class="text-center px-5 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paquetesEventos as $pe)
                            <tr class="border-b border-sand-200 dark:border-[#2D4A36] last:border-0 hover:bg-sand-50 dark:hover:bg-[#1E3327] transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $pe['nombre'] }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-[200px] truncate">{{ $pe['descripcion'] }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-right font-mono">${{ number_format($pe['precio']) }}</td>
                                <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300 text-center text-xs font-mono">{{ \Carbon\Carbon::parse($pe['fecha'])->format('d/m/Y') }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">{{ $pe['disponible'] }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $pe['status'] === 'Activo' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700' }}">{{ $pe['status'] }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button class="hover:text-gold-500 transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                                        <button class="hover:text-red-500 transition-colors"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="crud-balinesas" class="crud-panel hidden">
            <div class="bg-white dark:bg-[#16281D] border border-sand-200 dark:border-[#2D4A36] rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-[#2D4A36]">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Experiencias VIP — Balinesas</h3>
                    <button class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Nueva
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-[#2D4A36] bg-sand-50 dark:bg-[#1E3327]">
                                <th class="text-left px-5 py-3 font-medium">Ubicación</th>
                                <th class="text-right px-5 py-3 font-medium">Tarifa / Día</th>
                                <th class="text-center px-5 py-3 font-medium">Capacidad</th>
                                <th class="text-center px-5 py-3 font-medium">Tipo</th>
                                <th class="text-center px-5 py-3 font-medium">Vistas</th>
                                <th class="text-center px-5 py-3 font-medium">Estado</th>
                                <th class="text-center px-5 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($balinesas as $ba)
                            <tr class="border-b border-sand-200 dark:border-[#2D4A36] last:border-0 hover:bg-sand-50 dark:hover:bg-[#1E3327] transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $ba['ubicacion'] }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-right font-mono">${{ number_format($ba['tarifa']) }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">{{ $ba['capacidad'] }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $ba['tipo'] === 'VIP' ? 'bg-gold-50 dark:bg-gold-900/20 text-gold-600 dark:text-gold-400 border-gold-200 dark:border-gold-800' : 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800' }}">{{ $ba['tipo'] }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-center text-xs">{{ $ba['vistas'] }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $ba['status'] === 'Activo' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700' }}">{{ $ba['status'] }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button class="hover:text-gold-500 transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                                        <button class="hover:text-red-500 transition-colors"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function formatMoney(n) { return '$' + n.toLocaleString(); }

// ── Chart instances registry ──
const chartInstances = {};

function destroyChart(id) {
    if (chartInstances[id]) {
        chartInstances[id].destroy();
        delete chartInstances[id];
    }
}

// ── BCG data (shared) ──
const bcgData = {!! json_encode($bcgMatrix) !!};
const quadrants = {!! json_encode($bcgQuadrants) !!};
const quadrantColors = { star: '#C5A059', cow: '#10B981', question: '#3B82F6', dog: '#EF4444' };
const wcData = {!! json_encode($weeklyComparison) !!};
const mrData = {!! json_encode($monthlyRevenue) !!};
const ozData = {!! json_encode($occupancyByZone) !!};
const phData = {!! json_encode($peakHours) !!};
const seData = {!! json_encode($shiftEfficiency) !!};

// ── Render function ──
window.renderChartsForSection = function(section) {

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#111827',
                bodyColor: '#C5A059',
                borderColor: '#E5E7EB',
                borderWidth: 1,
                padding: 12,
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#F3F4F6', drawBorder: false }, ticks: { color: '#9CA3AF' } },
            x: { grid: { display: false }, ticks: { color: '#9CA3AF' } },
        }
    };

    if (section === 'general') {
        destroyChart('monthlyRevenue');
        chartInstances.monthlyRevenue = new Chart(document.getElementById('monthlyRevenueChart'), {
            type: 'line',
            data: {
                labels: mrData.map(d => d.month),
                datasets: [{
                    label: 'Ingresos',
                    data: mrData.map(d => d.amount),
                    borderColor: '#C5A059',
                    backgroundColor: 'rgba(197, 160, 89, 0.08)',
                    fill: true, tension: 0.4,
                    pointBackgroundColor: '#C5A059',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    borderWidth: 2,
                }]
            },
            options: { ...chartOptions, plugins: { ...chartOptions.plugins, legend: { display: false } },
                scales: { ...chartOptions.scales, y: { ...chartOptions.scales.y, ticks: { ...chartOptions.scales.y.ticks, callback: v => '$' + (v / 1000) + 'k' } } } }
        });
    }

    if (section === 'bcg') {
        destroyChart('bcgChart');
        chartInstances.bcgChart = new Chart(document.getElementById('bcgChart'), {
            type: 'scatter',
            data: {
                datasets: Object.keys(quadrantColors).map(q => ({
                    label: quadrants[q].label,
                    data: bcgData.filter(d => d.quadrant === q).map(d => ({ x: d.share, y: d.growth, r: Math.sqrt(d.revenue) / 8, name: d.name, type: d.type, revenue: d.revenue })),
                    backgroundColor: quadrantColors[q] + '99',
                    borderColor: quadrantColors[q],
                    borderWidth: 2,
                    pointRadius: 10,
                    pointHoverRadius: 14,
                })),
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#fff', titleColor: '#111827', bodyColor: '#C5A059',
                        borderColor: '#E5E7EB', borderWidth: 1, padding: 14,
                        callbacks: {
                            title: ctx => ctx[0].raw.name,
                            label: ctx => ['Tipo: ' + ctx.raw.type, 'Crecimiento: ' + ctx.raw.y + '%', 'Participación: ' + ctx.raw.x + '%', 'Revenue: $' + ctx.raw.revenue.toLocaleString()],
                        }
                    }
                },
                scales: {
                    x: { min: 0, max: 35, grid: { color: '#F3F4F6', drawBorder: false }, ticks: { color: '#9CA3AF', callback: v => v + '%', stepSize: 5 }, title: { display: true, text: 'Participación en Ventas (%)', color: '#9CA3AF', font: { size: 11 } } },
                    y: { min: -10, max: 30, grid: { color: '#F3F4F6', drawBorder: false }, ticks: { color: '#9CA3AF', callback: v => v + '%', stepSize: 5 }, title: { display: true, text: 'Tasa de Crecimiento en Demanda (%)', color: '#9CA3AF', font: { size: 11 } } },
                },
                plugins: [{
                    beforeDraw: function(ch) {
                        const ctx = ch.ctx, ca = ch.chartArea, xs = ch.scales.x, ys = ch.scales.y;
                        const midX = xs.getPixelForValue(50), midY = ys.getPixelForValue(0);
                        ctx.fillStyle = 'rgba(197, 160, 89, 0.04)'; ctx.fillRect(midX, ca.top, ca.right - midX, midY - ca.top);
                        ctx.fillStyle = 'rgba(16, 185, 129, 0.04)'; ctx.fillRect(midX, midY, ca.right - midX, ca.bottom - midY);
                        ctx.fillStyle = 'rgba(59, 130, 246, 0.04)'; ctx.fillRect(ca.left, ca.top, midX - ca.left, midY - ca.top);
                        ctx.fillStyle = 'rgba(239, 68, 68, 0.04)'; ctx.fillRect(ca.left, midY, midX - ca.left, ca.bottom - midY);
                        ctx.strokeStyle = 'rgba(0,0,0,0.06)'; ctx.lineWidth = 1; ctx.setLineDash([4, 4]);
                        ctx.beginPath(); ctx.moveTo(midX, ca.top); ctx.lineTo(midX, ca.bottom); ctx.stroke();
                        ctx.beginPath(); ctx.moveTo(ca.left, midY); ctx.lineTo(ca.right, midY); ctx.stroke();
                        ctx.setLineDash([]);
                    }
                }]
            }
        });
    }

    if (section === 'inventory') {
        const invItems = {!! json_encode(array_column($inventory, 'item')) !!};
        const invStock = {!! json_encode(array_column($inventory, 'stock')) !!};
        const invMin = {!! json_encode(array_column($inventory, 'minStock')) !!};
        const invReserved = {!! json_encode(array_column($inventory, 'reserved')) !!};
        destroyChart('inventoryChart');
        chartInstances.inventoryChart = new Chart(document.getElementById('inventoryChart'), {
            type: 'bar',
            data: {
                labels: invItems.map(i => i.length > 20 ? i.substring(0, 18) + '...' : i),
                datasets: [
                    { label: 'Stock Actual', data: invStock, backgroundColor: '#C5A059', borderRadius: 4, barThickness: 14 },
                    { label: 'Requerido (7d)', data: invReserved.map((r, i) => r + invMin[i]), backgroundColor: 'rgba(239, 68, 68, 0.6)', borderRadius: 4, barThickness: 14 },
                ]
            },
            options: { ...chartOptions, plugins: { ...chartOptions.plugins, legend: { position: 'top', labels: { color: '#6B7280', usePointStyle: true, pointStyle: 'rectRounded', padding: 16, font: { size: 11 } } } } }
        });
    }

    if (section === 'financial') {
        destroyChart('revenueByTypeChart');
        chartInstances.revenueByTypeChart = new Chart(document.getElementById('revenueByTypeChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_column($revenueByType, 'type')) !!},
                datasets: [{ data: {!! json_encode(array_column($revenueByType, 'amount')) !!}, backgroundColor: ['#3B82F6', '#C5A059', '#10B981'], borderColor: '#fff', borderWidth: 3, hoverOffset: 8 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%',
                plugins: { legend: { position: 'bottom', labels: { color: '#6B7280', padding: 14, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } } },
                    tooltip: { backgroundColor: '#fff', titleColor: '#111827', bodyColor: '#C5A059', borderColor: '#E5E7EB', borderWidth: 1, padding: 12, callbacks: { label: ctx => ctx.label + ': $' + ctx.parsed.toLocaleString() } } }
            }
        });

        destroyChart('monthlyRevenueChart2');
        chartInstances.monthlyRevenueChart2 = new Chart(document.getElementById('monthlyRevenueChart2'), {
            type: 'line',
            data: {
                labels: mrData.map(d => d.month),
                datasets: [{
                    label: 'Ingresos', data: mrData.map(d => d.amount),
                    borderColor: '#C5A059', backgroundColor: 'rgba(197, 160, 89, 0.08)',
                    fill: true, tension: 0.4, pointBackgroundColor: '#C5A059', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4, borderWidth: 2,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#fff', titleColor: '#111827', bodyColor: '#C5A059', borderColor: '#E5E7EB', borderWidth: 1, padding: 12, callbacks: { label: ctx => '$' + ctx.parsed.y.toLocaleString() } } },
                scales: { y: { beginAtZero: true, grid: { color: '#F3F4F6', drawBorder: false }, ticks: { color: '#9CA3AF', callback: v => '$' + (v / 1000) + 'k' } }, x: { grid: { display: false }, ticks: { color: '#9CA3AF' } } }
            }
        });

        destroyChart('weeklyComparisonChart');
        chartInstances.weeklyComparisonChart = new Chart(document.getElementById('weeklyComparisonChart'), {
            type: 'bar',
            data: {
                labels: wcData.map(d => d.week),
                datasets: [{ label: 'Ingresos', data: wcData.map(d => d.revenue), backgroundColor: ['#E5E7EB', '#C5A059'], borderRadius: 6, borderSkipped: false, barThickness: 40 }]
            },
            options: { responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#fff', titleColor: '#111827', bodyColor: '#C5A059', borderColor: '#E5E7EB', borderWidth: 1, padding: 12, callbacks: { label: ctx => '$' + ctx.parsed.y.toLocaleString() } } },
                scales: { y: { beginAtZero: true, grid: { color: '#F3F4F6', drawBorder: false }, ticks: { color: '#9CA3AF', callback: v => '$' + (v / 1000) + 'k' } }, x: { grid: { display: false }, ticks: { color: '#9CA3AF' } } }
            }
        });
    }

    if (section === 'occupancy') {
        destroyChart('occupancyChart');
        chartInstances.occupancyChart = new Chart(document.getElementById('occupancyChart'), {
            type: 'doughnut',
            data: {
                labels: ozData.map(d => d.zone),
                datasets: [{ data: ozData.map(d => d.percentage), backgroundColor: ['#C5A059', '#10B981', '#3B82F6'], borderColor: '#fff', borderWidth: 3, hoverOffset: 8 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: { legend: { position: 'bottom', labels: { color: '#6B7280', padding: 16, usePointStyle: true, pointStyle: 'circle', font: { size: 12 } } },
                    tooltip: { backgroundColor: '#fff', titleColor: '#111827', bodyColor: '#C5A059', borderColor: '#E5E7EB', borderWidth: 1, padding: 12, callbacks: { label: ctx => ctx.parsed + '%' } } }
            }
        });

        destroyChart('peakHoursChart');
        chartInstances.peakHoursChart = new Chart(document.getElementById('peakHoursChart'), {
            type: 'bar',
            data: {
                labels: phData.map(d => d.hour),
                datasets: [{ label: 'Solicitudes', data: phData.map(d => d.count), backgroundColor: ['rgba(197, 160, 89, 0.3)', 'rgba(197, 160, 89, 0.4)', 'rgba(197, 160, 89, 0.7)', 'rgba(197, 160, 89, 0.6)', '#C5A059', 'rgba(197, 160, 89, 0.5)'], borderRadius: 4, borderSkipped: false, barThickness: 28 }]
            },
            options: { ...chartOptions, plugins: { ...chartOptions.plugins, legend: { display: false } } }
        });
    }

    if (section === 'team') {
        destroyChart('shiftEfficiencyChart');
        chartInstances.shiftEfficiencyChart = new Chart(document.getElementById('shiftEfficiencyChart'), {
            type: 'bar',
            data: {
                labels: seData.map(d => d.shift),
                datasets: [{ label: 'Reservas', data: seData.map(d => d.reservations), backgroundColor: ['rgba(197, 160, 89, 0.7)', 'rgba(16, 185, 129, 0.7)', 'rgba(59, 130, 246, 0.7)'], borderColor: ['#C5A059', '#10B981', '#3B82F6'], borderWidth: 1, borderRadius: 4, borderSkipped: false, barThickness: 28 }]
            },
            options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y',
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#fff', titleColor: '#111827', bodyColor: '#C5A059', borderColor: '#E5E7EB', borderWidth: 1, padding: 12 } },
                scales: { y: { grid: { display: false }, ticks: { color: '#9CA3AF', font: { size: 11 } } }, x: { beginAtZero: true, grid: { color: '#F3F4F6', drawBorder: false }, ticks: { color: '#9CA3AF', stepSize: 10 } } }
            }
        });
    }
};

// ── Period tabs ──
document.addEventListener('click', (e) => {
    const tab = e.target.closest('.period-tab');
    if (tab) {
        document.querySelectorAll('.period-tab').forEach(t => {
            t.className = 'period-tab flex-1 py-2 px-3 rounded-lg text-xs font-medium transition-all duration-200 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 bg-transparent';
        });
        tab.className = 'period-tab flex-1 py-2 px-3 rounded-lg text-xs font-medium transition-all duration-200 bg-white dark:bg-[#16281D] text-gray-900 dark:text-gray-100 shadow-sm';
        document.querySelectorAll('.period-content').forEach(c => c.classList.add('hidden'));
        const el = document.querySelector('.period-content[data-period="' + tab.dataset.period + '"]');
        if (el) el.classList.remove('hidden');
    }

    // Department tabs
    const deptTab = e.target.closest('.dept-tab');
    if (deptTab) {
        document.querySelectorAll('.dept-tab').forEach(t => {
            t.className = 'dept-tab px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200 bg-gray-100 dark:bg-[#1E3327] text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-[#2D4A36]';
        });
        deptTab.className = 'dept-tab px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200 bg-gold-500 text-white';
        const dept = deptTab.dataset.dept;
        document.querySelectorAll('.collab-row').forEach(row => {
            row.style.display = (dept === 'all' || row.dataset.dept === dept) ? '' : 'none';
        });
    }

    // Agenda period tabs
    const agTab = e.target.closest('.agenda-period-tab');
    if (agTab) {
        document.querySelectorAll('.agenda-period-tab').forEach(t => {
            t.className = 'agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 bg-transparent';
        });
        agTab.className = 'agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 bg-white dark:bg-[#16281D] text-gray-900 dark:text-gray-100 shadow-sm';
    }

    // CRUD tabs
    const crudTab = e.target.closest('.crud-tab');
    if (crudTab) {
        document.querySelectorAll('.crud-tab').forEach(t => {
            t.className = 'crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-sand-100 dark:bg-[#1E3327] text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-[#2D4A36]';
        });
        crudTab.className = 'crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-gold-500 text-white';
        document.querySelectorAll('.crud-panel').forEach(p => p.classList.add('hidden'));
        const panel = document.getElementById('crud-' + crudTab.dataset.crud);
        if (panel) panel.classList.remove('hidden');
    }
});

// Initial render for general section on page load
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        window.renderChartsForSection('general');
    }, 100);
});
</script>
@endpush
