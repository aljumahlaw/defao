<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Content (2/3) --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Document Preview --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                معاينة الملف
            </h3>
            
            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                @php
                $icon = $this->fileIcon;
                @endphp
                @if($icon === 'heroicon-o-document-text')
                    <x-heroicon-o-document-text class="w-12 h-12 text-primary flex-shrink-0" />
                @elseif($icon === 'heroicon-o-document')
                    <x-heroicon-o-document class="w-12 h-12 text-primary flex-shrink-0" />
                @elseif($icon === 'heroicon-o-table-cells')
                    <x-heroicon-o-table-cells class="w-12 h-12 text-primary flex-shrink-0" />
                @else
                    <x-heroicon-o-document class="w-12 h-12 text-primary flex-shrink-0" />
                @endif
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-gray-900 dark:text-white truncate">
                        {{ $this->document->file_name }}
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $this->document->file_size }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="download"
                            class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors"
                            title="تنزيل">
                        <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
                    </button>
                    <button class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors"
                            title="عرض">
                        <x-heroicon-o-eye class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>

        {{-- Document Metadata --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                معلومات الوثيقة
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                        العنوان
                    </label>
                    <p class="text-gray-900 dark:text-white">
                        {{ $this->document->title }}
                    </p>
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                        النوع
                    </label>
                    <p class="text-gray-900 dark:text-white">
                        {{ $this->document->type === 'incoming' ? 'وارد' : 'صادر' }}
                    </p>
                </div>

                {{-- Current Stage --}}
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                        المرحلة الحالية
                    </label>
                    @php
                    $currentStageKey = $this->document->current_stage;
                    $currentStageName = collect($this->stages)->firstWhere('key', $currentStageKey)['name'] ?? 'غير محدد';
                    @endphp
                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium {{ $this->stageBadgeColor[$currentStageKey] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $currentStageName }}
                    </span>
                </div>

                {{-- Created At --}}
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                        تاريخ الإنشاء
                    </label>
                    <p class="text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($this->document->created_at)->format('Y/m/d - h:i A') }}
                    </p>
                </div>

                {{-- Creator --}}
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                        المُنشئ
                    </label>
                    <p class="text-gray-900 dark:text-white">
                        {{ $this->document->creator->name ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- Description --}}
            @if($this->document->description)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                        الوصف
                    </label>
                    <p class="text-gray-900 dark:text-white">
                        {{ $this->document->description }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Activity Log --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                سجل النشاطات
            </h3>
            
            <div class="space-y-4">
                @foreach($this->activityLog as $activity)
                    <div class="flex gap-4">
                        {{-- Icon --}}
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                @php
                                $activityIcon = $this->activityIcon[$activity['type']] ?? 'heroicon-o-document';
                                @endphp
                                @if($activityIcon === 'heroicon-o-document-plus')
                                    <x-heroicon-o-document-plus class="w-5 h-5 text-primary" />
                                @elseif($activityIcon === 'heroicon-o-arrow-up-tray')
                                    <x-heroicon-o-arrow-up-tray class="w-5 h-5 text-primary" />
                                @elseif($activityIcon === 'heroicon-o-check-circle')
                                    <x-heroicon-o-check-circle class="w-5 h-5 text-primary" />
                                @elseif($activityIcon === 'heroicon-o-x-circle')
                                    <x-heroicon-o-x-circle class="w-5 h-5 text-primary" />
                                @elseif($activityIcon === 'heroicon-o-arrow-right-circle')
                                    <x-heroicon-o-arrow-right-circle class="w-5 h-5 text-primary" />
                                @elseif($activityIcon === 'heroicon-o-chat-bubble-left')
                                    <x-heroicon-o-chat-bubble-left class="w-5 h-5 text-primary" />
                                @endif
                            </div>
                        </div>
                        
                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white">
                                <span class="font-semibold">{{ $activity['user'] }}</span>
                                {{ ' ' . $activity['action'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($activity['timestamp'])->diffForHumans() }}
                            </p>
                            @if($activity['comment'])
                                <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 italic">
                                    "{{ $activity['comment'] }}"
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Sidebar (1/3) --}}
    <div class="space-y-6">
        {{-- Actions --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                الإجراءات
            </h3>
            
            <div class="space-y-3">
                {{-- Approve --}}
                <button wire:click="approve"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-approve text-white rounded-lg hover:bg-approve-hover transition-colors">
                    <x-heroicon-o-check class="w-5 h-5" />
                    <span>موافقة</span>
                </button>

                {{-- Reject --}}
                <button wire:click="reject"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-reject text-white rounded-lg hover:bg-reject-hover transition-colors">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                    <span>رفض</span>
                </button>

                {{-- Forward --}}
                <button wire:click="forward"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-forward text-white rounded-lg hover:bg-forward-hover transition-colors">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                    <span>تحويل</span>
                </button>

                {{-- Comment --}}
                <button wire:click="comment"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <x-heroicon-o-chat-bubble-left class="w-5 h-5" />
                    <span>إضافة تعليق</span>
                </button>
            </div>
        </div>

        {{-- Stage Progress --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                مراحل الوثيقة
            </h3>
            
            <div class="space-y-4 relative">
                @foreach($this->stages as $index => $stage)
                    <div class="flex items-start gap-3 relative">
                        {{-- Icon --}}
                        <div class="flex-shrink-0 mt-1 relative z-10">
                            @if($stage['status'] === 'completed')
                                <div class="w-6 h-6 rounded-full bg-green-600 flex items-center justify-center">
                                    <x-heroicon-o-check class="w-4 h-4 text-white" />
                                </div>
                            @elseif($stage['status'] === 'current')
                                <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            @else
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600"></div>
                            @endif
                        </div>
                        
                        {{-- Label --}}
                        <div class="flex-1">
                            <p class="text-sm font-medium {{ $stage['status'] === 'current' ? 'text-primary' : ($stage['status'] === 'completed' ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400') }}">
                                {{ $stage['name'] }}
                            </p>
                        </div>
                        
                        {{-- Connector Line --}}
                        @if($index < count($this->stages) - 1)
                            <div class="absolute right-[11px] w-0.5 h-8 {{ $stage['status'] === 'completed' ? 'bg-green-600' : 'bg-gray-300 dark:bg-gray-600' }}" 
                                 style="margin-top: 24px; z-index: 0;"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
