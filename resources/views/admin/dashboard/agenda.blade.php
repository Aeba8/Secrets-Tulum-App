        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Agenda de Reservas</h2>
        </div>

        {{-- Stats pills --}}
        <div class="flex flex-wrap gap-3 mb-6">
            @foreach ($agendaStats as $as)
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500">
                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $as['color'] }}"></span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $as['label'] }}</span>
                <span class="text-sm font-bold text-gray-900 dark:text-gray-100 font-mono">{{ $as['count'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Period tabs --}}
        <div class="flex gap-1 mb-6 bg-sand-100 dark:bg-charcoal-500 rounded-xl p-1 w-fit">
            @foreach ($agendaPeriods as $i => $ap)
            <button class="agenda-period-tab px-4 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ $i === 0 ? 'bg-white dark:bg-charcoal-600 text-gray-900 dark:text-gray-100 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}"
                    data-period="{{ $ap['key'] }}">
                {{ $ap['label'] }} <span class="font-mono ml-1">({{ $ap['count'] }})</span>
            </button>
            @endforeach
        </div>

        {{-- Reservation table --}}
        <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-500">
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
                                'sapphire' => 'bg-sapphire-50 dark:bg-sapphire-900/20 text-sapphire-600 dark:text-sapphire-400 border-sapphire-200 dark:border-sapphire-800',
                                'amber' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                'red' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800',
                                'gray' => 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700',
                                default => 'bg-gray-50 text-gray-500 border-gray-200',
                            };
                        @endphp
                        <tr class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
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
