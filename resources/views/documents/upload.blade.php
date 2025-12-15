<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                رفع وثيقة جديدة
            </h2>
            <a href="{{ route('documents.index') }}"
               class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                <x-heroicon-o-arrow-right class="w-5 h-5" />
                <span>رجوع</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:documents.document-upload />
        </div>
    </div>
</x-app-layout>
