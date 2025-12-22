<div>
    
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">إجراءات سريعة</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['type' => 'button','wire:click' => 'openTaskModal','class' => 'flex flex-col items-center gap-3 p-6 rounded-lg hover:shadow-md hover:-translate-y-1 transition-all duration-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'openTaskModal','class' => 'flex flex-col items-center gap-3 p-6 rounded-lg hover:shadow-md hover:-translate-y-1 transition-all duration-200']); ?>
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-plus-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                <span class="font-medium">إنشاء مهمة جديدة</span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
            <a href="<?php echo e(route('documents.upload')); ?>"
               class="flex flex-col items-center gap-3 p-6 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-arrow-up-tray'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                <span class="font-medium">رفع وثيقة</span>
            </a>
            <a href="<?php echo e(route('reports.index')); ?>" class="flex flex-col items-center gap-3 p-6 rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 hover:shadow-md hover:-translate-y-1 transition-all duration-200 block">
                <?php if (isset($component)) { $__componentOriginal8736d1f9d247777307ff88fb551c8a70 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8736d1f9d247777307ff88fb551c8a70 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quick-action-card','data' => ['icon' => 'heroicon-o-chart-bar','title' => 'عرض التقارير','color' => 'purple']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quick-action-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-o-chart-bar','title' => 'عرض التقارير','color' => 'purple']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8736d1f9d247777307ff88fb551c8a70)): ?>
<?php $attributes = $__attributesOriginal8736d1f9d247777307ff88fb551c8a70; ?>
<?php unset($__attributesOriginal8736d1f9d247777307ff88fb551c8a70); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8736d1f9d247777307ff88fb551c8a70)): ?>
<?php $component = $__componentOriginal8736d1f9d247777307ff88fb551c8a70; ?>
<?php unset($__componentOriginal8736d1f9d247777307ff88fb551c8a70); ?>
<?php endif; ?>
            </a>
        </div>
    </div>

    
    <div class="bg-gradient-to-r from-red-50 to-yellow-50 dark:from-red-900/20 dark:to-yellow-900/20 p-6 rounded-xl border border-red-200 dark:border-red-800/50 mb-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            ماذا أفعل الآن؟
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <a href="/tasks?statusFilter=overdue" class="group p-4 bg-white dark:bg-gray-800 rounded-lg border border-red-200 hover:border-red-400 transition-all hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">مهام متأخرة</p>
                        <p class="text-2xl font-bold text-red-600"><?php echo e($this->actionItems['overdue_tasks'] ?? 0); ?></p>
                    </div>
                </div>
            </a>
            
            
            <a href="/tasks?date=today" class="group p-4 bg-white dark:bg-gray-800 rounded-lg border border-yellow-200 hover:border-yellow-400 transition-all hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">مهام اليوم</p>
                        <p class="text-2xl font-bold text-yellow-600"><?php echo e($this->actionItems['today_tasks'] ?? 0); ?></p>
                    </div>
                </div>
            </a>
            
            
            <a href="/documents?stage=review1" class="group p-4 bg-white dark:bg-gray-800 rounded-lg border border-blue-200 hover:border-blue-400 transition-all hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">تنتظر مراجعتي</p>
                        <p class="text-2xl font-bold text-blue-600"><?php echo e($this->actionItems['my_review_docs'] ?? 0); ?></p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">الإحصائيات</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php if (isset($component)) { $__componentOriginalb45570a04b4397e9a75619dfa25dae50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb45570a04b4397e9a75619dfa25dae50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-widget','data' => ['icon' => 'heroicon-o-clipboard-document-list','label' => 'المهام النشطة','value' => $this->stats['active_tasks'],'color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-o-clipboard-document-list','label' => 'المهام النشطة','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->stats['active_tasks']),'color' => 'blue']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $attributes = $__attributesOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $component = $__componentOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__componentOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalb45570a04b4397e9a75619dfa25dae50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb45570a04b4397e9a75619dfa25dae50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-widget','data' => ['icon' => 'heroicon-o-document-text','label' => 'الوثائق قيد المراجعة','value' => $this->stats['review_documents'],'color' => 'yellow']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-o-document-text','label' => 'الوثائق قيد المراجعة','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->stats['review_documents']),'color' => 'yellow']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $attributes = $__attributesOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $component = $__componentOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__componentOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalb45570a04b4397e9a75619dfa25dae50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb45570a04b4397e9a75619dfa25dae50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-widget','data' => ['icon' => 'heroicon-o-check-circle','label' => 'المهام المكتملة','value' => $this->stats['completed_tasks'],'color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-o-check-circle','label' => 'المهام المكتملة','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->stats['completed_tasks']),'color' => 'green']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $attributes = $__attributesOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $component = $__componentOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__componentOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalb45570a04b4397e9a75619dfa25dae50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb45570a04b4397e9a75619dfa25dae50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-widget','data' => ['icon' => 'heroicon-o-archive-box','label' => 'الوثائق المؤرشفة','value' => $this->stats['archived_documents'],'color' => 'purple']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-o-archive-box','label' => 'الوثائق المؤرشفة','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->stats['archived_documents']),'color' => 'purple']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $attributes = $__attributesOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $component = $__componentOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__componentOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
        </div>
    </div>

    
    <div>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">النشاط الأخير</h2>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $this->recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <p class="text-sm text-gray-900 dark:text-white"><?php echo e($activity['text']); ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo e($activity['time']); ?></p>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="p-4 text-center text-gray-500 dark:text-gray-400">
                        لا يوجد نشاط حديث
                    </li>
                <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
            </ul>
        </div>
    </div>
</div>



<?php /**PATH C:\Users\HP\Desktop\Master\resources\views/livewire/dashboard/dashboard-overview.blade.php ENDPATH**/ ?>