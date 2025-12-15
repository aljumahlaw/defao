<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use Livewire\Attributes\Computed;

class TaskTable extends Component
{
    public $status = 'all';
    public $priority = 'all';
    public $perPage = 20;

    protected $listeners = ['open-task-form' => 'openForm', 'task-saved' => '$refresh'];

    public function openForm()
    {
        $this->dispatch('open-task-form-modal');
    }

    #[Computed]
    public function tasks()
    {
        // TODO: Phase 5 - Replace with:
        // return Task::query()
        //     ->with('assignedTo', 'creator')
        //     ->when($this->status !== 'all', fn($q) => $q->where('status', $this->status))
        //     ->when($this->priority !== 'all', fn($q) => $q->where('priority', $this->priority))
        //     ->latest()
        //     ->paginate($this->perPage);
        
        $fakeTasks = collect([
            [
                'id' => 1,
                'title' => 'مراجعة عقد الإيجار التجاري',
                'status' => 'active',
                'priority' => 'high',
                'assigned_to' => 'محمد',
                'created_at' => now()->subHours(2),
            ],
            [
                'id' => 2,
                'title' => 'تدقيق الفواتير الشهرية',
                'status' => 'active',
                'priority' => 'medium',
                'assigned_to' => 'رنيم',
                'created_at' => now()->subHours(5),
            ],
            [
                'id' => 3,
                'title' => 'إعداد تقرير ربع سنوي',
                'status' => 'draft',
                'priority' => 'high',
                'assigned_to' => 'خالد علي',
                'created_at' => now()->subDay(),
            ],
            [
                'id' => 4,
                'title' => 'مراجعة العقود القديمة',
                'status' => 'completed',
                'priority' => 'low',
                'assigned_to' => 'العنود',
                'created_at' => now()->subDays(2),
            ],
            [
                'id' => 5,
                'title' => 'تحديث قاعدة البيانات',
                'status' => 'active',
                'priority' => 'medium',
                'assigned_to' => 'محمد سعيد',
                'created_at' => now()->subDays(3),
            ],
            [
                'id' => 6,
                'title' => 'إرسال التقارير للإدارة',
                'status' => 'completed',
                'priority' => 'high',
                'assigned_to' => 'مالك',
                'created_at' => now()->subDays(4),
            ],
            [
                'id' => 7,
                'title' => 'مراجعة الوثائق الواردة',
                'status' => 'active',
                'priority' => 'medium',
                'assigned_to' => 'عبدالرحمن خالد',
                'created_at' => now()->subDays(5),
            ],
            [
                'id' => 8,
                'title' => 'إعداد العروض التقديمية',
                'status' => 'draft',
                'priority' => 'low',
                'assigned_to' => 'ريم محمد',
                'created_at' => now()->subWeek(),
            ],
            [
                'id' => 9,
                'title' => 'تنظيم الأرشيف الإلكتروني',
                'status' => 'active',
                'priority' => 'low',
                'assigned_to' => 'طارق أحمد',
                'created_at' => now()->subWeek(),
            ],
            [
                'id' => 10,
                'title' => 'متابعة الطلبات المعلقة',
                'status' => 'completed',
                'priority' => 'medium',
                'assigned_to' => 'لينا سعد',
                'created_at' => now()->subWeeks(2),
            ],
        ]);

        // Filter by status
        if ($this->status !== 'all') {
            $fakeTasks = $fakeTasks->filter(fn($task) => $task['status'] === $this->status);
        }

        // Filter by priority
        if ($this->priority !== 'all') {
            $fakeTasks = $fakeTasks->filter(fn($task) => $task['priority'] === $this->priority);
        }

        return $fakeTasks;
    }

    public function viewTask($id)
    {
        // TODO: Phase 5 - Implement real view
        $this->dispatch('show-toast', 
            message: 'قريباً: عرض المهمة #' . $id,
            type: 'info'
        );
    }

    public function editTask($id)
    {
        $this->dispatch('open-task-form-modal', taskId: $id);
    }

    public function deleteTask($id)
    {
        // TODO: Phase 5 - Implement real delete
        $this->dispatch('show-toast', 
            message: 'قريباً: حذف المهمة #' . $id,
            type: 'warning'
        );
    }

    protected function getStatusBadgeClass(string $status): string
    {
        return match($status) {
            'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
            'active' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    protected function getPriorityBadgeClass(string $priority): string
    {
        return match($priority) {
            'high' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
            'medium' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
            'low' => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    protected function getStatusLabel(string $status): string
    {
        return match($status) {
            'draft' => 'مسودة',
            'active' => 'نشط',
            'completed' => 'مكتمل',
            default => 'غير محدد',
        };
    }

    protected function getPriorityLabel(string $priority): string
    {
        return match($priority) {
            'high' => 'عالية',
            'medium' => 'متوسطة',
            'low' => 'منخفضة',
            default => 'غير محدد',
        };
    }

    public function render()
    {
        return view('livewire.tasks.task-table');
    }
}
