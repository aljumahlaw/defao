<?php

namespace App\Livewire\Archive;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class ArchiveTable extends Component
{
    use WithPagination;

    public $perPage = 20;
    public $search = '';

    #[Computed]
    public function archivedDocuments()
    {
        return Document::query()
            ->with(['creator:id,name', 'assignee:id,name'])
            ->visibleTo(auth()->user())
            ->where('is_archived', true)
            ->when($this->search, fn($q) => $q->where('title', 'like', '%'.$this->search.'%'))
            ->latest('created_at')
            ->paginate($this->perPage);
    }

    public function unarchive($id)
    {
        $doc = Document::visibleTo(auth()->user())->findOrFail($id);
        $doc->update(['is_archived' => false]);
        $this->dispatch('show-toast', 
            message: 'تم إلغاء أرشفة الوثيقة بنجاح',
            type: 'success'
        );
    }

    public function render()
    {
        return view('livewire.archive.archive-table');
    }
}



