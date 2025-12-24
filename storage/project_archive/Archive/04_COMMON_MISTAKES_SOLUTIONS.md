# ⚠️ الأخطاء الشائعة والحلول - Common Mistakes & Solutions

**الإصدار**: 2.0 (Laravel Stack)  
**التاريخ**: $(date)  
**Stack**: Laravel 11 + Livewire 3 + Spatie + Custom Workflow

---

## 🎯 الهدف

هذا المستند يوثق:
- ✅ الأخطاء الشائعة التي حدثت في المشروع السابق
- ✅ كيفية تجنبها في Laravel
- ✅ الحلول الصحيحة

---

## 🚨 الأخطاء الحرجة (Critical Mistakes)

### 1. ❌ عدم استخدام Transactions

#### الخطأ:
```php
// ❌ خطأ - قد يحدث inconsistent state
public function completeStage(WorkflowStage $stage)
{
    $stage->update(['status' => 'completed']);
    $nextStage->update(['status' => 'in_progress']); // إذا فشلت، المشكلة!
    $task->update(['status' => 'review']);
}
```

#### المشكلة:
- إذا فشلت أي خطوة، البيانات تصبح inconsistent
- المرحلة الأولى مكتملة لكن الثانية لم تتحدث
- المهمة في حالة غير صحيحة

#### ✅ الحل (Laravel):
```php
// ✅ صحيح - جميع العمليات في transaction واحدة
use Illuminate\Support\Facades\DB;

public function completeStage(WorkflowStage $stage)
{
    return DB::transaction(function () use ($stage) {
        $stage->update(['status' => 'completed']);
        
        $nextStage = $this->getNextStage($stage);
        if ($nextStage) {
            $nextStage->update(['status' => 'in_progress']);
        } else {
            $stage->document->task->update(['status' => 'completed']);
        }
        
        AuditLog::create([...]);
        
        return $stage->fresh();
    });
}
```

#### 📋 قاعدة:
> **استخدم `DB::transaction()` لكل عملية تحتوي على خطوتين أو أكثر**

---

### 2. ❌ عدم التحقق من الصلاحيات (Spatie)

#### الخطأ:
```php
// ❌ خطأ - أي شخص يمكنه إنهاء أي مرحلة
public function completeStage($stageId)
{
    $stage = WorkflowStage::find($stageId);
    $stage->update(['status' => 'completed']);
}
```

#### المشكلة:
- أي مستخدم يمكنه إنهاء أي مرحلة
- لا يوجد تحقق من أن المستخدم مسؤول عن المرحلة
- ثغرة أمنية خطيرة

#### ✅ الحل (Laravel + Spatie):
```php
// ✅ صحيح - التحقق من الصلاحيات
public function completeStage(WorkflowStage $stage)
{
    // استخدام Policy
    $this->authorize('complete', $stage);
    
    // أو التحقق يدوياً
    if ($stage->assigned_user_id !== auth()->id()) {
        throw new ForbiddenException('You are not assigned to this stage');
    }
    
    if ($stage->status !== 'in_progress') {
        throw new ValidationException('Stage must be in progress');
    }
    
    return DB::transaction(function () use ($stage) {
        // ...
    });
}

// في Policy
public function complete(User $user, WorkflowStage $stage)
{
    return $stage->assigned_user_id === $user->id 
        && $stage->status === 'in_progress';
}
```

#### 📋 قاعدة:
> **تحقق من الصلاحيات في كل endpoint حساس - استخدم Policies أو Spatie**

---

### 3. ❌ N+1 Queries Problem

#### الخطأ:
```php
// ❌ خطأ - N+1 queries
$tasks = Task::all();
foreach ($tasks as $task) {
    echo $task->workflowStages->count(); // query لكل task!
    echo $task->documents->count(); // query لكل task!
}
```

#### المشكلة:
- 1 query للـ tasks
- N queries للـ workflowStages
- N queries للـ documents
- **المجموع: 1 + N + N queries!**

