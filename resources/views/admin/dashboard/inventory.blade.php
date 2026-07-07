        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Previsiones de Inventario Estimado</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-3 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Insumos Críticos — Stock vs. Reservas (Próximos 7 días)</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500">
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
                                'ok' => ['label' => 'OK', 'color' => 'text-sapphire-600', 'bg' => 'bg-sapphire-50'],
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
                            <tr class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                <td class="py-3 text-gray-900 dark:text-gray-100 font-medium whitespace-nowrap">{{ $item['item'] }}</td>
                                <td class="py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-20 h-1.5 rounded-full bg-sand-100 dark:bg-charcoal-500 overflow-hidden">
                                            <div class="h-full rounded-full bg-gold-500" style="width: {{ ($item['stock'] / $maxBar) * 100 }}%"></div>
                                        </div>
                                        <span class="text-gray-900 dark:text-gray-100 font-mono text-xs">{{ $item['stock'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-20 h-1.5 rounded-full bg-sand-100 dark:bg-charcoal-500 overflow-hidden">
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

            <div class="lg:col-span-2 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Stock Actual vs. Requerido</h3>
                <div class="relative h-72 w-full"><canvas id="inventoryChart"></canvas></div>
            </div>
        </div>
