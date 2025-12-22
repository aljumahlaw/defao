<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Dashboard Overview Component --}}
            <livewire:dashboard.dashboard-overview />
        </div>
    </div>
    
    <livewire:tasks.task-form wire:key="task-form" />
</x-app-layout>