#### ✅ الحل (Eloquent Eager Loading):
```php
// ✅ صحيح - Eager Loading
$tasks = Task::with(['workflowStages', 'documents'])->get();
foreach ($tasks as $task) {
    echo $task->workflowStages->count(); // already loaded
    echo $task->documents->count(); // already loaded
}
// فقط 3 queries: 1 للـ tasks، 1 للـ stages، 1 للـ documents

// أو في Livewire Component
public function render()
{
    $tasks = Task::with(['workflowStages', 'documents'])
        ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
        ->paginate(15);
    
    return view('livewire.tasks.task-table', compact('tasks'));
}
```

#### ⚠️ **مشكلة خاصة بـ Livewire**:
في Livewire، N+1 يحدث في **كل render**، ليس مرة واحدة!

```php
// ❌ خطأ - N+1 في كل render (كل 5 ثواني مع polling)
class TaskTable extends Component
{
    public function render()
    {
        $tasks = Task::all(); // Query 1
        return view('livewire.task-table', compact('tasks'));
    }
}

// في Blade
@foreach($tasks as $task)
    {{ $task->assignedUser->name }} // Query لكل task! × كل render!
@endforeach
```

**النتيجة**: 100 tasks × 10 renders/دقيقة = 1000 query/دقيقة!

#### ✅ الحل لـ Livewire:
```php
// ✅ صحيح
public function render()
{
    $tasks = Task::with('assignedUser', 'creator', 'documents') // Eager Loading
        ->paginate(20);
    
    return view('livewire.task-table', compact('tasks'));
}
```

#### التحقق من N+1:
```php
// في .env.local (للـ development)
DB_LOG_QUERIES=true

// أو في الكود
DB::enableQueryLog();
// ... render component
$queries = DB::getQueryLog();
dd(count($queries)); // يجب أن يكون ثابت (مثلاً 5 queries)
```

#### الهدف:
- عدد الـ queries يجب أن يكون **ثابت** بغض النظر عن عدد السجلات
- مثلاً: 5 queries لـ 10 tasks = 5 queries لـ 1000 tasks

#### 📋 قاعدة:
> **استخدم `with()` لـ Eager Loading عندما تحتاج relationships - خاصة في Livewire Components**

---

### 4. ❌ عدم التحقق من File Upload

#### الخطأ:
```php
// ❌ خطأ - لا validation
$file = $request->file('document');
Storage::disk('s3')->put('documents', $file);
```

#### المشاكل:
- يمكن رفع ملفات ضارة
- يمكن رفع ملفات كبيرة جداً
- يمكن رفع أنواع ملفات غير مسموحة
- ثغرة أمنية خطيرة

#### ✅ الحل (Laravel Form Request):
```php
// Form Request
class StoreDocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'document' => [
                'required',
                'file',
                'max:25600', // 25MB
                'mimes:pdf,doc,docx,xls,xlsx',
            ],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:incoming,outgoing'],
        ];
    }
}

// Controller
public function store(StoreDocumentRequest $request)
{
    $file = $request->file('document');
    
    // تحقق إضافي
    if ($file->getSize() > 25 * 1024 * 1024) {
        throw new ValidationException('File size exceeds 25MB');
    }
    
    // تنظيف اسم الملف (منع path traversal)
    $fileName = basename($file->getClientOriginalName());
    
    // رفع إلى S3 (في Job)
    ProcessDocumentJob::dispatch($document, $file);
}
```

#### 📋 قاعدة:
> **تحقق من كل شيء في File Upload: الحجم، النوع، الاسم - استخدم Form Requests**

---

### 5. ❌ عدم استخدام Indexes

#### الخطأ:
```php
// Migration بدون indexes
Schema::create('workflow_stages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('document_id');
    $table->string('stage');
    // ❌ لا indexes!
});
```

#### المشكلة:
- استعلامات بطيئة جداً
- Full table scans
- أداء سيء مع البيانات الكبيرة

