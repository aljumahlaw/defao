# 🏗️ وثيقة البنية المعمارية والتصميم - Architecture & Design Document

**الإصدار**: 2.0 (Laravel Stack)  
**التاريخ**: $(date)  
**Stack**: Laravel 11 + Livewire 3 + Breeze + Spatie + Custom Workflow + Redis + S3  
**الحالة**: ✅ جاهز للبدء بالبناء

---

## 🎯 الهدف

هذا المستند يحدد:
- ✅ البنية المعمارية للمشروع (Laravel 11 MVC)
- ✅ هيكل المجلدات والملفات
- ✅ الأنماط والـ Patterns المستخدمة
- ✅ قواعد التطوير
- ✅ كيفية تجنب الأخطاء الشائعة

---

## 1️⃣ البنية المعمارية (Architecture)

### 1.1 نمط التصميم (Design Pattern)

```
Laravel 11 MVC + Repository Pattern + Service Layer
```

```
┌─────────────────────────────────────────┐
│         Presentation Layer              │
│  ┌──────────────┐  ┌─────────────────┐ │
│  │   Blade      │  │   Livewire      │ │
│  │  Templates   │  │   Components    │ │
│  └──────────────┘  └─────────────────┘ │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│         Application Layer               │
│  ┌──────────────┐  ┌─────────────────┐ │
│  │ Controllers  │  │    Services     │ │
│  │   (Actions)  │  │   (Business     │ │
│  │              │  │    Logic)       │ │
│  └──────────────┘  └─────────────────┘ │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│          Domain Layer                   │
│  ┌──────────────┐  ┌─────────────────┐ │
│  │  Repositories│  │    Models       │ │
│  │   (Data      │  │   (Eloquent)    │ │
│  │   Access)    │  │                 │ │
│  └──────────────┘  └─────────────────┘ │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│          Infrastructure Layer           │
│  ┌──────────────┐  ┌─────────────────┐ │
│  │  Database    │  │  Storage (S3)   │ │
│  │ (PostgreSQL) │  │                 │ │
│  └──────────────┘  └─────────────────┘ │
└─────────────────────────────────────────┘
```

### 1.2 التدفق (Flow)

```
User Request
    ↓
Route → Middleware (Auth, Permissions - Spatie)
    ↓
Controller (validation, orchestration)
    ↓
Service (business logic - Workflow, etc.)
    ↓
Repository (data access)
    ↓
Model (Eloquent ORM)
    ↓
Database (PostgreSQL)
```

---

## 2️⃣ هيكل المجلدات (Folder Structure)

