<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balinesa;
use App\Models\Categoria;
use App\Models\CenaEspecial;
use App\Models\Espacio;
use App\Models\Experiencia;
use App\Models\Reserva;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        $ayer = Carbon::yesterday();
        $manana = Carbon::tomorrow();
        $inicioSemana = Carbon::today()->startOfWeek();
        $finSemana = Carbon::today()->endOfWeek();
        $inicioSemanaPasada = Carbon::today()->subWeek()->startOfWeek();
        $finSemanaPasada = Carbon::today()->subWeek()->endOfWeek();
        $inicioMes = Carbon::today()->startOfMonth();
        $finMes = Carbon::today()->endOfMonth();
        $inicioMesPasado = Carbon::today()->subMonth()->startOfMonth();
        $finMesPasado = Carbon::today()->subMonth()->endOfMonth();
        $inicioConsulta = Carbon::today()->subDays(90);
        $finConsulta = Carbon::today()->addDays(90);

        $totalActiveSpaces = Espacio::where('Is_Active', 1)->count();

        // ── Collections ──

        $hoyReservas = Reserva::whereDate('Dia', $hoy)->with('serviciable')->get();
        $ayerReservas = Reserva::whereDate('Dia', $ayer)->with('serviciable')->get();
        $semanaActualReservas = Reserva::whereBetween('Dia', [$inicioSemana, $finSemana])->with('serviciable')->get();
        $semanaPasadaReservas = Reserva::whereBetween('Dia', [$inicioSemanaPasada, $finSemanaPasada])->with('serviciable')->get();
        $mesReservas = Reserva::whereBetween('Dia', [$inicioMes, $finMes])->with('serviciable')->get();
        $mesPasadoReservas = Reserva::whereBetween('Dia', [$inicioMesPasado, $finMesPasado])->with('serviciable')->get();

        $hoyCount = $hoyReservas->count();
        $semCount = $semanaActualReservas->count();
        $mesCount = $mesReservas->count();

        $sumPrice = function ($collection) {
            return $collection->sum(fn($r) => $r->serviciable?->Precio ?? 0);
        };

        $calcChange = function ($current, $previous) {
            if ($previous == 0) return $current > 0 ? '+100%' : '0%';
            $pct = round((($current - $previous) / $previous) * 100);
            return ($pct >= 0 ? '+' : '') . $pct . '%';
        };

        // ── KPIs ──

        $hoyIngresos = $sumPrice($hoyReservas);
        $ayerIngresos = $sumPrice($ayerReservas);

        $hoyOccupied = Reserva::whereDate('Dia', $hoy)->distinct()->count('id_espacio');
        $ayerOccupied = Reserva::whereDate('Dia', $ayer)->distinct()->count('id_espacio');
        $ocupacionHoy = $totalActiveSpaces > 0 ? round(($hoyOccupied / $totalActiveSpaces) * 100) : 0;
        $ocupacionAyer = $totalActiveSpaces > 0 ? round(($ayerOccupied / $totalActiveSpaces) * 100) : 0;

        $mesIngresos = $sumPrice($mesReservas);
        $ticketPromedio = $mesCount > 0 ? round($mesIngresos / $mesCount) : 0;

        $mesPasadoIngresos = $sumPrice($mesPasadoReservas);
        $mesPasadoCount = $mesPasadoReservas->count();
        $ticketPromedioPasado = $mesPasadoCount > 0 ? round($mesPasadoIngresos / $mesPasadoCount) : 0;

        $kpis = [
            [
                'label'    => 'Ingresos Hoy',
                'value'    => '$' . number_format($hoyIngresos),
                'icon'     => 'fa-solid fa-money-bill-wave',
                'change'   => $calcChange($hoyIngresos, $ayerIngresos),
                'positive' => $hoyIngresos >= $ayerIngresos,
            ],
            [
                'label'    => 'Reservas Hoy',
                'value'    => (string)$hoyCount,
                'icon'     => 'fa-solid fa-calendar-check',
                'change'   => $calcChange($hoyCount, $ayerReservas->count()),
                'positive' => $hoyCount >= $ayerReservas->count(),
            ],
            [
                'label'    => 'Ocupación Promedio',
                'value'    => $ocupacionHoy . '%',
                'icon'     => 'fa-solid fa-bed',
                'change'   => $calcChange($ocupacionHoy, $ocupacionAyer),
                'positive' => $ocupacionHoy >= $ocupacionAyer,
            ],
            [
                'label'    => 'Ticket Promedio',
                'value'    => '$' . number_format($ticketPromedio),
                'icon'     => 'fa-solid fa-ticket',
                'change'   => $calcChange($ticketPromedio, $ticketPromedioPasado),
                'positive' => $ticketPromedio >= $ticketPromedioPasado,
            ],
        ];

        // ── BCG Matrix (real data) ──

        $limiteBCG = Carbon::today()->subDays(60);

        $serviciosBCG = collect()
            ->merge(Experiencia::with(['reservas' => fn($q) => $q->where('Dia', '>=', $limiteBCG), 'categoria'])->get()->map(fn($m) => ['model' => $m, 'type' => 'Experiencia']))
            ->merge(Balinesa::with(['reservas' => fn($q) => $q->where('Dia', '>=', $limiteBCG), 'categoria'])->get()->map(fn($m) => ['model' => $m, 'type' => 'Balinesa']))
            ->merge(CenaEspecial::with(['reservas' => fn($q) => $q->where('Dia', '>=', $limiteBCG), 'categoria'])->get()->map(fn($m) => ['model' => $m, 'type' => 'Cena']));

        $hoyCarbon = Carbon::today();
        $treintaAtras = Carbon::today()->subDays(30);
        $totalRevenueBCG = 0;
        $bcgProducts = [];

        foreach ($serviciosBCG as $s) {
            $model = $s['model'];
            $reservas = $model->reservas;
            $reservas30 = $reservas->filter(fn($r) => Carbon::parse($r->Dia)->between($treintaAtras, $hoyCarbon));
            $reservas60 = $reservas->filter(fn($r) => Carbon::parse($r->Dia)->lt($treintaAtras));

            $count30 = $reservas30->count();
            $count60 = $reservas60->count();
            $price = $model->Precio ?? 0;
            $cost = $model->Costo_Operativo ?? 0;
            $revenue30 = $price * $count30;
            $revenue60 = $price * $count60;
            $totalRevenueBCG += $revenue30;

            $bcgProducts[] = [
                'name'     => $model->Nombre ?? 'Sin nombre',
                'type'     => $s['type'],
                'category' => $model->categoria?->Nombre ?? 'Sin categoría',
                'price'    => (int)$price,
                'growth'   => $revenue60 > 0 ? round((($revenue30 - $revenue60) / $revenue60) * 100, 1) : ($revenue30 > 0 ? 100 : 0),
                'share'    => 0,
                'revenue'  => (int)$revenue30,
                'count'    => $count30,
                'margin'   => $price > 0 ? round((($price - $cost) / $price) * 100) : 0,
            ];
        }

        foreach ($bcgProducts as &$p) {
            $p['share'] = $totalRevenueBCG > 0 ? round(($p['revenue'] / $totalRevenueBCG) * 100, 1) : 0;
        }
        unset($p);

        $bcgQuadrants = [
            'star'     => ['label' => 'Estrella',     'desc' => 'Potenciar e invertir',     'icon' => 'fa-star',     'color' => '#C5A059'],
            'cow'      => ['label' => 'Vaca',         'desc' => 'Mantener y optimizar',     'icon' => 'fa-cow',      'color' => '#10B981'],
            'question' => ['label' => 'Incógnita',    'desc' => 'Evaluar viabilidad',       'icon' => 'fa-question', 'color' => '#3B82F6'],
            'dog'      => ['label' => 'Perro',        'desc' => 'Reestructurar o retirar',  'icon' => 'fa-dog',      'color' => '#EF4444'],
        ];

        $bcgProducts = array_map(function ($p) use ($bcgQuadrants) {
            $growth = $p['growth'];
            $share = $p['share'];

            if ($growth >= 10 && $share >= 10) {
                $p['quadrant'] = 'star';
            } elseif ($growth < 10 && $share >= 10) {
                $p['quadrant'] = 'cow';
            } elseif ($growth >= 10 && $share < 10) {
                $p['quadrant'] = 'question';
            } else {
                $p['quadrant'] = 'dog';
            }

            $p['color'] = $bcgQuadrants[$p['quadrant']]['color'];

            $p['recommendation'] = match ($p['quadrant']) {
                'star'     => 'Alto crecimiento y participación — mantener inversión y potenciar',
                'cow'      => 'Alta participación, bajo crecimiento — optimizar márgenes y maximizar ingresos',
                'question' => 'Alto crecimiento, baja participación — evaluar viabilidad y apostar selectivamente',
                'dog'      => 'Bajo crecimiento y participación — reestructurar o retirar del catálogo',
            };

            return $p;
        }, $bcgProducts);

        usort($bcgProducts, fn($a, $b) => $b['revenue'] <=> $a['revenue']);

        $productoMasRentable = !empty($bcgProducts) ? $bcgProducts[0]['name'] : 'N/A';
        $totalProfitBCG = array_sum(array_map(fn($p) => $p['revenue'] * $p['margin'] / 100, $bcgProducts));

        $bcgSummary = [
            'total_products'   => count($bcgProducts),
            'total_revenue'    => (int)$totalRevenueBCG,
            'total_profit'     => (int)$totalProfitBCG,
            'most_profitable'  => $productoMasRentable,
        ];

        // ── Financial ──

        $byType = $mesReservas->groupBy('serviciable_type');
        $totalRevenue = $sumPrice($mesReservas);

        $typeConfig = [
            'App\Models\Experiencia'  => ['label' => 'Experiencias VIP', 'color' => '#3B82F6'],
            'App\Models\CenaEspecial' => ['label' => 'Cenas Especiales', 'color' => '#C5A059'],
            'App\Models\Balinesa'     => ['label' => 'Balinesas',        'color' => '#10B981'],
        ];

        $revenueByType = [];
        foreach ($typeConfig as $class => $cfg) {
            $group = $byType->get($class, collect());
            $amount = $group->sum(fn($r) => $r->serviciable?->Precio ?? 0);
            $revenueByType[] = [
                'type'       => $cfg['label'],
                'amount'     => (int)$amount,
                'percentage' => $totalRevenue > 0 ? round(($amount / $totalRevenue) * 100) : 0,
                'color'      => $cfg['color'],
            ];
        }

        $balinesasReservas = $mesReservas->where('serviciable_type', 'App\Models\Balinesa');
        $balinesasIngresos = $balinesasReservas->sum(fn($r) => $r->serviciable?->Precio ?? 0);
        $balinesasCount = $balinesasReservas->count();

        $ticketAverages = [
            [
                'label' => 'Por Habitación',
                'value' => $balinesasCount > 0 ? round($balinesasIngresos / $balinesasCount) : 0,
                'icon'  => 'fa-door-open',
            ],
            [
                'label' => 'Por Reserva',
                'value' => $ticketPromedio,
                'icon'  => 'fa-ticket',
            ],
        ];

        // Monthly revenue (last 6 months)
        $seisMesesAtras = Carbon::today()->subMonths(6)->startOfMonth();
        $reservas6Meses = Reserva::where('Dia', '>=', $seisMesesAtras)->with('serviciable.categoria')->get();

        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::today()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            $reservasMes = $reservas6Meses->filter(fn($r) =>
                Carbon::parse($r->Dia)->between($start, $end)
            );
            $monthlyRevenue[] = [
                'month'  => $month->locale('es')->monthName,
                'amount' => (int)$reservasMes->sum(fn($r) => $r->serviciable?->Precio ?? 0),
            ];
        }

        $weeklyComparison = [
            [
                'week'    => 'Semana Pasada',
                'revenue' => (int)$sumPrice($semanaPasadaReservas),
            ],
            [
                'week'    => 'Esta Semana',
                'revenue' => (int)$sumPrice($semanaActualReservas),
            ],
        ];

        // Top 5 packages
        $paquetesAgrupados = $mesReservas
            ->groupBy(fn($r) => $r->serviciable_type . '::' . $r->serviciable_id)
            ->map(function ($group) {
                $first = $group->first();
                $service = $first->serviciable;
                $revenue = $group->sum(fn($r) => $r->serviciable?->Precio ?? 0);
                $cost = $service?->Costo_Operativo ?? 0;
                $typeLabel = match ($first->serviciable_type) {
                    'App\Models\Balinesa'     => 'Balinesa',
                    'App\Models\CenaEspecial' => 'Cena',
                    'App\Models\Experiencia'  => 'Experiencia',
                    default                   => 'Otros',
                };
                return [
                    'name'    => $service?->Nombre ?? 'Sin nombre',
                    'type'    => $typeLabel,
                    'revenue' => (int)$revenue,
                    'margin'  => $revenue > 0 ? round((($revenue - $cost) / $revenue) * 100) : 0,
                ];
            })
            ->sortByDesc('revenue')
            ->take(5)
            ->values()
            ->toArray();

        $topPackages = array_map(function ($pkg, $i) {
            return ['position' => $i + 1] + $pkg;
        }, $paquetesAgrupados, array_keys($paquetesAgrupados));

        // ── Ticket Promedio por Categoría ──

        $ticketByCategory = $mesReservas
            ->groupBy(fn($r) => $r->serviciable?->categoria?->Nombre ?? 'Sin categoría')
            ->map(function ($group) {
                $catName = $group->first()->serviciable?->categoria?->Nombre ?? 'Sin categoría';
                $total = $group->sum(fn($r) => $r->serviciable?->Precio ?? 0);
                $count = $group->count();
                return [
                    'label' => $catName,
                    'value' => $count > 0 ? round($total / $count) : 0,
                    'icon'  => 'fa-tag',
                ];
            })
            ->values()
            ->toArray();

        // ── Revenue por Día de la Semana ──

        $ultimas4Semanas = Carbon::today()->subWeeks(4);
        $reservas4semanas = $reservas6Meses->filter(fn($r) => Carbon::parse($r->Dia)->greaterThanOrEqualTo($ultimas4Semanas));

        $dowRevenue = array_fill(0, 7, 0);
        foreach ($reservas4semanas as $r) {
            $dow = Carbon::parse($r->Dia)->dayOfWeek;
            $dowRevenue[$dow] += $r->serviciable?->Precio ?? 0;
        }

        $dayNames = [0 => 'Dom', 1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb'];
        $revenueByDayOfWeek = [];
        foreach ([1, 2, 3, 4, 5, 6, 0] as $dow) {
            $revenueByDayOfWeek[] = ['day' => $dayNames[$dow], 'amount' => (int)($dowRevenue[$dow] ?? 0)];
        }

        // ── Top 5 por Cantidad de Reservas ──

        $topByCount = $mesReservas
            ->groupBy(fn($r) => $r->serviciable_type . '::' . $r->serviciable_id)
            ->map(function ($group) {
                $first = $group->first();
                $typeLabel = match ($first->serviciable_type) {
                    'App\Models\Balinesa'     => 'Balinesa',
                    'App\Models\CenaEspecial' => 'Cena',
                    'App\Models\Experiencia'  => 'Experiencia',
                    default                   => 'Otros',
                };
                return [
                    'name'  => $first->serviciable?->Nombre ?? 'Sin nombre',
                    'type'  => $typeLabel,
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('count')
            ->take(5)
            ->values()
            ->toArray();

        $topByCount = array_map(fn($pkg, $i) => ['position' => $i + 1] + $pkg, $topByCount, array_keys($topByCount));

        // ── Evolución Ticket Promedio (6 meses) ──

        $monthlyTicketAvg = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::today()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            $reservasMes = $reservas6Meses->filter(fn($r) => Carbon::parse($r->Dia)->between($start, $end));
            $count = $reservasMes->count();
            $revenue = $reservasMes->sum(fn($r) => $r->serviciable?->Precio ?? 0);
            $monthlyTicketAvg[] = [
                'month' => $month->locale('es')->monthName,
                'avg'   => $count > 0 ? round($revenue / $count) : 0,
            ];
        }

        // ── Distribución de Márgenes ──

        $allMargins = [];
        foreach ($serviciosBCG as $s) {
            $model = $s['model'];
            $margin = $model->Precio > 0 ? round((($model->Precio - ($model->Costo_Operativo ?? 0)) / $model->Precio) * 100) : 0;
            $allMargins[] = $margin;
        }

        $marginDistribution = [
            ['range' => '0-25%',  'count' => count(array_filter($allMargins, fn($m) => $m >= 0 && $m <= 25)),  'color' => '#EF4444'],
            ['range' => '26-50%', 'count' => count(array_filter($allMargins, fn($m) => $m > 25 && $m <= 50)), 'color' => '#F59E0B'],
            ['range' => '51-75%', 'count' => count(array_filter($allMargins, fn($m) => $m > 50 && $m <= 75)), 'color' => '#3B82F6'],
            ['range' => '76-100%','count' => count(array_filter($allMargins, fn($m) => $m > 75)),              'color' => '#10B981'],
        ];

        // ── Crecimiento Mensual ──

        $mesActualRevenue = !empty($monthlyRevenue) ? end($monthlyRevenue)['amount'] : 0;
        $mesAnteriorRevenue = count($monthlyRevenue) >= 2 ? $monthlyRevenue[count($monthlyRevenue) - 2]['amount'] : 0;
        $crecimientoMensual = $mesAnteriorRevenue > 0 ? round((($mesActualRevenue - $mesAnteriorRevenue) / $mesAnteriorRevenue) * 100) : 0;
        $proyeccionMensual = $mesActualRevenue;

        // ── Reservation Volume ──

        $reservationVolume = [
            ['period' => 'Hoy',         'count' => $hoyCount, 'key' => 'today'],
            ['period' => 'Esta Semana', 'count' => $semCount, 'key' => 'week'],
            ['period' => 'Este Mes',    'count' => $mesCount, 'key' => 'month'],
        ];

        // ── Occupancy by Zone ──

        $totalByZone = Espacio::where('Is_Active', 1)
            ->selectRaw('Zona, COUNT(*) as total')
            ->groupBy('Zona')
            ->pluck('total', 'Zona');

        $reservedTodayByZone = Reserva::whereDate('Dia', $hoy)
            ->whereNotNull('id_espacio')
            ->join('Espacios', 'Reservas.id_espacio', '=', 'Espacios.Id')
            ->selectRaw('Espacios.Zona, COUNT(DISTINCT Reservas.id_espacio) as reserved')
            ->groupBy('Espacios.Zona')
            ->pluck('reserved', 'Zona');

        $zoneColors = ['#C5A059', '#10B981', '#3B82F6', '#8B5CF6', '#F59E0B'];
        $colorIndex = 0;
        $occupancyByZone = [];
        foreach ($totalByZone as $zone => $total) {
            $reserved = $reservedTodayByZone[$zone] ?? 0;
            $pct = $total > 0 ? round(($reserved / $total) * 100) : 0;
            $occupancyByZone[] = [
                'zone'       => $zone,
                'percentage' => $pct,
                'color'      => $zoneColors[$colorIndex % count($zoneColors)],
            ];
            $colorIndex++;
        }

        // ── Peak Days (últimas 4 semanas) ──

        $ultimas4Semanas = Reserva::where('Dia', '>=', Carbon::today()->subWeeks(4))
            ->get(['Dia']);

        $dayCounts = [];
        foreach ($ultimas4Semanas as $r) {
            $dow = Carbon::parse($r->Dia)->dayOfWeek;
            $dayCounts[$dow] = ($dayCounts[$dow] ?? 0) + 1;
        }

        $dayNames = [0 => 'Dom', 1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb'];
        $maxCount = max($dayCounts ?: [1]);

        $peakDays = [];
        foreach ([1, 2, 3, 4, 5, 6, 0] as $dow) {
            $count = $dayCounts[$dow] ?? 0;
            $intensity = $maxCount > 0 ? max(1, min(5, round(($count / $maxCount) * 5))) : 1;
            $peakDays[] = ['day' => $dayNames[$dow], 'intensity' => $intensity];
        }

        // ── Cancellation Rate ──

        $canceladosMes = $mesReservas->whereIn('Estado', ['Cancelado', 'No-Show'])->count();
        $cancellationRate = $mesCount > 0 ? round(($canceladosMes / $mesCount) * 100, 1) : 0;

        // ── Operations ──

        $avgLeadTime = Reserva::whereBetween('Dia', [Carbon::today()->subDays(90), Carbon::today()])
            ->whereNotNull('created_at')
            ->selectRaw('AVG(DATEDIFF(day, created_at, Dia)) as avg')
            ->value('avg');
        $avgLeadTime = round((float)($avgLeadTime ?? 0), 1);

        $alerts = [];
        foreach ($totalByZone as $zone => $total) {
            $reserved = $reservedTodayByZone[$zone] ?? 0;
            $pct = $total > 0 ? round(($reserved / $total) * 100) : 0;
            $available = $total - $reserved;

            if ($pct >= 95) {
                $alerts[] = [
                    'severity'  => 'danger',
                    'zone'      => $zone,
                    'message'   => "$zone: $available de $total disponibles",
                    'detail'    => "Ocupación al $pct% — considerar cierre de ventas",
                    'indicator' => '🔴',
                ];
            } elseif ($pct >= 80) {
                $alerts[] = [
                    'severity'  => 'warning',
                    'zone'      => $zone,
                    'message'   => "$zone: $available de $total disponibles",
                    'detail'    => "Ocupación al $pct% — límite operativo próximo",
                    'indicator' => '🟡',
                ];
            }
        }

        if (empty($alerts)) {
            $alerts[] = [
                'severity'  => 'info',
                'zone'      => 'General',
                'message'   => 'Todas las zonas con disponibilidad normal',
                'detail'    => 'Sin novedades operativas',
                'indicator' => '🟢',
            ];
        }

        // ── Team ──

        $colaboradores = $mesReservas
            ->whereNotNull('Numero_de_colaborador_vendedor')
            ->where('Numero_de_colaborador_vendedor', '!=', '')
            ->groupBy('Numero_de_colaborador_vendedor')
            ->map(function ($group) {
                $numColab = $group->first()->Numero_de_colaborador_vendedor;
                $usuario = Usuario::where('Numero_de_colaborador', $numColab)->first();
                $totalRes = $group->count();
                $completadas = $group->where('Estado', 'Completado')->count();
                $revenue = $group->sum(fn($r) => $r->serviciable?->Precio ?? 0);
                return [
                    'name'         => $usuario?->Nombre ?? 'Sin nombre',
                    'id'           => $numColab,
                    'reservations' => $totalRes,
                    'amount'       => (int)$revenue,
                    'efficiency'   => $totalRes > 0 ? round(($completadas / $totalRes) * 100) : 0,
                ];
            })
            ->sortByDesc('amount')
            ->take(5)
            ->values()
            ->toArray();

        $topCollaborators = array_map(function ($col, $i) {
            return ['position' => $i + 1] + $col;
        }, $colaboradores, array_keys($colaboradores));

        // ── Agenda ──

        $agendaReservations = Reserva::with(['espacio', 'serviciable', 'usuario'])
            ->whereBetween('Dia', [$inicioConsulta, $finConsulta])
            ->orderBy('Dia')
            ->orderBy('created_at', 'desc')
            ->get();

        $agendaPeriods = [
            ['key' => 'today',    'label' => 'Hoy',     'count' => $hoyCount],
            ['key' => 'tomorrow', 'label' => 'Mañana',  'count' => Reserva::whereDate('Dia', $manana)->count()],
            ['key' => 'week',     'label' => 'Esta Semana', 'count' => $semCount],
            ['key' => 'month',    'label' => 'Este Mes',    'count' => $mesCount],
        ];

        $agendaStats = [
            ['label' => 'Confirmadas', 'count' => $semanaActualReservas->where('Estado', 'Confirmado')->count(), 'color' => '#065F46'],
            ['label' => 'Pendientes',  'count' => $semanaActualReservas->where('Estado', 'Pendiente')->count(),  'color' => '#D4A853'],
            ['label' => 'No-Show',     'count' => $semanaActualReservas->where('Estado', 'No-Show')->count(),    'color' => '#DC2626'],
            ['label' => 'Completadas', 'count' => $semanaActualReservas->where('Estado', 'Completado')->count(),  'color' => '#9CA3AF'],
            ['label' => 'Canceladas',  'count' => $semanaActualReservas->where('Estado', 'Cancelado')->count(),   'color' => '#6B7280'],
        ];

        // ── CRUD data ──

        $balinesas = Balinesa::all();
        $cenasEspeciales = CenaEspecial::all();
        $paquetesEventos = Experiencia::all();
        $usuarios = Usuario::where('Rol', 'Operativo')->get();
        $espacios = Espacio::all();
        $fondos = [];

        // ── Booking Pace (últ. 3 meses vs mes actual) ──

        $bookingPace = [];
        $primerDiaMes = (int)Carbon::today()->day;
        $tresMesesAtras = Carbon::today()->subMonths(3)->startOfMonth();

        $reservasBookingPace = Reserva::whereBetween('Dia', [$tresMesesAtras, Carbon::today()])->get(['Dia']);

        $historial = [];
        foreach ($reservasBookingPace as $r) {
            $diaMes = (int)Carbon::parse($r->Dia)->day;
            $fecha = Carbon::parse($r->Dia);
            if ($fecha->between($tresMesesAtras, Carbon::today()->subDay()->endOfMonth())) {
                $historial[$diaMes] = ($historial[$diaMes] ?? 0) + 1;
            }
        }

        $actualPace = [];
        foreach ($reservasBookingPace as $r) {
            $fecha = Carbon::parse($r->Dia);
            if ($fecha->month === Carbon::today()->month && $fecha->year === Carbon::today()->year) {
                $diaMes = (int)$fecha->day;
                $actualPace[$diaMes] = ($actualPace[$diaMes] ?? 0) + 1;
            }
        }

        $numMonths = 3;
        for ($d = 1; $d <= $primerDiaMes; $d++) {
            $bookingPace[] = [
                'day'     => $d,
                'actual'  => (int)($actualPace[$d] ?? 0),
                'average' => round(($historial[$d] ?? 0) / $numMonths, 1),
            ];
        }

        // ── Demand Calendar (próximos 30 días) ──

        $demandCalendar = [];
        $proximos30 = Reserva::whereBetween('Dia', [Carbon::today(), Carbon::today()->addDays(30)])
            ->whereNotIn('Estado', ['Cancelado', 'No-Show'])
            ->get(['Dia']);

        $demandCounts = [];
        foreach ($proximos30 as $r) {
            $fecha = Carbon::parse($r->Dia)->format('Y-m-d');
            $demandCounts[$fecha] = ($demandCounts[$fecha] ?? 0) + 1;
        }

        $maxDemand = max($demandCounts ?: [1]);
        for ($i = 0; $i < 30; $i++) {
            $fecha = Carbon::today()->addDays($i);
            $fechaStr = $fecha->format('Y-m-d');
            $count = $demandCounts[$fechaStr] ?? 0;
            $intensity = $maxDemand > 0 ? min(4, max(0, floor(($count / $maxDemand) * 4) + 1)) : 1;
            $demandCalendar[] = [
                'date'      => $fechaStr,
                'day'       => $fecha->day,
                'dayName'   => $fecha->locale('es')->shortDayName,
                'month'     => $fecha->locale('es')->monthName,
                'count'     => $count,
                'intensity' => $intensity,
            ];
        }

        // ── Repeat Guest Rate + Top Spenders ──

        $habitacionesGrupo = $mesReservas
            ->whereNotNull('Habitacion')
            ->where('Habitacion', '!=', '')
            ->groupBy('Habitacion');

        $totalHabs = $habitacionesGrupo->count();
        $repeatHabs = $habitacionesGrupo->filter(fn($g) => $g->count() > 1)->count();
        $repeatGuestRate = $totalHabs > 0 ? round(($repeatHabs / $totalHabs) * 100, 1) : 0;

        $habitacionesRevenue = $habitacionesGrupo->map(function ($group, $habitacion) {
            return [
                'habitacion'   => $habitacion,
                'reservations' => $group->count(),
                'revenue'      => (int)$group->sum(fn($r) => $r->serviciable?->Precio ?? 0),
            ];
        });

        $topSpenders = $habitacionesRevenue
            ->sortByDesc('revenue')
            ->take(5)
            ->values()
            ->toArray();

        // ── Space Utilization (últimos 30 días) ──

        $ultimos30 = Carbon::today()->subDays(30);
        $espaciosActivos = Espacio::where('Is_Active', 1)->get();
        $totalDias = 30;

        $spaceUtilization = [];
        foreach ($espaciosActivos as $esp) {
            $diasOcupados = Reserva::where('id_espacio', $esp->Id)
                ->where('Dia', '>=', $ultimos30)
                ->whereNotIn('Estado', ['Cancelado', 'No-Show'])
                ->distinct('Dia')
                ->count('Dia');

            $pct = $totalDias > 0 ? round(($diasOcupados / $totalDias) * 100) : 0;
            $spaceUtilization[] = [
                'nombre'      => $esp->Nombre,
                'tipo'        => $esp->Tipo,
                'zona'        => $esp->Zona,
                'capacidad'   => $esp->Capacidad,
                'dias_ocupados' => $diasOcupados,
                'total_dias'  => $totalDias,
                'utilizacion' => $pct,
            ];
        }

        usort($spaceUtilization, fn($a, $b) => $b['utilizacion'] <=> $a['utilizacion']);

        // ── Colaborador Trend (top 5 por revenue, últimos 6 meses) ──

        $seisMesesAtras2 = Carbon::today()->subMonths(6)->startOfMonth();

        $top5ColabIds = $mesReservas
            ->whereNotNull('Numero_de_colaborador_vendedor')
            ->where('Numero_de_colaborador_vendedor', '!=', '')
            ->groupBy('Numero_de_colaborador_vendedor')
            ->map(fn($group) => [
                'id'   => $group->first()->Numero_de_colaborador_vendedor,
                'rev'  => $group->sum(fn($r) => $r->serviciable?->Precio ?? 0),
                'name' => Usuario::where('Numero_de_colaborador', $group->first()->Numero_de_colaborador_vendedor)->first()?->Nombre ?? 'Sin nombre',
            ])
            ->sortByDesc('rev')
            ->take(5)
            ->values();

        $colaboradorTrend = [];
        $reservasTrend = Reserva::where('Dia', '>=', $seisMesesAtras2)
            ->whereNotNull('Numero_de_colaborador_vendedor')
            ->where('Numero_de_colaborador_vendedor', '!=', '')
            ->with('serviciable')
            ->get();

        foreach ($top5ColabIds as $colab) {
            $monthly = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::today()->subMonths($i);
                $start = $month->copy()->startOfMonth();
                $end = $month->copy()->endOfMonth();
                $reservasMes = $reservasTrend->filter(fn($r) =>
                    $r->Numero_de_colaborador_vendedor === $colab['id']
                    && Carbon::parse($r->Dia)->between($start, $end)
                );
                $monthly[] = [
                    'month'  => $month->locale('es')->monthName,
                    'amount' => (int)$reservasMes->sum(fn($r) => $r->serviciable?->Precio ?? 0),
                ];
            }
            $colaboradorTrend[] = [
                'colaborador' => $colab['name'],
                'id'          => $colab['id'],
                'monthly'     => $monthly,
            ];
        }

        return view('admin.dashboard', compact(
            'kpis',
            'bcgProducts',
            'bcgQuadrants',
            'bcgSummary',
            'revenueByType',
            'ticketAverages',
            'ticketByCategory',
            'monthlyRevenue',
            'monthlyTicketAvg',
            'weeklyComparison',
            'topPackages',
            'topByCount',
            'revenueByDayOfWeek',
            'marginDistribution',
            'crecimientoMensual',
            'proyeccionMensual',
            'reservationVolume',
            'occupancyByZone',
            'peakDays',
            'cancellationRate',
            'avgLeadTime',
            'alerts',
            'topCollaborators',
            'agendaReservations',
            'agendaPeriods',
            'agendaStats',
            'cenasEspeciales',
            'paquetesEventos',
            'balinesas',
            'usuarios',
            'espacios',
            'fondos',
            'inicioConsulta',
            'finConsulta',
            'bookingPace',
            'demandCalendar',
            'repeatGuestRate',
            'topSpenders',
            'spaceUtilization',
            'colaboradorTrend',
        ));
    }
}
