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
}
