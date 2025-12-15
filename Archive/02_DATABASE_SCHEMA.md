# 🗄️ وثيقة Schema قاعدة البيانات - Database Schema Document

**الإصدار**: 2.0 (Laravel Stack)  
**التاريخ**: $(date)  
**قاعدة البيانات**: PostgreSQL 14+  
**Stack**: Laravel 11 + Spatie Permission  
**الحالة**: ✅ جاهز للبدء بالبناء

---

## 🎯 الهدف

هذا المستند يحدد:
- ✅ جميع الجداول والعلاقات (Laravel Migrations)
- ✅ الـ Indexes المطلوبة
- ✅ الـ Constraints
- ✅ Eloquent Relationships

---

## 📋 الجداول (Tables)

### 1. users (Laravel Breeze + Spatie Permission)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

**Spatie Permission Tables (تلقائية):**
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

هذا ينشئ:
- `roles` - الأدوار
- `permissions` - الصلاحيات
- `model_has_roles` - ربط المستخدمين بالأدوار
- `model_has_permissions` - ربط المستخدمين بالصلاحيات
- `role_has_permissions` - ربط الأدوار بالصلاحيات

---

### 2. tasks

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('draft');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('due_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_favorite')->default(false);
            $table->foreignId('favorite_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'due_date']);
            $table->index(['created_by', 'status']);
            $table->index(['is_favorite', 'favorite_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
```

---

### 3. task_user (Pivot Table - أدوار المهمة)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_user', function (Blueprint $table) {
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['edit', 'review', 'approve']);
            $table->timestamps();
            
            $table->primary(['task_id', 'user_id', 'role']);
            $table->index(['task_id', 'role']);
            $table->index(['user_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_user');
    }
};
```

---

### 4. documents

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('type', ['incoming', 'outgoing'])->default('incoming');
            // ❌ تم إزالة s3_path, file_size, mime_type - موجودة في document_versions فقط
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_favorite')->default(false);
            $table->foreignId('favorite_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            // ❌ تم إزالة softDeletes() - نستخدم is_archived فقط لتجنب التعقيد
            
            $table->index(['uploaded_by', 'type', 'created_at']);
            $table->index(['task_id', 'is_archived']);
            $table->index(['is_favorite', 'favorite_by']);
            $table->index(['is_archived', 'archived_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
```

---

### 5. document_versions

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->integer('version_number');
            $table->string('s3_key', 512);
            $table->string('s3_url', 512);
            $table->string('file_name', 255);
            $table->integer('file_size'); // bytes
            $table->string('mime_type', 100);
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            
            $table->unique(['document_id', 'version_number']);
            $table->index('document_id');
            $table->index(['document_id', 'version_number']);
            $table->index('s3_key');
            $table->index('uploaded_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};
```

---

### 6. workflow_stages (Custom State Machine)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->enum('stage', ['draft', 'review1', 'proofread', 'final_approval']);
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->foreignId('assigned_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['document_id', 'stage']);
            $table->index(['document_id', 'stage']);
            $table->index(['assigned_user_id', 'status']);
            $table->index(['status', 'stage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_stages');
    }
};
```

---

### 7. comments

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
            
            $table->index('document_id');
            $table->index('author_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
```

---

### 8. audit_log

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id();
            $table->enum('entity_type', ['task', 'document', 'workflow_stage']);
            $table->unsignedBigInteger('entity_id');
            $table->enum('action', ['created', 'updated', 'deleted', 'archived', 'shared', 'commented']);
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
            $table->jsonb('changes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['entity_type', 'entity_id']);
            $table->index('performed_by');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};
```

---

### 9. notifications (Laravel Default)

```bash
php artisan notifications:table
php artisan migrate
```

هذا ينشئ جدول `notifications` افتراضياً من Laravel.

---

### 10. folders

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('parent_folder_id')->nullable()->constrained('folders')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->foreignId('locked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index('created_by');
            $table->index('is_locked');
            $table->index('parent_folder_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
```

---

### 11. document_shares (Temporary Signed Routes)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->string('share_token', 64)->unique();
            $table->foreignId('shared_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('shared_with')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('expires_at');
            $table->boolean('can_download')->default(true);
            $table->boolean('can_comment')->default(false);
            $table->integer('view_count')->default(0);
            $table->timestamps();
            
            $table->unique('share_token');
            $table->index('document_id');
            $table->index('shared_by');
            $table->index(['expires_at', 'share_token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_shares');
    }
};
```

---

### 12. tags

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('color', 7)->default('#1e40af'); // Tailwind blue-800
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
```

---

### 13. document_tags (Pivot Table)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_tags', function (Blueprint $table) {
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            
            $table->primary(['document_id', 'tag_id']);
            $table->index('document_id');
            $table->index('tag_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_tags');
    }
};
```

---

## 🔗 Eloquent Relationships (ملخص)

### User Model:
```php
// Relationships
hasMany(Document::class, 'uploaded_by')
belongsToMany(Task::class, 'task_user')->withPivot('role')
hasMany(WorkflowStage::class, 'assigned_user_id')
hasMany(WorkflowStage::class, 'completed_by')
hasMany(Comment::class, 'author_id')

// Spatie Permission
use HasRoles; // provides: hasRole(), assignRole(), etc.
```

### Task Model:
```php
// Relationships
belongsTo(User::class, 'created_by')
belongsToMany(User::class, 'task_user')->withPivot('role')
hasMany(Document::class)
hasManyThrough(WorkflowStage::class, Document::class)
belongsTo(User::class, 'favorite_by')
```

### Document Model:
```php
// Relationships
belongsTo(User::class, 'uploaded_by')
belongsTo(Task::class)
hasMany(DocumentVersion::class)
hasOne(DocumentVersion::class)->latestOfMany('version_number')
hasMany(Comment::class)
hasMany(WorkflowStage::class)
hasOne(DocumentShare::class)
belongsToMany(Tag::class, 'document_tags')
belongsTo(User::class, 'favorite_by')
belongsTo(User::class, 'archived_by')

// Accessors للتوافق (استخدام latestVersion بدلاً من s3_path مباشرة)
getS3PathAttribute() // يرجع latestVersion->s3_key
getFileSizeAttribute() // يرجع latestVersion->file_size
getMimeTypeAttribute() // يرجع latestVersion->mime_type
```

### WorkflowStage Model:
```php
// Relationships
belongsTo(Document::class)
belongsTo(User::class, 'assigned_user_id')
belongsTo(User::class, 'completed_by')
```

### DocumentVersion Model:
```php
// Relationships
belongsTo(Document::class)
belongsTo(User::class, 'uploaded_by')
```

---

## 📊 ملخص الـ Indexes

### Indexes للاستعلامات الشائعة:

```
✅ users: email (unique)
✅ tasks: (status, due_date), (created_by, status), (is_favorite, favorite_by)
✅ task_user: (task_id, user_id, role) primary, (task_id, role), (user_id, role)
✅ documents: (uploaded_by, type, created_at), (task_id, is_archived), (is_favorite, favorite_by), (is_archived, archived_at)
✅ document_versions: (document_id, version_number) unique, document_id, s3_key, uploaded_at
✅ workflow_stages: (document_id, stage) unique, (assigned_user_id, status), (status, stage)
✅ comments: document_id, author_id, created_at
✅ audit_log: (entity_type, entity_id), performed_by, created_at
✅ folders: created_by, is_locked, parent_folder_id
✅ document_shares: share_token (unique), document_id, shared_by, (expires_at, share_token)
✅ tags: name (unique), created_by
✅ document_tags: (document_id, tag_id) primary
```

---

## 📋 ترتيب Migrations

**يجب أن يكون الترتيب كما يلي (Foreign Keys dependencies):**

```
1. 0001_create_users_table.php
2. 0002_create_roles_tables.php (Spatie)
3. 0003_create_tasks_table.php
4. 0004_create_task_user_table.php
5. 0005_create_documents_table.php
6. 0006_create_document_versions_table.php
7. 0007_create_workflow_stages_table.php
8. 0008_create_comments_table.php
9. 0009_create_audit_log_table.php
10. 0010_create_notifications_table.php (Laravel)
11. 0011_create_folders_table.php
12. 0012_create_document_shares_table.php
13. 0013_create_tags_table.php
14. 0014_create_document_tags_table.php
```

---

## 📊 Performance Indexes (إجباري)

### Indexes إضافية للأداء:

#### في migration: create_tasks_table
```php
// أضف هذه الـ Indexes
$table->index('status'); // للفلترة
$table->index('priority'); // للترتيب
$table->index('due_date'); // للبحث عن المهام المتأخرة
$table->index(['status', 'due_date']); // Composite للاستعلامات المشتركة
```

#### في migration: create_documents_table
```php
// أضف هذه الـ Indexes
$table->index('type'); // للفلترة
$table->index('is_archived'); // للفلترة
$table->index(['task_id', 'is_archived']); // Composite
$table->index(['created_at', 'type']); // للترتيب والفلترة
```

#### في migration: create_workflow_stages_table
```php
// أضف هذه الـ Indexes
$table->index('status'); // للفلترة
$table->index('assigned_user_id'); // للـ dashboard
$table->index(['document_id', 'order']); // للترتيب (إذا كان لديك order column)
$table->index(['assigned_user_id', 'status']); // Composite للاستعلامات الشائعة
```

#### في migration: create_notifications_table (Laravel default)
```php
// أضف هذه الـ Indexes (في migration منفصلة)
Schema::table('notifications', function (Blueprint $table) {
    $table->index(['notifiable_id', 'read_at']); // للإشعارات غير المقروءة
    $table->index('created_at'); // للترتيب
});
```

### Full-Text Search (للبحث العربي):

#### بعد تشغيل Migrations الأساسية، نفذ هذا SQL في PostgreSQL:

```sql
-- Full-Text Search Index على documents.title
CREATE INDEX documents_title_fulltext_idx 
ON documents 
USING GIN (to_tsvector('arabic', title));

-- Full-Text Search Index على documents.description
CREATE INDEX documents_description_fulltext_idx 
ON documents 
USING GIN (to_tsvector('arabic', description));

-- Full-Text Search Index على tasks.title
CREATE INDEX tasks_title_fulltext_idx 
ON tasks 
USING GIN (to_tsvector('arabic', title));

-- Full-Text Search Index على tasks.description
CREATE INDEX tasks_description_fulltext_idx 
ON tasks 
USING GIN (to_tsvector('arabic', description));
```

#### الاستخدام في Laravel:

```php
// البحث بالعربي في Documents
Document::whereRaw("to_tsvector('arabic', title) @@ to_tsquery('arabic', ?)", ['مستند'])
    ->orWhereRaw("to_tsvector('arabic', description) @@ to_tsquery('arabic', ?)", ['مستند'])
    ->get();

// أو دالة helper
public function scopeFullTextSearch($query, $term)
{
    return $query->whereRaw(
        "to_tsvector('arabic', coalesce(title, '') || ' ' || coalesce(description, '')) @@ to_tsquery('arabic', ?)",
        [$term]
    );
}

// الاستخدام
Document::fullTextSearch('مستند')->get();
```

#### ملاحظات:
- Full-Text Search Indexes **ليست إلزامية** للمشروع - يمكن إضافتها لاحقاً
- تبدأ بـ PostgreSQL Full-Text Search، ثم تنتقل لـ Meilisearch عند الحاجة
- Indexes Full-Text Search تستهلك مساحة إضافية في Database

---

## ✅ قائمة التحقق (Checklist)

قبل إنشاء Migrations، تأكد من:

- [ ] ✅ جميع الجداول محددة
- [ ] ✅ جميع العلاقات (Foreign Keys) محددة
- [ ] ✅ جميع الـ Indexes محددة
- [ ] ✅ جميع الـ Constraints محددة
- [ ] ✅ جميع الـ Defaults محددة
- [ ] ✅ ترتيب Migrations صحيح (Foreign Keys بعد الجداول المرجعية)
- [ ] ✅ CASCADE rules محددة (ON DELETE CASCADE)
- [ ] ✅ Unique constraints محددة
- [ ] ✅ Enum values صحيحة
- [ ] ✅ Soft Deletes حيث لزم (ملاحظة: documents لا تستخدم softDeletes - نستخدم is_archived فقط)
- [ ] ✅ Performance Indexes (status, priority, due_date, composite indexes)
- [ ] ✅ Full-Text Search Indexes (للبحث العربي - اختياري للمرحلة الأولى)

---

## 🎯 Enums

### WorkflowStageEnum

```php
<?php

namespace App\Enums;

enum WorkflowStageEnum: string
{
    case Draft = 'draft';
    case Review1 = 'review1';
    case Proofread = 'proofread';
    case FinalApproval = 'final_approval';
}
```

### TaskStatusEnum

```php
<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Completed = 'completed';
    case Archived = 'archived';
}
```

### DocumentTypeEnum

```php
<?php

namespace App\Enums;

enum DocumentTypeEnum: string
{
    case Incoming = 'incoming';
    case Outgoing = 'outgoing';
}
```

---

## 🔄 Seeders

### RoleSeeder (Spatie)

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'authorized']);
        Role::create(['name' => 'user']);
    }
}
```

---

**هذا المستند يجب أن يكون المرجع لكل Migrations!**
