<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ✅ Role Constants (متوافق PHP 7.3+)
    public const ROLE_ADMIN = 'admin';
    public const ROLE_LAWYER = 'lawyer';
    public const ROLE_ASSISTANT = 'assistant';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'department',
        'position',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ✅ Role Helper Methods
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isLawyer(): bool
    {
        return $this->role === self::ROLE_LAWYER;
    }

    public function isAssistant(): bool
    {
        return $this->role === self::ROLE_ASSISTANT;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    // Relationships
    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id');
    }

    public function assignedDocuments()
    {
        return $this->hasMany(Document::class, 'assignee_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    public function documentActivities()
    {
        return $this->hasMany(DocumentActivity::class);
    }

    public function notificationSetting()
    {
        return $this->hasOne(NotificationSetting::class);
    }

    /**
     * Get role display info (label + CSS classes)
     * DRY - single source of truth for role UI
     */
    public function getRoleDisplay(): array
    {
        return match($this->role) {
            self::ROLE_ADMIN => [
                'label' => 'مدير',
                'classes' => 'bg-red-100 text-red-800 border-red-200'
            ],
            self::ROLE_LAWYER => [
                'label' => 'محامي',
                'classes' => 'bg-blue-100 text-blue-800 border-blue-200'
            ],
            self::ROLE_ASSISTANT => [
                'label' => 'مساعد محامي',
                'classes' => 'bg-green-100 text-green-800 border-green-200'
            ],
            default => [
                'label' => 'غير محدد',
                'classes' => 'bg-gray-100 text-gray-800 border-gray-200'
            ]
        };
    }
}
