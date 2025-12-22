@props(['icon', 'label', 'value', 'color' => 'blue'])

@php
$colorClasses = match($color) {
    'blue' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
    'yellow' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
    'green' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
    'purple' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
    default => 'bg-gray-100 dark:bg-gray-900/30 text-gray-700'
};
@endphp

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $label }}</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $value }}</p>
        </div>
        <div class="p-3 rounded-lg {{ $colorClasses }}">
            @if($icon === 'heroicon-o-clipboard-document-list')
                <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
            @elseif($icon === 'heroicon-o-document-text')
                <x-heroicon-o-document-text class="w-6 h-6" />
            @elseif($icon === 'heroicon-o-check-circle')
                <x-heroicon-o-check-circle class="w-6 h-6" />
            @elseif($icon === 'heroicon-o-archive-box')
                <x-heroicon-o-archive-box class="w-6 h-6" />
            @endif
        </div>
    </div>
</div>

