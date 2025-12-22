<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6" wire:id="documents-document-tasks-{{ $this->documentId }}">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
        مهام الوثيقة
    </h3>

    {{-- Add Task Form --}}
    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
            إضافة مهمة جديدة
        </h4>
        <form wire:submit.prevent="addTask" class="space-y-3">
            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    العنوان <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       wire:model="title"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary"
                       placeholder="عنوان المهمة">
                @error('title')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    الملاحظات
                </label>
                <textarea wire:model="notes"
                          rows="2"
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary"
                          placeholder="ملاحظات إضافية"></textarea>
            </div>

            {{-- Due Date & Assigned To --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        تاريخ الاستحقاق
                    </label>
                    <input type="date"
                           wire:model="due_date"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        إسناد إلى
                    </label>
                    <select wire:model="assigned_to"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
                        <option value="">-- اختر مستخدم --</option>
                        @foreach($this->users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                    class="w-full md:w-auto px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                إضافة مهمة
            </button>
        </form>
    </div>

    {{-- Tasks List --}}
    <div class="space-y-3">
        @forelse($this->tasks as $task)
            <div wire:key="document-task-{{ $task->id }}"
                 class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg {{ $task->status === 'closed' ? 'bg-gray-50 dark:bg-gray-700/30 opacity-75' : 'bg-white dark:bg-gray-800' }}">
                <div class="flex items-start justify-between gap-4">
                    {{-- Task Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            @if($task->status === 'closed')
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" />
                            @else
                                <x-heroicon-o-circle-stack class="w-5 h-5 text-primary flex-shrink-0" />
                            @endif
                            <h4 class="font-medium text-gray-900 dark:text-white {{ $task->status === 'closed' ? 'line-through' : '' }}">
                                {{ $task->title }}
                            </h4>
                        </div>

                        @if($task->notes)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $task->notes }}
                            </p>
                        @endif

                        <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-gray-500 dark:text-gray-400">
                            @if($task->due_date)
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-calendar class="w-4 h-4" />
                                    {{ $task->due_date->format('Y-m-d') }}
                                    @if($task->due_date->isPast() && $task->status === 'open')
                                        <span class="text-red-600 dark:text-red-400">(متأخرة)</span>
                                    @endif
                                </span>
                            @endif

                            @if($task->assignee)
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-user class="w-4 h-4" />
                                    {{ $task->assignee->name }}
                                </span>
                            @endif

                            <span class="flex items-center gap-1">
                                <x-heroicon-o-clock class="w-4 h-4" />
                                {{ $task->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if($task->status === 'open')
                            <button wire:click="markDone({{ $task->id }})"
                                    class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                                    title="تم">
                                <x-heroicon-o-check-circle class="w-5 h-5" />
                            </button>
                        @else
                            <button wire:click="reopen({{ $task->id }})"
                                    class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                    title="إعادة فتح">
                                <x-heroicon-o-arrow-path class="w-5 h-5" />
                            </button>
                        @endif

                        <div class="flex gap-1">
                            <button
                                type="button"
                                wire:click="viewTask({{ $task->id }})"
                                class="p-1 text-blue-500 hover:bg-blue-100 rounded"
                                title="عرض"
                            >
                                👁️
                            </button>
                            <button
                                type="button"
                                wire:click.stop="deleteTask({{ $task->id }})"
                                onclick="return confirm('تأكيد الحذف؟')"
                                class="p-1 text-red-500 hover:bg-red-100 rounded"
                                title="حذف"
                            >
                                🗑️
                            </button>
                        </div>
                    </div>

                    @if($selectedTaskId === $task->id)
                        <div class="mt-3 bg-blue-50 p-3 rounded border-l-4 border-blue-400">
                            <div class="font-bold">{{ $task->title }}</div>
                            <div class="text-sm mt-1">
                                {{ $task->description ?? $task->notes }}
                            </div>
                            <div class="text-xs mt-2 opacity-70">
                                الحالة: {{ $task->status }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <x-heroicon-o-circle-stack class="w-12 h-12 mx-auto mb-2 text-gray-400" />
                <p>لا توجد مهام لهذه الوثيقة</p>
            </div>
        @endforelse
    </div>
</div>