```
app/
├── Console/
│   └── Commands/              # Artisan commands
│
├── Events/
│   ├── DocumentUploaded.php
│   ├── WorkflowStageCompleted.php
│   └── TaskCompleted.php
│
├── Exceptions/
│   ├── Handler.php
│   ├── NotFoundException.php
│   ├── ForbiddenException.php
│   └── ValidationException.php
│
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── AuthenticatedSessionController.php (Breeze)
│   │   ├── DashboardController.php
│   │   ├── TaskController.php
│   │   ├── DocumentController.php
│   │   ├── ArchiveController.php
│   │   ├── UserController.php
│   │   ├── SettingsController.php
│   │   ├── ProfileController.php
│   │   └── ShareController.php
│   │
│   ├── Livewire/             # Livewire components
│   │   ├── Tasks/
│   │   │   ├── TaskTable.php
│   │   │   └── WorkflowStageCard.php
│   │   ├── Documents/
│   │   │   ├── DocumentUpload.php
│   │   │   ├── DocumentTable.php
│   │   │   └── DocumentViewer.php (PDF.js)
│   │   ├── Dashboard/
│   │   │   └── NotificationCenter.php
│   │   └── Shared/
│   │       └── FavoriteToggle.php (⭐)
│   │
│   ├── Middleware/
│   │   ├── Authenticate.php (Breeze)
│   │   └── EnsureUserHasRole.php (Spatie)
│   │
│   └── Requests/             # Form Requests (validation)
│       ├── StoreTaskRequest.php
│       ├── UpdateTaskRequest.php
│       ├── StoreDocumentRequest.php
│       └── UploadVersionRequest.php
│
├── Jobs/                      # Queue jobs (Redis)
│   ├── ProcessDocumentJob.php (رفع 25MB)
│   ├── SendNotificationJob.php
│   └── ArchiveTaskJob.php
│
├── Mail/                      # Mail classes
│   └── NotificationMail.php
│
├── Models/
│   ├── User.php (HasRoles trait من Spatie)
│   ├── Task.php
│   ├── Document.php
│   ├── WorkflowStage.php (State Machine)
│   ├── DocumentVersion.php
│   ├── Comment.php
│   ├── AuditLog.php
│   ├── Notification.php (Laravel default)
│   ├── Folder.php
│   ├── DocumentShare.php
│   └── Tag.php
│
├── Policies/                  # Authorization policies
│   ├── DocumentPolicy.php
│   ├── TaskPolicy.php
│   └── UserPolicy.php
│
├── Providers/
│   ├── AppServiceProvider.php
│   ├── AuthServiceProvider.php (Policies registration)
│   └── FilamentServiceProvider.php (if using Filament)
│
├── Repositories/              # Repository pattern (Optional - راجع الملاحظة أدناه)
│   ├── Interfaces/
│   │   ├── TaskRepositoryInterface.php
│   │   ├── DocumentRepositoryInterface.php
│   │   └── WorkflowRepositoryInterface.php
│   │
│   └── Eloquent/
│       ├── TaskRepository.php
│       ├── DocumentRepository.php
│       └── WorkflowRepository.php
│
├── Services/                  # Business logic
│   ├── WorkflowService.php (Custom State Machine)
│   ├── StorageService.php (S3)
│   ├── NotificationService.php
│   └── ArchiveService.php
│
└── Enums/
    ├── WorkflowStage.php (draft, review1, proofread, final_approval)
    ├── TaskStatus.php (draft, active, completed, archived)
    └── DocumentType.php (incoming, outgoing)

config/
├── auth.php
├── database.php
├── filesystems.php (S3 configuration)
├── queue.php (Redis configuration)
├── permission.php (Spatie)
└── scout.php (Meilisearch - optional)

database/
├── migrations/
│   ├── 0001_create_users_table.php (Breeze + Spatie)
│   ├── 0002_create_tasks_table.php
│   ├── 0003_create_documents_table.php
│   ├── 0004_create_workflow_stages_table.php
│   ├── 0005_create_document_versions_table.php
│   ├── 0006_create_comments_table.php
│   ├── 0007_create_audit_log_table.php
│   ├── 0008_create_notifications_table.php (Laravel default)
│   ├── 0009_create_folders_table.php
│   ├── 0010_create_document_shares_table.php
│   ├── 0011_create_tags_table.php
│   └── 0012_create_document_tags_table.php
│
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── RoleSeeder.php (Spatie: admin, authorized, user)
│   ├── UserSeeder.php
│   └── SettingsSeeder.php
│
└── factories/
    ├── UserFactory.php
    ├── TaskFactory.php
    └── DocumentFactory.php

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php (Main layout)
│   │   └── guest.blade.php (Auth layout - Breeze)
│   │
│   ├── components/
│   │   ├── header.blade.php
│   │   ├── sidebar.blade.php
│   │   └── notification-bell.blade.php
│   │
│   ├── dashboard/
│   │   └── index.blade.php
│   │
│   ├── tasks/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   │
│   ├── documents/
│   │   ├── upload.blade.php
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   │
│   └── search/
│       └── index.blade.php
│
└── js/
    └── app.js                 # Alpine.js (included with Livewire)

routes/
├── web.php                    # Web routes
├── auth.php                   # Auth routes (Breeze)
└── api.php                    # API routes (if needed)

app/Filament/ (Optional - Admin Panel)
├── Resources/
│   ├── UserResource.php       # Filament admin panel
│   ├── TaskResource.php
│   └── DocumentResource.php
│
└── Pages/
    └── Settings.php

tests/
├── Unit/
│   ├── Models/
│   ├── Services/
│   └── Repositories/
│
└── Feature/
    ├── TasksTest.php
    ├── DocumentsTest.php
    └── WorkflowTest.php
```

---

## 3️⃣ Models + Eloquent Relationships

### 3.1 User Model (Spatie Permission)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationships
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    public function workflowStages()
    {
        return $this->hasMany(WorkflowStage::class, 'assigned_user_id');
    }

    public function completedStages()
    {
        return $this->hasMany(WorkflowStage::class, 'completed_by');
    }

    // Spatie Permission methods available:
    // $user->hasRole('admin')
    // $user->assignRole('admin')
    // $user->can('edit documents')
}
```

### 3.2 Task Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'created_by',
        'is_favorite',
        'favorite_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_favorite' => 'boolean',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function workflowStages()
    {
        return $this->hasManyThrough(WorkflowStage::class, Document::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFavorite($query, $userId)
    {
        return $query->where('is_favorite', true)
            ->where('favorite_by', $userId);
    }
}
```

