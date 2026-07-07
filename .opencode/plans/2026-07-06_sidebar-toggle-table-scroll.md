# Plan: Sidebar toggle + Table scroll fix

## Edit 1 — `resources/views/admin/layouts/dashboard.blade.php`

### 1a. Replace Dashboard General link + add submenu container ID

**oldString:**
```
                {{-- Dashboard General --}}
                <a href="#general" data-section="general"
                   class="nav-item nav-section-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 nav-item-active">
                    <i class="fa-solid fa-gauge-high w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label">Dashboard General</span>
                </a>

                {{-- Sub-apartados --}}
                <div class="ml-2 mt-0.5 mb-1 border-l-2 border-gray-100 pl-2 space-y-0.5">
                    <span class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold ml-3 nav-label block mt-2 mb-1">Módulos de Métricas</span>
```

**newString:**
```
                {{-- Dashboard General (toggle padre) --}}
                <button id="dashboard-parent-toggle"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 nav-item-active w-full text-left">
                    <i class="fa-solid fa-gauge-high w-5 text-center flex-shrink-0"></i>
                    <span class="whitespace-nowrap nav-label flex-1">Dashboard General</span>
                    <i id="dashboard-chevron" class="fa-solid fa-chevron-down text-xs transition-transform duration-300 nav-label"></i>
                </button>

                {{-- Sub-apartados (colapsable) --}}
                <div id="submenu-container" class="ml-2 border-l-2 border-gray-100 pl-2 space-y-0.5 overflow-hidden transition-all duration-300 max-h-[500px] opacity-100">
                    <span class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold ml-3 nav-label block mt-2 mb-1">Módulos de Métricas</span>
```

### 1b. Add toggle JS + integrate with sidebar collapse

Insert after `navLabels.forEach(el => el.style.display = '');` (inside sidebar toggle else block — line 236):

**oldString:**
```
                navLabels.forEach(el => el.style.display = '');
            }
        });

        // Current date
```

**newString:**
```
                navLabels.forEach(el => el.style.display = '');
                if (dashboardChevron) dashboardChevron.style.display = '';
            }
            if (sidebarCollapsed && dashboardChevron) dashboardChevron.style.display = 'none';
        });

        // Dashboard parent toggle
        const parentToggle = document.getElementById('dashboard-parent-toggle');
        const submenuContainer = document.getElementById('submenu-container');
        const dashboardChevron = document.getElementById('dashboard-chevron');

        if (parentToggle && submenuContainer && dashboardChevron) {
            parentToggle.addEventListener('click', () => {
                const isCollapsed = submenuContainer.style.maxHeight === '0px' || submenuContainer.classList.contains('max-h-0');
                if (isCollapsed) {
                    submenuContainer.style.maxHeight = submenuContainer.scrollHeight + 'px';
                    submenuContainer.style.opacity = '1';
                    submenuContainer.classList.remove('max-h-0');
                    dashboardChevron.style.transform = 'rotate(0deg)';
                } else {
                    submenuContainer.style.maxHeight = '0px';
                    submenuContainer.style.opacity = '0';
                    submenuContainer.classList.add('max-h-0');
                    dashboardChevron.style.transform = 'rotate(-90deg)';
                }
                showSection('general');
            });
        }

        // Current date
```

---

## Edit 2 — `resources/views/admin/dashboard.blade.php`

### 2a. Inventory table — overflow-x-auto + whitespace-nowrap

