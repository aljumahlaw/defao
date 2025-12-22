<?php

namespace App\Livewire\Dashboard;

use App\Models\Document;
use App\Models\DocumentActivity;
use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

class DashboardOverview extends Component
{
    // ✅ P1-4: Configurable activity limit
    public int $activityLimit = 5;
    
    protected $listeners = ['task-saved' => '$refresh'];

    #[Computed]
    public function stats()
    {
        $user = auth()->user();
        
        // ✅ P1-6: GROUP BY بدلاً من count() منفصلة (6 queries → 3)
        $taskCounts = Task::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $documentCounts = Document::visibleTo($user)
            ->select('current_stage', DB::raw('count(*) as count'))
            ->where('is_archived', false)
            ->groupBy('current_stage')
            ->pluck('count', 'current_stage');

        return [
            'active_tasks' => ($taskCounts['pending'] ?? 0) + ($taskCounts['in_progress'] ?? 0),
            'review_documents' => $documentCounts['review1'] ?? 0,
            'completed_tasks' => $taskCounts['completed'] ?? 0,
            'archived_documents' => Document::visibleTo($user)->where('is_archived', true)->count(),
        ];
    }

    #[Computed]
    public function recentActivity()
    {
        return DocumentActivity::with(['user', 'document'])
            ->latest()
            ->take($this->activityLimit) // ✅ P1-4: Configurable
            ->get()
            ->map(function ($activity) {
                $documentTitle = $activity->document ? $activity->document->title : 'وثيقة محذوفة';
                $userName = $activity->user ? $activity->user->name : 'مستخدم غير معروف';
                
                $text = match($activity->action_type) {
                    'created' => "تم إنشاء وثيقة: {$documentTitle}",
                    'uploaded' => "تم رفع وثيقة: {$documentTitle}",
                    'approved' => "تم الموافقة على: {$documentTitle}",
                    'rejected' => "تم رفض: {$documentTitle}",
                    'forwarded' => "تم تحويل: {$documentTitle}",
                    'commented' => "تم إضافة تعليق على: {$documentTitle}",
                    'archived' => "تم أرشفة: {$documentTitle}",
                    default => "إجراء على: {$documentTitle}",
                };
                
                return [
                    'text' => $text,
                    'time' => $activity->created_at->diffForHumans(['locale' => 'ar']),
                ];
            });
    }

    public function openTaskModal()
    {
        $this->dispatch('open-task-form-modal');
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-overview');
    }
}

