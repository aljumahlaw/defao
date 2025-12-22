<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class DocumentArchive extends Component
{
    use WithPagination;

    public $perPage = 20;
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';

    #[Computed]
    public function documents()
    {
        return Document::query()
            ->with(['creator:id,name', 'assignee:id,name']) // ✅ P1-7: Eager Loading دائماً
            ->visibleTo(auth()->user())
            ->where('is_archived', true)
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->latest()
            ->paginate($this->perPage);
    }

    public function unarchive($id)
    {
        // ✅ P1-10: Transaction wrapper for data integrity
        try {
            DB::transaction(function () use ($id) {
                $document = Document::findOrFail($id);
                
                // تأكيد أن المستند مؤرشف
                if (!$document->is_archived) {
                    throw new \Exception('هذه الوثيقة غير مؤرشفة');
                }

                $document->unarchive();
            });
            
            $this->dispatch('show-toast', 
                message: 'تم استعادة الوثيقة بنجاح',
                type: 'success'
            );
        } catch (\Exception $e) {
            $this->dispatch('show-toast', 
                message: $e->getMessage(),
                type: 'error'
            );
        }
    }

    public function forceDelete($id)
    {
        // ✅ P1-10: Transaction wrapper for data integrity
        try {
            $title = DB::transaction(function () use ($id) {
                $document = Document::findOrFail($id);
                
                // تأكيد أن المستند مؤرشف
                if (!$document->is_archived) {
                    throw new \Exception('لا يمكن حذف وثيقة غير مؤرشفة');
                }

                $title = $document->title;
                $document->forceDelete();
                
                return $title;
            });
            
            $this->dispatch('show-toast', 
                message: 'تم حذف الوثيقة "' . $title . '" نهائياً',
                type: 'success'
            );
        } catch (\Exception $e) {
            $this->dispatch('show-toast', 
                message: $e->getMessage(),
                type: 'error'
            );
        }
    }

    #[Computed]
    public function resultsCount()
    {
        return $this->documents->total();
    }

    public function render()
    {
        return view('livewire.documents.document-archive');
    }
}
