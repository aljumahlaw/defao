<div x-data="{ expanded: false }" class="<?php echo e($this->stageColorClasses['bg']); ?> rounded-lg p-4 border <?php echo e($this->stageColorClasses['border']); ?>">
    <div class="flex items-center justify-between mb-3">
        <h4 class="font-semibold <?php echo e($this->stageColorClasses['text']); ?>">
            <?php echo e($this->stageLabel); ?>

        </h4>
        <button @click="expanded = !expanded" 
                class="text-sm <?php echo e($this->stageColorClasses['textSecondary']); ?> hover:opacity-75 transition-opacity">
            <span x-show="!expanded">▼</span>
            <span x-show="expanded">▲</span>
        </button>
    </div>

    <div x-show="expanded" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         style="display: none;">
        <!--[if BLOCK]><![endif]--><?php if($this->recentDocuments->isEmpty()): ?>
            <p class="text-sm <?php echo e($this->stageColorClasses['textSecondary']); ?> py-2">
                لا توجد مستندات في هذه المرحلة
            </p>
        <?php else: ?>
            <ul class="space-y-2">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->recentDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <div class="p-2 rounded hover:bg-white/50 dark:hover:bg-gray-800/50 transition-colors">
                            <a href="<?php echo e(route('documents.show', $document->id)); ?>" 
                               class="block">
                                <p class="text-sm font-medium <?php echo e($this->stageColorClasses['text']); ?> truncate">
                                    <?php echo e($document->title); ?>

                                </p>
                                <p class="text-xs <?php echo e($this->stageColorClasses['textSecondary']); ?> mt-1">
                                    آخر تحديث: <?php echo e($document->updated_at->diffForHumans()); ?>

                                </p>
                            </a>
                            <div class="flex gap-2 mt-2">
                                <!--[if BLOCK]><![endif]--><?php if($this->stage !== 'finalapproval'): ?>
                                    <button wire:click="advanceStage(<?php echo e($document->id); ?>)" 
                                            wire:confirm="هل تريد إرسال هذا المستند للمرحلة التالية؟"
                                            class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition-colors">
                                        <?php echo e($this->getNextStageLabel); ?>

                                    </button>
                                <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
                                <button wire:click="rejectStage(<?php echo e($document->id); ?>)" 
                                        wire:confirm="هل تريد إرجاع هذا المستند للمسودة؟"
                                        class="px-3 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 transition-colors">
                                    إرجاع
                                </button>
                            </div>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <!--[if ENDBLOCK]><![endif]-->
            </ul>
        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH C:\Users\HP\Desktop\Master\resources\views/livewire/workflow/workflow-stage-card.blade.php ENDPATH**/ ?>