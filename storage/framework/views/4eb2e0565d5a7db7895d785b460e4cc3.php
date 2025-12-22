<div class="space-y-6">
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-calendar-days'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-blue-600 dark:text-blue-400']); ?>
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
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 text-center">مهام اليوم</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mx-auto w-fit"><?php echo e($this->stats['today']); ?></p>
                </div>
            </div>
        </div>

        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-purple-600 dark:text-purple-400']); ?>
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
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 text-center">مهام هذا الأسبوع</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mx-auto w-fit"><?php echo e($this->stats['this_week']); ?></p>
                </div>
            </div>
        </div>

        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-lg">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-exclamation-triangle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-red-600 dark:text-red-400']); ?>
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
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 text-center">مهام متأخرة</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400 mx-auto w-fit"><?php echo e($this->stats['overdue']); ?></p>
                </div>
            </div>
        </div>

        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-chart-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-green-600 dark:text-green-400']); ?>
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
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 text-center">معدل الإنجاز</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mx-auto w-fit"><?php echo e($this->stats['completion_rate']); ?>%</p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                
                <div class="flex flex-wrap gap-2">
                    <button wire:click="setStatusFilter('all')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($statusFilter === 'all' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'); ?>">
                        الكل
                        <span class="mr-1 px-2 py-0.5 rounded-full text-xs <?php echo e($statusFilter === 'all' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-600'); ?>">
                            <?php echo e($this->statusCounts['all']); ?>

                        </span>
                    </button>
                    <button wire:click="setStatusFilter('pending')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($statusFilter === 'pending' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'); ?>">
                        معلقة
                        <span class="mr-1 px-2 py-0.5 rounded-full text-xs <?php echo e($statusFilter === 'pending' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-600'); ?>">
                            <?php echo e($this->statusCounts['pending']); ?>

                        </span>
                    </button>
                    <button wire:click="setStatusFilter('in_progress')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($statusFilter === 'in_progress' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'); ?>">
                        قيد التنفيذ
                        <span class="mr-1 px-2 py-0.5 rounded-full text-xs <?php echo e($statusFilter === 'in_progress' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-600'); ?>">
                            <?php echo e($this->statusCounts['in_progress']); ?>

                        </span>
                    </button>
                    <button wire:click="setStatusFilter('completed')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($statusFilter === 'completed' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'); ?>">
                        مكتملة
                        <span class="mr-1 px-2 py-0.5 rounded-full text-xs <?php echo e($statusFilter === 'completed' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-600'); ?>">
                            <?php echo e($this->statusCounts['completed']); ?>

                        </span>
                    </button>
                    <button wire:click="setStatusFilter('overdue')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($statusFilter === 'overdue' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'); ?>">
                        متأخرة
                        <span class="mr-1 px-2 py-0.5 rounded-full text-xs <?php echo e($statusFilter === 'overdue' ? 'bg-white/20' : 'bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400'); ?>">
                            <?php echo e($this->statusCounts['overdue']); ?>

                        </span>
                    </button>
                </div>

                
                <div class="w-full sm:w-64">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        البحث
                    </label>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="ابحث عن مهمة..."
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
                </div>
            </div>

            
            <div class="flex flex-col sm:flex-row gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                
                <div class="w-full sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        المكلف
                    </label>
                    <select wire:model.live="assigneeFilter" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
                        <option value="">جميع المكلفين</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>

                
                <div class="w-full sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        الأولوية
                    </label>
                    <select wire:model.live="priorityFilter" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
                        <option value="all">الكل</option>
                        <option value="low">منخفضة</option>
                        <option value="medium">متوسطة</option>
                        <option value="high">عالية</option>
                        <option value="urgent">عاجلة</option>
                    </select>
                </div>

                
                <div class="w-full sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        من تاريخ
                    </label>
                    <input type="date"
                           wire:model.live="dateFrom"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
                </div>

                
                <div class="w-full sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        إلى تاريخ
                    </label>
                    <input type="date"
                           wire:model.live="dateTo"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary">
                </div>

                
                <!--[if BLOCK]><![endif]--><?php if($assigneeFilter || $priorityFilter !== 'all' || $dateFrom || $dateTo): ?>
                    <div class="flex items-end">
                        <button wire:click="clearFilters" 
                                class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                            مسح الفلاتر
                        </button>
                    </div>
                <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                تم العثور على <span class="font-semibold text-gray-900 dark:text-white"><?php echo e($this->resultsCount); ?></span> مهمة
            </p>
        </div>

        
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
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $this->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            
                            <td class="px-6 py-4 text-center">
                                <button wire:click="viewTask(<?php echo e($task->id); ?>)"
                                        class="text-primary hover:underline font-medium">
                                    <?php echo e($task->title); ?>

                                </button>
                            </td>

                            
                            <td class="px-6 py-4 text-center">
                                <!--[if BLOCK]><![endif]--><?php if($task->document_id && $task->document): ?>
                                    <a href="<?php echo e(route('documents.show', $task->document_id)); ?>"
                                       class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                        <?php echo e($task->document->title); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
                            </td>

                            
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap <?php echo e($this->getStatusBadgeColor($task->status)); ?>">
                                    <?php echo e($this->getStatusLabel($task->status)); ?>

                                </span>
                            </td>

                            
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap <?php echo e($this->getPriorityBadgeColor($task->priority)); ?>">
                                    <?php echo e($this->getPriorityLabel($task->priority)); ?>

                                </span>
                            </td>

                            
                            <td class="px-6 py-4 text-center">
                                <!--[if BLOCK]><![endif]--><?php if($task->due_date): ?>
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            <?php echo e($task->due_date->format('Y/m/d')); ?>

                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php if($task->is_overdue): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                متأخرة
                                            </span>
                                        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <?php echo e($task->due_date->locale('ar')->diffForHumans()); ?>

                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
                            </td>

                            
                            <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-white">
                                <?php echo e($task->assignee ? $task->assignee->name : '-'); ?>

                            </td>

                            
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="viewTask(<?php echo e($task->id); ?>)"
                                            class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                            title="عرض">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-eye'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5']); ?>
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
                                    </button>
                                    <button wire:click="editTask(<?php echo e($task->id); ?>)"
                                            class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                            title="تعديل">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-pencil'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5']); ?>
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
                                    </button>
                                    <button wire:click="deleteTask(<?php echo e($task->id); ?>)"
                                            class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                            title="حذف">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-trash'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5']); ?>
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
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-inbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mx-auto text-gray-400 mb-3']); ?>
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
                                <p class="text-gray-500 dark:text-gray-400">لا توجد مهام</p>
                            </td>
                        </tr>
                    <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>

        
        <div class="mt-4">
            <?php echo e($this->tasks->links()); ?>

        </div>
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($showTaskModal && $this->selectedTask): ?>
        <div class="fixed inset-0 lg:mr-64 bg-black/50 z-50 flex items-center justify-center p-4 animate-fade-in">
            <div class="bg-gray-50 dark:bg-gray-900 rounded-xl w-[min(92vw,28rem)] mx-auto p-4 max-h-[80vh] overflow-y-auto shadow-2xl ring-1 ring-black/10 dark:ring-white/10">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">تفاصيل المهمة</h3>
                    <button wire:click="closeTaskModal" class="p-1 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        ✕
                    </button>
                </div>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">العنوان:</span>
                        <div class="mt-1 font-semibold text-gray-900 dark:text-white"><?php echo e($this->selectedTask->title); ?></div>
                    </div>
                    
                    <!--[if BLOCK]><![endif]--><?php if($this->selectedTask->description): ?>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">الوصف:</span>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-900 dark:text-white"><?php echo e($this->selectedTask->description); ?></div>
                    </div>
                    <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
                    
                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300 block">الحالة:</span>
                            <span class="inline-flex items-center px-2 py-1 mt-1 <?php echo e($this->getStatusBadgeColor($this->selectedTask->status)); ?> rounded-full text-xs">
                                <?php echo e($this->getStatusLabel($this->selectedTask->status)); ?>

                            </span>
                        </div>
                        
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300 block">الأولوية:</span>
                            <span class="inline-flex items-center px-2 py-1 mt-1 <?php echo e($this->getPriorityBadgeColor($this->selectedTask->priority)); ?> rounded-full text-xs">
                                <?php echo e($this->getPriorityLabel($this->selectedTask->priority)); ?>

                            </span>
                        </div>
                        
                        <!--[if BLOCK]><![endif]--><?php if($this->selectedTask->assignee): ?>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300 block">مسند إلى:</span>
                            <span class="text-gray-900 dark:text-white"><?php echo e($this->selectedTask->assignee->name); ?></span>
                        </div>
                        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
                        
                        <!--[if BLOCK]><![endif]--><?php if($this->selectedTask->due_date): ?>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300 block">تاريخ الاستحقاق:</span>
                            <span class="text-gray-900 dark:text-white"><?php echo e($this->selectedTask->due_date->format('Y-m-d')); ?></span>
                        </div>
                        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
                        
                        <!--[if BLOCK]><![endif]--><?php if($this->selectedTask->document): ?>
                        <div class="col-span-2">
                            <span class="font-medium text-gray-700 dark:text-gray-300 block">الوثيقة المرتبطة:</span>
                            <a href="<?php echo e(route('documents.show', $this->selectedTask->document->id)); ?>" class="text-blue-600 dark:text-blue-400 hover:underline">
                                <?php echo e($this->selectedTask->document->title); ?>

                            </a>
                        </div>
                        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
                        
                        <!--[if BLOCK]><![endif]--><?php if($this->selectedTask->creator): ?>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300 block">المنشئ:</span>
                            <span class="text-gray-900 dark:text-white"><?php echo e($this->selectedTask->creator->name); ?></span>
                        </div>
                        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
                        
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300 block">تاريخ الإنشاء:</span>
                            <span class="text-gray-900 dark:text-white"><?php echo e($this->selectedTask->created_at->format('Y-m-d H:i')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\Users\HP\Desktop\Master\resources\views/livewire/tasks/task-list.blade.php ENDPATH**/ ?>