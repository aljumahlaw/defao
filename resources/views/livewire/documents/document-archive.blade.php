<div class="space-y-4">
    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    البحث
                </label>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="ابحث عن وثيقة مؤرشفة..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
            </div>

            {{-- Date From --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    من تاريخ
                </label>
                <input type="date"
                       wire:model.live="dateFrom"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
            </div>

            {{-- Date To --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    إلى تاريخ
                </label>
                <input type="date"
                       wire:model.live="dateTo"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
            </div>
        </div>
    </div>

    {{-- Results Counter --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            تم العثور على <span class="font-semibold text-gray-900 dark:text-white">{{ $this->resultsCount }}</span> وثيقة مؤرشفة
        </p>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        العنوان
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        تاريخ الأرشفة
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        الإجراءات
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700" wire:loading.remove>
                @forelse($this->documents as $doc)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-archive-box class="w-4 h-4 text-gray-400" />
                                <a href="{{ route('documents.show', $doc->id) }}" 
                                   class="text-sm font-medium text-primary hover:underline">
                                    {{ $doc->title }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" dir="rtl">
                            {{ $doc->updated_at->format('Y-m-d H:i') }}
                            <span class="text-xs text-gray-400">({{ $doc->updated_at->diffForHumans() }})</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="unarchive({{ $doc->id }})"
                                        wire:confirm="هل أنت متأكد من استعادة هذه الوثيقة؟"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400"
                                        title="استعادة">
                                    <x-heroicon-o-arrow-path class="w-4 h-4" />
                                </button>
                                <button wire:click="forceDelete({{ $doc->id }})"
                                        wire:confirm="⚠️ تحذير: هذا الإجراء لا يمكن التراجع عنه. هل أنت متأكد من حذف هذه الوثيقة نهائياً؟"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400"
                                        title="حذف نهائي">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <x-heroicon-o-archive-box class="w-12 h-12 text-gray-400" />
                                <p class="text-gray-500 dark:text-gray-400">لا توجد وثائق مؤرشفة</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

            {{-- Loading Skeleton --}}
            <tbody wire:loading class="divide-y divide-gray-200 dark:divide-gray-700">
                @for($i = 0; $i < 5; $i++)
                    <tr>
                        <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div></td>
                        <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse w-32"></div></td>
                        <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse w-20"></div></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-4">
        @forelse($this->documents as $doc)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <a href="{{ route('documents.show', $doc->id) }}" 
                           class="text-sm font-medium text-primary hover:underline">
                            {{ $doc->title }}
                        </a>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            مؤرشف: {{ $doc->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3">
                    <button wire:click="unarchive({{ $doc->id }})"
                            wire:confirm="هل أنت متأكد من استعادة هذه الوثيقة؟"
                            class="flex items-center gap-1 px-3 py-1 text-xs bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded hover:bg-blue-200 dark:hover:bg-blue-900/50">
                        <x-heroicon-o-arrow-path class="w-3 h-3" />
                        استعادة
                    </button>
                    <button wire:click="forceDelete({{ $doc->id }})"
                            wire:confirm="⚠️ تحذير: هذا الإجراء لا يمكن التراجع عنه. هل أنت متأكد من حذف هذه الوثيقة نهائياً؟"
                            class="flex items-center gap-1 px-3 py-1 text-xs bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded hover:bg-red-200 dark:hover:bg-red-900/50">
                        <x-heroicon-o-trash class="w-3 h-3" />
                        حذف نهائي
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <x-heroicon-o-archive-box class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                <p class="text-gray-500 dark:text-gray-400">لا توجد وثائق مؤرشفة</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $this->documents->links() }}
    </div>
</div>
