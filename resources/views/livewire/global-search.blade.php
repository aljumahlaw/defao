<div class="mb-6 space-y-4">
    {{-- Search Bar --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            {{-- Search Input --}}
            <div class="flex-1 relative">
                <div class="absolute right-3 top-1/2 -translate-y-1/2">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400" />
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ $page === 'documents' ? 'ابحث في الوثائق...' : 'ابحث في المهام...' }}"
                    class="w-full pr-10 pl-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-primary"
                />
                @if($search)
                    <button 
                        wire:click="clearSearch"
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                    >
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                @endif
            </div>

            {{-- Filters Toggle Button --}}
            <button 
                wire:click="toggleFilters"
                class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
            >
                <x-heroicon-o-funnel class="w-5 h-5" />
                <span>فلاتر</span>
            </button>
        </div>
    </div>

    {{-- Filters Dropdown --}}
    @if($showFilters)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        الحالة
                    </label>
                    <select 
                        wire:model.live="statusFilter"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                    >
                        @if($page === 'documents')
                            <option value="all">الكل</option>
                            <option value="draft">مسودة</option>
                            <option value="review1">مراجعة أولى</option>
                            <option value="proofread">تدقيق</option>
                            <option value="finalapproval">موافقة نهائية</option>
                        @else
                            <option value="all">الكل</option>
                            <option value="pending">معلقة</option>
                            <option value="in_progress">قيد التنفيذ</option>
                            <option value="completed">مكتملة</option>
                            <option value="overdue">متأخرة</option>
                        @endif
                    </select>
                </div>

                {{-- Type Filter (Documents only) --}}
                @if($page === 'documents')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            النوع
                        </label>
                        <select 
                            wire:model.live="typeFilter"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                        >
                            <option value="all">الكل</option>
                            <option value="incoming">وارد</option>
                            <option value="outgoing">صادر</option>
                        </select>
                    </div>
                @endif

                {{-- Date From --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        من تاريخ
                    </label>
                    <input 
                        type="date" 
                        wire:model.live="dateFrom"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                    />
                </div>

                {{-- Date To --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        إلى تاريخ
                    </label>
                    <input 
                        type="date" 
                        wire:model.live="dateTo"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                    />
                </div>
            </div>

            {{-- Clear Filters Button --}}
            <div class="mt-4 flex justify-end">
                <button 
                    wire:click="clearFilters"
                    class="w-full sm:w-auto px-4 py-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                >
                    مسح الفلاتر
                </button>
            </div>
        </div>
    @endif
</div>
