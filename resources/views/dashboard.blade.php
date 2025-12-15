<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Quick Actions --}}
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">إجراءات سريعة</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button x-data @click="$dispatch('open-task-form-modal')"
                            class="flex flex-col items-center gap-3 p-6 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                        <x-heroicon-o-plus-circle class="w-8 h-8" />
                        <span class="font-medium">إنشاء مهمة جديدة</span>
                    </button>
                    <a href="{{ route('documents.upload') }}"
                       class="flex flex-col items-center gap-3 p-6 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                        <x-heroicon-o-arrow-up-tray class="w-8 h-8" />
                        <span class="font-medium">رفع وثيقة</span>
                    </a>
                    <x-quick-action-card icon="heroicon-o-chart-bar" title="عرض التقارير" color="purple" />
                </div>
            </div>

            {{-- Stats Widgets --}}
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">الإحصائيات</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-stat-widget icon="heroicon-o-clipboard-document-list" label="المهام النشطة" value="12" color="blue" />
                    <x-stat-widget icon="heroicon-o-document-text" label="الوثائق قيد المراجعة" value="8" color="yellow" />
                    <x-stat-widget icon="heroicon-o-check-circle" label="المهام المكتملة" value="45" color="green" />
                    <x-stat-widget icon="heroicon-o-archive-box" label="الوثائق المؤرشفة" value="23" color="purple" />
                </div>
            </div>

            {{-- Recent Activity --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">النشاط الأخير</h2>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @php
                        $activities = [
                            ['text' => 'تم إنشاء مهمة: مراجعة عقد الإيجار', 'time' => 'منذ ساعتين'],
                            ['text' => 'تم رفع وثيقة: تقرير ربع سنوي', 'time' => 'منذ 3 ساعات'],
                            ['text' => 'تم إكمال مهمة: تدقيق الفاتورة', 'time' => 'منذ 5 ساعات'],
                            ['text' => 'تم أرشفة وثيقة: عقد 2023', 'time' => 'منذ يوم واحد'],
                            ['text' => 'تم تعيين مهمة لأحمد', 'time' => 'منذ يومين'],
                        ];
                        @endphp
                        
                        @foreach($activities as $activity)
                            <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <p class="text-sm text-gray-900 dark:text-white">{{ $activity['text'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $activity['time'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <livewire:tasks.task-form />
</x-app-layout>