**oldString:** (line 153)
```
                <div class="overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="text-left pb-3 font-medium">Insumo</th>
                                <th class="text-center pb-3 font-medium">Stock Actual</th>
                                <th class="text-center pb-3 font-medium">Reservado</th>
                                <th class="text-center pb-3 font-medium">Estado</th>
                                <th class="text-right pb-3 font-medium">A Ordenar</th>
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
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">
                                <td class="py-3 text-gray-900 font-medium">{{ $item['item'] }}</td>
                                <td class="py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-20 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                            <div class="h-full rounded-full bg-gold-500" style="width: {{ ($item['stock'] / $maxBar) * 100 }}%"></div>
                                        </div>
                                        <span class="text-gray-900 font-mono text-xs">{{ $item['stock'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-20 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                            <div class="h-full rounded-full" style="width: {{ ($item['reserved'] / $maxBar) * 100 }}%; background-color: {{ $item['status'] === 'danger' ? '#EF4444' : ($item['status'] === 'warning' ? '#F59E0B' : '#10B981') }};"></div>
                                        </div>
                                        <span class="text-gray-900 font-mono text-xs">{{ $item['reserved'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md {{ $st['bg'] }} {{ $st['color'] }} font-medium">{{ $st['label'] }}</span>
                                </td>
                                <td class="py-3 text-right">
                                    @if ($toOrder > 0)
                                    <span class="text-gray-900 font-mono font-bold text-sm">{{ $toOrder }} {{ $item['unit'] }}</span>
                                    @else
                                    <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
```

**newString:**
```
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
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
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">
                                <td class="py-3 text-gray-900 font-medium whitespace-nowrap">{{ $item['item'] }}</td>
                                <td class="py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-20 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                            <div class="h-full rounded-full bg-gold-500" style="width: {{ ($item['stock'] / $maxBar) * 100 }}%"></div>
                                        </div>
                                        <span class="text-gray-900 font-mono text-xs">{{ $item['stock'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-20 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                            <div class="h-full rounded-full" style="width: {{ ($item['reserved'] / $maxBar) * 100 }}%; background-color: {{ $item['status'] === 'danger' ? '#EF4444' : ($item['status'] === 'warning' ? '#F59E0B' : '#10B981') }};"></div>
                                        </div>
                                        <span class="text-gray-900 font-mono text-xs">{{ $item['reserved'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-center whitespace-nowrap">
                                    <span class="text-xs px-2 py-0.5 rounded-md {{ $st['bg'] }} {{ $st['color'] }} font-medium">{{ $st['label'] }}</span>
                                </td>
                                <td class="py-3 text-right whitespace-nowrap">
                                    @if ($toOrder > 0)
                                    <span class="text-gray-900 font-mono font-bold text-sm whitespace-nowrap">{{ $toOrder }} {{ $item['unit'] }}</span>
                                    @else
                                    <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
```

### 2b. Top packages table — overflow-x-auto + whitespace-nowrap

**oldString:** (lines 267-302)
```
                <div class="overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="text-left pb-2 font-medium">#</th>
                                <th class="text-left pb-2 font-medium">Paquete</th>
                                <th class="text-left pb-2 font-medium">Tipo</th>
                                <th class="text-right pb-2 font-medium">Ingresos</th>
                                <th class="text-right pb-2 font-medium">Margen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topPackages as $pkg)
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">
                                <td class="py-2.5 text-gray-400 text-xs">{{ $pkg['position'] }}</td>
                                <td class="py-2.5 text-gray-900 font-medium">{{ $pkg['name'] }}</td>
                                <td class="py-2.5">
                                    @php
                                        $badgeColor = match($pkg['type']) {
                                            'Balinesa' => 'bg-amber-50 text-amber-600 border-amber-200',
                                            'Cena' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            'Experiencia' => 'bg-blue-50 text-blue-600 border-blue-200',
                                            default => 'bg-gray-50 text-gray-500 border-gray-200',
                                        };
                                    @endphp
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $badgeColor }}">{{ $pkg['type'] }}</span>
                                </td>
                                <td class="py-2.5 text-gray-900 text-right font-mono">${{ number_format($pkg['revenue']) }}</td>
                                <td class="py-2.5 text-right">
                                    <span class="text-emerald-600 font-mono text-xs">{{ $pkg['margin'] }}%</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
```

