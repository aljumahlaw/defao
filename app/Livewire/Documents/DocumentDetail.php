<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Models\DocumentActivity;
use Livewire\Component;
use Livewire\Attributes\Computed;

class DocumentDetail extends Component
{
    public $documentId;

    public function mount($documentId)
    {
        $this->documentId = $documentId;
    }

    #[Computed]
    public function document()
    {
        return Document::with('creator', 'assignee')
            ->findOrFail($this->documentId);
    }

    #[Computed]
    public function activityLog()
    {
        return DocumentActivity::with('user')
            ->where('document_id', $this->documentId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($activity) {
                return [
                    'user' => $activity->user->name,
                    'action' => $activity->action_label,
                    'type' => $activity->action_type,
                    'comment' => $activity->comment,
                    'timestamp' => $activity->created_at->format('Y-m-d H:i'),
                ];
            })
            ->toArray();
    }

    #[Computed]
    public function stages()
    {
        $currentStage = $this->document->current_stage;
        $stagesOrder = ['draft', 'review1', 'proofread', 'finalapproval'];
        $currentIndex = array_search($currentStage, $stagesOrder);
        
        return [
            [
                'name' => 'مسودة',
                'key' => 'draft',
                'status' => $currentIndex > 0 ? 'completed' : ($currentStage === 'draft' ? 'current' : 'pending'),
            ],
            [
                'name' => 'مراجعة أولى',
                'key' => 'review1',
                'status' => $currentIndex > 1 ? 'completed' : ($currentStage === 'review1' ? 'current' : 'pending'),
            ],
            [
                'name' => 'تدقيق',
                'key' => 'proofread',
                'status' => $currentIndex > 2 ? 'completed' : ($currentStage === 'proofread' ? 'current' : 'pending'),
            ],
            [
                'name' => 'موافقة نهائية',
                'key' => 'finalapproval',
                'status' => $currentStage === 'finalapproval' ? 'current' : 'pending',
            ],
        ];
    }

    #[Computed]
    public function fileIcon()
    {
        $mimeType = $this->document->mime_type;
        if (str_contains($mimeType, 'pdf')) {
            return 'heroicon-o-document-text';
        } elseif (str_contains($mimeType, 'word')) {
            return 'heroicon-o-document';
        } elseif (str_contains($mimeType, 'sheet') || str_contains($mimeType, 'excel')) {
            return 'heroicon-o-table-cells';
        }
        return 'heroicon-o-document';
    }

    #[Computed]
    public function activityIcon()
    {
        return [
            'created' => 'heroicon-o-document-plus',
            'uploaded' => 'heroicon-o-arrow-up-tray',
            'approved' => 'heroicon-o-check-circle',
            'rejected' => 'heroicon-o-x-circle',
            'forwarded' => 'heroicon-o-arrow-right-circle',
            'commented' => 'heroicon-o-chat-bubble-left',
            'archived' => 'heroicon-o-archive-box',
        ];
    }

    #[Computed]
    public function stageBadgeColor()
    {
        return [
            'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'review1' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
            'proofread' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
            'finalapproval' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
        ];
    }

    public function approve()
    {
        $document = Document::findOrFail($this->documentId);
        $document->update(['current_stage' => 'finalapproval']);
        
        // Create activity
        DocumentActivity::create([
            'document_id' => $this->documentId,
            'user_id' => auth()->id(),
            'action_type' => 'approved',
        ]);
        
        $this->dispatch('show-toast', 
            message: 'تم الموافقة على الوثيقة بنجاح',
            type: 'success'
        );
    }

    public function reject()
    {
        $document = Document::findOrFail($this->documentId);
        $document->update(['current_stage' => 'draft']);
        
        // Create activity
        DocumentActivity::create([
            'document_id' => $this->documentId,
            'user_id' => auth()->id(),
            'action_type' => 'rejected',
        ]);
        
        $this->dispatch('show-toast', 
            message: 'تم رفض الوثيقة',
            type: 'error'
        );
    }

    public function forward()
    {
        $document = Document::findOrFail($this->documentId);
        $stages = ['draft', 'review1', 'proofread', 'finalapproval'];
        $currentIndex = array_search($document->current_stage, $stages);
        
        if ($currentIndex < count($stages) - 1) {
            $document->update(['current_stage' => $stages[$currentIndex + 1]]);
            
            // Create activity
            DocumentActivity::create([
                'document_id' => $this->documentId,
                'user_id' => auth()->id(),
                'action_type' => 'forwarded',
            ]);
            
            $this->dispatch('show-toast', 
                message: 'تم تحويل الوثيقة بنجاح',
                type: 'success'
            );
        }
    }

    public function comment()
    {
        // TODO: Phase 6 - Open comment modal
        $this->dispatch('show-toast', 
            message: 'قريباً: إضافة تعليق (Phase 3: لا يوجد modal)',
            type: 'info'
        );
    }

    public function download()
    {
        // TODO: Phase 6 - Generate signed URL from S3
        $this->dispatch('show-toast', 
            message: 'قريباً: تنزيل الملف (Phase 3: لا يوجد S3)',
            type: 'info'
        );
    }

    public function render()
    {
        return view('livewire.documents.document-detail');
    }
}
