<div class="space-y-4">
    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    الحالة
                </label>
                <select wire:model.live="status" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="all">الكل</option>
                    <option value="draft">مسودة</option>
                    <option value="active">نشط</option>
                    <option value="completed">مكتمل</option>
                </select>
            </div>

            {{-- Priority Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    الأولوية
                </label>
                <select wire:model.live="priority" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="all">الكل</option>
                    <option value="high">عالية</option>
                    <option value="medium">متوسطة</option>
                    <option value="low">منخفضة</option>
                </select>
            </div>
        </div>
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
                        الحالة
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        الأولوية
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                        المعين له
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
                @forelse($this->tasks as $task)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $task['title'] }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($task['status']) }}">
                                {{ $this->getStatusLabel($task['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getPriorityBadgeClass($task['priority']) }}">
                                {{ $this->getPriorityLabel($task['priority']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                            {{ $task['assigned_to'] }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" dir="rtl">
                            {{ $task['created_at']->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="viewTask({{ $task['id'] }})" 
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                </button>
                                <button wire:click="editTask({{ $task['id'] }})" 
                                        class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400">
                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                </button>
                                <button wire:click="deleteTask({{ $task['id'] }})" 
                                        class="text-red-600 hover:text-red-800 dark:text-red-400">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <x-heroicon-o-clipboard-document-list class="w-12 h-12 text-gray-400" />
                                <p class="text-gray-500 dark:text-gray-400">لا توجد مهام</p>
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
                        <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse w-20"></div></td>
                        <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div></td>
                        <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div></td>
                        <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse w-16"></div></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-4">
        @forelse($this->tasks as $task)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $task['title'] }}</h3>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">الحالة:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($task['status']) }}">
                            {{ $this->getStatusLabel($task['status']) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">الأولوية:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getPriorityBadgeClass($task['priority']) }}">
                            {{ $this->getPriorityLabel($task['priority']) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        المعين له: {{ $task['assigned_to'] }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400" dir="rtl">
                        {{ $task['created_at']->diffForHumans() }}
                    </div>
                </div>

                <div class="flex gap-2">
                    <button wire:click="viewTask({{ $task['id'] }})" 
                            class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100">
                        <x-heroicon-o-eye class="w-4 h-4" />
                        <span class="text-sm">عرض</span>
                    </button>
                    <button wire:click="editTask({{ $task['id'] }})" 
                            class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100">
                        <x-heroicon-o-pencil class="w-4 h-4" />
                        <span class="text-sm">تعديل</span>
                    </button>
                    <button wire:click="deleteTask({{ $task['id'] }})" 
                            class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100">
                        <x-heroicon-o-trash class="w-4 h-4" />
                        <span class="text-sm">حذف</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12">
                <div class="flex flex-col items-center gap-3">
                    <x-heroicon-o-clipboard-document-list class="w-16 h-16 text-gray-400" />
                    <p class="text-gray-500 dark:text-gray-400 text-center">لا توجد مهام</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