### 3.3 Document Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory; // ❌ تم إزالة SoftDeletes - نستخدم is_archived فقط

    protected $fillable = [
        'title',
        'description',
        'type', // incoming, outgoing
        // ❌ تم إزالة s3_path, file_size, mime_type - موجودة في document_versions فقط
        'uploaded_by',
        'task_id',
        'is_favorite',
        'favorite_by',
        'is_archived',
        'archived_at',
        'archived_by',
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    // Relationships
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)
            ->orderBy('version_number', 'desc');
    }

    public function latestVersion()
    {
        return $this->hasOne(DocumentVersion::class)
            ->latestOfMany('version_number');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->orderBy('created_at', 'asc');
    }

    public function workflowStages()
    {
        return $this->hasMany(WorkflowStage::class);
    }

    public function share()
    {
        return $this->hasOne(DocumentShare::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'document_tags');
    }

    // Scopes
    public function scopeIncoming($query)
    {
        return $query->where('type', 'incoming');
    }

    public function scopeOutgoing($query)
    {
        return $query->where('type', 'outgoing');
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeFavorite($query, $userId)
    {
        return $query->where('is_favorite', true)
            ->where('favorite_by', $userId);
    }

    // Accessors للتوافق الخلفي (استخدام latestVersion بدلاً من columns مباشرة)
    public function getS3PathAttribute()
    {
        return $this->latestVersion?->s3_key;
    }
    
    public function getFileSizeAttribute()
    {
        return $this->latestVersion?->file_size;
    }
    
    public function getMimeTypeAttribute()
    {
        return $this->latestVersion?->mime_type;
    }
    
    public function getFileSizeHumanAttribute()
    {
        $size = $this->latestVersion?->file_size;
        return $size ? number_format($size / 1024 / 1024, 2) . ' MB' : '0 MB';
    }
}
```

### 3.4 WorkflowStage Model (Custom State Machine)

```php
<?php

namespace App\Models;

use App\Enums\WorkflowStageEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'stage', // draft, review1, proofread, final_approval
        'status', // pending, in_progress, completed
        'assigned_user_id',
        'completed_by',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'stage' => WorkflowStageEnum::class,
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function completedByUser()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Methods
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeCompletedBy(User $user): bool
    {
        return $this->assigned_user_id === $user->id 
            && $this->status === 'in_progress';
    }
}
```

### 3.5 DocumentVersion Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'version_number',
        's3_key',
        's3_url',
        'file_name',
        'file_size',
        'mime_type',
        'uploaded_by',
        'notes',
    ];

    protected $casts = [
        'version_number' => 'integer',
        'file_size' => 'integer',
        'uploaded_at' => 'datetime',
    ];

    // Relationships
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
```

---

## 4️⃣ Livewire Components (مُحدّد)

### ⚠️ تحذير حرج: لا تستخدم Livewire Polling

**المشكلة**:
- استخدام `#[Refresh(interval: 5000)]` مع بيانات كبيرة (1000+ records) يسبب **Memory Leaks**
- Dashboard crash بعد 10-15 دقيقة
- استهلاك RAM متزايد
- "500 Internal Server Error"

**الحل الإجباري**:
1. ✅ **لا تستخدم Polling** - استخدم Events بدلاً من ذلك
2. ✅ **Pagination إجباري** - `$perPage = 20` دائماً
3. ✅ **Eager Loading** - `with()` لجميع Relationships
4. ✅ **استخدم `$this->dispatch()`** لتحديث Components

**مثال صحيح**:
```php
class TaskTable extends Component
{
    use WithPagination;
    
    public $perPage = 20; // ✅ Pagination إجباري
    
    // ❌ لا تستخدم Polling - يسبب Memory Leaks
    // #[Refresh(interval: 5000)]
    
    // ✅ استخدم Events بدلاً من Polling
    protected $listeners = ['taskUpdated' => '$refresh'];
    
    public function render()
    {
        return view('livewire.tasks.task-table', [
            'tasks' => Task::with(['creator', 'documents']) // ✅ Eager Loading
                ->latest()
                ->paginate($this->perPage) // ✅ Pagination
        ]);
    }
}
```

**في Controller بعد تحديث Task**:
```php
$this->dispatch('taskUpdated'); // ✅ Events بدلاً من Polling
```

---

### 4.1 TaskTable Component

