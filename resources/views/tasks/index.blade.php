<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                المهام
            </h2>
            <button x-data @click="$dispatch('open-task-form-modal')"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                <x-heroicon-o-plus class="w-5 h-5" />
                <span>مهمة جديدة</span>
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:global-search page="tasks" />
            <livewire:tasks.task-list />
            <livewire:tasks.task-form />
        </div>
    </div>
</x-app-layout>
