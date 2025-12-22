<div x-data="{ expanded: false }" class="{{ $this->stageColorClasses['bg'] }} rounded-lg p-4 border {{ $this->stageColorClasses['border'] }}">
    <div class="flex items-center justify-between mb-3">
        <h4 class="font-semibold {{ $this->stageColorClasses['text'] }}">
            {{ $this->stageLabel }}
        </h4>
        <button @click="expanded = !expanded" 
                class="text-sm {{ $this->stageColorClasses['textSecondary'] }} hover:opacity-75 transition-opacity">
            <span x-show="!expanded">▼</span>
            <span x-show="expanded">▲</span>
        </button>
    </div>

    <div x-show="expanded" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         style="display: none;">
        @if($this->recentDocuments->isEmpty())
            <p class="text-sm {{ $this->stageColorClasses['textSecondary'] }} py-2">
                لا توجد مستندات في هذه المرحلة
            </p>
        @else
            <ul class="space-y-2">
                @foreach($this->recentDocuments as $document)
                    <li>
                        <div class="p-2 rounded hover:bg-white/50 dark:hover:bg-gray-800/50 transition-colors">
                            <a href="{{ route('documents.show', $document->id) }}" 
                               class="block">
                                <p class="text-sm font-medium {{ $this->stageColorClasses['text'] }} truncate">
                                    {{ $document->title }}
                                </p>
                                <p class="text-xs {{ $this->stageColorClasses['textSecondary'] }} mt-1">
                                    آخر تحديث: {{ $document->updated_at->diffForHumans() }}
                                </p>
                            </a>
                            <div class="flex gap-2 mt-2">
                                @if($this->stage !== 'finalapproval')
                                    <button wire:click="advanceStage({{ $document->id }})" 
                                            wire:confirm="هل تريد إرسال هذا المستند للمرحلة التالية؟"
                                            class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition-colors">
                                        {{ $this->getNextStageLabel }}
                                    </button>
                                @endif
                                <button wire:click="rejectStage({{ $document->id }})" 
                                        wire:confirm="هل تريد إرجاع هذا المستند للمسودة؟"
                                        class="px-3 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 transition-colors">
                                    إرجاع
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
