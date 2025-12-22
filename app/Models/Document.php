<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'case_number',
        'type',
        'description',
        'file_name',
        'file_size',
        'mime_type',
        's3_path',
        'current_stage',
        'is_archived',
        'user_id',
        'assignee_id',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function documentTasks()
    {
        return $this->hasMany(DocumentTask::class);
    }

    public function activities()
    {
        return $this->hasMany(DocumentActivity::class)->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'incoming' => 'وارد',
            'outgoing' => 'صادر',
            default => 'غير محدد',
        };
    }

    public function getStageLabelAttribute(): string
    {
        return match($this->current_stage) {
            'draft' => 'مسودة',
            'review1' => 'مراجعة أولى',
            'proofread' => 'تدقيق',
            'finalapproval' => 'موافقة نهائية',
            default => 'غير محدد',
        };
    }

    // Methods
    public function unarchive()
    {
        $this->update(['is_archived' => false]);
    }

    /**
     * Check if document is overdue based on stage
     * review1: 7 days, proofread: 5 days, finalapproval: 3 days
     */
    public function isOverdue(): bool
    {
        if ($this->is_archived || $this->current_stage === 'draft') {
            return false;
        }

        $daysLimit = match($this->current_stage) {
            'review1' => 7,
            'proofread' => 5,
            'finalapproval' => 3,
            default => 0,
        };

        return $daysLimit > 0 && $this->updated_at < now()->subDays($daysLimit);
    }

    // Scopes
    public function scopeVisibleTo($query, User $user)
    {
        if ($user->isAdmin()) {
            return $query;
        }
        
        if ($user->isLawyer()) {
            return $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('assignee_id', $user->id);
            });
        }
        
        // ✅ المساعد فقط - return واضح
        return $query->where('assignee_id', $user->id);
    }
}
