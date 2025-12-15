<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'user_id',
        'action_type',
        'comment',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getActionLabelAttribute(): string
    {
        return match($this->action_type) {
            'created' => 'أنشأ الوثيقة',
            'uploaded' => 'رفع الملف',
            'approved' => 'وافق على الوثيقة',
            'rejected' => 'رفض الوثيقة',
            'forwarded' => 'حوّل الوثيقة',
            'commented' => 'أضاف تعليق',
            'archived' => 'أرشف الوثيقة',
            default => 'إجراء غير محدد',
        };
    }
}
