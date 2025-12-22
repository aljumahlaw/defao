<?php

namespace App\Livewire\Workflow;

use App\Models\Document;
use Livewire\Component;
use Livewire\Attributes\Computed;

class WorkflowStageCard extends Component
{
    public string $stage; // draft/review1/proofread/finalapproval

    public function mount(string $stage): void
    {
        $this->stage = $stage;
    }

    #[Computed]
    public function recentDocuments()
    {
        $user = auth()->user();
        if (!$user) {
            return collect([]);
        }

        return Document::visibleTo($user)
            ->with(['creator:id,name', 'assignee:id,name']) // ✅ Eager Loading - يمنع N+1
            ->where('current_stage', $this->stage)
            ->where('is_archived', false)
            ->latest()
            ->limit(3)
            ->get(['id', 'title', 'updated_at', 'user_id', 'assignee_id']); // ✅ FKs للـ relations
    }

    #[Computed]
    public function stageLabel(): string
    {
        return match($this->stage) {
            'draft' => 'مسودة',
            'review1' => 'مراجعة أولى',
            'proofread' => 'تدقيق',
            'finalapproval' => 'موافقة نهائية',
            default => 'غير محدد',
        };
    }

    #[Computed]
    public function stageColorClasses(): array
    {
        return match($this->stage) {
            'draft' => [
                'bg' => 'bg-gray-50 dark:bg-gray-700/50',
                'border' => 'border-gray-200 dark:border-gray-600',
                'text' => 'text-gray-900 dark:text-white',
                'textSecondary' => 'text-gray-600 dark:text-gray-400',
            ],
            'review1' => [
                'bg' => 'bg-blue-50 dark:bg-blue-900/20',
                'border' => 'border-blue-200 dark:border-blue-800',
                'text' => 'text-blue-900 dark:text-blue-300',
                'textSecondary' => 'text-blue-700 dark:text-blue-400',
            ],
            'proofread' => [
                'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
                'border' => 'border-yellow-200 dark:border-yellow-800',
                'text' => 'text-yellow-900 dark:text-yellow-300',
                'textSecondary' => 'text-yellow-700 dark:text-yellow-400',
            ],
            'finalapproval' => [
                'bg' => 'bg-green-50 dark:bg-green-900/20',
                'border' => 'border-green-200 dark:border-green-800',
                'text' => 'text-green-900 dark:text-green-300',
                'textSecondary' => 'text-green-700 dark:text-green-400',
            ],
            default => [
                'bg' => 'bg-gray-50 dark:bg-gray-700/50',
                'border' => 'border-gray-200 dark:border-gray-600',
                'text' => 'text-gray-900 dark:text-white',
                'textSecondary' => 'text-gray-600 dark:text-gray-400',
            ],
        };
    }

    public function advanceStage($documentId)
    {
        $document = Document::visibleTo(auth()->user())->findOrFail($documentId);

        if (!$document->is_archived) {
            $nextStage = $this->getNextStage($document->current_stage);
            $document->update(['current_stage' => $nextStage]);
            
            $this->dispatch('document-stage-changed');
            $this->dispatch('show-toast', message: __('workflow.stage_advanced'), type: 'success');
        }
    }

    public function rejectStage($documentId)
    {
        $document = Document::visibleTo(auth()->user())->findOrFail($documentId);
        
        if (!$document->is_archived) {
            $document->update(['current_stage' => 'draft']);
            
            $this->dispatch('document-stage-changed');
            
            $this->dispatch('show-toast', message: __('workflow.stage_rejected'), type: 'warning');
        }
    }

    private function getNextStage(string $currentStage)
    {
        return match($currentStage) {
            'draft' => 'review1',
            'review1' => 'proofread',
            'proofread' => 'finalapproval',
            default => 'finalapproval'
        };
    }

    #[Computed]
    public function getNextStageLabel()
    {
        $nextStage = $this->getNextStage($this->stage);
        return match($nextStage) {
            'review1' => 'مراجعة أولى',
            'proofread' => 'تدقيق',
            'finalapproval' => 'موافقة نهائية',
            default => 'المرحلة التالية'
        };
    }

    public function render()
    {
        return view('livewire.workflow.workflow-stage-card');
    }
}
