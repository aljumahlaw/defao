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

    {{-- Action Items Card - P1-3 --}}
    <div class="bg-gradient-to-r from-red-50 to-yellow-50 dark:from-red-900/20 dark:to-yellow-900/20 p-6 rounded-xl border border-red-200 dark:border-red-800/50 mb-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            ماذا أفعل الآن؟
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Overdue Tasks --}}
            <a href="/tasks?statusFilter=overdue" class="group p-4 bg-white dark:bg-gray-800 rounded-lg border border-red-200 hover:border-red-400 transition-all hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">مهام متأخرة</p>
                        <p class="text-2xl font-bold text-red-600">{{ $this->actionItems['overdue_tasks'] ?? 0 }}</p>
                    </div>
                </div>
            </a>
            
            {{-- Today Tasks --}}
            <a href="/tasks?date=today" class="group p-4 bg-white dark:bg-gray-800 rounded-lg border border-yellow-200 hover:border-yellow-400 transition-all hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">مهام اليوم</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $this->actionItems['today_tasks'] ?? 0 }}</p>
                    </div>
                </div>
            </a>
            
            {{-- My Review Docs --}}
            <a href="/documents?stage=review1" class="group p-4 bg-white dark:bg-gray-800 rounded-lg border border-blue-200 hover:border-blue-400 transition-all hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">تنتظر مراجعتي</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $this->actionItems['my_review_docs'] ?? 0 }}</p>
                    </div>
                </div>
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