```php
<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class TaskTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $priority = '';
    public $isFavorite = false;
    public $perPage = 20; // Pagination إجباري - منع Memory Leaks

    // Badge Helper Methods
    public function getStatusBadgeClasses(string $status): string
    {
        return match($status) {
            'draft' => 'bg-[#FFF8E8] text-[#92400E]',
            'active' => 'bg-[#E8F9F8] text-[#0891B2]',
            'completed' => 'bg-[#E8F8EF] text-[#065F46]',
            'archived' => 'bg-[#FFE8EA] text-[#991B1B]',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    public function getPriorityBadgeClasses(string $priority): string
    {
        return match($priority) {
            'low' => 'bg-gray-100 text-gray-600',
            'medium' => 'bg-[#E8F9F8] text-[#0891B2]',
            'high' => 'bg-[#FFF8E8] text-[#92400E]',
            'urgent' => 'bg-[#FFE8EA] text-[#991B1B]',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'priority' => ['except' => ''],
        'isFavorite' => ['except' => false],
    ];

    // ❌ لا تستخدم Polling - يسبب Memory Leaks
    // #[Refresh(interval: 5000)]
    
    // ✅ استخدم Events بدلاً من Polling
    protected $listeners = ['taskUpdated' => '$refresh'];

    public function render()
    {
        // Eager Loading + Pagination لمنع N+1 و Memory Leaks
        $tasks = Task::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->priority, fn($q) => $q->where('priority', $this->priority))
            ->when($this->isFavorite, fn($q) => $q->favorite(auth()->id()))
            ->with(['creator', 'documents']) // Eager Loading
            ->latest()
            ->paginate($this->perPage); // Pagination إجباري

        return view('livewire.tasks.task-table', compact('tasks'));
    }

    public function toggleFavorite($taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->update([
            'is_favorite' => !$task->is_favorite,
            'favorite_by' => $task->is_favorite ? auth()->id() : null,
        ]);
        
        // Dispatch event للتحديث
        $this->dispatch('taskUpdated');
    }
}
```

### 4.2 DocumentUpload Component

```php
<?php

namespace App\Http\Livewire\Documents;

use App\Jobs\ProcessDocumentJob;
use App\Models\Document;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentUpload extends Component
{
    use WithFileUploads;

    public $file;
    public $title;
    public $description;
    public $type = 'incoming';
    public $taskId;
    public $tags = [];
    public $uploadProgress = 0;

    // Document Type Badge Helper
    public function getDocumentTypeBadgeClasses(string $type): string
    {
        return match($type) {
            'incoming' => 'bg-[#E8F9F8] text-[#0891B2]',
            'outgoing' => 'bg-[#FFF8E8] text-[#92400E]',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    protected $rules = [
        'file' => 'required|file|max:25600|mimes:pdf,doc,docx,xls,xlsx',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|in:incoming,outgoing',
        'taskId' => 'nullable|exists:tasks,id',
        'tags' => 'array',
    ];

    public function upload()
    {
        $this->validate();

        $document = Document::create([
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'task_id' => $this->taskId,
            'uploaded_by' => auth()->id(),
            'file_size' => $this->file->getSize(),
            'mime_type' => $this->file->getMimeType(),
        ]);

        // Dispatch Job to process file upload
        ProcessDocumentJob::dispatch($document, $this->file);

        session()->flash('message', 'الملف قيد الرفع...');

        return redirect()->route('documents.show', $document);
    }

    public function render()
    {
        return view('livewire.documents.document-upload');
    }
}
```

### 4.3 DocumentViewer Component (PDF.js)

```php
<?php

namespace App\Http\Livewire\Documents;

use App\Models\Document;
use Livewire\Component;

class DocumentViewer extends Component
{
    public Document $document;

    public function mount(Document $document)
    {
        $this->document = $document;
    }

    public function getSignedUrlProperty()
    {
        // Generate signed URL for S3 file (expires in 1 hour)
        // استخدام latestVersion بدلاً من s3_path مباشرة
        return Storage::disk('s3')->temporaryUrl(
            $this->document->latestVersion->s3_key,
            now()->addHour()
        );
    }

    public function render()
    {
        return view('livewire.documents.document-viewer');
    }
}
```

**Blade Template (document-viewer.blade.php):**
```blade
<div>
    @if($document->mime_type === 'application/pdf')
        <iframe 
            src="{{ $this->signedUrl }}"
            width="100%" 
            height="800px"
            class="border rounded">
        </iframe>
    @else
        <div class="p-8 text-center">
            <p>معاينة هذا النوع من الملفات غير متاحة</p>
            <a href="{{ $this->signedUrl }}" class="btn btn-primary mt-4">
                📥 تنزيل الملف
            </a>
        </div>
    @endif
</div>
```

### 4.4 NotificationCenter Component

```php
<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class NotificationCenter extends Component
{
    public function getUnreadCountProperty()
    {
        return auth()->user()->unreadNotifications()->count();
    }

    public function getNotificationsProperty()
    {
        return auth()->user()->notifications()->take(5)->get();
    }

    public function markAsRead($notificationId)
    {
        auth()->user()->notifications()
            ->where('id', $notificationId)
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        return view('livewire.dashboard.notification-center');
    }
}
```

### 4.5 WorkflowStageCard Component

```php
<?php

namespace App\Http\Livewire\Tasks;

use App\Models\WorkflowStage;
use App\Services\WorkflowService;
use Livewire\Component;

class WorkflowStageCard extends Component
{
    public WorkflowStage $stage;

    public function complete()
    {
        $this->authorize('complete', $this->stage);

        app(WorkflowService::class)->completeStage($this->stage, auth()->user());

        session()->flash('message', 'تم إنهاء المرحلة بنجاح');
        
        $this->dispatch('stage-completed');
    }

    public function render()
    {
        return view('livewire.tasks.workflow-stage-card');
    }
}
```

