<div>
    {{-- Search --}}
    <div class="mb-4">
        <div class="relative">
            <input type="text" 
                   wire:model.live.debounce.300ms="search"
                   placeholder="ابحث في الوثائق المؤرشفة..."
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
            <x-heroicon-o-magnifying-glass class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" />
        </div>
    </div>

    {{-- Table --}}
    @if($this->archivedDocuments->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                العنوان
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                النوع
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                المنشئ
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                تاريخ الإنشاء
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->archivedDocuments as $doc)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $doc->title }}
                                    </div>
                                    @if($doc->description)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ Str::limit($doc->description, 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $doc->type === 'incoming' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' }}">
                                        {{ $doc->type_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $doc->creator->name ?? 'غير معروف' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" dir="rtl">
                                    {{ $doc->created_at->diffForHumans() }}
                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $doc->created_at->format('Y-m-d H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="unarchive({{ $doc->id }})" 
                                                wire:confirm="هل أنت متأكد من إلغاء أرشفة؟"
                                                class="text-green-600 hover:text-green-800 dark:text-green-400 hover:underline"
                                                title="إلغاء أرشفة">
                                            <x-heroicon-o-arrow-path class="w-5 h-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->archivedDocuments->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
            <x-heroicon-o-archive-box class="w-16 h-16 mx-auto mb-4 text-gray-400" />
            <p>لا توجد وثائق مؤرشفة حالياً</p>
        </div>
    @endif
</div>



