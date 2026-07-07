        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Rendimiento del Equipo (Ventas Internas)</h2>
        </div>

        <div class="flex gap-2 mb-4">
            <button class="dept-tab px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200 bg-gold-500 text-white" data-dept="all">
                Todos los Departamentos
            </button>
            @foreach ($departments as $dept)
            <button class="dept-tab px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200 bg-gray-100 dark:bg-charcoal-500 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-charcoal-500" data-dept="{{ strtolower($dept) }}">
                {{ $dept }}
            </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-3 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Top 5 Colaboradores</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500">
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
                            <tr class="collab-row border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors" data-dept="{{ strtolower($col['department']) }}">
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
                                        <div class="w-16 h-1.5 rounded-full bg-sand-100 dark:bg-charcoal-500 overflow-hidden">
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

            <div class="lg:col-span-2 bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm mb-4">Eficiencia por Turno</h3>
                <div class="relative h-72 w-full"><canvas id="shiftEfficiencyChart"></canvas></div>
            </div>
        </div>
