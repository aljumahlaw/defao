<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                الوثائق
            </h2>
            <a href="{{ route('documents.upload') }}"
               class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                <x-heroicon-o-arrow-up-tray class="w-5 h-5" />
                <span>رفع وثيقة جديدة</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:global-search page="documents" />
            <livewire:documents.document-table />
        </div>
    </div>
</x-app-layout>