### 4.6 FavoriteToggle Component (⭐)

```php
<?php

namespace App\Http\Livewire\Shared;

use Livewire\Component;

class FavoriteToggle extends Component
{
    public $model;
    public $modelType; // 'task' or 'document'

    public function toggle()
    {
        $this->model->update([
            'is_favorite' => !$this->model->is_favorite,
            'favorite_by' => $this->model->is_favorite ? auth()->id() : null,
        ]);
    }

    public function render()
    {
        return view('livewire.shared.favorite-toggle');
    }
}
```

---

## 5️⃣ Services (Business Logic)

### 5.1 WorkflowService (Custom State Machine)

```php
<?php

namespace App\Services;

use App\Enums\WorkflowStageEnum;
use App\Models\WorkflowStage;
use App\Models\AuditLog;
use App\Jobs\ArchiveTaskJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkflowService
{
    /**
     * إكمال مرحلة workflow مع منع Race Conditions
     * 
     * @param WorkflowStage $stage
     * @param \App\Models\User $user
     * @return WorkflowStage
     * @throws \Exception
     */
    public function completeStage(WorkflowStage $stage, $user)
    {
        if (!$stage->canBeCompletedBy($user)) {
            throw new \Exception('You cannot complete this stage');
        }

        return DB::transaction(function () use ($stage, $user) {
            // 1. قفل السجل للتحديث (يمنع concurrent updates - Race Condition)
            $stage = WorkflowStage::where('id', $stage->id)
                ->lockForUpdate()
                ->first();
            
            // 2. التحقق من أن المرحلة لم تُكمل بالفعل (double-check بعد lock)
            if ($stage->status === 'completed') {
                Log::warning('Attempt to complete already completed stage', [
                    'stage_id' => $stage->id,
                    'user_id' => $user->id,
                ]);
                throw new \Exception('Stage already completed');
            }
            
            // 3. التحقق من صلاحيات المستخدم مرة أخرى (بعد lock)
            if ($stage->assigned_user_id !== $user->id) {
                throw new \Exception('User not authorized');
            }

            // 4. تحديث المرحلة الحالية
            $stage->update([
                'status' => 'completed',
                'completed_by' => $user->id,
                'completed_at' => now(),
            ]);

            // 5. الحصول على المرحلة التالية
            $nextStage = $this->getNextStage($stage);

            if ($nextStage) {
                // 6. تفعيل المرحلة التالية
                $nextStage->update(['status' => 'in_progress']);

                // 7. إرسال إشعار للمسؤول عن المرحلة التالية
                $nextStage->assignedUser->notify(
                    new \App\Notifications\WorkflowStageAssignedNotification($nextStage)
                );
            } else {
                // 8. آخر مرحلة - اكتمال المهمة
                $task = $stage->document->task;
                if ($task) {
                    $task->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);

                    // 9. تفعيل الأرشفة التلقائية
                    ArchiveTaskJob::dispatch($task);
                }
            }

            // 10. تسجيل في audit log
            AuditLog::create([
                'entity_type' => 'workflow_stage',
                'entity_id' => $stage->id,
                'action' => 'completed',
                'performed_by' => $user->id,
            ]);

            return $stage->fresh();
        });
    }

    protected function getNextStage(WorkflowStage $stage): ?WorkflowStage
    {
        $stages = [
            WorkflowStageEnum::Draft,
            WorkflowStageEnum::Review1,
            WorkflowStageEnum::Proofread,
            WorkflowStageEnum::FinalApproval,
        ];

        $currentIndex = array_search($stage->stage, $stages);
        
        if ($currentIndex === false || $currentIndex === count($stages) - 1) {
            return null;
        }

        $nextStageEnum = $stages[$currentIndex + 1];

        return WorkflowStage::where('document_id', $stage->document_id)
            ->where('stage', $nextStageEnum)
            ->first();
    }

    public function initializeWorkflow(Document $document)
    {
        $stages = [
            WorkflowStageEnum::Draft,
            WorkflowStageEnum::Review1,
            WorkflowStageEnum::Proofread,
            WorkflowStageEnum::FinalApproval,
        ];

        foreach ($stages as $index => $stageEnum) {
            WorkflowStage::create([
                'document_id' => $document->id,
                'stage' => $stageEnum,
                'status' => $index === 0 ? 'in_progress' : 'pending',
                'assigned_user_id' => $document->uploaded_by,
            ]);
        }
    }
}
```

### 5.2 StorageService (S3)

