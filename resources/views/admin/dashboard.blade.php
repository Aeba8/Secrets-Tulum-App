@extends('admin.layouts.dashboard')

@section('title', 'Dashboard — SecretsPad Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <div id="section-general" class="dashboard-section">
        @include('admin.dashboard.general')
    </div>

    <div id="section-bcg" class="dashboard-section hidden">
        @include('admin.dashboard.bcg')
    </div>

    <div id="section-financial" class="dashboard-section hidden">
        @include('admin.dashboard.financial')
    </div>

    <div id="section-occupancy" class="dashboard-section hidden">
        @include('admin.dashboard.occupancy')
    </div>

    <div id="section-operations" class="dashboard-section hidden">
        @include('admin.dashboard.operations')
    </div>

    <div id="section-team" class="dashboard-section hidden">
        @include('admin.dashboard.team')
    </div>

    <div id="section-agenda" class="dashboard-section hidden">
        @include('admin.dashboard.agenda')
    </div>

    <div id="section-cenas" class="dashboard-section hidden">
        @include('admin.dashboard.cruds.index')
    </div>

    <div id="section-usuarios" class="dashboard-section hidden">
        @include('admin.dashboard.cruds.usuarios')
    </div>

    <div id="section-espacios" class="dashboard-section hidden">
        @include('admin.dashboard.cruds.espacios')
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
const bcgData = {!! json_encode($bcgProducts) !!};
const quadrants = {!! json_encode($bcgQuadrants) !!};
const quadrantColors = { star: '#C5A059', cow: '#10B981', question: '#3B82F6', dog: '#EF4444' };
const bcgAxisMax = { growth: {{ $bcgMaxGrowth }}, share: {{ $bcgMaxShare }} };
const wcData = {!! json_encode($weeklyComparison) !!};
const mrData = {!! json_encode($monthlyRevenue) !!};
const ozData = {!! json_encode($occupancyByZone) !!};
const tbcData = {!! json_encode($ticketByCategory ?? []) !!};
const rbdData = {!! json_encode($revenueByDayOfWeek ?? []) !!};
const top5CtData = {!! json_encode($topByCount ?? []) !!};
const teData = {!! json_encode($monthlyTicketAvg ?? []) !!};
const mdData = {!! json_encode($marginDistribution ?? []) !!};
const pcData = {!! json_encode($bcgProducts ?? []) !!};
const bpData = {!! json_encode($bookingPace) !!};
const ctData = {!! json_encode($colaboradorTrend) !!};


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

        destroyChart('bookingPaceChart');
        chartInstances.bookingPaceChart = new Chart(document.getElementById('bookingPaceChart'), {
            type: 'line',
            data: {
                labels: bpData.map(d => d.day),
                datasets: [
                    {
                        label: 'Este Mes',
                        data: bpData.map(d => d.actual),
                        borderColor: '#C5A059',
                        backgroundColor: 'rgba(197, 160, 89, 0.08)',
                        fill: true, tension: 0.4,
                        pointBackgroundColor: '#C5A059',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        borderWidth: 2,
                    },
                    {
                        label: 'Promedio (3 meses)',
                        data: bpData.map(d => d.average),
                        borderColor: '#9CA3AF',
                        backgroundColor: 'transparent',
                        borderDash: [6, 3],
                        tension: 0.4,
                        pointRadius: 0,
                        borderWidth: 2,
                    }
                ]
            },
            options: { ...chartOptions, plugins: { ...chartOptions.plugins, legend: { position: 'top', labels: { color: '#6B7280', usePointStyle: true, pointStyle: 'circle', padding: 14, font: { size: 11 } } } },
                scales: { ...chartOptions.scales, y: { ...chartOptions.scales.y, ticks: { ...chartOptions.scales.y.ticks, stepSize: 1 } } } }
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
                    x: { min: 0, max: Math.max(35, bcgAxisMax.share), grid: { color: '#F3F4F6', drawBorder: false }, ticks: { color: '#9CA3AF', callback: v => v + '%', stepSize: Math.max(5, Math.ceil(bcgAxisMax.share / 6)) }, title: { display: true, text: 'Participación en Ventas (%)', color: '#9CA3AF', font: { size: 11 } } },
                    y: { min: -10, max: Math.max(30, bcgAxisMax.growth), grid: { color: '#F3F4F6', drawBorder: false }, ticks: { color: '#9CA3AF', callback: v => v + '%', stepSize: Math.max(5, Math.ceil(bcgAxisMax.growth / 6)) }, title: { display: true, text: 'Tasa de Crecimiento en Demanda (%)', color: '#9CA3AF', font: { size: 11 } } },
                },
                plugins: [{
                    beforeDraw: function(ch) {
                        const ctx = ch.ctx, ca = ch.chartArea, xs = ch.scales.x, ys = ch.scales.y;
                        const midX = xs.getPixelForValue(10), midY = ys.getPixelForValue(0);
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

        destroyChart('popularityChart');
        chartInstances.popularityChart = new Chart(document.getElementById('popularityChart'), {
            type: 'scatter',
            data: {
                datasets: Object.keys(quadrantColors).map(q => ({
                    label: quadrants[q].label,
                    data: bcgData.filter(d => d.quadrant === q).map(d => ({ x: d.count, y: d.margin, r: Math.sqrt(d.revenue) / 10, name: d.name, type: d.type })),
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
                            label: ctx => ['Tipo: ' + ctx.raw.type, 'Reservas: ' + ctx.raw.x, 'Margen: ' + ctx.raw.y + '%'],
                        }
                    }
                },
                scales: {
                    x: { grid: { color: '#F3F4F6', drawBorder: false }, ticks: { color: '#9CA3AF' }, title: { display: true, text: 'Número de Reservas (Popularidad)', color: '#9CA3AF', font: { size: 11 } } },
                    y: { grid: { color: '#F3F4F6', drawBorder: false }, ticks: { color: '#9CA3AF', callback: v => v + '%' }, title: { display: true, text: 'Margen de Rentabilidad (%)', color: '#9CA3AF', font: { size: 11 } } },
                },
            }
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

        destroyChart('revenueByDayChart');
        chartInstances.revenueByDayChart = new Chart(document.getElementById('revenueByDayChart'), {
            type: 'bar',
            data: {
                labels: rbdData.map(d => d.day),
                datasets: [{ label: 'Ingresos', data: rbdData.map(d => d.amount), backgroundColor: '#C5A059', borderRadius: 6, borderSkipped: false, barThickness: 32 }]
            },
            options: { ...chartOptions, plugins: { ...chartOptions.plugins, legend: { display: false } },
                scales: { ...chartOptions.scales, y: { ...chartOptions.scales.y, ticks: { ...chartOptions.scales.y.ticks, callback: v => '$' + (v / 1000) + 'k' } } } }
        });

        destroyChart('ticketEvolutionChart');
        chartInstances.ticketEvolutionChart = new Chart(document.getElementById('ticketEvolutionChart'), {
            type: 'line',
            data: {
                labels: teData.map(d => d.month),
                datasets: [{
                    label: 'Ticket Promedio', data: teData.map(d => d.avg),
                    borderColor: '#10B981', backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    fill: true, tension: 0.4, pointBackgroundColor: '#10B981', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4, borderWidth: 2,
                }]
            },
            options: { ...chartOptions, plugins: { ...chartOptions.plugins, legend: { display: false } },
                scales: { ...chartOptions.scales, y: { ...chartOptions.scales.y, ticks: { ...chartOptions.scales.y.ticks, callback: v => '$' + v } } } }
        });

        destroyChart('marginDistributionChart');
        chartInstances.marginDistributionChart = new Chart(document.getElementById('marginDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: mdData.map(d => d.range),
                datasets: [{ data: mdData.map(d => d.count), backgroundColor: ['#10B981', '#C5A059', '#3B82F6', '#EF4444', '#8B5CF6'], borderColor: '#fff', borderWidth: 3, hoverOffset: 8 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '60%',
                plugins: { legend: { position: 'bottom', labels: { color: '#6B7280', padding: 14, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } } },
                    tooltip: { backgroundColor: '#fff', titleColor: '#111827', bodyColor: '#C5A059', borderColor: '#E5E7EB', borderWidth: 1, padding: 12 } }
            }
        });
    }

    if (section === 'occupancy') {
        destroyChart('occupancyChart');
        chartInstances.occupancyChart = new Chart(document.getElementById('occupancyChart'), {
            type: 'doughnut',
            data: {
                labels: ozData.map(d => d.zone),
                datasets: [{ data: ozData.map(d => d.percentage), backgroundColor: ozData.map(d => d.color), borderColor: '#fff', borderWidth: 3, hoverOffset: 8 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: { legend: { position: 'bottom', labels: { color: '#6B7280', padding: 16, usePointStyle: true, pointStyle: 'circle', font: { size: 12 } } },
                    tooltip: { backgroundColor: '#fff', titleColor: '#111827', bodyColor: '#C5A059', borderColor: '#E5E7EB', borderWidth: 1, padding: 12, callbacks: { label: ctx => ctx.parsed + '%' } } }
            }
        });

    }

    if (section === 'team') {
        destroyChart('colaboradorTrendChart');
        const ctColors = ['#C5A059', '#10B981', '#3B82F6', '#EF4444', '#8B5CF6'];
        chartInstances.colaboradorTrendChart = new Chart(document.getElementById('colaboradorTrendChart'), {
            type: 'line',
            data: {
                labels: ctData[0]?.monthly.map(d => d.month) || [],
                datasets: ctData.map((col, i) => ({
                    label: '#' + col.id + ' ' + col.colaborador,
                    data: col.monthly.map(d => d.amount),
                    borderColor: ctColors[i % ctColors.length],
                    backgroundColor: ctColors[i % ctColors.length] + '15',
                    fill: false, tension: 0.4,
                    pointBackgroundColor: ctColors[i % ctColors.length],
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    borderWidth: 2,
                })),
            },
            options: { ...chartOptions, plugins: { ...chartOptions.plugins, legend: { position: 'top', labels: { color: '#6B7280', usePointStyle: true, pointStyle: 'circle', padding: 14, font: { size: 11 } } } },
                scales: { ...chartOptions.scales, y: { ...chartOptions.scales.y, ticks: { ...chartOptions.scales.y.ticks, callback: v => '$' + (v / 1000) + 'k' } } } }
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
        tab.className = 'period-tab flex-1 py-2 px-3 rounded-lg text-xs font-medium transition-all duration-200 bg-white dark:bg-charcoal-600 text-gray-900 dark:text-gray-100 shadow-sm';
        document.querySelectorAll('.period-content').forEach(c => c.classList.add('hidden'));
        const el = document.querySelector('.period-content[data-period="' + tab.dataset.period + '"]');
        if (el) el.classList.remove('hidden');
    }

    // Agenda period tabs
    const agTab = e.target.closest('.agenda-period-tab');
    if (agTab) {
        document.querySelectorAll('.agenda-period-tab').forEach(t => {
            t.className = 'agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 bg-transparent';
        });
        agTab.className = 'agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 bg-white dark:bg-charcoal-600 text-gray-900 dark:text-gray-100 shadow-sm';
    }

    // CRUD tabs
    const crudTab = e.target.closest('.crud-tab');
    if (crudTab) {
        document.querySelectorAll('.crud-tab').forEach(t => {
            t.className = 'crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-sand-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-sand-200 dark:hover:bg-charcoal-500';
        });
        crudTab.className = 'crud-tab px-4 py-2 rounded-xl text-xs font-medium bg-gold-500 text-white';
        document.querySelectorAll('.crud-panel').forEach(p => p.classList.add('hidden'));
        const panel = document.getElementById('crud-' + crudTab.dataset.crud);
        if (panel) panel.classList.remove('hidden');
        // Reset filters on tab switch
        const searchInput = panel?.querySelector('.search-servicios');
        if (searchInput) searchInput.value = '';
        const estadoDropdown = panel?.querySelector('.estado-filter-dropdown');
        if (estadoDropdown) {
            estadoDropdown.dataset.filtroEstado = 'todos';
            const label = estadoDropdown.querySelector('.estado-filter-label');
            if (label) label.textContent = 'Todos';
            estadoDropdown.querySelectorAll('.estado-filter-option').forEach(function(o) {
                o.className = 'estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500';
            });
            const firstOpt = estadoDropdown.querySelector('.estado-filter-option[data-filtro="todos"]');
            if (firstOpt) firstOpt.className = 'estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg bg-gold-500 text-white';
        }
        panel?.querySelectorAll('tbody tr').forEach(r => r.style.display = '');
    }
});

// Initial render for general section on page load
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        window.renderChartsForSection('general');
    }, 100);
});

function filtrarServicios() {
    const panel = document.querySelector('.crud-panel:not(.hidden)');
    if (!panel) return;
    const q = panel.querySelector('.search-servicios')?.value.toLowerCase().trim() || '';
    const dropdown = panel.querySelector('.estado-filter-dropdown');
    const estado = dropdown?.dataset.filtroEstado || 'todos';

    panel.querySelectorAll('tbody tr').forEach(row => {
        if (row.querySelector('td[colspan]')) return;
        const matchTexto = !q || row.textContent.toLowerCase().includes(q);
        const estadoCelda = row.querySelector('td:nth-last-child(2)')?.textContent.trim() || '';
        const matchEstado = estado === 'todos' || estadoCelda === estado;
        row.style.display = (matchTexto && matchEstado) ? '' : 'none';
    });
}

// Toggle estado filter dropdowns
document.addEventListener('click', function(e) {
    const toggle = e.target.closest('.estado-filter-toggle');
    if (toggle) {
        e.stopPropagation();
        const menu = toggle.parentElement.querySelector('.estado-filter-menu');
        if (menu) {
            const rect = toggle.getBoundingClientRect();
            menu.style.top = (rect.bottom + 4) + 'px';
            menu.style.left = rect.left + 'px';
            menu.style.minWidth = rect.width + 'px';
            menu.classList.toggle('hidden');
        }
        return;
    }

    const option = e.target.closest('.estado-filter-option');
    if (option) {
        const menu = option.closest('.estado-filter-menu');
        const container = menu.closest('.estado-filter-dropdown');
        const label = container.querySelector('.estado-filter-label');
        const value = option.dataset.filtro;

        label.textContent = option.textContent;
        container.dataset.filtroEstado = value;
        menu.classList.add('hidden');

        menu.querySelectorAll('.estado-filter-option').forEach(function(o) {
            o.className = 'estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg text-gray-600 dark:text-gray-400 hover:bg-sand-100 dark:hover:bg-charcoal-500';
        });
        option.className = 'estado-filter-option w-full text-left px-3 py-2 text-xs rounded-lg bg-gold-500 text-white';

        filtrarServicios();
        return;
    }

    // Close open menus when clicking outside
    document.querySelectorAll('.estado-filter-menu:not(.hidden)').forEach(function(m) {
        m.classList.add('hidden');
    });
});

// Search input filtering
document.addEventListener('input', function(e) {
    if (e.target.closest('.search-servicios')) {
        filtrarServicios();
    }
});
</script>
@endpush