#### ✅ الحل:
```php
// ✅ صحيح - Indexes على الأعمدة المستخدمة في WHERE/JOIN
Schema::create('workflow_stages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('document_id');
    $table->enum('stage', ['draft', 'review1', 'proofread', 'final_approval']);
    $table->enum('status', ['pending', 'in_progress', 'completed']);
    
    // Indexes للاستعلامات الشائعة
    $table->unique(['document_id', 'stage']);
    $table->index(['assigned_user_id', 'status']);
    $table->index(['status', 'stage']);
});
```

#### 📋 قاعدة:
> **أضف Indexes على: Foreign Keys، الأعمدة في WHERE/JOIN، Composite indexes للـ UNIQUE constraints**

---

### 6. ❌ Version Number يدوياً

#### الخطأ:
```php
// ❌ خطأ - المستخدم يمكنه إدخال أي رقم
DocumentVersion::create([
    'document_id' => $document->id,
    'version_number' => $request->input('version_number'), // خطأ!
    // ...
]);
```

#### المشكلة:
- يمكن إنشاء version 5 بدون 1, 2, 3, 4
- يمكن إنشاء versions مكررة
- بيانات غير صحيحة

#### ✅ الحل:
```php
// ✅ صحيح - حساب تلقائي
public function createVersion(Document $document, $file, $notes = null)
{
    // احصل على أحدث إصدار
    $latestVersion = DocumentVersion::where('document_id', $document->id)
        ->orderBy('version_number', 'desc')
        ->first();
    
    // احسب الرقم التالي
    $nextVersion = $latestVersion 
        ? $latestVersion->version_number + 1 
        : 1;
    
    // رفع الملف
    $fileData = app(StorageService::class)->uploadFile($file, $document->id, $nextVersion);
    
    // إنشاء الإصدار
    return DocumentVersion::create([
        'document_id' => $document->id,
        'version_number' => $nextVersion, // ✅ محسوب تلقائياً
        's3_key' => $fileData['s3_key'],
        // ...
    ]);
}
```

#### 📋 قاعدة:
> **احسب Version Numbers تلقائياً - لا تثق في المستخدم**

---

### 7. ❌ عدم التحقق من حالة المرحلة

#### الخطأ:
```php
// ❌ خطأ - يمكن إنهاء مرحلة pending
public function completeStage(WorkflowStage $stage)
{
    $stage->update(['status' => 'completed']); // ❌ حتى لو كانت pending!
}
```

#### المشكلة:
- يمكن إنهاء مراحل pending
- workflow غير صحيح
- بيانات inconsistent

#### ✅ الحل:
```php
// ✅ صحيح - التحقق من الحالة
public function completeStage(WorkflowStage $stage)
{
    if ($stage->status !== 'in_progress') {
        throw new ValidationException('Stage must be in progress to complete');
    }
    
    if ($stage->assigned_user_id !== auth()->id()) {
        throw new ForbiddenException('You are not assigned to this stage');
    }
    
    return DB::transaction(function () use ($stage) {
        $stage->update([
            'status' => 'completed',
            'completed_by' => auth()->id(),
            'completed_at' => now(),
        ]);
        
        // الانتقال للمرحلة التالية
        // ...
    });
}

// أو في Model
public function canBeCompletedBy(User $user): bool
{
    return $this->assigned_user_id === $user->id 
        && $this->status === 'in_progress';
}
```

#### 📋 قاعدة:
> **تحقق من State قبل State Transition**

---

### 8. ❌ عدم استخدام Jobs للمهام الثقيلة

#### الخطأ:
```php
// ❌ خطأ - رفع ملف كبير في Request
public function uploadDocument(Request $request)
{
    $file = $request->file('document');
    $path = Storage::disk('s3')->put('documents', $file); // قد يستغرق 30 ثانية!
    
    DocumentVersion::create([...]);
    
    return redirect()->back(); // المستخدم ينتظر!
}
```

#### المشكلة:
- Request timeout (30+ seconds)
- تجربة مستخدم سيئة
- Server overload

