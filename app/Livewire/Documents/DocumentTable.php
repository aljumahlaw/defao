<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class DocumentTable extends Component
{
    use WithPagination;

    public $type = 'all'; // all/incoming/outgoing
    public $stage = 'all'; // all/draft/review1/proofread/finalapproval
    public $archived = false;
    public $perPage = 20;
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';

    protected $listeners = [
        'global-search-updated' => 'handleGlobalSearch',
        'global-filter-updated' => 'handleGlobalFilter',
        'global-search-cleared' => 'clearSearch',
        'global-filters-cleared' => 'clearFilters',
    ];

    #[Computed]
    public function documents()
    {
        return Document::query()
            ->with('creator', 'assignee')
            ->when($this->type !== 'all', fn($q) => $q->where('type', $this->type))
            ->when($this->stage !== 'all', fn($q) => $q->where('current_stage', $this->stage))
            ->when($this->archived, fn($q) => $q->where('is_archived', true))
            ->when(!$this->archived, fn($q) => $q->where('is_archived', false))
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->latest()
            ->paginate($this->perPage);
    }

    public function viewDocument($id)
    {
        return redirect()->route('documents.show', $id);
    }

    public function downloadDocument($id)
    {
        // TODO: Phase 6 - Generate signed URL from S3
        $this->dispatch('show-toast', 
            message: 'قريباً: تنزيل الوثيقة #' . $id,
            type: 'info'
        );
    }

    public function uploadNewVersion($id)
    {
        // TODO: Phase 6 - Open upload modal for new version
        $this->dispatch('show-toast', 
            message: 'قريباً: رفع نسخة جديدة للوثيقة #' . $id,
            type: 'info'
        );
    }

    public function archiveDocument($id)
    {
        $document = Document::findOrFail($id);
        $document->update(['is_archived' => true]);
        
        $this->dispatch('show-toast', 
            message: 'تم أرشفة الوثيقة بنجاح',
            type: 'success'
        );
    }

    protected function getTypeBadgeClass(string $type): string
    {
        return match($type) {
            'incoming' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'outgoing' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    protected function getStageBadgeClass(string $stage): string
    {
        return match($stage) {
            'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
            'review1' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'proofread' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
            'finalapproval' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    protected function getTypeLabel(string $type): string
    {
        return match($type) {
            'incoming' => 'وارد',
            'outgoing' => 'صادر',
            default => 'غير محدد',
        };
    }

    protected function getStageLabel(string $stage): string
    {
        return match($stage) {
            'draft' => 'مسودة',
            'review1' => 'مراجعة أولى',
            'proofread' => 'تدقيق',
            'finalapproval' => 'موافقة نهائية',
            default => 'غير محدد',
        };
    }

    public function handleGlobalSearch($search)
    {
        $this->search = $search;
        $this->resetPage();
    }

    public function handleGlobalFilter($filter, $value)
    {
        match($filter) {
            'status' => $this->stage = $value,
            'type' => $this->type = $value,
            'dateFrom' => $this->dateFrom = $value,
            'dateTo' => $this->dateTo = $value,
            default => null,
        };
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->stage = 'all';
        $this->type = 'all';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    #[Computed]
    public function resultsCount()
    {
        return $this->documents->total();
    }

    public function render()
    {
        return view('livewire.documents.document-table');
    }
}
