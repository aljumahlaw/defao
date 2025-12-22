<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class TaskTable extends Component
{
    use WithPagination;

    public $status = 'all';
    public $priority = 'all';
    public $perPage = 10;
    
    public $task = null;

    protected $listeners = ['open-task-form' => 'openForm', 'task-saved' => '$refresh'];

    public function openForm()
    {
        $this->dispatch('open-task-form-modal');
    }

    #[Computed]
    public function tasks()
    {
        return Task::query()
            ->visibleTo(auth()->user()) // ✅ Role-aware filtering
            ->with([
                'document',
                'assignee:id,name',
                'creator:id,name',
            ])
            ->when($this->status !== 'all', fn($q) => $q->where('status', $this->status))
            ->when($this->priority !== 'all', fn($q) => $q->where('priority', $this->priority))
            ->latest()
            ->paginate($this->perPage);
    }

    public function viewTask($id)
    {
        $this->task = Task::with([
            'document',
            'assignee:id,name',
        ])->findOrFail($id);
        
        $this->dispatch('open-modal', 'task-details');
    }

    public function editTask($id)
    {
        $this->dispatch('open-task-form-modal', taskId: $id);
    }

    public function deleteTask($id)
    {
        // ✅ P1-3: Real delete implementation
        $task = Task::findOrFail($id);
        
        // Authorization: Only creator, assignee, or admin can delete
        $user = auth()->user();
        if (!$user->hasRole('admin') && 
            $task->created_by !== $user->id && 
            $task->assigned_to !== $user->id) {
            $this->dispatch('show-toast', 
                message: 'ليس لديك صلاحية حذف هذه المهمة',
                type: 'error'
            );
            return;
        }
        
        $title = $task->title;
        $task->delete();
        
        $this->dispatch('show-toast', 
            message: 'تم حذف المهمة "' . $title . '" بنجاح',
            type: 'success'
        );
    }

    protected function getStatusBadgeClass(string $status): string
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
            'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            'overdue' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
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
            'pending' => 'معلقة',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتملة',
            'overdue' => 'متأخرة',
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