#### ✅ الحل (Laravel Queue):
```php
// ✅ صحيح - استخدام Queue Job
public function uploadDocument(StoreDocumentRequest $request)
{
    $document = Document::create([
        'title' => $request->title,
        'uploaded_by' => auth()->id(),
        // ...
    ]);
    
    // رفع الملف في Job
    ProcessDocumentJob::dispatch($document, $request->file('document'));
    
    return redirect()->back()->with('success', 'File is being uploaded...');
}

// Job
class ProcessDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $timeout = 300; // 5 minutes for 25MB files
    
    public function __construct(
        public Document $document,
        public $file
    ) {}
    
    public function handle(StorageService $storageService)
    {
        $fileData = $storageService->uploadFile(
            $this->file,
            $this->document->id,
            1
        );
        
        DocumentVersion::create([
            'document_id' => $this->document->id,
            'version_number' => 1,
            // ...
        ]);
    }
}
```

#### 📋 قاعدة:
> **استخدم Jobs للمهام التي تستغرق > 5 ثوانٍ - استخدم Redis Queue**

---

### 9. ❌ عدم التحقق من Archived Documents

#### الخطأ:
```php
// ❌ خطأ - يمكن رفع إصدار جديد لمستند مؤرشف
public function uploadVersion(Document $document, Request $request)
{
    $file = $request->file('file');
    // ❌ لا تحقق من is_archived!
    DocumentVersion::create([...]);
}
```

#### المشكلة:
- يمكن تعديل مستندات مؤرشفة
- انتهاك business rule
- بيانات غير صحيحة

#### ✅ الحل:
```php
// ✅ صحيح - التحقق من الأرشيف
public function uploadVersion(Document $document, StoreDocumentRequest $request)
{
    if ($document->is_archived) {
        throw new ForbiddenException('Cannot upload version to archived document');
    }
    
    // التحقق من المرحلة
    $currentStage = $document->workflowStages()
        ->where('status', 'in_progress')
        ->first();
    
    $allowedStages = ['draft', 'review1', 'proofread'];
    if (!$currentStage || !in_array($currentStage->stage->value, $allowedStages)) {
        throw new ForbiddenException('Cannot upload version in current stage');
    }
    
    // رفع الإصدار
    // ...
}

// أو في Policy
public function uploadVersion(User $user, Document $document)
{
    if ($document->is_archived) {
        return false;
    }
    
    $currentStage = $document->workflowStages()
        ->where('status', 'in_progress')
        ->first();
    
    $allowedStages = ['draft', 'review1', 'proofread'];
    
    return $currentStage 
        && in_array($currentStage->stage->value, $allowedStages)
        && $currentStage->assigned_user_id === $user->id;
}
```

#### 📋 قاعدة:
> **تحقق من Business Rules قبل أي عملية - استخدم Policies**

---

### 10. ❌ عدم Logging الأخطاء

#### الخطأ:
```php
// ❌ خطأ - لا logging
public function completeStage(WorkflowStage $stage)
{
    try {
        // ...
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed');
        // ❌ لا logging!
    }
}
```

#### المشكلة:
- لا يمكن تتبع الأخطاء
- صعوبة في Debug
- لا يوجد audit trail

#### ✅ الحل:
```php
// ✅ صحيح - Logging كامل
use Illuminate\Support\Facades\Log;

public function completeStage(WorkflowStage $stage)
{
    try {
        return DB::transaction(function () use ($stage) {
            // ...
        });
        
        Log::info('Workflow stage completed', [
            'stage_id' => $stage->id,
            'user_id' => auth()->id(),
        ]);
        
        return redirect()->back()->with('success', 'Stage completed');
        
    } catch (\Exception $e) {
        Log::error('Failed to complete workflow stage', [
            'stage_id' => $stage->id,
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        
        return redirect()->back()->with('error', 'Operation failed');
    }
}
```

#### 📋 قاعدة:
> **Log كل شيء: Success, Errors, Important Actions - استخدم Laravel Log facade**

---

### 11. ❌ Orphaned Records بين Database و S3

#### الخطأ:
```php
// ❌ خطأ - فشل جزئي يترك ملفات يتيمة
public function uploadFile($file, $document)
{
    $path = Storage::disk('s3')->put('documents', $file); // نجح
    DocumentVersion::create([...]); // فشل → ملف يتيم في S3!
}
```

