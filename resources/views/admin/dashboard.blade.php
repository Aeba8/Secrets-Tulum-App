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

    <div id="section-inventory" class="dashboard-section hidden">
        @include('admin.dashboard.inventory')
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
        tab.className = 'period-tab flex-1 py-2 px-3 rounded-lg text-xs font-medium transition-all duration-200 bg-white dark:bg-charcoal-600 text-gray-900 dark:text-gray-100 shadow-sm';
        document.querySelectorAll('.period-content').forEach(c => c.classList.add('hidden'));
        const el = document.querySelector('.period-content[data-period="' + tab.dataset.period + '"]');
        if (el) el.classList.remove('hidden');
    }

    // Department tabs
    const deptTab = e.target.closest('.dept-tab');
    if (deptTab) {
        document.querySelectorAll('.dept-tab').forEach(t => {
            t.className = 'dept-tab px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200 bg-gray-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-charcoal-500';
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
