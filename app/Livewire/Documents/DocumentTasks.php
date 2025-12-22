<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Models\DocumentTask;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class DocumentTasks extends Component
{
    public int $documentId;
    public ?int $selectedTaskId = null;

    protected $listeners = ['*']; // استمع لكل الـ events

    // Form properties
    public string $title = '';
    public ?string $notes = null;
    public ?string $due_date = null;
    public ?int $assigned_to = null;

    public function mount(int $documentId)
    {
        $this->documentId = $documentId;

        // Check if user can view this document
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        $canView = Document::visibleTo($user)
            ->where('id', $this->documentId)
            ->exists();

        if (!$canView) {
            abort(403);
        }
    }

    public function getDocumentProperty()
    {
        return Document::findOrFail($this->documentId);
    }

    public function addTask()
    {
        $validated = $this->validate([
            'title' => 'required|string|min:3',
            'notes' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        DocumentTask::create([
            'document_id' => $this->documentId,
            'created_by' => auth()->id(),
            'assigned_to' => $this->assigned_to,
            'title' => $this->title,
            'notes' => $this->notes,
            'status' => 'open',
            'due_date' => $this->due_date,
        ]);

        // Reset form
        $this->title = '';
        $this->notes = null;
        $this->due_date = null;
        $this->assigned_to = null;

        $this->dispatch('show-toast', 
            message: 'تم إضافة المهمة بنجاح',
            type: 'success'
        );
    }

    public function markDone(int $taskId)
    {
        $task = DocumentTask::findOrFail($taskId);
        
        // Verify task belongs to this document
        if ($task->document_id !== $this->documentId) {
            abort(403);
        }

        $task->update(['status' => 'closed']);

        $this->dispatch('show-toast', 
            message: 'تم إكمال المهمة',
            type: 'success'
        );
    }

    public function reopen(int $taskId)
    {
        $task = DocumentTask::findOrFail($taskId);
        
        // Verify task belongs to this document
        if ($task->document_id !== $this->documentId) {
            abort(403);
        }

        $task->update(['status' => 'open']);

        $this->dispatch('show-toast', 
            message: 'تم إعادة فتح المهمة',
            type: 'success'
        );
    }

    public function updatedSelectedTaskId($value)
    {
        Log::info("DocumentTasks selectedTaskId updated: $value");
    }

    public function viewTask(int $taskId)
    {
        Log::info("DocumentTasks viewTask called with taskId: $taskId, documentId: {$this->documentId}");
        
        $task = DocumentTask::findOrFail($taskId);
        
        // 1. التحقق الأساسي من ملكية المهمة للمستند الحالي (ضروري)
        if ($task->document_id !== $this->documentId) {
            // استخدام رسالة Toast بدلاً من abort(403) للحفاظ على استجابة الواجهة
            $this->dispatch(
                'show-toast',
                message: 'لا تملك صلاحية عرض هذه المهمة',
                type: 'error'
            );
            return;
        }

        // 2. تطبيق منطق التبديل (Toggle)
        if ($this->selectedTaskId === $taskId) {
            $this->selectedTaskId = null; // إخفاء التفاصيل
            $message = 'تم إخفاء تفاصيل المهمة: ' . $task->title;
        } else {
            $this->selectedTaskId = (int) $taskId; // عرض التفاصيل
            $message = 'تم عرض تفاصيل المهمة: ' . $task->title;
        }

        Log::info("DocumentTasks selectedTaskId set to: {$this->selectedTaskId}");

        // 3. إرسال إشعار للمستخدم
        $this->dispatch(
            'show-toast',
            message: $message,
            type: 'success'
        );
    }

    public function deleteTask(int $taskId)
    {
        $task = DocumentTask::findOrFail($taskId);
        
        // Verify task belongs to this document
        if ($task->document_id !== $this->documentId) {
            abort(403);
        }

        $task->delete();

        $this->dispatch('show-toast', 
            message: 'تم حذف المهمة',
            type: 'success'
        );
    }

    public function getTasksProperty()
    {
        return DocumentTask::where('document_id', $this->documentId)
            ->orderByRaw("CASE WHEN status = 'open' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUsersProperty()
    {
        return \App\Models\User::select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.documents.document-tasks');
    }
}
