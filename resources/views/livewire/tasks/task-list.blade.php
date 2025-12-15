<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Today's Tasks --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                    <x-heroicon-o-calendar-days class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 text-center">مهام اليوم</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mx-auto w-fit">{{ $this->stats['today'] }}</p>
                </div>
            </div>
        </div>

        {{-- This Week --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                    <x-heroicon-o-calendar class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 text-center">مهام هذا الأسبوع</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mx-auto w-fit">{{ $this->stats['this_week'] }}</p>
                </div>
            </div>
        </div>

        {{-- Overdue --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-lg">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-600 dark:text-red-400" />
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 text-center">مهام متأخرة</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400 mx-auto w-fit">{{ $this->stats['overdue'] }}</p>
                </div>
            </div>
        </div>

        {{-- Completion Rate --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <x-heroicon-o-chart-bar class="w-6 h-6 text-green-600 dark:text-green-400" />
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 text-center">معدل الإنجاز</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mx-auto w-fit">{{ $this->stats['completion_rate'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                {{-- Status Tabs --}}
                <div class="flex flex-wrap gap-2">
                    <button wire:click="setStatusFilter('all')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'all' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        الكل
                        <span class="mr-1 px-2 py-0.5 rounded-full text-xs {{ $statusFilter === 'all' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-600' }}">
                            {{ $this->statusCounts['all'] }}
                        </span>
                    </button>
                    <button wire:click="setStatusFilter('pending')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'pending' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        معلقة
                        <span class="mr-1 px-2 py-0.5 rounded-full text-xs {{ $statusFilter === 'pending' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-600' }}">
                            {{ $this->statusCounts['pending'] }}
                        </span>
                    </button>
                    <button wire:click="setStatusFilter('in_progress')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'in_progress' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        قيد التنفيذ
                        <span class="mr-1 px-2 py-0.5 rounded-full text-xs {{ $statusFilter === 'in_progress' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-600' }}">
                            {{ $this->statusCounts['in_progress'] }}
                        </span>
                    </button>
                    <button wire:click="setStatusFilter('completed')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'completed' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        مكتملة
                        <span class="mr-1 px-2 py-0.5 rounded-full text-xs {{ $statusFilter === 'completed' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-600' }}">
                            {{ $this->statusCounts['completed'] }}
                        </span>
                    </button>
                    <button wire:click="setStatusFilter('overdue')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'overdue' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        متأخرة
                        <span class="mr-1 px-2 py-0.5 rounded-full text-xs {{ $statusFilter === 'overdue' ? 'bg-white/20' : 'bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400' }}">
                            {{ $this->statusCounts['overdue'] }}
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Results Counter --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                تم العثور على <span class="font-semibold text-gray-900 dark:text-white">{{ $this->resultsCount }}</span> مهمة
            </p>
        </div>

        {{-- Task Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            عنوان المهمة
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            الوثيقة المرتبطة
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            الحالة
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            الأولوية
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            التاريخ
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            المكلف
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($this->tasks as $task)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            {{-- Title --}}
                            <td class="px-6 py-4 text-center">
                                <button wire:click="viewTask({{ $task->id }})"
                                        class="text-primary hover:underline font-medium">
                                    {{ $task->title }}
                                </button>
                            </td>

                            {{-- Related Document --}}
                            <td class="px-6 py-4 text-center">
                                @if($task->document_id && $task->document)
                                    <a href="{{ route('documents.show', $task->document_id) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                        {{ $task->document->title }}
                                    </a>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $this->getStatusBadgeColor($task->status) }}">
                                    {{ $this->getStatusLabel($task->status) }}
                                </span>
                            </td>

                            {{-- Priority --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $this->getPriorityBadgeColor($task->priority) }}">
                                    {{ $this->getPriorityLabel($task->priority) }}
                                </span>
                            </td>

                            {{-- Due Date --}}
                            <td class="px-6 py-4 text-center">
                                @if($task->due_date)
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $task->due_date->format('Y/m/d') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $task->due_date->locale('ar')->diffForHumans() }}
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                @endif
                            </td>

                            {{-- Assignee --}}
                            <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-white">
                                {{ $task->assignee ? $task->assignee->name : '-' }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="viewTask({{ $task->id }})"
                                            class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                            title="عرض">
                                        <x-heroicon-o-eye class="w-5 h-5" />
                                    </button>
                                    <button wire:click="editTask({{ $task->id }})"
                                            class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                            title="تعديل">
                                        <x-heroicon-o-pencil class="w-5 h-5" />
                                    </button>
                                    <button wire:click="deleteTask({{ $task->id }})"
                                            class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                            title="حذف">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <x-heroicon-o-inbox class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                                <p class="text-gray-500 dark:text-gray-400">لا توجد مهام</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $this->tasks->links() }}
        </div>
    </div>
</div>
