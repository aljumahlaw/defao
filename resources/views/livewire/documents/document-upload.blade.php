<div class="space-y-6">
    {{-- Upload Area --}}
    @if(!$file)
        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-12 text-center hover:border-primary transition-colors"
             x-data="{ 
                 isDragging: false,
                 handleDragOver(e) { 
                     e.preventDefault(); 
                     this.isDragging = true; 
                 },
                 handleDragLeave() { 
                     this.isDragging = false; 
                 },
                 handleDrop(e) {
                     e.preventDefault();
                     this.isDragging = false;
                     let files = e.dataTransfer.files;
                     if (files.length) {
                         @this.upload('file', files[0]);
                     }
                 }
             }"
             :class="{ 'border-primary bg-primary/5': isDragging }"
             @dragover="handleDragOver"
             @dragleave="handleDragLeave"
             @drop="handleDrop">
            
            <x-heroicon-o-cloud-arrow-up class="w-16 h-16 mx-auto text-gray-400 mb-4" />
            
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                اسحب الملف وأفلته هنا
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                أو اضغط لاختيار الملف
            </p>

            <input type="file" 
                   wire:model="file"
                   id="file-upload"
                   class="hidden"
                   accept=".pdf,.doc,.docx,.xls,.xlsx">
            <div wire:loading wire:target="file" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300 ease-in-out"
                         :style="'width: ' + ($wire.uploadProgress?.progress || 0) + '%'"
                         x-data
                         x-ref="progress">
                    </div>
                </div>
                <p class="text-sm font-medium text-blue-800">
                    جاري الرفع... <span x-text="($wire.uploadProgress?.progress || 0) + '%'"></span>
                </p>
            </div>
            
            <label for="file-upload"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors cursor-pointer">
                <x-heroicon-o-folder-open class="w-5 h-5" />
                <span>اختر الملف</span>
            </label>

            <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                <p>الأنواع المدعومة: PDF, DOCX, XLSX</p>
                <p>الحجم الأقصى: 25 ميجابايت</p>
            </div>

            @error('file')
                <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                </div>
            @enderror
        </div>

        {{-- Loading Indicator --}}
        <div wire:loading wire:target="file" class="text-center py-8">
            <div class="inline-flex items-center gap-3">
                <svg class="animate-spin h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600 dark:text-gray-400">جاري التحميل...</span>
            </div>
        </div>
    @endif

    {{-- File Preview + Progress --}}
    @if($file)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            {{-- File Info --}}
            <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0">
                    @php
                    $icon = $this->getFileIcon();
                    @endphp
                    @if($icon === 'heroicon-o-document-text')
                        <x-heroicon-o-document-text class="w-12 h-12 text-primary" />
                    @elseif($icon === 'heroicon-o-document')
                        <x-heroicon-o-document class="w-12 h-12 text-primary" />
                    @elseif($icon === 'heroicon-o-table-cells')
                        <x-heroicon-o-table-cells class="w-12 h-12 text-primary" />
                    @else
                        <x-heroicon-o-document class="w-12 h-12 text-primary" />
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                        {{ $file->getClientOriginalName() }}
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $this->getFileSizeFormatted() }}
                    </p>
                </div>
                @if(!$uploadComplete)
                    <button wire:click="removeFile" 
                            class="text-red-600 hover:text-red-800 dark:text-red-400">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                @endif
            </div>

            {{-- Upload Progress --}}
            @if($isUploading || $uploadProgress > 0)
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $isUploading ? 'جاري الرفع...' : 'تم الرفع' }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $uploadProgress }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                             style="width: {{ $uploadProgress }}%"></div>
                    </div>
                </div>
            @endif

            {{-- Processing Progress --}}
            @if($isProcessing || $processingProgress > 0)
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $isProcessing ? 'جاري المعالجة...' : 'تمت المعالجة' }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $processingProgress }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300"
                             style="width: {{ $processingProgress }}%"></div>
                    </div>
                </div>
            @endif

            {{-- Success Message --}}
            @if($uploadComplete)
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center gap-3">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-green-600 dark:text-green-400" />
                        <p class="text-sm text-green-600 dark:text-green-400">
                            تم رفع الملف ومعالجته بنجاح
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Document Details Form --}}
        @if($uploadComplete)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    تفاصيل الوثيقة
                </h3>

                <form wire:submit.prevent="save" class="space-y-6">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            عنوان الوثيقة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               wire:model.blur="title"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('title') border-red-500 @enderror"
                               placeholder="أدخل عنوان الوثيقة">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ strlen($title) }}/200 حرف
                        </p>
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            نوع الوثيقة <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="type"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('type') border-red-500 @enderror">
                            <option value="incoming">وارد</option>
                            <option value="outgoing">صادر</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            الوصف
                        </label>
                        <textarea wire:model.blur="description"
                                  rows="4"
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('description') border-red-500 @enderror"
                                  placeholder="وصف تفصيلي للوثيقة (اختياري)"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ strlen($description) }}/500 حرف
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('documents.index') }}"
                           class="w-full sm:w-auto px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-center">
                            إلغاء
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                            <x-heroicon-o-check class="w-5 h-5" />
                            <span>حفظ الوثيقة</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    @endif
</div>