```php
<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StorageService
{
    /**
     * رفع ملف مع معالجة Orphaned Records
     * 
     * @param UploadedFile $file
     * @param Document $document
     * @param int $versionNumber
     * @return array
     * @throws \Exception
     */
    public function uploadFile(UploadedFile $file, Document $document, int $versionNumber): array
    {
        $s3Path = null;
        
        return DB::transaction(function () use ($file, $document, $versionNumber, &$s3Path) {
            try {
                // 1. رفع إلى S3 أولاً (قبل DB) - مع التشفير
                $path = "documents/{$document->id}/v{$versionNumber}_{$file->hashName()}";
                
                $s3Path = Storage::disk('s3')->putFileAs(
                    "documents/{$document->id}",
                    $file,
                    "v{$versionNumber}_{$file->hashName()}",
                    [
                        'ServerSideEncryption' => 'AES256',
                        'Metadata' => [
                            'uploaded_by' => auth()->id(),
                            'document_id' => $document->id,
                        ],
                    ]
                );
                
                if (!$s3Path) {
                    throw new \Exception('S3 upload failed');
                }
                
                // 2. حفظ البيانات في DB
                $version = DocumentVersion::create([
                    'document_id' => $document->id,
                    'version_number' => $versionNumber,
                    's3_key' => $s3Path,
                    's3_url' => Storage::disk('s3')->url($s3Path),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => auth()->id(),
                ]);
                
                DB::commit();
                
                return [
                    's3_key' => $s3Path,
                    's3_url' => Storage::disk('s3')->url($s3Path),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
                
            } catch (\Exception $e) {
                DB::rollBack();
                
                // 3. Cleanup: حذف الملف من S3 إذا تم رفعه (منع Orphaned Files)
                if ($s3Path) {
                    try {
                        Storage::disk('s3')->delete($s3Path);
                        Log::info('Deleted orphaned S3 file after DB failure', [
                            'path' => $s3Path,
                        ]);
                    } catch (\Exception $cleanupException) {
                        Log::error('Failed to cleanup orphaned S3 file', [
                            'path' => $s3Path,
                            'error' => $cleanupException->getMessage(),
                        ]);
                    }
                }
                
                Log::error('Document upload failed', [
                    'document_id' => $document->id,
                    'version_number' => $versionNumber,
                    'error' => $e->getMessage(),
                ]);
                
                throw $e;
            }
        });
    }

    public function getSignedUrl(string $s3Key, int $expiresInMinutes = 60): string
    {
        return Storage::disk('s3')->temporaryUrl(
            $s3Key,
            now()->addMinutes($expiresInMinutes)
        );
    }

    public function deleteFile(string $s3Key): bool
    {
        return Storage::disk('s3')->delete($s3Key);
    }
}
```

---

## 6️⃣ Jobs (Redis Queue)

### 6.1 ProcessDocumentJob

