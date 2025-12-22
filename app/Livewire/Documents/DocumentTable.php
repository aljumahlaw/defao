<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class DocumentTable extends Component
{
    use WithPagination;

    public $type = 'all'; // all/incoming/outgoing
    public $stage = 'all'; // all/draft/review1/proofread/finalapproval
    public $archived = false;
    public bool $overdue = false;
    public $perPage = 20;
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $caseFilter = '';
    public $caseNumber = '';
    public $assigneeFilter = '';

    // Bulk Actions
    public array $selected = [];
    public string $bulkAction = '';
    public string $bulkActionValue = '';
    public bool $bulkLoading = false;
    public $showBulkActions = false;

    // Toggle Columns
    public bool $showTitle = true;
    public bool $showCaseNumber = true;
    public bool $showType = true;
    public bool $showStage = true;
    public bool $showCreatedAt = true;
    public bool $showAssignee = false; // hidden by default

    protected $listeners = [
        'global-search-updated' => 'handleGlobalSearch',
        'global-filter-updated' => 'handleGlobalFilter',
        'global-search-cleared' => 'clearSearch',
        'global-filters-cleared' => 'clearFilters',
    ];

    public function mount(): void
    {
        if (request()->has('stage')) {
            $stage = request()->get('stage');
            // Validate stage value
            if (in_array($stage, ['draft', 'review1', 'proofread', 'finalapproval'])) {
                $this->stage = $stage;
            }
        }

        if (request()->has('overdue') && request()->get('overdue') == '1') {
            $this->overdue = true;
        }
    }

    #[Computed]
    public function documents()
    {
        $query = Document::query()
            ->with(['creator:id,name', 'assignee:id,name'])
            ->visibleTo(auth()->user())
            ->when($this->type !== 'all', fn($q) => $q->where('type', $this->type))
            ->when($this->stage !== 'all', fn($q) => $q->where('current_stage', $this->stage))
            ->when($this->archived, fn($q) => $q->where('is_archived', true))
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->assigneeFilter, fn($q) => $q->where('assignee_id', $this->assigneeFilter));

        // CASE FILTER - أضف هنا بالضبط:
        if ($this->caseFilter === 'none') {
            $query->whereNull('case_number');
        } elseif ($this->caseFilter) {
            $query->where('case_number', $this->caseFilter);
        }

        return $query->when($this->overdue, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('current_stage', 'review1')
                        ->where('updated_at', '<', now()->subDays(7));
                    $sub->orWhere('current_stage', 'proofread')
                        ->where('updated_at', '<', now()->subDays(5));
                    $sub->orWhere('current_stage', 'finalapproval')
                        ->where('updated_at', '<', now()->subDays(3));
                });
            })
            ->latest()
            ->paginate($this->perPage);
    }

    public function viewDocument($id)
    {
        return redirect()->route('documents.show', $id);
    }

    public function downloadDocument($id)
    {
        $document = Document::visibleTo(auth()->user())->findOrFail($id);

        // Check if file exists
        if (!$document->s3_path) {
            $this->dispatch('show-toast', 
                message: 'لا يوجد ملف مرفق لهذه الوثيقة',
                type: 'warning'
            );
            return;
        }

        try {
            // S3 Signed URL (5 minutes expiry)
            $url = Storage::disk('s3')->temporaryUrl(
                $document->s3_path, 
                now()->addMinutes(5)
            );

            // Dispatch download event to frontend
            $this->dispatch('download-file', [
                'url' => $url,
                'filename' => $document->file_name ?? ($document->title . '.pdf')
            ]);

            $this->dispatch('show-toast', 
                message: 'جاري تنزيل الوثيقة...',
                type: 'info'
            );
        } catch (\Exception $e) {
            \Log::error('S3 Download Error: ' . $e->getMessage());
            $this->dispatch('show-toast', 
                message: 'حدث خطأ أثناء تنزيل الملف',
                type: 'error'
            );
        }
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
            'incoming' => 'bg-primary/10 text-primary border border-primary/20',
            'outgoing' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            default => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
        };
    }

    protected function getStageBadgeClass(string $stage): string
    {
        return match($stage) {
            'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
            'review1' => 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-900/40',
            'proofread' => 'bg-amber-100 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-900/40',
            'finalapproval' => 'bg-green-100 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-900/40',
            default => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
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
        $this->assigneeFilter = '';
        $this->resetPage();
    }

    #[Computed]
    public function assignees()
    {
        return User::where('is_active', true)
            ->whereIn('role', [User::ROLE_LAWYER, User::ROLE_ASSISTANT, User::ROLE_ADMIN])
            ->orderBy('name')
            ->get()
            ->pluck('display_name', 'id');
    }

    #[Computed]
    public function resultsCount()
    {
        return $this->documents->total();
    }

    #[Computed]
    public function visibleColumns()
    {
        return [
            'title' => $this->showTitle,
            'case_number' => $this->showCaseNumber,
            'type' => $this->showType,
            'stage' => $this->showStage,
            'created_at' => $this->showCreatedAt,
            'assignee' => $this->showAssignee,
        ];
    }

    public function resetColumns()
    {
        $this->showTitle = true;
        $this->showCaseNumber = true;
        $this->showType = true;
        $this->showStage = true;
        $this->showCreatedAt = true;
        $this->showAssignee = false;
    }

    public function getExistingCasesProperty()
    {
        $cacheKey = "document_case_numbers:documents:" . auth()->id();
        
        return Cache::remember($cacheKey, 3600, function () {
            try {
                $cases = Document::query()
                    ->whereNotNull('case_number')
                    ->where('user_id', auth()->id())
                    ->selectRaw('DISTINCT case_number as case_number')
                    ->orderBy('case_number')
                    ->limit(100)
                    ->pluck('case_number')
                    ->filter()
                    ->values()
                    ->all();
                
                array_unshift($cases, 'none', '');
                return $cases;
                
            } catch (\Throwable $e) {
                \Log::error('Case cache error: ' . $e->getMessage());
                return ['none', ''];
            }
        });
    }

    public function updatedCaseFilter()
    {
        $this->resetPage();
    }

    // Bulk Actions Methods
    public function selectAll()
    {
        $this->selected = $this->documents->pluck('id')->toArray();
        $this->dispatch('show-toast')
            ->message('تم تحديد جميع المستندات (' . count($this->selected) . ')')
            ->type('info');
    }

    public function clearSelection()
    {
        $this->selected = [];
        $this->bulkAction = '';
        $this->bulkActionValue = '';
        $this->caseNumber = '';
    }

    public function bulkAction()
    {
        $this->bulkLoading = true;

        if (empty($this->selected)) {
            $this->bulkLoading = false;
            $this->dispatch('show-toast', 
                message: 'لم يتم تحديد أي وثائق',
                type: 'error'
            );
            return;
        }

        $validated = $this->validate([
            'bulkActionValue' => 'required|in:archive,delete,stage:draft,stage:review1,stage:proofread,stage:finalapproval'
        ]);

        $documentsQuery = Document::visibleTo(auth()->user())
            ->whereIn('id', $this->selected);

        $count = 0;
        $errors = 0;

        try {
            match($this->bulkActionValue) {
                'archive' => $count = $documentsQuery->update(['is_archived' => true]),
                'delete' => $count = $documentsQuery->delete(),
                'stage:draft' => $count = $documentsQuery->update(['current_stage' => 'draft']),
                'stage:review1' => $count = $documentsQuery->update(['current_stage' => 'review1']),
                'stage:proofread' => $count = $documentsQuery->update(['current_stage' => 'proofread']),
                'stage:finalapproval' => $count = $documentsQuery->update(['current_stage' => 'finalapproval']),
            };
        } catch (\Exception $e) {
            $errors = 1;
            $count = 0;
        }

        $this->bulkActionValue = '';
        $this->clearSelection();
        $this->resetPage();
        $this->bulkLoading = false;

        $this->dispatch('show-toast', 
            message: "تمت العملية على {$count} وثيقة بنجاح",
            type: 'success'
        );
    }

    public function exportPdf()
    {
        // Get documents query without pagination
        $query = Document::query()
            ->with(['creator:id,name', 'assignee:id,name'])
            ->visibleTo(auth()->user())
            ->when($this->type !== 'all', fn($q) => $q->where('type', $this->type))
            ->when($this->stage !== 'all', fn($q) => $q->where('current_stage', $this->stage))
            ->when($this->archived, fn($q) => $q->where('is_archived', true))
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->caseFilter === 'none', fn($q) => $q->whereNull('case_number'))
            ->when($this->caseFilter && $this->caseFilter !== 'none', fn($q) => $q->where('case_number', $this->caseFilter))
            ->when($this->overdue, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('current_stage', 'review1')
                        ->where('updated_at', '<', now()->subDays(7));
                    $sub->orWhere('current_stage', 'proofread')
                        ->where('updated_at', '<', now()->subDays(5));
                    $sub->orWhere('current_stage', 'finalapproval')
                        ->where('updated_at', '<', now()->subDays(3));
                });
            })
            ->latest();

        // ✅ P1-9: OOM Protection - limit(500) + smart warning
        $totalCount = $query->count();
        if ($totalCount > 500) {
            $this->dispatch('show-toast', 
                message: 'الحد الأقصى 500 مستند. استخدم Bulk Export للمزيد (' . $totalCount . ' إجمالي)',
                type: 'warning');
            return;
        }
        $documents = $query->limit(500)->get();

        // Configure DomPDF for Arabic/UTF-8 support
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        
        $dompdf = new Dompdf($options);
        $html = view('exports.documents-pdf', compact('documents'))->render();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response()->streamDownload(
            function() use ($dompdf) {
                echo $dompdf->output();
            },
            'documents-' . now()->format('Y-m-d') . '.pdf',
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }

    public function bulkArchive()
    {
        $count = count($this->selected);
        Document::whereIn('id', $this->selected)->update(['is_archived' => true]);
        $this->selected = [];
        $this->showBulkActions = false;
        $this->dispatch('show-toast', message: "تم أرشفة {$count} مستند بنجاح", type: 'success');
    }

    public function bulkDelete()
    {
        // ✅ P1-8: Policy check مع chunking للأداء
        $selectedIds = $this->selected;
        $deletableIds = [];

        Document::whereIn('id', $selectedIds)
            ->chunk(500, function ($documents) use (&$deletableIds) {
                foreach ($documents as $document) {
                    if (auth()->user()->can('delete', $document)) {
                        $deletableIds[] = $document->id;
                    }
                }
            });
        
        if (empty($deletableIds)) {
            $this->dispatch('show-toast', 
                message: 'غير مصرّح لهذه العملية',
                type: 'error'
            );
            return;
        }
        
        $count = count($deletableIds);
        Document::whereIn('id', $deletableIds)->delete();
        $this->selected = [];
        $this->showBulkActions = false;
        $this->dispatch('show-toast', message: "تم حذف {$count} مستند بنجاح", type: 'success');
    }

    public function bulkExportPdf()
    {
        $count = count($this->selected);
        $cacheKey = 'pdf_export_' . auth()->id() . '_' . now()->format('YmdHis');

        $documents = Document::whereIn('id', $this->selected)->get();
        $html = view('exports.documents-pdf', [
            'documents' => $documents,
            'selected_count' => $count,
            'export_date' => now()->format('Y-m-d H:i:s')
        ])->render();

        // ✅ Filename synced
        $filename = 'defao-documents-' . now()->format('Ymd-His') . '.pdf';

        // ✅ Cache OBJECT with filename
        Cache::put($cacheKey, [
            'html' => $html,
            'filename' => $filename
        ], now()->addMinutes(5));

        $this->dispatch('show-toast')
            ->message("جاري تصدير {$count} مستند...")
            ->type('info');

        $this->dispatch('download-pdf', [
            'url' => route('documents.download-pdf', ['key' => $cacheKey]),
            'filename' => $filename  // ✅ Passed to frontend
        ]);

        $this->selected = [];
        $this->showBulkActions = false;
    }

    public function bulkPrint()
    {
        $this->dispatch('bulk-print');
        $this->selected = [];
        $this->showBulkActions = false;
        $this->dispatch('show-toast', message: 'تم إرسال المستندات للطباعة', type: 'success');
    }

    public function bulkAssignCase()
    {
        $this->validate([
            'caseNumber' => ['nullable', 'string', 'max:20', 'regex:/^\d{4}\/\d{3}$|^new$|^none$/']
        ]);

        $selectedIds = $this->selected ?? [];

        if (!empty($selectedIds)) {
            $caseNumber = $this->caseNumber === 'none' ? null : $this->caseNumber;
            
            Document::whereIn('id', $selectedIds)
                ->update(['case_number' => $caseNumber]);

            $this->dispatch('show-toast', [
                'message' => "تم ربط " . count($selectedIds) . " مستندات بقضية: " . ($caseNumber ?? 'بدون'),
                'type' => 'success'
            ]);
            
            $cacheKey = "document_case_numbers:documents:" . auth()->id();
            Cache::forget($cacheKey);
        }

        $this->resetBulkActions();
    }

    protected function resetBulkActions()
    {
        $this->selected = [];
        $this->showBulkActions = false;
        $this->caseNumber = '';
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.documents.document-table', [
            'existingCases' => $this->existingCases,
        ]);
    }
}