**newString:**
```
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="text-left pb-2 font-medium whitespace-nowrap">#</th>
                                <th class="text-left pb-2 font-medium whitespace-nowrap">Paquete</th>
                                <th class="text-left pb-2 font-medium whitespace-nowrap">Tipo</th>
                                <th class="text-right pb-2 font-medium whitespace-nowrap">Ingresos</th>
                                <th class="text-right pb-2 font-medium whitespace-nowrap">Margen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topPackages as $pkg)
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">
                                <td class="py-2.5 text-gray-400 text-xs whitespace-nowrap">{{ $pkg['position'] }}</td>
                                <td class="py-2.5 text-gray-900 font-medium whitespace-nowrap">{{ $pkg['name'] }}</td>
                                <td class="py-2.5 whitespace-nowrap">
                                    @php
                                        $badgeColor = match($pkg['type']) {
                                            'Balinesa' => 'bg-amber-50 text-amber-600 border-amber-200',
                                            'Cena' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            'Experiencia' => 'bg-blue-50 text-blue-600 border-blue-200',
                                            default => 'bg-gray-50 text-gray-500 border-gray-200',
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
```

### 2c. Collaborators table — overflow-x-auto + whitespace-nowrap

**oldString:** (lines 545-586)
```
                <div class="overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="text-left pb-2 font-medium">#</th>
                                <th class="text-left pb-2 font-medium">Colaborador (ID)</th>
                                <th class="text-left pb-2 font-medium">Depto.</th>
                                <th class="text-right pb-2 font-medium">Reservas</th>
                                <th class="text-right pb-2 font-medium">Monto Vendido</th>
                                <th class="text-right pb-2 font-medium">Eficiencia</th>
                            </tr>
                        </thead>
                        <tbody id="collaboratorsBody">
                            @foreach ($topCollaborators as $col)
                            <tr class="collab-row border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors" data-dept="{{ strtolower($col['department']) }}">
                                <td class="py-3">
                                    <div class="w-6 h-6 rounded-full bg-gold-100 flex items-center justify-center text-xs font-bold text-gold-600">
                                        {{ $col['position'] }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="text-gray-900 font-medium">{{ $col['name'] }}</span>
                                    <span class="text-gray-400 text-xs ml-1 font-mono">({{ $col['id'] }})</span>
                                </td>
                                <td class="py-3">
                                    <span class="text-xs text-gray-500">{{ $col['department'] }}</span>
                                </td>
                                <td class="py-3 text-gray-900 text-right font-mono">{{ $col['reservations'] }}</td>
                                <td class="py-3 text-gray-900 text-right font-mono">${{ number_format($col['amount']) }}</td>
                                <td class="py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="w-16 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-gold-500 to-gold-400" style="width: {{ $col['efficiency'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-mono text-gold-600">{{ $col['efficiency'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
```

**newString:**
```
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
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
                            <tr class="collab-row border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors" data-dept="{{ strtolower($col['department']) }}">
                                <td class="py-3 whitespace-nowrap">
                                    <div class="w-6 h-6 rounded-full bg-gold-100 flex items-center justify-center text-xs font-bold text-gold-600">
                                        {{ $col['position'] }}
                                    </div>
                                </td>
                                <td class="py-3 whitespace-nowrap">
                                    <span class="text-gray-900 font-medium">{{ $col['name'] }}</span>
                                    <span class="text-gray-400 text-xs ml-1 font-mono">({{ $col['id'] }})</span>
                                </td>
                                <td class="py-3 whitespace-nowrap">
                                    <span class="text-xs text-gray-500">{{ $col['department'] }}</span>
                                </td>
                                <td class="py-3 text-gray-900 text-right font-mono whitespace-nowrap">{{ $col['reservations'] }}</td>
                                <td class="py-3 text-gray-900 text-right font-mono whitespace-nowrap">${{ number_format($col['amount']) }}</td>
                                <td class="py-3 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="w-16 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-gold-500 to-gold-400" style="width: {{ $col['efficiency'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-mono text-gold-600">{{ $col['efficiency'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
```
