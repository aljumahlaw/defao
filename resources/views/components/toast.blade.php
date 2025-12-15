<div x-data="{
    toasts: [],
    addToast(type, message) {
        const id = Date.now();
        this.toasts.push({ id, type, message, show: false });
        
        // Trigger animation
        setTimeout(() => {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) toast.show = true;
        }, 100);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            this.removeToast(id);
        }, 5000);
    },
    removeToast(id) {
        const toast = this.toasts.find(t => t.id === id);
        if (toast) {
            toast.show = false;
            // Remove from array after animation
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 300);
        }
    }
}"
@show-toast.window="addToast($event.detail.type || 'info', $event.detail.message)"
class="fixed top-4 left-4 z-50 space-y-3"
style="max-width: 400px;">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="flex items-start gap-3 p-4 rounded-lg shadow-lg backdrop-blur-sm"
             :class="{
                 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800': toast.type === 'success',
                 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800': toast.type === 'error',
                 'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800': toast.type === 'warning',
                 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800': toast.type === 'info'
             }">
            
            {{-- Icon --}}
            <div class="flex-shrink-0">
                <div x-show="toast.type === 'success'">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-600 dark:text-green-400" />
                </div>
                <div x-show="toast.type === 'error'">
                    <x-heroicon-o-x-circle class="w-6 h-6 text-red-600 dark:text-red-400" />
                </div>
                <div x-show="toast.type === 'warning'">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                </div>
                <div x-show="toast.type === 'info'">
                    <x-heroicon-o-information-circle class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
            </div>

            {{-- Message --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium"
                   :class="{
                       'text-green-800 dark:text-green-200': toast.type === 'success',
                       'text-red-800 dark:text-red-200': toast.type === 'error',
                       'text-yellow-800 dark:text-yellow-200': toast.type === 'warning',
                       'text-blue-800 dark:text-blue-200': toast.type === 'info'
                   }"
                   x-text="toast.message">
                </p>
            </div>

            {{-- Close Button --}}
            <button @click="removeToast(toast.id)"
                    class="flex-shrink-0 p-1 rounded-lg transition-colors"
                    :class="{
                        'text-green-600 hover:bg-green-100 dark:text-green-400 dark:hover:bg-green-900/50': toast.type === 'success',
                        'text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/50': toast.type === 'error',
                        'text-yellow-600 hover:bg-yellow-100 dark:text-yellow-400 dark:hover:bg-yellow-900/50': toast.type === 'warning',
                        'text-blue-600 hover:bg-blue-100 dark:text-blue-400 dark:hover:bg-blue-900/50': toast.type === 'info'
                    }">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>
    </template>
</div>
