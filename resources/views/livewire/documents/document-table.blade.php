<div class="space-y-4">
    {{-- Workflow Status Bar --}}
    <div class="w-full bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-lg p-4 mb-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            {{-- Stats --}}
            <div class="flex items-center space-x-6 space-x-reverse">
                {{-- Total Documents --}}
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-blue-100 rounded-full">
                        <x-heroicon-o-document-text class="w-4 h-4 text-blue-600" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 font-medium">إجمالي المستندات</span>
                        <span class="text-lg font-bold text-gray-900 leading-none">{{ $this->documents->total() }}</span>
                    </div>
                </div>
                
                {{-- Selected Count --}}
                <div @class([
                    'flex items-center gap-2 transition-all duration-300 animate-pulse',
                    'opacity-100' => count($this->selected) > 0,
                    'opacity-50 grayscale' => count($this->selected) === 0
                ])>
                    <div @class([
                        'p-1.5 rounded-full',
                        'bg-orange-100' => count($this->selected) > 0,
                        'bg-gray-100' => count($this->selected) === 0
                    ])>
                        <x-heroicon-o-check-circle @class([
                            'w-4 h-4',
                            'text-orange-600' => count($this->selected) > 0,
                            'text-gray-400' => count($this->selected) === 0
                        ]) />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 font-medium">تم تحديده</span>
                        <span @class([
                            'text-lg font-bold leading-none',
                            'text-orange-600' => count($this->selected) > 0,
                            'text-gray-400' => count($this->selected) === 0
                        ])>{{ count($this->selected) }}</span>
                    </div>
                </div>
            </div>
            
            {{-- Actions --}}
            <div class="flex items-center gap-3">
                @if($this->search || $this->stage !== 'all' || $this->type !== 'all' || $this->dateFrom || $this->dateTo)
                    <div class="hidden md:flex items-center px-3 py-1 bg-white border border-green-200 rounded-full shadow-sm">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse ml-2"></div>
                        <span class="text-xs font-medium text-green-700">تصفية نشطة</span>
                    </div>
                    <button wire:click="clearFilters" class="text-xs px-3 py-1 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                        مسح المرشحات
                    </button>
                @endif
                
                <div class="h-8 w-px bg-gray-200 mx-2 hidden md:block"></div>
                
                {{-- 🚨 Bulk Actions Dropdown — TEXT ONLY (Heroicons Fixed) --}}
                <div class="relative">
                    @php($isDisabled = (count($this->selected) === 0))
                    @if($isDisabled)
                        <x-secondary-button disabled class="flex items-center gap-2 px-3 py-2 text-sm" wire:click="$set('showBulkActions', true)">
                            📋 إجراءات جماعية ({{ count($this->selected) }})
                        </x-secondary-button>
                    @else
                        <x-secondary-button class="flex items-center gap-2 px-3 py-2 text-sm" wire:click="$set('showBulkActions', true)">
                            📋 إجراءات جماعية ({{ count($this->selected) }})
                        </x-secondary-button>
                    @endif
                    
                    @if($this->showBulkActions)
                        <div class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl shadow-2xl z-50 py-2 animate-in fade-in slide-in-from-top-2 duration-200">
                            
                            {{-- 📁 مجموعة الإدارة --}}
                            <div class="px-3 py-1">
                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">إدارة</span>
                            </div>
                            
                            {{-- أرشفة --}}
                            <x-secondary-button 
                                wire:click="bulkArchive"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-wait"
                                wire:target="bulkArchive"
                                class="w-full justify-start text-left px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm transition-colors">
                                <span wire:loading.remove wire:target="bulkArchive">📦 أرشفة المحدد</span>
                                <span wire:loading wire:target="bulkArchive">⏳ جاري الأرشفة...</span>
                            </x-secondary-button>
                            
                            {{-- حذف --}}
                            <x-secondary-button 
                                wire:click="bulkDelete"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-wait"
                                wire:target="bulkDelete"
                                class="w-full justify-start text-left px-4 py-2.5 hover:bg-red-50 dark:hover:bg-red-900/20 text-sm transition-colors text-red-600 dark:text-red-400">
                                <span wire:loading.remove wire:target="bulkDelete">🗑️ حذف نهائي</span>
                                <span wire:loading wire:target="bulkDelete">⏳ جاري الحذف...</span>
                            </x-secondary-button>
                            
                            {{-- Divider --}}
                            <div class="border-t border-gray-200 dark:border-gray-600 mx-3 my-2"></div>
                            
                            {{-- 📤 مجموعة التصدير --}}
                            <div class="px-3 py-1">
                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">تصدير</span>
                            </div>
                            
                            {{-- تصدير PDF --}}
                            <x-secondary-button 
                                wire:click="bulkExportPdf"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-wait"
                                wire:target="bulkExportPdf"
                                class="w-full justify-start text-left px-4 py-2.5 hover:bg-green-50 dark:hover:bg-green-900/20 text-sm transition-colors text-green-600 dark:text-green-400">
                                <span wire:loading.remove wire:target="bulkExportPdf">📄 تصدير PDF</span>
                                <span wire:loading wire:target="bulkExportPdf">⏳ جاري التصدير...</span>
                            </x-secondary-button>
                            
                            {{-- طباعة --}}
                            <button 
                                type="button"
                                onclick="handleBulkPrint()"
                                class="w-full justify-start text-left px-4 py-2.5 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-sm transition-colors bg-transparent border-0 cursor-pointer flex items-center text-blue-600 dark:text-blue-400">
                                🖨️ طباعة
                            </button>
                            
                            {{-- Divider --}}
                            <div class="border-t border-gray-200 dark:border-gray-600 mx-3 my-2"></div>
                            
                            {{-- إغلاق --}}
                            <x-secondary-button 
                                wire:click="$set('showBulkActions', false)"
                                class="w-full justify-center text-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-500 dark:text-gray-400 transition-colors">
                                ✖️ إغلاق
                            </x-secondary-button>
                        </div>
                    @endif
                </div>
                
                <button wire:click="$refresh" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="تحديث">
                    <x-heroicon-o-arrow-path class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>

    {{-- Toggle Columns --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 mb-4">
        <div class="flex flex-wrap items-center gap-4">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                <x-heroicon-o-view-columns class="w-4 h-4 inline ml-1" />
                عرض الأعمدة:
            </span>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" wire:model.live="showTitle" class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="text-gray-700 dark:text-gray-300">العنوان</span>
            </label>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" wire:model.live="showCaseNumber" class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="text-gray-700 dark:text-gray-300">رقم القضية</span>
            </label>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" wire:model.live="showType" class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="text-gray-700 dark:text-gray-300">النوع</span>
            </label>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" wire:model.live="showStage" class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="text-gray-700 dark:text-gray-300">المرحلة</span>
            </label>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" wire:model.live="showCreatedAt" class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="text-gray-700 dark:text-gray-300">تاريخ الإنشاء</span>
            </label>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" wire:model.live="showAssignee" class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="text-gray-700 dark:text-gray-300">المعين له</span>
            </label>
            <button wire:click="resetColumns" class="text-xs px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                إعادة تعيين
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

            {{-- Case Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    القضية
                </label>
                <select wire:model.live="caseFilter" 
                        wire:change="updatedCaseFilter"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">جميع القضايا</option>
                    <option value="none">بدون قضية</option>
                    @foreach($existingCases as $case)
                        <option value="{{ $case }}">{{ $case }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    البحث
                </label>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="ابحث عن وثيقة..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
            </div>

            {{-- Assignee Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    المعين له
                </label>
                <select wire:model.live="assigneeFilter" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
                    <option value="">جميع المستخدمين</option>
                    @foreach($this->assignees as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Export Buttons --}}
    <div class="flex gap-2 mb-4">
        <x-secondary-button type="button" wire:click="exportPdf" 
                            wire:loading.attr="disabled"
                            wire:target="exportPdf"
                            class="flex items-center gap-2 border-success/20 text-success hover:bg-success/10">
            <span wire:loading.remove wire:target="exportPdf" class="flex items-center gap-2">
              <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
              <span>📄 تصدير PDF</span>
            </span>

            <span wire:loading wire:target="exportPdf" class="flex items-center gap-2">
              <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>جاري التصدير...</span>
            </span>
        </x-secondary-button>
        <x-secondary-button type="button" onclick="printTable()" 
                            class="flex items-center gap-2 border-primary/20 text-primary hover:bg-primary/10">
            <x-heroicon-o-printer class="w-4 h-4" />
            <span class="font-medium">🖨️ طباعة</span>
        </x-secondary-button>
    </div>

    {{-- Results Counter --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                تم العثور على <span class="font-semibold text-gray-900 dark:text-white">{{ $this->resultsCount }}</span> وثيقة
            </p>
            @if(count($this->selected) > 0)
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-primary">
                        {{ count($this->selected) }} محدد
                    </span>
                    <button wire:click="clearSelection" 
                            class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        إلغاء التحديد
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Bulk Actions Toolbar --}}
    @if(count($this->selected) > 0)
        <div class="fixed bottom-4 right-4 left-4 z-50 md:relative md:mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-primary/20 p-4 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-primary font-bold">
                    <span class="bg-primary/10 p-2 rounded-full">
                        <x-heroicon-o-check-circle class="w-5 h-5"/>
                    </span>
                    <span>تم تحديد {{ count($this->selected) }} وثيقة</span>
                </div>

                <div class="flex flex-wrap gap-2 justify-center">
                    <select
                        wire:model.live="bulkActionValue"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">-- اختر إجراء --</option>
                        <option value="archive">أرشفة</option>
                        <option value="delete">حذف نهائي</option>
                        <optgroup label="تغيير المرحلة">
                            <option value="stage:draft">مسودة</option>
                            <option value="stage:review1">مراجعة</option>
                            <option value="stage:proofread">تدقيق</option>
                            <option value="stage:finalapproval">موافقة نهائية</option>
                        </optgroup>
                    </select>

                    @php($isBulkDisabled = (empty($this->bulkActionValue) || $this->bulkLoading))
                    @if($isBulkDisabled)
                        <x-primary-button
                            type="button"
                            wire:click="bulkAction"
                            disabled
                            class="relative"
                        >
                            @if($this->bulkLoading)
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            @endif
                            <span @class([
                                'opacity-0' => $this->bulkLoading,
                                'opacity-100' => !$this->bulkLoading
                            ])>تنفيذ</span>
                        </x-primary-button>
                    @else
                        <x-primary-button
                            type="button"
                            wire:click="bulkAction"
                            class="relative"
                        >
                            @if($this->bulkLoading)
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            @endif
                            <span @class([
                                'opacity-0' => $this->bulkLoading,
                                'opacity-100' => !$this->bulkLoading
                            ])>تنفيذ</span>
                        </x-primary-button>
                    @endif

                    <x-secondary-button type="button" wire:click="clearSelection">
                        إلغاء
                    </x-secondary-button>
                </div>
            </div>
        </div>
    @endif

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" 
                                   wire:click="selectAll"
                                   wire:checked="count($this->selected) === count($this->documents->items()) && count($this->documents->items()) > 0"
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span>تحديد الكل</span>
                        </div>
                    </th>
                    @if($this->visibleColumns['title'])
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        العنوان
                    </th>
                    @endif
                    @if($this->visibleColumns['case_number'])
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        رقم القضية
                    </th>
                    @endif
                    @if($this->visibleColumns['type'])
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        النوع
                    </th>
                    @endif
                    @if($this->visibleColumns['stage'])
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        المرحلة
                    </th>
                    @endif
                    @if($this->visibleColumns['created_at'])
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        تاريخ الإنشاء
                    </th>
                    @endif
                    @if($this->visibleColumns['assignee'])
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        المعين له
                    </th>
                    @endif
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        الإجراءات
                    </th>
                </tr>
            </thead>
            <tbody wire:loading wire:target="search,type,stage,dateFrom,dateTo,archived">
              @for($i = 0; $i < 5; $i++)
                <tr class="animate-pulse bg-white dark:bg-gray-800">
                  <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-4"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div></td>
                  <td class="px-6 py-4"><div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full w-20"></div></td>
                  <td class="px-6 py-4"><div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full w-24"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-12"></div></td>
                </tr>
              @endfor
            </tbody>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700" wire:loading.remove wire:target="search,type,stage,dateFrom,dateTo,archived">
                @forelse($this->documents as $doc)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50" @class(['bg-primary/5 dark:bg-primary/10' => in_array($doc->id, $this->selected)])>
                        <td class="px-6 py-4">
                            <input type="checkbox" 
                                   wire:model="selected"
                                   value="{{ $doc->id }}"
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                        </td>
                        @if($this->visibleColumns['title'])
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-document-text class="w-4 h-4 text-gray-400" />
                                <a href="{{ route('documents.show', $doc->id) }}" 
                                   class="text-sm font-medium text-primary hover:underline">
                                    {{ $doc->title }}
                                </a>
                            </div>
                        </td>
                        @endif
                        @if($this->visibleColumns['case_number'])
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 min-w-[90px] max-w-[120px] truncate case-column">
                            {{ $doc->case_number ?? 'بدون قضية' }}
                        </td>
                        @endif
                        @if($this->visibleColumns['type'])
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getTypeBadgeClass($doc->type) }}">
                                {{ $this->getTypeLabel($doc->type) }}
                            </span>
                        </td>
                        @endif
                        @if($this->visibleColumns['stage'])
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap {{ $this->getStageBadgeClass($doc->current_stage) }}">
                                    {{ $this->getStageLabel($doc->current_stage) }}
                                </span>
                                @if($doc->isOverdue())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                        متأخرة
                                    </span>
                                @endif
                            </div>
                        </td>
                        @endif
                        @if($this->visibleColumns['created_at'])
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" dir="rtl">
                            {{ $doc->created_at->diffForHumans() }}
                        </td>
                        @endif
                        @if($this->visibleColumns['assignee'])
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $doc->assignee?->name ?? '-' }}
                        </td>
                        @endif
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('documents.show', $doc->id) }}"
                                   class="text-primary hover:text-primary/80 dark:text-primary/80"
                                   title="عرض">
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                </a>
                                <button type="button" wire:click="downloadDocument({{ $doc->id }})" 
                                        class="text-success hover:text-success/80 dark:text-success/80"
                                        title="تنزيل">
                                    <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                                </button>
                                @if($doc->current_stage !== 'finalapproval')
                                    <button type="button" wire:click='uploadNewVersion(@json($doc->id))' class="text-gray-600 hover:text-gray-800 dark:text-gray-400 transition-colors duration-200" title="نسخة جديدة">
                                        <x-heroicon-o-arrow-up-tray class="w-4 h-4" />
                                    </button>
                                @endif
                                @if(!$doc->is_archived)
                                    <button type="button" wire:click="archiveDocument({{ $doc->id }})" 
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
                        <td colspan="7" class="px-6 py-12 text-center">
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
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4" @class(['ring-2 ring-primary' => in_array($doc->id, $this->selected)])>
                <div class="flex items-start gap-2 mb-3">
                    <input type="checkbox" 
                           wire:model="selected"
                           value="{{ $doc->id }}"
                           class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
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
                        <span class="text-sm text-gray-500 dark:text-gray-400">رقم القضية:</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $doc->case_number ?? 'بدون قضية' }}</span>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm text-gray-500 dark:text-gray-400">المرحلة:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap {{ $this->getStageBadgeClass($doc->current_stage) }}">
                            {{ $this->getStageLabel($doc->current_stage) }}
                        </span>
                        @if($doc->isOverdue())
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                متأخرة
                            </span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400" dir="rtl">
                        {{ $doc->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('documents.show', $doc->id) }}"
                       class="flex items-center justify-center gap-2 px-3 py-2 bg-primary/10 text-primary border border-primary/20 rounded-lg hover:bg-primary/20 transition-colors">
                        <x-heroicon-o-eye class="w-4 h-4" />
                        <span class="text-sm">عرض</span>
                    </a>
                    <x-secondary-button type="button" wire:click="downloadDocument({{ $doc->id }})" 
                                        class="flex items-center justify-center gap-2 border-success/20 text-success hover:bg-success/10">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                        <span class="text-sm">تنزيل</span>
                    </x-secondary-button>
                    @if($doc->current_stage !== 'finalapproval')
                        <x-secondary-button type="button" wire:click='uploadNewVersion(@json($doc->id))' class="flex items-center justify-center gap-2">
                            <x-heroicon-o-arrow-up-tray class="w-4 h-4" />
                            <span class="text-sm">نسخة جديدة</span>
                        </x-secondary-button>
                    @endif
                    @if(!$doc->is_archived)
                        <x-secondary-button type="button" wire:click="archiveDocument({{ $doc->id }})" 
                                            class="flex items-center justify-center gap-2">
                            <x-heroicon-o-archive-box class="w-4 h-4" />
                            <span class="text-sm">أرشفة</span>
                        </x-secondary-button>
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

    {{-- Keyboard Shortcuts (Single Listener + Livewire v3) --}}
    <script>
        // ✅ Bulk Print Handler - Direct window.print()
        function handleBulkPrint() {
            // Close dropdown first
            @this.set('showBulkActions', false);
            @this.set('selected', []);
            
            // Show toast
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message: 'جاري فتح الطباعة...', type: 'info' }
            }));
            
            // Small delay to let UI update, then print
            setTimeout(() => {
                window.print();
            }, 100);
        }

        // ✅ Table Print Function (for Export button)
        function printTable() {
            window.print();
        }

        document.addEventListener('livewire:init', () => {
            Livewire.hook('component.init', ({ component }) => {
                if (component.name === 'documents.document-table') {
                    // Single optimized listener
                    document.addEventListener('keydown', (e) => {
                        if (!e.ctrlKey && !e.key) return;
                        
                        switch(true) {
                            // Ctrl+A: Select All
                            case e.ctrlKey && e.key === 'a' && component.selected.length === 0:
                                e.preventDefault();
                                component.selectAll();
                                break;
                            
                            // Delete: Bulk Delete
                            case e.key === 'Delete' && component.selected.length > 0:
                                component.bulkDelete();
                                break;
                            
                            // Ctrl+P: Print
                            case e.ctrlKey && e.key === 'p':
                                e.preventDefault();
                                window.print();
                                break;
                        }
                    });

                    // PDF Download Listener (Livewire v3)
                    Livewire.on('download-pdf', (data) => {
                        const link = document.createElement('a');
                        link.href = data.url;
                        link.download = data.filename;
                        link.style.display = 'none';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });

                    // S3 File Download Listener (Livewire v3)
                    Livewire.on('download-file', (data) => {
                        const link = document.createElement('a');
                        link.href = data.url;
                        link.download = data.filename;
                        link.target = '_blank';
                        link.style.display = 'none';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });
                }
            });
        });
    </script>

    {{-- Print Table CSS (Ctrl+P Legal Reports) --}}
    <style media="print">
        /* إخفاء كل UI */
        nav, header, footer, aside, 
        .status-bar, .search-input, .filters,
        .pagination, .table-actions,
        button { 
            display: none !important; 
        }
        
        /* ✅ Print Button Exception - Specificity Winner */
        button[wire\:click*="bulkPrint"] {
            display: block !important; 
            position: fixed !important;
            bottom: 20px !important;
            right: 20px !important;
            z-index: 9999 !important;
        }
        
        /* RTL + Cairo */
        body {
            direction: rtl !important;
            font-family: 'Cairo', Arial, sans-serif !important;
            font-size: 11pt !important;
        }
        
        .table-container {
            width: 100% !important;
            max-width: none !important;
        }
        
        table { width: 100% !important; border-collapse: collapse !important; }
        thead { display: table-header-group !important; }
        tbody tr { page-break-inside: avoid !important; }
        
        /* Stage Badges Print Protection */
        span.rounded-full {
            white-space: nowrap !important;
            display: inline-block !important;
            max-width: 120px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            font-family: 'Cairo', Arial, sans-serif !important;
            font-size: 10pt !important;
        }
        
        /* Case Column Print Protection */
        td.case-column {
            min-width: 80px !important;
            max-width: 100px !important;
        }
        
        @page { size: A4 landscape; margin: 15mm; }
    </style>
</div>
