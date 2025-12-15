<div class="space-y-4">
    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Type Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    النوع
                </label>
                <select wire:model.live="type" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="all">الكل</option>
                    <option value="incoming">وارد</option>
                    <option value="outgoing">صادر</option>
                </select>
            </div>

            {{-- Stage Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    المرحلة
                </label>
                <select wire:model.live="stage" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="all">الكل</option>
                    <option value="draft">مسودة</option>
                    <option value="review1">مراجعة أولى</option>
                    <option value="proofread">تدقيق</option>
                    <option value="finalapproval">موافقة نهائية</option>
                </select>
            </div>

            {{-- Archived Toggle --}}
            <div class="flex items-end">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="archived"
                           class="rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm text-gray-700 dark:text-gray-300">عرض المؤرشفة فقط</span>
                </label>
            </div>
        </div>
    </div>

    {{-- Results Counter --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            تم العثور على <span class="font-semibold text-gray-900 dark:text-white">{{ $this->resultsCount }}</span> وثيقة
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
                        النوع
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        المرحلة
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        الحجم
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        تاريخ الإنشاء
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
                                <x-heroicon-o-document-text class="w-4 h-4 text-gray-400" />
                                <a href="{{ route('documents.show', $doc->id) }}" 
                                   class="text-sm font-medium text-primary hover:underline">
                                    {{ $doc->title }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getTypeBadgeClass($doc->type) }}">
                                {{ $this->getTypeLabel($doc->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStageBadgeClass($doc->current_stage) }}">
                                {{ $this->getStageLabel($doc->current_stage) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $doc->file_size }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" dir="rtl">
                            {{ $doc->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('documents.show', $doc->id) }}"
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400"
                                   title="عرض">
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                </a>
                                <button wire:click="downloadDocument({{ $doc->id }})" 
                                        class="text-green-600 hover:text-green-800 dark:text-green-400"
                                        title="تنزيل">
                                    <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                                </button>
                                <button wire:click="uploadNewVersion({{ $doc->id }})" 
                                        class="text-purple-600 hover:text-purple-800 dark:text-purple-400 {{ $doc->current_stage === 'finalapproval' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $doc->current_stage === 'finalapproval' ? 'disabled' : '' }}
                                        title="نسخة جديدة">
                                    <x-heroicon-o-arrow-up-tray class="w-4 h-4" />
                                </button>
                                @if(!$doc->is_archived)
                                    <button wire:click="archiveDocument({{ $doc->id }})" 
                                            class="text-gray-600 hover:text-gray-800 dark:text-gray-400"
                                            title="أرشفة">
                                        <x-heroicon-o-archive-box class="w-4 h-4" />
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <x-heroicon-o-document-text class="w-12 h-12 text-gray-400" />
                                <p class="text-gray-500 dark:text-gray-400">لا توجد وثائق</p>
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
                        <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse w-20"></div></td>
                        <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse w-24"></div></td>
                        <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse w-16"></div></td>
                        <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div></td>
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
                <div class="flex items-start gap-2 mb-3">
                    <x-heroicon-o-document-text class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" />
                    <a href="{{ route('documents.show', $doc->id) }}" 
                       class="font-semibold text-primary hover:underline">{{ $doc->title }}</a>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">النوع:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getTypeBadgeClass($doc->type) }}">
                            {{ $this->getTypeLabel($doc->type) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">المرحلة:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStageBadgeClass($doc->current_stage) }}">
                            {{ $this->getStageLabel($doc->current_stage) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        الحجم: {{ $doc->file_size }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400" dir="rtl">
                        {{ $doc->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('documents.show', $doc->id) }}"
                       class="flex items-center justify-center gap-2 px-3 py-2 bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400 rounded-lg hover:bg-blue-100">
                        <x-heroicon-o-eye class="w-4 h-4" />
                        <span class="text-sm">عرض</span>
                    </a>
                    <button wire:click="downloadDocument({{ $doc->id }})" 
                            class="flex items-center justify-center gap-2 px-3 py-2 bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 rounded-lg hover:bg-green-100">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                        <span class="text-sm">تنزيل</span>
                    </button>
                    <button wire:click="uploadNewVersion({{ $doc->id }})" 
                            class="flex items-center justify-center gap-2 px-3 py-2 bg-purple-50 text-purple-700 dark:bg-purple-900/20 dark:text-purple-400 rounded-lg hover:bg-purple-100 {{ $doc->current_stage === 'finalapproval' ? 'opacity-50' : '' }}"
                            {{ $doc->current_stage === 'finalapproval' ? 'disabled' : '' }}>
                        <x-heroicon-o-arrow-up-tray class="w-4 h-4" />
                        <span class="text-sm">نسخة جديدة</span>
                    </button>
                    @if(!$doc->is_archived)
                        <button wire:click="archiveDocument({{ $doc->id }})" 
                                class="flex items-center justify-center gap-2 px-3 py-2 bg-gray-50 text-gray-700 dark:bg-gray-900/20 dark:text-gray-400 rounded-lg hover:bg-gray-100">
                            <x-heroicon-o-archive-box class="w-4 h-4" />
                            <span class="text-sm">أرشفة</span>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12">
                <div class="flex flex-col items-center gap-3">
                    <x-heroicon-o-document-text class="w-16 h-16 text-gray-400" />
                    <p class="text-gray-500 dark:text-gray-400 text-center">لا توجد وثائق</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $this->documents->links() }}
    </div>
</div>
