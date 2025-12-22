<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class TaskList extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $assigneeFilter = '';
    public $priorityFilter = 'all';
    
    public ?int $selectedTaskId = null;
    public bool $showTaskModal = false;

    protected $listeners = [
        'global-search-updated' => 'handleGlobalSearch',
        'global-filter-updated' => 'handleGlobalFilter',
        'global-search-cleared' => 'clearSearch',
        'global-filters-cleared' => 'clearFilters',
    ];

    #[Computed]
    public function tasks()
    {
        return Task::query()
            ->visibleTo(auth()->user()) // ✅ Role-aware filtering
            ->with('document', 'creator', 'assignee')
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term);
                });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('due_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('due_date', '<=', $this->dateTo))
            ->when($this->assigneeFilter, fn($q) => $q->where('assignee_id', $this->assigneeFilter))
            ->when($this->priorityFilter !== 'all', fn($q) => $q->where('priority', $this->priorityFilter))
            ->latest()
            ->paginate(20);
    }

    #[Computed]
    public function assignees()
    {
        return User::where('is_active', true)
            ->whereIn('role', [User::ROLE_LAWYER, User::ROLE_ASSISTANT])
            ->orderBy('name')
            ->get(['id', 'name', 'role'])
            ->mapWithKeys(fn($u) => [
                $u->id => $u->name . ' — ' .
                    ($u->role === User::ROLE_LAWYER ? 'محامي' : 'مساعد') .
                    ' #' . $u->id
            ]);
    }

    #[Computed]
    public function stats()
    {
        $today = now()->startOfDay();
        $weekStart = now()->startOfWeek();

        return [
            'today' => Task::whereDate('due_date', $today)->count(),
            'this_week' => Task::whereDate('due_date', '>=', $weekStart)->count(),
            'overdue' => Task::where('status', '!=', 'completed')
                ->where('due_date', '<', now())
                ->count(),
            'completion_rate' => Task::count() > 0 
                ? round((Task::where('status', 'completed')->count() / Task::count()) * 100)
                : 0,
        ];
    }

    #[Computed]
    public function statusCounts()
    {
        return Cache::remember('task_stats_' . auth()->id(), 300, function () {
            $pending = Task::where('status', 'pending')->count();
            $inProgress = Task::where('status', 'in_progress')->count();
            $completed = Task::where('status', 'completed')->count();
            $overdue = Task::where('status', 'overdue')->count();
            
            return [
                'all' => $pending + $inProgress + $completed + $overdue,
                'pending' => $pending,
                'in_progress' => $inProgress,
                'completed' => $completed,
                'overdue' => $overdue,
            ];
        });
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function getStatusBadgeColor($status)
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
            'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
            'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }

    public function getStatusLabel($status)
    {
        return match($status) {
            'pending' => 'معلقة',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتملة',
            'overdue' => 'متأخرة',
            default => 'غير محدد',
        };
    }

    public function getPriorityBadgeColor($priority)
    {
        return match($priority) {
            'low' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'medium' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
            'high' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400',
            'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }

    public function getPriorityLabel($priority)
    {
        return match($priority) {
            'low' => 'منخفضة',
            'medium' => 'متوسطة',
            'high' => 'عالية',
            'urgent' => 'عاجلة',
            default => 'غير محدد',
        };
    }

    public function viewTask(int $taskId)
    {
        $this->selectedTaskId = (int) $taskId;
        $this->showTaskModal = true;
    }
    
    public function closeTaskModal()
    {
        $this->showTaskModal = false;
        $this->selectedTaskId = null;
    }
    
    #[Computed]
    public function getSelectedTaskProperty()
    {
        return $this->selectedTaskId
            ? Task::with(['document:id,title', 'assignee:id,name', 'creator:id,name'])
                ->find($this->selectedTaskId)
            : null;
    }

    public function editTask($taskId)
    {
        // Open TaskForm modal for editing
        $this->dispatch('open-task-form-modal', taskId: $taskId);
    }

    public function deleteTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->delete();
        
        $this->dispatch('show-toast', 
            message: "تم حذف المهمة بنجاح",
            type: 'success'
        );
    }

    public function handleGlobalSearch($search)
    {
        $this->search = $search;
        $this->resetPage();
    }

    public function handleGlobalFilter($filter, $value)
    {
        match($filter) {
            'status' => $this->statusFilter = $value,
            'dateFrom' => $this->dateFrom = $value,
            'dateTo' => $this->dateTo = $value,
            default => null,
        };
        if ($filter === 'status') {
            $this->setStatusFilter($value);
        }
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->statusFilter = 'all';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->assigneeFilter = '';
        $this->priorityFilter = 'all';
        $this->resetPage();
    }

    #[Computed]
    public function resultsCount()
    {
        return $this->tasks->total();
    }

    public function render()
    {
        return view('livewire.tasks.task-list');
    }
}