#### المشكلة:
- رفع S3 ينجح لكن DB transaction تفشل → ملف يتيم في S3
- DB record يُنشأ لكن رفع S3 يفشل → record بدون ملف
- بيانات غير متسقة + تكاليف S3

#### ✅ الحل:
```php
// ✅ صحيح - معالجة Orphaned Records
public function uploadFile(UploadedFile $file, Document $document)
{
    $s3Path = null;
    
    return DB::transaction(function () use ($file, $document, &$s3Path) {
        try {
            // 1. رفع إلى S3 أولاً
            $s3Path = Storage::disk('s3')->putFile('documents', $file);
            
            // 2. حفظ في DB
            DocumentVersion::create([
                's3_key' => $s3Path,
                // ...
            ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // 3. Cleanup: احذف الملف من S3 إذا فشل DB
            if ($s3Path) {
                Storage::disk('s3')->delete($s3Path);
            }
            
            throw $e;
        }
    });
}

// Scheduled Cleanup Job (يومي)
class CleanupOrphanedFiles extends Command
{
    public function handle()
    {
        $s3Files = Storage::disk('s3')->allFiles('documents');
        
        foreach ($s3Files as $file) {
            $exists = DocumentVersion::where('s3_key', $file)->exists();
            
            if (!$exists) {
                $lastModified = Storage::disk('s3')->lastModified($file);
                // احذف بعد 24 ساعة
                if (now()->timestamp - $lastModified > 86400) {
                    Storage::disk('s3')->delete($file);
                    Log::info('Deleted orphaned file', ['path' => $file]);
                }
            }
        }
    }
}
```

#### 📋 قاعدة:
> **ارفع S3 أولاً → احفظ DB → إذا فشل DB، احذف من S3**

---

### 12. ❌ Race Conditions في Workflow

#### الخطأ:
```php
// ❌ خطأ - مستخدمان ينقران "إكمال" معاً
public function completeStage(WorkflowStage $stage)
{
    $stage->update(['status' => 'completed']); // ✅
    $nextStage->update(['status' => 'in_progress']); // ✅ (مكرر!)
}
```

#### المشكلة:
- مستخدمان ينقران "إكمال المرحلة" في نفس الوقت
- المرحلة تُكمل مرتين
- المرحلة التالية تُنشأ مرتين
- بيانات inconsistent

#### ✅ الحل:
```php
// ✅ صحيح - استخدام lockForUpdate()
public function completeStage(WorkflowStage $stage)
{
    return DB::transaction(function () use ($stage) {
        // 1. قفل السجل
        $stage = WorkflowStage::where('id', $stage->id)
            ->lockForUpdate() // ⚠️ مهم!
            ->first();
        
        // 2. تحقق مرة أخرى (double-check)
        if ($stage->status === 'completed') {
            throw new \Exception('Already completed');
        }
        
        // 3. أكمل المرحلة
        $stage->update(['status' => 'completed']);
    });
}
```

#### 📋 قاعدة:
> **استخدم `lockForUpdate()` دائماً في العمليات الحرجة**

---

### 13. ❌ Livewire Memory Leaks مع Polling

#### الخطأ:
```php
// ❌ خطأ - Memory Leak
class TaskTable extends Component
{
    #[Refresh(interval: 5000)] // Polling كل 5 ثواني
    public function render()
    {
        $tasks = Task::all(); // 1000+ tasks!
        return view('livewire.task-table', compact('tasks'));
    }
}
```

#### المشكلة:
- Polling كل 5 ثواني على 1000+ tasks
- استهلاك RAM متزايد
- Dashboard crash بعد 10-15 دقيقة

#### ✅ الحل:
```php
// ✅ صحيح - Pagination + Events
class TaskTable extends Component
{
    use WithPagination;
    
    public $perPage = 20; // Pagination إجباري
    
    // ❌ لا تستخدم Polling
    // #[Refresh(interval: 5000)]
    
    // ✅ استخدم Events
    protected $listeners = ['taskUpdated' => '$refresh'];
    
    public function render()
    {
        return view('livewire.task-table', [
            'tasks' => Task::with(['creator', 'documents']) // Eager Loading
                ->latest()
                ->paginate($this->perPage) // Pagination
        ]);
    }
}

// في Controller بعد تحديث Task
$this->dispatch('taskUpdated');
```

