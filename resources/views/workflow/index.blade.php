<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                سير العمل
            </h2>
            <a href="{{ route('documents.archive') }}" 
               class="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                <x-heroicon-o-archive-box class="w-5 h-5" />
                <span>الأرشيف</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Workflow Stages --}}
            <div class="space-y-6">
                <livewire:workflow.workflow-overview />

                {{-- Workflow Stage Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <livewire:workflow.workflow-stage-card stage="draft" />
                    <livewire:workflow.workflow-stage-card stage="review1" />
                    <livewire:workflow.workflow-stage-card stage="proofread" />
                    <livewire:workflow.workflow-stage-card stage="finalapproval" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
