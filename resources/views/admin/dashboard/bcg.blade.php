        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Análisis Estratégico de Catálogo — Matriz BCG</h2>
        </div>

        {{-- Summary cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gold-500/10 flex items-center justify-center text-gold-500">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Paquetes Activos</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 font-mono">{{ $bcgSummary['total_products'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gold-500/10 flex items-center justify-center text-gold-500">
                        <i class="fa-solid fa-dollar-sign"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Revenue Total (30d)</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 font-mono">${{ number_format($bcgSummary['total_revenue']) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gold-500/10 flex items-center justify-center text-gold-500">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ganancias Totales</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 font-mono">${{ number_format($bcgSummary['total_profit']) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gold-500/10 flex items-center justify-center text-gold-500">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">+ Rentable</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100 truncate max-w-[180px]">{{ $bcgSummary['most_profitable'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- BCG Scatter Chart --}}
        <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-6 mb-6">
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

        {{-- Collapsible table --}}
        <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Detalle de Productos</h3>
                <button id="bcgToggleBtn" onclick="toggleBcgTable()" class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white hover:bg-gold-600 transition-colors">
                    Mostrar detalle completo
                </button>
            </div>
            <div id="bcgTableContainer" class="hidden overflow-x-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500">
                            <th class="text-left pb-2 pr-2 font-medium">#</th>
                            <th class="text-left pb-2 pr-2 font-medium">Producto</th>
                            <th class="text-left pb-2 pr-2 font-medium">Tipo</th>
                            <th class="text-left pb-2 pr-2 font-medium">Categoría</th>
                            <th class="text-right pb-2 pr-2 font-medium">Crec.</th>
                            <th class="text-right pb-2 pr-2 font-medium">Part.</th>
                            <th class="text-right pb-2 pr-2 font-medium">Revenue</th>
                            <th class="text-right pb-2 pr-2 font-medium">Margen</th>
                            <th class="text-right pb-2 pr-2 font-medium">Pop.</th>
                            <th class="text-center pb-2 pr-2 font-medium">Cuadrante</th>
                            <th class="text-left pb-2 font-medium">Recomendación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bcgProducts as $i => $p)
                        <tr class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                            <td class="py-2.5 pr-2 text-gray-400 text-xs">{{ $i + 1 }}</td>
                            <td class="py-2.5 pr-2 text-gray-900 dark:text-gray-100 font-medium">{{ $p['name'] }}</td>
                            <td class="py-2.5 pr-2">
                                <span class="text-xs px-1.5 py-0.5 rounded-md border {{ $p['type'] === 'Balinesa' ? 'bg-amber-50 text-amber-600 border-amber-200' : ($p['type'] === 'Cena' ? 'bg-sapphire-50 text-sapphire-600 border-sapphire-200' : 'bg-blue-50 text-blue-600 border-blue-200') }}">
                                    {{ $p['type'] }}
                                </span>
                            </td>
                            <td class="py-2.5 pr-2 text-xs text-gray-500">{{ $p['category'] }}</td>
                            <td class="py-2.5 pr-2 text-right font-mono text-xs {{ $p['growth'] >= 0 ? 'text-sapphire-600' : 'text-red-500' }}">{{ $p['growth'] >= 0 ? '+' : '' }}{{ $p['growth'] }}%</td>
                            <td class="py-2.5 pr-2 text-right font-mono text-xs text-gray-700 dark:text-gray-300">{{ $p['share'] }}%</td>
                            <td class="py-2.5 pr-2 text-right font-mono text-xs text-gray-900 dark:text-gold-400">${{ number_format($p['revenue']) }}</td>
                            <td class="py-2.5 pr-2 text-right font-mono text-xs text-sapphire-600">{{ $p['margin'] }}%</td>
                            <td class="py-2.5 pr-2 text-right font-mono text-xs text-gray-700 dark:text-gray-300">{{ $p['count'] }}</td>
                            <td class="py-2.5 pr-2 text-center">
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background-color: {{ $p['color'] }}15; color: {{ $p['color'] }};">
                                    {{ $bcgQuadrants[$p['quadrant']]['label'] }}
                                </span>
                            </td>
                            <td class="py-2.5 text-xs text-gray-500 dark:text-gray-400 max-w-[200px] whitespace-normal">{{ $p['recommendation'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Popularidad vs Rentabilidad --}}
        <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Popularidad vs. Rentabilidad</h3>
                <span class="text-xs text-gray-500 dark:text-gray-400">Tamaño del círculo = Revenue | Eje X = # Reservas | Eje Y = Margen %</span>
            </div>
            <div class="relative h-[420px] w-full">
                <canvas id="popularityChart"></canvas>
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

        <script>
        function toggleBcgTable() {
            const container = document.getElementById('bcgTableContainer');
            const btn = document.getElementById('bcgToggleBtn');
            const isHidden = container.classList.contains('hidden');
            container.classList.toggle('hidden');
            btn.textContent = isHidden ? 'Ocultar detalle' : 'Mostrar detalle completo';
        }
        </script>
