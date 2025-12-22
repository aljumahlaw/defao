<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            الأرشيف
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Archive Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        الوثائق المؤرشفة
                    </h3>
                    
                    {{-- ArchiveTable component --}}
                    <livewire:archive.archive-table />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
