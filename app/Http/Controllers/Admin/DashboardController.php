<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $kpis = [
            [
                'label'    => 'Ingresos Hoy',
                'value'    => '$12,450',
                'icon'     => 'fa-solid fa-money-bill-wave',
                'change'   => '+12%',
                'positive' => true,
            ],
            [
                'label'    => 'Reservas Hoy',
                'value'    => '18',
                'icon'     => 'fa-solid fa-calendar-check',
                'change'   => '-3%',
                'positive' => false,
            ],
            [
                'label'    => 'Ocupación Promedio',
                'value'    => '74%',
                'icon'     => 'fa-solid fa-bed',
                'change'   => '+5%',
                'positive' => true,
            ],
            [
                'label'    => 'Ticket Promedio',
                'value'    => '$692',
                'icon'     => 'fa-solid fa-ticket',
                'change'   => '+8%',
                'positive' => true,
            ],
        ];

        $bcgMatrix = [
            ['name' => 'Balinesa con Burbujas', 'type' => 'Balinesa', 'growth' => 18, 'share' => 28, 'revenue' => 52100, 'quadrant' => 'star', 'color' => '#C5A059'],
            ['name' => 'Cena Romántica Grotto', 'type' => 'Cena', 'growth' => 22, 'share' => 26, 'revenue' => 48500, 'quadrant' => 'star', 'color' => '#C5A059'],
            ['name' => 'Spa con Burbujas', 'type' => 'Experiencia', 'growth' => 3, 'share' => 19, 'revenue' => 36200, 'quadrant' => 'cow', 'color' => '#10B981'],
            ['name' => 'Cena Terraza Mar', 'type' => 'Cena', 'growth' => 5, 'share' => 12, 'revenue' => 22400, 'quadrant' => 'cow', 'color' => '#10B981'],
            ['name' => 'Balinesa Sunset', 'type' => 'Balinesa', 'growth' => 15, 'share' => 8, 'revenue' => 15800, 'quadrant' => 'question', 'color' => '#3B82F6'],
            ['name' => "Chef's Table Experience", 'type' => 'Experiencia', 'growth' => 20, 'share' => 5, 'revenue' => 9200, 'quadrant' => 'question', 'color' => '#3B82F6'],
            ['name' => 'Cena Básica Interior', 'type' => 'Cena', 'growth' => -2, 'share' => 3, 'revenue' => 6500, 'quadrant' => 'dog', 'color' => '#EF4444'],
            ['name' => 'Masaje Express', 'type' => 'Experiencia', 'growth' => -5, 'share' => 2, 'revenue' => 4100, 'quadrant' => 'dog', 'color' => '#EF4444'],
        ];

        $bcgQuadrants = [
            'star'     => ['label' => 'Estrella',     'desc' => 'Potenciar e invertir', 'icon' => 'fa-star',       'color' => '#C5A059'],
            'cow'      => ['label' => 'Vaca',         'desc' => 'Mantener y optimizar', 'icon' => 'fa-cow',        'color' => '#10B981'],
            'question' => ['label' => 'Incógnita',    'desc' => 'Evaluar viabilidad',   'icon' => 'fa-question',   'color' => '#3B82F6'],
            'dog'      => ['label' => 'Perro',        'desc' => 'Reestructurar o retirar', 'icon' => 'fa-dog',   'color' => '#EF4444'],
        ];

        $inventory = [
            ['item' => "Moët & Chandon Brut 750ml", 'stock' => 24, 'reserved' => 18, 'minStock' => 10, 'unit' => 'botellas', 'status' => 'ok'],
            ['item' => 'Tequila Don Julio 70', 'stock' => 12, 'reserved' => 8, 'minStock' => 5, 'unit' => 'botellas', 'status' => 'ok'],
            ['item' => 'Decoración rosas premium', 'stock' => 30, 'reserved' => 22, 'minStock' => 15, 'unit' => 'ramos', 'status' => 'ok'],
            ['item' => 'Champagne Veuve Clicquot', 'stock' => 6, 'reserved' => 8, 'minStock' => 5, 'unit' => 'botellas', 'status' => 'warning'],
            ['item' => 'Langosta (kg)', 'stock' => 8, 'reserved' => 10, 'minStock' => 5, 'unit' => 'kg', 'status' => 'danger'],
        ];

        $revenueByType = [
            ['type' => 'Experiencias VIP', 'amount' => 124800, 'percentage' => 35, 'color' => '#3B82F6'],
            ['type' => 'Cenas Especiales', 'amount' => 142500, 'percentage' => 40, 'color' => '#C5A059'],
            ['type' => 'Balinesas',       'amount' => 89100,  'percentage' => 25, 'color' => '#10B981'],
        ];

        $ticketAverages = [
            ['label' => 'Por Habitación', 'value' => 845, 'icon' => 'fa-door-open'],
            ['label' => 'Por Reserva',    'value' => 692, 'icon' => 'fa-ticket'],
        ];

        $monthlyRevenue = [
            ['month' => 'Febrero', 'amount' => 185000],
            ['month' => 'Marzo',   'amount' => 210000],
            ['month' => 'Abril',   'amount' => 248000],
            ['month' => 'Mayo',    'amount' => 195000],
            ['month' => 'Junio',   'amount' => 278000],
            ['month' => 'Julio',   'amount' => 312000],
        ];

        $weeklyComparison = [
            ['week' => 'Semana Pasada', 'revenue' => 68500],
            ['week' => 'Esta Semana',   'revenue' => 74200],
        ];

        $topPackages = [
            ['position' => 1, 'name' => 'Balinesa con Burbujas', 'type' => 'Balinesa',  'revenue' => 52100, 'margin' => 65],
            ['position' => 2, 'name' => 'Cena Romántica Grotto', 'type' => 'Cena',       'revenue' => 48500, 'margin' => 72],
            ['position' => 3, 'name' => 'Spa con Burbujas',      'type' => 'Experiencia','revenue' => 36200, 'margin' => 68],
            ['position' => 4, 'name' => 'Balinesa Sunset',       'type' => 'Balinesa',   'revenue' => 28900, 'margin' => 61],
            ['position' => 5, 'name' => 'Cena Terraza Mar',      'type' => 'Cena',       'revenue' => 22400, 'margin' => 70],
        ];

        $reservationVolume = [
            ['period' => 'Hoy',          'count' => 18,  'key' => 'today'],
            ['period' => 'Esta Semana',  'count' => 124, 'key' => 'week'],
            ['period' => 'Este Mes',     'count' => 512, 'key' => 'month'],
        ];

        $occupancyByZone = [
            ['zone' => 'Playa',     'percentage' => 45, 'color' => '#C5A059'],
            ['zone' => 'Jardín',    'percentage' => 30, 'color' => '#10B981'],
            ['zone' => 'Pool Club', 'percentage' => 25, 'color' => '#3B82F6'],
        ];

        $peakDays = [
            ['day' => 'Lun', 'intensity' => 3],
            ['day' => 'Mar', 'intensity' => 2],
            ['day' => 'Mié', 'intensity' => 2],
            ['day' => 'Jue', 'intensity' => 3],
            ['day' => 'Vie', 'intensity' => 5],
            ['day' => 'Sáb', 'intensity' => 5],
            ['day' => 'Dom', 'intensity' => 4],
        ];

        $peakHours = [
            ['hour' => '6:00-9:00', 'count' => 8],
            ['hour' => '9:00-12:00', 'count' => 22],
            ['hour' => '12:00-15:00', 'count' => 35],
            ['hour' => '15:00-18:00', 'count' => 28],
            ['hour' => '18:00-21:00', 'count' => 45],
            ['hour' => '21:00-00:00', 'count' => 18],
        ];

        $cancellationRate   = 4.2;
        $cancellationReasons = [
            ['reason' => 'Cambio de planes del huésped',  'percentage' => 40],
            ['reason' => 'Condiciones climáticas',        'percentage' => 25],
            ['reason' => 'Problemas de salud',            'percentage' => 15],
            ['reason' => 'Overbooking operativo',        'percentage' => 12],
            ['reason' => 'Otros',                          'percentage' => 8],
        ];

        $avgLeadTime = 2.3;
        $avgPax      = 2.8;

        $alerts = [
            ['severity' => 'warning', 'zone' => 'Balinesas', 'message' => 'Balinesas Beach Club: 2 de 8 disponibles', 'detail' => 'Límite operativo de 3 disponibles antes de restricción', 'indicator' => '🟡'],
            ['severity' => 'danger', 'zone' => 'The Grotto', 'message' => 'Mesas The Grotto: 0 de 12 disponibles', 'detail' => 'Overbooking alcanzado — considerar cierre de ventas', 'indicator' => '🔴'],
            ['severity' => 'info', 'zone' => 'SPA', 'message' => 'Experiencias SPA: 5 de 6 slots ocupados', 'detail' => 'Próximo turno completo en 2 horas', 'indicator' => '🟢'],
        ];

        $topCollaborators = [
            ['position' => 1, 'name' => 'Carlos Mendoza',   'id' => '123456', 'department' => 'Restaurante', 'reservations' => 34, 'amount' => 23800, 'efficiency' => 94],
            ['position' => 2, 'name' => 'Ana López',        'id' => '234567', 'department' => 'Restaurante', 'reservations' => 28, 'amount' => 19600, 'efficiency' => 88],
            ['position' => 3, 'name' => 'Pedro Ramírez',    'id' => '345678', 'department' => 'Spa',         'reservations' => 25, 'amount' => 17200, 'efficiency' => 82],
            ['position' => 4, 'name' => 'María Fernández',  'id' => '456789', 'department' => 'Pool',        'reservations' => 22, 'amount' => 15800, 'efficiency' => 79],
            ['position' => 5, 'name' => 'Jorge Torres',     'id' => '567890', 'department' => 'Restaurante', 'reservations' => 19, 'amount' => 14100, 'efficiency' => 75],
        ];

        $departments = ['Restaurante', 'Spa', 'Pool'];

        $shiftEfficiency = [
            ['shift' => 'Matutino (6:00-14:00)',   'reservations' => 48, 'revenue' => 33600, 'color' => '#C5A059'],
            ['shift' => 'Vespertino (14:00-22:00)', 'reservations' => 62, 'revenue' => 43400, 'color' => '#10B981'],
            ['shift' => 'Nocturno (22:00-6:00)',   'reservations' => 14, 'revenue' => 9800,  'color' => '#3B82F6'],
        ];

        $agendaReservations = [
            ['id' => 1, 'mesa' => 'Balinesa #3 - Playa', 'guest' => 'Carlos Rivera', 'room' => 'Suite 204', 'time' => '10:00 - 13:00', 'pax' => 4, 'status' => 'confirmado', 'status_color' => 'sapphire', 'phone' => '+52 998 123 4567'],
            ['id' => 2, 'mesa' => 'The Grotto - Mesa VIP', 'guest' => 'Ana Sofía García', 'room' => 'Master 101', 'time' => '14:00 - 16:30', 'pax' => 2, 'status' => 'confirmado', 'status_color' => 'sapphire', 'phone' => '+52 998 234 5678'],
            ['id' => 3, 'mesa' => 'Balinesa #7 - Alberca', 'guest' => 'Roberto Méndez', 'room' => 'Junior 305', 'time' => '11:00 - 14:00', 'pax' => 3, 'status' => 'pendiente', 'status_color' => 'amber', 'phone' => '+52 998 345 6789'],
            ['id' => 4, 'mesa' => 'Terraza Mar - Mesa 5', 'guest' => 'María Fernanda López', 'room' => 'Suite 110', 'time' => '19:00 - 21:30', 'pax' => 6, 'status' => 'confirmado', 'status_color' => 'sapphire', 'phone' => '+52 998 456 7890'],
            ['id' => 5, 'mesa' => 'Balinesa #1 - Playa', 'guest' => 'José Luis Martínez', 'room' => 'Penthouse 501', 'time' => '09:00 - 12:00', 'pax' => 2, 'status' => 'no-show', 'status_color' => 'red', 'phone' => '+52 998 567 8901'],
            ['id' => 6, 'mesa' => 'The Grotto - Mesa 8', 'guest' => 'Patricia Vega', 'room' => 'Deluxe 208', 'time' => '13:00 - 15:30', 'pax' => 4, 'status' => 'completado', 'status_color' => 'gray', 'phone' => '+52 998 678 9012'],
            ['id' => 7, 'mesa' => 'Pool Club - Camastro 2', 'guest' => 'Fernando Castillo', 'room' => 'Suite 402', 'time' => '10:30 - 17:00', 'pax' => 2, 'status' => 'pendiente', 'status_color' => 'amber', 'phone' => '+52 998 789 0123'],
            ['id' => 8, 'mesa' => 'Balinesa #5 - Sunset', 'guest' => 'Laura Jiménez', 'room' => 'Master 103', 'time' => '15:00 - 18:30', 'pax' => 4, 'status' => 'confirmado', 'status_color' => 'sapphire', 'phone' => '+52 998 890 1234'],
        ];

        $agendaPeriods = [
            ['key' => 'today', 'label' => 'Hoy', 'count' => 8],
            ['key' => 'tomorrow', 'label' => 'Mañana', 'count' => 12],
            ['key' => 'week', 'label' => 'Esta Semana', 'count' => 47],
        ];

        $agendaStats = [
            ['label' => 'Confirmadas', 'count' => 4, 'color' => '#065F46'],
            ['label' => 'Pendientes', 'count' => 2, 'color' => '#D4A853'],
            ['label' => 'No-Show', 'count' => 1, 'color' => '#DC2626'],
            ['label' => 'Completadas', 'count' => 1, 'color' => '#9CA3AF'],
        ];

        $cenasEspeciales = [
            ['id' => 1, 'lugar' => 'The Grotto', 'descripcion' => 'Cena romántica en cueva privada con mariachi', 'precio' => 8500, 'capacidad' => 12, 'status' => 'Activo', 'imagenes' => []],
            ['id' => 2, 'lugar' => 'Terraza Mar', 'descripcion' => 'Cena al atardecer frente al mar', 'precio' => 5200, 'capacidad' => 40, 'status' => 'Activo', 'imagenes' => []],
            ['id' => 3, 'lugar' => 'Balcón Colonial', 'descripcion' => 'Cena privada en mirador colonial', 'precio' => 6800, 'capacidad' => 8, 'status' => 'Activo', 'imagenes' => []],
            ['id' => 4, 'lugar' => 'Jardín Secreto', 'descripcion' => 'Cena rodeada de vegetación exótica', 'precio' => 4800, 'capacidad' => 20, 'status' => 'Inactivo', 'imagenes' => []],
            ['id' => 5, 'lugar' => 'Pool Club Noche', 'descripcion' => 'Cena iluminada junto a la alberca infinita', 'precio' => 6100, 'capacidad' => 30, 'status' => 'Activo', 'imagenes' => []],
        ];

        $paquetesEventos = [
            ['id' => 1, 'nombre' => 'Paquete Mundial', 'descripcion' => 'Evento con pantalla gigante y buffet', 'precio' => 35000, 'fecha' => '2026-07-15', 'disponible' => 50, 'status' => 'Activo', 'imagenes' => []],
            ['id' => 2, 'nombre' => 'Cata de Tequila Premium', 'descripcion' => 'Degustación guiada con sommelier', 'precio' => 2800, 'fecha' => '2026-07-20', 'disponible' => 20, 'status' => 'Activo', 'imagenes' => []],
            ['id' => 3, 'nombre' => 'Noche de Jazz & Vinos', 'descripcion' => 'Música en vivo y maridaje', 'precio' => 4200, 'fecha' => '2026-07-28', 'disponible' => 35, 'status' => 'Activo', 'imagenes' => []],
            ['id' => 4, 'nombre' => 'Fiesta de Año Nuevo', 'descripcion' => 'Cena de gala y cotillón incluido', 'precio' => 12000, 'fecha' => '2026-12-31', 'disponible' => 100, 'status' => 'Activo', 'imagenes' => []],
            ['id' => 5, 'nombre' => 'Brunch Dominical', 'descripcion' => 'Bufer dominical con estaciones gourmet', 'precio' => 1800, 'fecha' => '2026-07-14', 'disponible' => 60, 'status' => 'Inactivo', 'imagenes' => []],
        ];

        $balinesas = [
            ['id' => 1, 'ubicacion' => 'Playa Norte', 'tarifa' => 3200, 'capacidad' => 4, 'tipo' => 'Sombrilla', 'vistas' => 'Mar', 'status' => 'Activo', 'imagenes' => []],
            ['id' => 2, 'ubicacion' => 'Playa Central', 'tarifa' => 2800, 'capacidad' => 2, 'tipo' => 'Sol', 'vistas' => 'Mar', 'status' => 'Activo', 'imagenes' => []],
            ['id' => 3, 'ubicacion' => 'Alberca Infinity', 'tarifa' => 2500, 'capacidad' => 4, 'tipo' => 'Sombrilla', 'vistas' => 'Jardín', 'status' => 'Activo', 'imagenes' => []],
            ['id' => 4, 'ubicacion' => 'Sunset Deck', 'tarifa' => 3800, 'capacidad' => 6, 'tipo' => 'VIP', 'vistas' => 'Atardecer', 'status' => 'Activo', 'imagenes' => []],
            ['id' => 5, 'ubicacion' => 'Playa Privada', 'tarifa' => 4500, 'capacidad' => 6, 'tipo' => 'VIP', 'vistas' => 'Mar Abierto', 'status' => 'Inactivo', 'imagenes' => []],
            ['id' => 6, 'ubicacion' => 'Pool Club', 'tarifa' => 2200, 'capacidad' => 2, 'tipo' => 'Sol', 'vistas' => 'Alberca', 'status' => 'Activo', 'imagenes' => []],
        ];

        $fondos = [];

        return view('admin.dashboard', compact(
            'kpis',
            'bcgMatrix',
            'bcgQuadrants',
            'inventory',
            'revenueByType',
            'ticketAverages',
            'monthlyRevenue',
            'weeklyComparison',
            'topPackages',
            'reservationVolume',
            'occupancyByZone',
            'peakDays',
            'peakHours',
            'cancellationRate',
            'cancellationReasons',
            'avgLeadTime',
            'avgPax',
            'alerts',
            'topCollaborators',
            'departments',
            'shiftEfficiency',
            'agendaReservations',
            'agendaPeriods',
            'agendaStats',
            'cenasEspeciales',
            'paquetesEventos',
            'balinesas',
            'fondos',
        ));
    }
}