```php
<?php

namespace App\Jobs;

use App\Models\Document;
use App\Services\StorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Xenolope\Quahog\Client as ClamAVClient;

class ProcessDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // إعدادات إعادة المحاولة
    public $tries = 3; // محاولة 3 مرات
    public $backoff = [60, 300, 900]; // 1 دقيقة، 5 دقائق، 15 دقيقة
    public $timeout = 300; // 5 دقائق لرفع 25MB + فحص الفيروسات

    public function __construct(
        public Document $document,
        public $file
    ) {}

    public function handle(StorageService $storageService)
    {
        try {
            // 1. فحص الفيروسات (ClamAV)
            $this->scanForViruses($this->file);
            
            // 2. رفع إلى S3 (مع التشفير ومعالجة Orphaned Records)
            $fileData = $storageService->uploadFile(
                $this->file,
                $this->document,
                1
            );

            // 3. Initialize workflow
            app(\App\Services\WorkflowService::class)
                ->initializeWorkflow($this->document);

            // 4. Send notifications
            $this->document->uploader->notify(
                new \App\Notifications\DocumentUploadedNotification($this->document)
            );
            
        } catch (\Exception $e) {
            Log::error('ProcessDocumentJob failed', [
                'document_id' => $this->document->id,
                'attempt' => $this->attempts(),
                'error' => $e->getMessage(),
            ]);
            
            // إذا نفذت جميع المحاولات (3 محاولات)
            if ($this->attempts() >= $this->tries) {
                // إشعار المستخدم بالفشل النهائي
                $this->document->uploader->notify(
                    new \App\Notifications\DocumentUploadFailedNotification($this->document, $e->getMessage())
                );
                
                // حذف document record
                $this->document->delete();
                
                Log::critical('ProcessDocumentJob failed permanently after all retries', [
                    'document_id' => $this->document->id,
                    'attempts' => $this->attempts(),
                    'error' => $e->getMessage(),
                ]);
            }
            
            // re-throw لإعادة المحاولة (إذا لم تنفذ المحاولات)
            throw $e;
        }
    }
    
    /**
     * يُستدعى بعد فشل جميع المحاولات
     * 
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::critical('ProcessDocumentJob failed permanently', [
            'document_id' => $this->document->id,
            'attempts' => $this->attempts(),
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
        
        // إشعار المستخدم بالفشل النهائي
        if ($this->document->uploader) {
            $this->document->uploader->notify(
                new \App\Notifications\DocumentUploadFailedNotification($this->document, $exception->getMessage())
            );
        }
        
        // حذف document record
        $this->document->delete();
    }
    
    /**
     * فحص الملف للفيروسات باستخدام ClamAV
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @throws \Exception
     */
    protected function scanForViruses($file)
    {
        try {
            $tempPath = $file->getRealPath();
            
            // الاتصال بـ ClamAV
            $client = new ClamAVClient('unix:///var/run/clamav/clamd.sock', \Socket::AF_UNIX);
            $result = $client->scanFile($tempPath);
            
            if ($result['status'] === 'FOUND') {
                // ملف مصاب - احذفه وأشعر المستخدم
                Log::error('Malware detected in uploaded file', [
                    'document_id' => $this->document->id,
                    'file' => $file->getClientOriginalName(),
                    'threat' => $result['reason'],
                    'user_id' => $this->document->uploaded_by,
                ]);
                
                // إشعار Admin
                $adminUsers = \App\Models\User::role('admin')->get();
                foreach ($adminUsers as $admin) {
                    $admin->notify(
                        new \App\Notifications\MalwareDetectedNotification($this->document, $result['reason'])
                    );
                }
                
                throw new \Exception('Malware detected: ' . $result['reason']);
            }
            
        } catch (\Exception $e) {
            // إذا فشل الاتصال بـ ClamAV، سجل التحذير لكن لا تمنع الرفع (في التطوير)
            if (config('app.env') === 'production') {
                Log::error('ClamAV scan failed', [
                    'error' => $e->getMessage(),
                    'document_id' => $this->document->id,
                ]);
                throw $e;
            } else {
                Log::warning('ClamAV not available, skipping virus scan', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
```

### 6.2 ArchiveTaskJob

```php
<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ArchiveTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Task $task) {}

    public function handle()
    {
        DB::transaction(function () {
            foreach ($this->task->documents as $document) {
                $document->update([
                    'is_archived' => true,
                    'archived_at' => now(),
                    'archived_by' => $this->task->creator->id,
                ]);

                // Move to archive folder or create folder
                // ...
            }

            $this->task->update(['status' => 'archived']);
        });
    }
}
```

---

## 7️⃣ Policies (Authorization)

### 7.1 DocumentPolicy

```php
<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * عرض قائمة المستندات
     */
    public function viewAny(User $user)
    {
        // يرى المستندات فقط إذا كان له صلاحية
        return $user->hasAnyPermission(['view documents', 'manage documents']);
    }

    /**
     * عرض مستند محدد (صارم - حسب الصلاحيات)
     */
    public function view(User $user, Document $document)
    {
        // Admin يرى كل شيء
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // المستخدم يرى المستندات التي رفعها
        if ($document->uploaded_by === $user->id) {
            return true;
        }
        
        // المستخدم يرى المستندات التابعة لمهامه
        if ($document->task) {
            return $document->task->users()
                ->where('user_id', $user->id)
                ->exists();
        }
        
        // Authorized users يمكنهم رؤية المستندات المرتبطة بمهامهم
        if ($user->hasRole('authorized') && $document->task) {
            return $document->task->users()
                ->where('user_id', $user->id)
                ->exists();
        }
        
        return false;
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create documents');
    }

    public function update(User $user, Document $document)
    {
        // Admin يمكنه تعديل أي مستند
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // فقط مَن رفع المستند يمكنه التعديل
        return $document->uploaded_by === $user->id;
    }

    public function delete(User $user, Document $document)
    {
        // فقط Admin يمكنه الحذف
        return $user->hasRole('admin');
    }

    public function archive(User $user, Document $document)
    {
        return $user->hasAnyRole(['admin', 'authorized']);
    }

    public function uploadVersion(User $user, Document $document)
    {
        // فقط في مراحل معينة
        $currentStage = $document->workflowStages()
            ->where('status', 'in_progress')
            ->first();

        if (!$currentStage) {
            return false;
        }

        $allowedStages = ['draft', 'review1', 'proofread'];
        
        return in_array($currentStage->stage->value, $allowedStages)
            && $currentStage->assigned_user_id === $user->id;
    }
}
```

---

## 8️⃣ ملاحظة حول Repository Pattern

### الوضع الحالي:
المشروع يحتوي على مجلد `Repositories/` كطبقة إضافية.

