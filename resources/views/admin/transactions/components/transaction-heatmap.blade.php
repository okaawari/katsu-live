{{-- Transaction Heatmap Component --}}
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Transaction Activity Heatmap</h3>
            <div class="flex space-x-2">
                <button onclick="toggleHeatmapView('hourly')" id="hourlyBtn" class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    Hourly
                </button>
                <button onclick="toggleHeatmapView('daily')" id="dailyBtn" class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    Daily
                </button>
            </div>
        </div>
    </div>
    <div class="p-6">
        <!-- Hourly Heatmap -->
        <div id="hourlyHeatmap" class="space-y-2">
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                <span>Transaction intensity by hour of day</span>
                <div class="flex items-center space-x-2">
                    <span>Low</span>
                    <div class="flex space-x-1">
                        <div class="w-3 h-3 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        <div class="w-3 h-3 bg-blue-200 dark:bg-blue-800 rounded"></div>
                        <div class="w-3 h-3 bg-blue-400 dark:bg-blue-600 rounded"></div>
                        <div class="w-3 h-3 bg-blue-600 dark:bg-blue-400 rounded"></div>
                        <div class="w-3 h-3 bg-blue-800 dark:bg-blue-300 rounded"></div>
                    </div>
                    <span>High</span>
                </div>
            </div>
            
            <div class="grid grid-cols-24 gap-1">
                @for($hour = 0; $hour < 24; $hour++)
                    @php
                        // Simulate transaction intensity (in real app, get from database)
                        $intensity = rand(0, 100);
                        $colorClass = $intensity < 20 ? 'bg-gray-200 dark:bg-gray-700' : 
                                     ($intensity < 40 ? 'bg-blue-200 dark:bg-blue-800' : 
                                     ($intensity < 60 ? 'bg-blue-400 dark:bg-blue-600' : 
                                     ($intensity < 80 ? 'bg-blue-600 dark:bg-blue-400' : 'bg-blue-800 dark:bg-blue-300')));
                    @endphp
                    <div class="w-4 h-8 {{ $colorClass }} rounded tooltip-trigger" 
                         data-tooltip="Hour {{ $hour }}:00 - {{ $intensity }}% activity"
                         onmouseover="showTooltip(event, 'Hour {{ $hour }}:00 - {{ $intensity }}% activity')">
                    </div>
                @endfor
            </div>
            
            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                @for($hour = 0; $hour < 24; $hour += 6)
                    <span>{{ $hour }}:00</span>
                @endfor
            </div>
        </div>

        <!-- Daily Heatmap -->
        <div id="dailyHeatmap" class="hidden space-y-2">
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                <span>Transaction intensity by day of week</span>
                <div class="flex items-center space-x-2">
                    <span>Low</span>
                    <div class="flex space-x-1">
                        <div class="w-3 h-3 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        <div class="w-3 h-3 bg-green-200 dark:bg-green-800 rounded"></div>
                        <div class="w-3 h-3 bg-green-400 dark:bg-green-600 rounded"></div>
                        <div class="w-3 h-3 bg-green-600 dark:bg-green-400 rounded"></div>
                        <div class="w-3 h-3 bg-green-800 dark:bg-green-300 rounded"></div>
                    </div>
                    <span>High</span>
                </div>
            </div>
            
            <div class="grid grid-cols-7 gap-2">
                @php $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] @endphp
                @foreach($days as $day)
                    @php
                        $intensity = rand(0, 100);
                        $colorClass = $intensity < 20 ? 'bg-gray-200 dark:bg-gray-700' : 
                                     ($intensity < 40 ? 'bg-green-200 dark:bg-green-800' : 
                                     ($intensity < 60 ? 'bg-green-400 dark:bg-green-600' : 
                                     ($intensity < 80 ? 'bg-green-600 dark:bg-green-400' : 'bg-green-800 dark:bg-green-300')));
                    @endphp
                    <div class="text-center">
                        <div class="w-full h-16 {{ $colorClass }} rounded-lg mb-2 tooltip-trigger"
                             data-tooltip="{{ $day }} - {{ $intensity }}% activity"
                             onmouseover="showTooltip(event, '{{ $day }} - {{ $intensity }}% activity')">
                        </div>
                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ substr($day, 0, 3) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Tooltip -->
        <div id="heatmapTooltip" class="absolute bg-gray-900 text-white text-xs rounded py-1 px-2 pointer-events-none opacity-0 transition-opacity z-10">
        </div>
    </div>
</div>

<script>
function toggleHeatmapView(view) {
    const hourlyView = document.getElementById('hourlyHeatmap');
    const dailyView = document.getElementById('dailyHeatmap');
    const hourlyBtn = document.getElementById('hourlyBtn');
    const dailyBtn = document.getElementById('dailyBtn');
    
    if (view === 'hourly') {
        hourlyView.classList.remove('hidden');
        dailyView.classList.add('hidden');
        hourlyBtn.className = 'px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        dailyBtn.className = 'px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300';
    } else {
        dailyView.classList.remove('hidden');
        hourlyView.classList.add('hidden');
        dailyBtn.className = 'px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        hourlyBtn.className = 'px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300';
    }
}

function showTooltip(event, text) {
    const tooltip = document.getElementById('heatmapTooltip');
    tooltip.textContent = text;
    tooltip.style.left = event.pageX + 10 + 'px';
    tooltip.style.top = event.pageY - 30 + 'px';
    tooltip.classList.remove('opacity-0');
}

// Hide tooltip when not hovering
document.addEventListener('mouseleave', function() {
    document.getElementById('heatmapTooltip').classList.add('opacity-0');
});
</script>