#### 📋 قاعدة:
> **لا تستخدم Polling على بيانات كبيرة - استخدم Pagination + Events**

---

### 14. ❌ عدم استخدام Policies في Livewire

#### الخطأ:
```php
// ❌ خطأ - لا authorization في Livewire
class WorkflowStageCard extends Component
{
    public function complete()
    {
        // ❌ لا تحقق من الصلاحيات!
        $this->stage->update(['status' => 'completed']);
    }
}
```

#### ✅ الحل:
```php
// ✅ صحيح - استخدام authorize في Livewire
class WorkflowStageCard extends Component
{
    public function complete()
    {
        $this->authorize('complete', $this->stage);
        
        // أو
        if (!Gate::allows('complete', $this->stage)) {
            throw new ForbiddenException();
        }
        
        app(WorkflowService::class)->completeStage($this->stage, auth()->user());
    }
}
```

---

### 15. ❌ Mass Assignment في Livewire (خطر أمني حرج)

#### الخطأ:
```php
// ❌ خطر أمني - يمكن تعديل أي حقل من المتصفح
class DocumentForm extends Component
{
    public Document $document; // ⚠️ خطر! يمكن تعديل أي حقل
    
    public function save()
    {
        $this->document->save(); // ❌ يحفظ جميع التغييرات!
    }
}
```

#### المشكلة:
- المستخدم يمكنه تعديل HTML في Developer Tools
- يمكن تغيير `is_archived`, `status`, وغيرها بدون صلاحيات
- ثغرة أمنية خطيرة

#### مثال على الهجوم:
```html
<!-- المستخدم يضيف في Developer Tools -->
<input wire:model="document.is_archived" value="1" type="hidden">
<input wire:model="document.status" value="approved" type="hidden">
<!-- الآن المستند أصبح "موافق عليه" بدون صلاحيات! -->
```

#### ✅ الحل:
```php
// ✅ آمن - تحديث فقط الحقول المسموح بها
class DocumentForm extends Component
{
    public $title;
    public $description;
    public $type;
    
    public Document $document; // فقط للقراءة
    
    public function mount(Document $document)
    {
        $this->document = $document;
        // نسخ القيم فقط للحقول المسموح تعديلها
        $this->title = $document->title;
        $this->description = $document->description;
        $this->type = $document->type;
    }
    
    public function save()
    {
        $this->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'type' => 'required|in:incoming,outgoing'
        ]);
        
        // تحديث فقط الحقول المسموح بها (whitelist)
        $this->document->update([
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            // ❌ لا نحدّث is_archived, status, uploaded_by, etc.
        ]);
    }
}
```

#### أو استخدام Form Objects:
```php
// ✅ أفضل - استخدام Form Object
class DocumentForm extends Component
{
    public DocumentFormObject $form;
    
    public function mount(Document $document)
    {
        $this->form = new DocumentFormObject($document);
    }
    
    public function save()
    {
        $this->form->save(); // يحفظ فقط الحقول المحددة
    }
}

class DocumentFormObject
{
    public $title;
    public $description;
    public $type;
    
    protected Document $document;
    
    public function __construct(Document $document)
    {
        $this->document = $document;
        $this->title = $document->title;
        $this->description = $document->description;
        $this->type = $document->type;
    }
    
    public function save()
    {
        // تحديث فقط الحقول المسموح بها
        $this->document->update([
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
        ]);
    }
}
```

#### 📋 قاعدة:
> **لا تستخدم `public Model $model` مباشرة في Livewire - استخدم properties منفصلة + whitelist في update()**

---

### 16. ❌ عدم استخدام Cache

#### الخطأ:
```php
// ❌ خطأ - استعلام في كل request
public function index()
{
    $users = User::all(); // في كل request!
    $tags = Tag::all(); // في كل request!
}
```

