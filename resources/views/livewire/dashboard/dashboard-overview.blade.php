<div>
    {{-- Quick Actions --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">إجراءات سريعة</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-primary-button type="button" wire:click="openTaskModal" 
                              class="flex flex-col items-center gap-3 p-6 rounded-lg hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <x-heroicon-o-plus-circle class="w-8 h-8" />
                <span class="font-medium">إنشاء مهمة جديدة</span>
            </x-primary-button>
            <a href="{{ route('documents.upload') }}"
               class="flex flex-col items-center gap-3 p-6 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <x-heroicon-o-arrow-up-tray class="w-8 h-8" />
                <span class="font-medium">رفع وثيقة</span>
            </a>
            <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-3 p-6 rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 hover:shadow-md hover:-translate-y-1 transition-all duration-200 block">
                <x-quick-action-card icon="heroicon-o-chart-bar" title="عرض التقارير" color="purple" />
            </a>
        </div>
    </div>

    {{-- Stats Widgets --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">الإحصائيات</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-widget icon="heroicon-o-clipboard-document-list" label="المهام النشطة" :value="$this->stats['active_tasks']" color="blue" />
            <x-stat-widget icon="heroicon-o-document-text" label="الوثائق قيد المراجعة" :value="$this->stats['review_documents']" color="yellow" />
            <x-stat-widget icon="heroicon-o-check-circle" label="المهام المكتملة" :value="$this->stats['completed_tasks']" color="green" />
            <x-stat-widget icon="heroicon-o-archive-box" label="الوثائق المؤرشفة" :value="$this->stats['archived_documents']" color="purple" />
        </div>
    </div>

    {{-- Recent Activity --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">النشاط الأخير</h2>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($this->recentActivity as $activity)
                    <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <p class="text-sm text-gray-900 dark:text-white">{{ $activity['text'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $activity['time'] }}</p>
                    </li>
                @empty
                    <li class="p-4 text-center text-gray-500 dark:text-gray-400">
                        لا يوجد نشاط حديث
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>



