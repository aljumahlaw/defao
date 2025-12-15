<div>
    @if($isOpen)
        {{-- Modal Backdrop --}}
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('isOpen') }" x-show="show">
            {{-- Overlay --}}
            <div class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 transition-opacity"
                 wire:click="close"></div>

            {{-- Modal Container --}}
            <div class="flex min-h-full items-center justify-center p-4">
                {{-- Modal Content --}}
                <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all">
                    {{-- Header --}}
                    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $taskId ? 'تعديل المهمة' : 'إنشاء مهمة جديدة' }}
                        </h3>
                        <button wire:click="close" 
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <x-heroicon-o-x-mark class="w-6 h-6" />
                        </button>
                    </div>

                    {{-- Form --}}
                    <form wire:submit.prevent="save" class="p-6 space-y-6">
                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                العنوان <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model.blur="title"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('title') border-red-500 @enderror"
                                   placeholder="أدخل عنوان المهمة">
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ strlen($title) }}/100 حرف
                            </p>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                الوصف
                            </label>
                            <textarea wire:model.blur="description"
                                      rows="4"
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('description') border-red-500 @enderror"
                                      placeholder="وصف تفصيلي للمهمة (اختياري)"></textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ strlen($description) }}/500 حرف
                            </p>
                        </div>

                        {{-- Priority & Assigned To --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Priority --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    الأولوية <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="priority"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('priority') border-red-500 @enderror">
                                    <option value="high">عالية</option>
                                    <option value="medium">متوسطة</option>
                                    <option value="low">منخفضة</option>
                                </select>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Assigned To --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    المعين له <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="assigned_to"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('assigned_to') border-red-500 @enderror">
                                    <option value="">اختر المستخدم</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Due Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                تاريخ الاستحقاق
                            </label>
                            <input type="date" 
                                   wire:model="due_date"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('due_date') border-red-500 @enderror">
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="button"
                                    wire:click="close"
                                    class="w-full sm:w-auto px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                إلغاء
                            </button>
                            <button type="submit"
                                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                                <x-heroicon-o-check class="w-5 h-5" />
                                <span>{{ $taskId ? 'تحديث' : 'حفظ' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
