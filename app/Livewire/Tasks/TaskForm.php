<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\User;
use Livewire\Component;

class TaskForm extends Component
{
    public $isOpen = false;
    public $taskId = null; // null = create, number = edit
    
    // Form fields
    public $title = '';
    public $description = '';
    public $priority = 'medium';
    public $assigned_to = '';
    public $document_id = null;
    public $due_date = '';
    
    // Validation rules
    protected $rules = [
        'title' => 'required|max:100',
        'description' => 'max:500',
        'priority' => 'required|in:low,medium,high,urgent',
        'assigned_to' => 'required|exists:users,id',
        'document_id' => 'nullable|exists:documents,id',
        'due_date' => 'nullable|date',
    ];

    protected $messages = [
        'title.required' => 'العنوان مطلوب',
        'title.max' => 'العنوان لا يمكن أن يتجاوز 100 حرف',
        'description.max' => 'الوصف لا يمكن أن يتجاوز 500 حرف',
        'priority.required' => 'الأولوية مطلوبة',
        'assigned_to.required' => 'يجب تحديد المعين له',
        'assigned_to.exists' => 'المستخدم المحدد غير موجود',
        'document_id.exists' => 'الوثيقة المحددة غير موجودة',
        'due_date.date' => 'تاريخ غير صحيح',
    ];

    protected $listeners = ['open-task-form-modal' => 'open'];

    public function mount($taskId = null)
    {
        $this->taskId = $taskId;
        
        // If editing, load data
        if ($taskId) {
            $this->loadTask($taskId);
        }
    }

    protected function loadTask($id)
    {
        $task = Task::with('assignee', 'document')->findOrFail($id);
        
        $this->title = $task->title;
        $this->description = $task->description ?? '';
        $this->priority = $task->priority;
        $this->assigned_to = $task->assignee_id ?? '';
        $this->document_id = $task->document_id ?? null;
        $this->due_date = $task->due_date ? $task->due_date->format('Y-m-d') : '';
    }

    public function open($taskId = null)
    {
        if ($taskId) {
            $this->taskId = $taskId;
            $this->loadTask($taskId);
        } else {
            $this->taskId = null;
            $this->reset(['title', 'description', 'priority', 'assigned_to', 'document_id', 'due_date']);
            $this->priority = 'medium';
        }
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->reset(['title', 'description', 'priority', 'assigned_to', 'document_id', 'due_date', 'taskId']);
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        if ($this->taskId) {
            // Update existing task
            $task = Task::findOrFail($this->taskId);
            $task->update([
                'title' => $this->title,
                'description' => $this->description,
                'priority' => $this->priority,
                'assignee_id' => $this->assigned_to,
                'document_id' => $this->document_id ?: null,
                'due_date' => $this->due_date ?: null,
            ]);
            $message = 'تم تحديث المهمة بنجاح';
        } else {
            // Create new task
            Task::create([
                'title' => $this->title,
                'description' => $this->description,
                'priority' => $this->priority,
                'status' => 'pending',
                'assignee_id' => $this->assigned_to,
                'document_id' => $this->document_id ?: null,
                'due_date' => $this->due_date ?: null,
                'user_id' => auth()->id(),
            ]);
            $message = 'تم إنشاء المهمة بنجاح';
        }

        $this->dispatch('show-toast', 
            message: $message,
            type: 'success'
        );

        $this->dispatch('task-saved');
        $this->close();
    }

    public function getUsers()
    {
        return User::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.tasks.task-form', [
            'users' => $this->getUsers(),
        ]);
    }
}
