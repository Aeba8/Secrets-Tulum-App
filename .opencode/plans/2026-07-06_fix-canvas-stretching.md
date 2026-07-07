# Plan: Fix vertical stretching of Chart.js canvases

## Root cause
Cards inside Flexbox layout (`h-screen overflow-hidden`) inherit `align-items: stretch`. Canvases without a fixed-height parent expand infinitely.

## Fix
Wrap each `<canvas>` in `<div class="relative h-{size} w-full">` to give Chart.js a bounded container.

## 9 edits — `resources/views/admin/dashboard.blade.php`

### 1. monthlyRevenueChart (line 69) — small card
**oldString:** `<canvas id="monthlyRevenueChart" height="180"></canvas>`
**newString:** `<div class="relative h-72 w-full"><canvas id="monthlyRevenueChart"></canvas></div>`

### 2. bcgChart (line 126-128) — BCG matrix card
**oldString:**
```
            <div class="relative" style="height: 420px;">
                <canvas id="bcgChart"></canvas>
            </div>
```
**newString:**
```
            <div class="relative h-[420px] w-full">
                <canvas id="bcgChart"></canvas>
            </div>
```

### 3. inventoryChart (line 218) — right column card
**oldString:** `<canvas id="inventoryChart" height="280"></canvas>`
**newString:** `<div class="relative h-72 w-full"><canvas id="inventoryChart"></canvas></div>`

### 4. revenueByTypeChart (line 236) — doughnut card
**oldString:** `<canvas id="revenueByTypeChart" height="200"></canvas>`
**newString:** `<div class="relative h-72 w-full"><canvas id="revenueByTypeChart"></canvas></div>`

### 5. monthlyRevenueChart2 (line 262) — financial section line chart
**oldString:** `<canvas id="monthlyRevenueChart2" height="200"></canvas>`
**newString:** `<div class="relative h-72 w-full"><canvas id="monthlyRevenueChart2"></canvas></div>`

### 6. weeklyComparisonChart (line 312) — small bar chart
**oldString:** `<canvas id="weeklyComparisonChart" height="120"></canvas>`
**newString:** `<div class="relative h-40 w-full"><canvas id="weeklyComparisonChart"></canvas></div>`

### 7. occupancyChart (line 367) — doughnut card
**oldString:** `<canvas id="occupancyChart" height="200"></canvas>`
**newString:** `<div class="relative h-72 w-full"><canvas id="occupancyChart"></canvas></div>`

### 8. peakHoursChart (line 408) — bar chart
**oldString:** `<canvas id="peakHoursChart" height="140"></canvas>`
**newString:** `<div class="relative h-40 w-full"><canvas id="peakHoursChart"></canvas></div>`

### 9. shiftEfficiencyChart (line 591) — horizontal bar
**oldString:** `<canvas id="shiftEfficiencyChart" height="220"></canvas>`
**newString:** `<div class="relative h-72 w-full"><canvas id="shiftEfficiencyChart"></canvas></div>`