### رأي معماري (من مطور Laravel ذو خبرة):
في Laravel، **Repository Pattern غالباً غير ضروري** في معظم المشاريع:

**الأسباب**:
1. **Eloquent هو بالفعل Repository Pattern** (Active Record)
2. يضيف **complexity** بدون فائدة واضحة في معظم الحالات
3. يزيد **وقت التطوير** (interface + implementation + binding)
4. يصعب **Refactoring** (3 ملفات لتعديل استعلام واحد)

**البديل الموصى به** (Service Classes):
```php
// ✅ بسيط ومباشر
class TaskService
{
    public function createTask(array $data): Task
    {
        return DB::transaction(function () use ($data) {
            $task = Task::create($data);
            $task->users()->attach($data['assigned_users']);
            
            // Business logic هنا
            event(new TaskCreated($task));
            
            return $task;
        });
    }
    
    public function getActiveTasks(User $user): Collection
    {
        return Task::query()
            ->where('status', 'active')
            ->where('assigned_to', $user->id)
            ->with(['documents', 'workflowStages'])
            ->get();
    }
}
```

**الفوائد**:
- ✅ كود أقل (ملف واحد بدلاً من 3)
- ✅ Refactoring أسرع
- ✅ Type hints واضحة (Task بدلاً من Model)
- ✅ Business logic واضحة

### متى **يجب** استخدام Repository:
1. **إذا كنت تخطط للانتقال من Eloquent** إلى Query Builder أو DB آخر (نادر جداً)
2. **إذا كانت logic الاستعلامات معقدة جداً** وتحتاج Testability عالية جداً
3. **إذا كان الفريق يصر على DDD** (Domain-Driven Design)

### القرار النهائي:
- إذا أردت الاستمرار مع Repository Pattern: **لا مشكلة** - الوثائق تدعمه
- إذا أردت تبسيط المشروع: **احذف Repositories** واستخدم Service Classes فقط
- **Repository Pattern هنا: optional، ليس إجباري**

### التوصية للمشروع الحالي:
نظراً لأن:
- المشروع بسيط إلى متوسط (17 واجهة)
- Eloquent كافٍ تماماً
- Service Classes موجودة بالفعل

**التوصية**: احذف مجلد `Repositories/` واستخدم Service Classes فقط - سيوفر وقت كبير في التطوير.

---

## 9️⃣ قواعد التطوير (Development Rules)

### 8.1 قاعدة البيانات

#### ✅ DO (افعل):
```php
// استخدم Transactions للعمليات المتعددة
DB::transaction(function () {
    $task = Task::create([...]);
    $stages = $this->createWorkflowStages($task);
    $this->sendNotification($task);
});

// استخدم Eloquent Relationships
$task->workflowStages;
$document->versions()->latest()->first();

// استخدم Query Scopes
Task::inProgress()->get();
Document::archived()->get();

// استخدم Eager Loading لمنع N+1
Task::with('workflowStages', 'documents')->get();
```

#### ❌ DON'T (لا تفعل):
```php
// ❌ لا تستخدم queries داخل loops
foreach ($tasks as $task) {
    $stages = WorkflowStage::where('task_id', $task->id)->get(); // N+1!
}

// ❌ لا تحدث records بدون transactions للعمليات المركبة
$stage->update(['status' => 'completed']);
$nextStage->update(['status' => 'inProgress']); // إذا فشلت، inconsistent!
```

### 8.2 الصلاحيات (Authorization)

#### ✅ DO:
```php
// استخدم Policies
$this->authorize('update', $task);

// استخدم Spatie Permission
$user->hasRole('admin');
$user->can('edit documents');

// في Controllers
public function update(Task $task, UpdateTaskRequest $request)
{
    $this->authorize('update', $task);
    // ...
}
```

### 8.3 Validation

#### ✅ DO:
```php
// استخدم Form Requests
class StoreDocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:25600', 'mimes:pdf,doc,docx,xls,xlsx'],
            'type' => ['required', 'in:incoming,outgoing'],
        ];
    }
}
```

---

## 9️⃣ Storage Structure (S3)

```
documents/
├── {user_id}/
│   ├── {document_id}/
│   │   ├── v1_{timestamp}_{filename}
│   │   └── v2_{timestamp}_{filename}
└── archived/
    └── {folder_id}/
        └── {document_id}/...
```

---

## ✅ قائمة التحقق (Checklist)

### قبل البدء، تأكد من:

- [ ] ✅ فهم البنية المعمارية
- [ ] ✅ فهم هيكل المجلدات
- [ ] ✅ فهم Relationships بين Models
- [ ] ✅ فهم Livewire Components
- [ ] ✅ فهم Services و Jobs
- [ ] ✅ فهم Policies

---

**هذا المستند يجب أن يكون المرجع لكل التطوير!**
