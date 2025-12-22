<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            مراحل سير العمل
        </h3>
        <button wire:click="exportWorkflowReport" 
                class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors text-sm">
            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-arrow-down-tray'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
            <span>تصدير تقرير PDF</span>
        </button>
    </div>
    
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
            <div class="flex items-center justify-between">
                <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-300">إجمالي المستندات</h4>
                <span class="text-xl font-bold bg-blue-200 dark:bg-blue-800 text-blue-700 dark:text-blue-300 px-3 py-1 rounded">
                    <?php echo e($this->totalDocuments); ?>

                </span>
            </div>
            <p class="text-sm text-blue-700 dark:text-blue-400 mt-2">جميع الوثائق النشطة (غير المؤرشفة)</p>
        </div>

        
        <a href="<?php echo e(route('documents.index', ['stage' => 'all', 'overdue' => '1'])); ?>" 
           class="block bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800 hover:shadow-md hover:-translate-y-0.5 transform transition cursor-pointer">
            <div class="flex items-center justify-between">
                <h4 class="text-lg font-semibold text-red-900 dark:text-red-300">المستندات المتأخرة</h4>
                <span class="text-xl font-bold bg-red-200 dark:bg-red-800 text-red-700 dark:text-red-300 px-3 py-1 rounded">
                    <?php echo e($this->overdueDocuments); ?>

                </span>
            </div>
            <p class="text-sm text-red-700 dark:text-red-400 mt-2">وثائق متوقفة في مراحلها لأكثر من المدة المحددة</p>
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        
        <a href="<?php echo e(route('documents.index', ['stage' => 'draft'])); ?>" 
           class="block bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md hover:-translate-y-0.5 transform transition cursor-pointer">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-gray-900 dark:text-white">مسودة</h4>
                <span class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded">
                    <?php echo e($this->stageCounts['draft']); ?>

                </span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400">الوثائق في مرحلة المسودة</p>
        </a>

        
        <a href="<?php echo e(route('documents.index', ['stage' => 'review1'])); ?>" 
           class="block bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800 hover:shadow-md hover:-translate-y-0.5 transform transition cursor-pointer">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-blue-900 dark:text-blue-300">مراجعة أولى</h4>
                <span class="text-xs bg-blue-200 dark:bg-blue-800 text-blue-700 dark:text-blue-300 px-2 py-1 rounded">
                    <?php echo e($this->stageCounts['review1']); ?>

                </span>
            </div>
            <p class="text-sm text-blue-700 dark:text-blue-400">الوثائق قيد المراجعة الأولى</p>
        </a>

        
        <a href="<?php echo e(route('documents.index', ['stage' => 'proofread'])); ?>" 
           class="block bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800 hover:shadow-md hover:-translate-y-0.5 transform transition cursor-pointer">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-yellow-900 dark:text-yellow-300">تدقيق لغوي</h4>
                <span class="text-xs bg-yellow-200 dark:bg-yellow-800 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded">
                    <?php echo e($this->stageCounts['proofread']); ?>

                </span>
            </div>
            <p class="text-sm text-yellow-700 dark:text-yellow-400">الوثائق قيد التدقيق</p>
        </a>

        
        <a href="<?php echo e(route('documents.index', ['stage' => 'finalapproval'])); ?>" 
           class="block bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800 hover:shadow-md hover:-translate-y-0.5 transform transition cursor-pointer">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-green-900 dark:text-green-300">موافقة نهائية</h4>
                <span class="text-xs bg-green-200 dark:bg-green-800 text-green-700 dark:text-green-300 px-2 py-1 rounded">
                    <?php echo e($this->stageCounts['finalapproval']); ?>

                </span>
            </div>
            <p class="text-sm text-green-700 dark:text-green-400">الوثائق قيد الموافقة النهائية</p>
        </a>
    </div>
</div>
<?php /**PATH C:\Users\HP\Desktop\Master\resources\views/livewire/workflow/workflow-overview.blade.php ENDPATH**/ ?>