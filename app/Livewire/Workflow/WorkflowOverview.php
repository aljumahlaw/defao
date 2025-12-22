<?php

namespace App\Livewire\Workflow;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkflowOverview extends Component
{
    protected $listeners = ['document-stage-changed' => '$refresh'];

    #[Computed]
    public function totalDocuments()
    {
        $user = Auth::user();
        if (!$user) return 0;
        
        return Document::visibleTo($user)
            ->where('is_archived', false)
            ->count();
    }

    #[Computed]
    public function overdueDocuments()
    {
        $user = Auth::user();
        if (!$user) return 0;

        return Document::visibleTo($user)
            ->where('is_archived', false)
            ->where(function ($q) {
                $q->where('current_stage', 'review1')
                  ->where('updated_at', '<', now()->subDays(7));
                $q->orWhere('current_stage', 'proofread')
                  ->where('updated_at', '<', now()->subDays(5));
                $q->orWhere('current_stage', 'finalapproval')
                  ->where('updated_at', '<', now()->subDays(3));
            })
            ->count();
    }

    #[Computed]
    public function stageCounts()
    {
        $user = auth()->user();

        if (!$user) {
            return [
                'draft' => 0,
                'review1' => 0,
                'proofread' => 0,
                'finalapproval' => 0,
            ];
        }

        $counts = Document::visibleTo($user)
            ->where('is_archived', false)
            ->groupBy('current_stage')
            ->selectRaw('current_stage, count(*) as cnt')
            ->pluck('cnt', 'current_stage')
            ->toArray();

        return array_merge([
            'draft' => 0,
            'review1' => 0,
            'proofread' => 0,
            'finalapproval' => 0,
        ], $counts);
    }

    public function exportWorkflowReport()
    {
        $data = [
            'total' => $this->totalDocuments,
            'overdue' => $this->overdueDocuments,
            'stages' => $this->stageCounts,
            'generated_at' => now()->format('Y-m-d H:i'),
        ];

        $pdf = Pdf::loadView('reports.workflow-report', $data);

        return response()->streamDownload(
            function() use ($pdf) {
                echo $pdf->output();
            },
            'workflow-report-' . now()->format('Y-m-d') . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    public function render()
    {
        return view('livewire.workflow.workflow-overview');
    }
}