#### ✅ الحل:
```php
// ✅ صحيح - استخدام Cache
use Illuminate\Support\Facades\Cache;

public function index()
{
    $users = Cache::remember('users', 3600, function () {
        return User::all();
    });
    
    $tags = Cache::remember('tags', 3600, function () {
        return Tag::all();
    });
}
```

---

## 📋 قائمة التحقق السريعة

### قبل كتابة أي دالة، تأكد من:

- [ ] ✅ تستخدم Transactions للعمليات المتعددة؟
- [ ] ✅ تتحقق من الصلاحيات (Policies/Spatie)؟
- [ ] ✅ تتحقق من State/Business Rules؟
- [ ] ✅ تستخدم Eager Loading لتجنب N+1؟
- [ ] ✅ تتحقق من File Upload بشكل صحيح (Form Requests)؟
- [ ] ✅ تستخدم Jobs للمهام الثقيلة (Redis Queue)？
- [ ] ✅ Log الأخطاء والعمليات المهمة؟
- [ ] ✅ تستخدم Indexes في الاستعلامات؟
- [ ] ✅ تتحقق من Validation (Form Requests)؟
- [ ] ✅ تستخدم Cache للاستعلامات المتكررة؟

---

## 🎯 ملخص القواعد الذهبية

1. **Transactions للعمليات المركبة** ✅ (`DB::transaction()`)
2. **Authorization في كل endpoint** ✅ (Policies/Spatie)
3. **Eager Loading لتجنب N+1** ✅ (`with()`)
4. **Validation كاملة للـ File Upload** ✅ (Form Requests)
5. **Indexes على الأعمدة المستخدمة** ✅
6. **Auto-calculate Version Numbers** ✅
7. **State Validation قبل Transitions** ✅
8. **Jobs للمهام الثقيلة** ✅ (Redis Queue)
9. **Business Rules Validation** ✅ (Policies)
10. **Logging شامل** ✅ (Laravel Log)

---

**استخدم هذه القواعد لتجنب الأخطاء!**

---

## 🎨 خطأ شائع: استخدام ألوان غير متسقة

### ❌ المشكلة:
```html
<!-- ألوان عشوائية -->
<span class="bg-green-100 text-green-800">نشط</span>
<span class="bg-blue-200 text-blue-700">مكتمل</span>
<span class="bg-yellow-50 text-yellow-900">مسودة</span>
```

#### المشاكل:
- ألوان غير متناسقة
- درجات لونية مختلفة
- صعوبة الصيانة
- Accessibility ضعيف

### ✅ الحل: نظام ألوان موحّد
```html
<!-- نظام موحّد من 00_REQUIREMENTS_DOCUMENT.md قسم 5.1 -->
<span class="bg-[#E8F9F8] text-[#0891B2]">نشط</span>
<span class="bg-[#E8F8EF] text-[#065F46]">مكتمل</span>
<span class="bg-[#FFF8E8] text-[#92400E]">مسودة</span>
```

#### الفوائد:
- ✅ تناسق بصري
- ✅ سهولة الصيانة
- ✅ Accessibility أفضل (Contrast ratio مناسب)
- ✅ مطابقة للتصميم المرجعي

### 📋 Best Practice: Helper Methods
```php
// في Livewire Component
public function getBadgeClasses(string $status): string
{
    return match($status) {
        'active' => 'bg-[#E8F9F8] text-[#0891B2]',
        'completed' => 'bg-[#E8F8EF] text-[#065F46]',
        'draft' => 'bg-[#FFF8E8] text-[#92400E]',
        'archived' => 'bg-[#FFE8EA] text-[#991B1B]',
        default => 'bg-gray-100 text-gray-600',
    };
}
```

#### الاستخدام في Blade:
```blade
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $this->getBadgeClasses($task->status) }}">
    {{ $task->status_label }}
</span>
```

### 📋 قاعدة ذهبية:
> **استخدم نظام الألوان من `00_REQUIREMENTS_DOCUMENT.md` قسم 5.1 فقط - لا ألوان عشوائية**
