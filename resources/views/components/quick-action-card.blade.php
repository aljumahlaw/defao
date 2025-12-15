@props(['icon', 'title', 'color' => 'blue'])

@php
$colorClasses = match($color) {
    'blue' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400',
    'green' => 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400',
    'purple' => 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400',
    default => 'bg-gray-50 dark:bg-gray-900/20 text-gray-600'
};
@endphp

<button class="flex flex-col items-center gap-3 p-6 rounded-lg {{ $colorClasses }} hover:shadow-md hover:-translate-y-1 transition-all duration-200">
    @if($icon === 'heroicon-o-plus-circle')
        <x-heroicon-o-plus-circle class="w-8 h-8" />
    @elseif($icon === 'heroicon-o-arrow-up-tray')
        <x-heroicon-o-arrow-up-tray class="w-8 h-8" />
    @elseif($icon === 'heroicon-o-chart-bar')
        <x-heroicon-o-chart-bar class="w-8 h-8" />
    @endif
    <span class="font-medium">{{ $title }}</span>
</button>
