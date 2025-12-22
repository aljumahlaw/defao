<!-- Updated: 2025-12-22 v1.0.3 -->
---
**Updated:** 2025-12-22 - Defao v1.0.3  
**Status:** ✅ Production Ready  
**Features:** Workflow, Reports, Arabic toasts, Performance optimizations  
---

# دورة حياة الوثيقة الكاملة (Document Lifecycle)

## 📋 الفهرس

1. [الحالات (States)](#1-الحالات-states)
2. [الانتقالات (Transitions)](#2-الانتقالات-transitions)
3. [الشروط (Conditions)](#3-الشروط-conditions)
4. [الأحداث (Events)](#4-الأحداث-events)
5. [المخططات](#5-المخططات)

---

## 1. الحالات (States)

### 1.1 حالات المرحلة (Stage States)

| الحالة | المفتاح | الوصف | القيمة الافتراضية |
|--------|---------|-------|-------------------|
| **مسودة** | `draft` | المرحلة الأولى عند إنشاء الوثيقة | ✅ افتراضي |
| **مراجعة أولى** | `review1` | المرحلة الثانية - مراجعة أولية | - |
| **تدقيق** | `proofread` | المرحلة الثالثة - تدقيق لغوي | - |
| **موافقة نهائية** | `finalapproval` | المرحلة الأخيرة - موافقة نهائية | - |

**المصدر:** `database/migrations/2025_12_15_004636_create_documents_table.php`
```php
$table->enum('current_stage', ['draft', 'review1', 'proofread', 'finalapproval'])->default('draft');
```

### 1.2 حالات الأرشفة (Archive States)

| الحالة | المفتاح | الوصف | القيمة الافتراضية |
|--------|---------|-------|-------------------|
| **نشط** | `is_archived = false` | الوثيقة نشطة ويمكن العمل عليها | ✅ افتراضي |
| **مؤرشف** | `is_archived = true` | الوثيقة مؤرشفة | - |

**المصدر:** `app/Models/Document.php`
```php
$table->boolean('is_archived')->default(false);
```

### 1.3 حالات الحذف (Delete States)

| الحالة | المفتاح | الوصف |
|--------|---------|-------|
| **نشط** | `deleted_at = null` | الوثيقة موجودة في النظام |
| **محذوف (soft delete)** | `deleted_at = timestamp` | الوثيقة محذوفة لكن موجودة في DB |
| **محذوف نهائياً** | `forceDelete()` | الوثيقة محذوفة نهائياً من DB |

**المصدر:** `app/Models/Document.php` - `use SoftDeletes;`

### 1.4 الحالات المركبة (Composite States)

الوثيقة يمكن أن تكون في **حالات متعددة في نفس الوقت**:

| الحالة المركبة | الوصف | المثال |
|---------------|-------|--------|
| **نشط + draft** | وثيقة في مرحلة المسودة ونشطة | `current_stage='draft'` + `is_archived=false` + `deleted_at=null` |
| **مؤرشف + finalapproval** | وثيقة موافق عليها ومؤرشفة | `current_stage='finalapproval'` + `is_archived=true` + `deleted_at=null` |
| **محذوف + draft** | وثيقة محذوفة (soft delete) | `current_stage='draft'` + `is_archived=false` + `deleted_at!=null` |

---

## 2. الانتقالات (Transitions)

### 2.1 انتقالات المراحل (Stage Transitions)

#### ✅ الانتقالات المسموحة:

| من | إلى | الإجراء | المكون | الشرط |
|---|-----|---------|--------|-------|
| `draft` | `review1` | `forward()` | `DocumentDetail` | ✅ المكلّف فقط |
| `draft` | `review1` | `advanceStage()` | `WorkflowStageCard` | ⚠️ visibleTo فقط |
| `review1` | `proofread` | `forward()` | `DocumentDetail` | ✅ المكلّف فقط |
| `review1` | `proofread` | `advanceStage()` | `WorkflowStageCard` | ⚠️ visibleTo فقط |
| `proofread` | `finalapproval` | `forward()` | `DocumentDetail` | ✅ المكلّف فقط |
| `proofread` | `finalapproval` | `advanceStage()` | `WorkflowStageCard` | ⚠️ visibleTo فقط |
| `draft` | `finalapproval` | `approve()` | `DocumentDetail` | ✅ المكلّف فقط |
| `review1` | `finalapproval` | `approve()` | `DocumentDetail` | ✅ المكلّف فقط |
| `proofread` | `finalapproval` | `approve()` | `DocumentDetail` | ✅ المكلّف فقط |
| `review1` | `draft` | `reject()` | `DocumentDetail` | ✅ المكلّف فقط |
| `proofread` | `draft` | `reject()` | `DocumentDetail` | ✅ المكلّف فقط |
| `finalapproval` | `draft` | `reject()` | `DocumentDetail` | ✅ المكلّف فقط |
| `review1` | `draft` | `rejectStage()` | `WorkflowStageCard` | ⚠️ visibleTo فقط |
| `proofread` | `draft` | `rejectStage()` | `WorkflowStageCard` | ⚠️ visibleTo فقط |
| `finalapproval` | `draft` | `rejectStage()` | `WorkflowStageCard` | ⚠️ visibleTo فقط |

#### ⚠️ الانتقالات غير الآمنة (bulkAction):

| من | إلى | الإجراء | المكون | الشرط |
|---|-----|---------|--------|-------|
| **أي مرحلة** | `draft` | `bulkAction('stage_draft')` | `DocumentTable` | ⚠️ visibleTo فقط |
| **أي مرحلة** | `review1` | `bulkAction('stage_review1')` | `DocumentTable` | ⚠️ visibleTo فقط |
| **أي مرحلة** | `proofread` | `bulkAction('stage_proofread')` | `DocumentTable` | ⚠️ visibleTo فقط |
| **أي مرحلة** | `finalapproval` | `bulkAction('stage_finalapproval')` | `DocumentTable` | ⚠️ visibleTo فقط |

**⚠️ تحذير:** `bulkAction` لا يستخدم `DocumentPolicy@update`!

### 2.2 انتقالات الأرشفة (Archive Transitions)

| من | إلى | الإجراء | المكون | الشرط |
|---|-----|---------|--------|-------|
| **أي حالة نشطة** | `is_archived = true` | `archiveDocument()` | `DocumentTable` | ⚠️ visibleTo فقط |
| **أي حالة نشطة** | `is_archived = true` | `bulkAction('archive')` | `DocumentTable` | ⚠️ visibleTo فقط |
| `is_archived = true` | `is_archived = false` | `unarchive()` | `DocumentArchive` | ⚠️ visibleTo فقط |

**⚠️ تحذير:** لا يوجد Policy للأرشفة!

### 2.3 انتقالات الحذف (Delete Transitions)

| من | إلى | الإجراء | المكون | الشرط |
|---|-----|---------|--------|-------|
| **أي حالة نشطة** | `deleted_at = timestamp` | `bulkAction('delete')` | `DocumentTable` | ⚠️ visibleTo فقط |
| `deleted_at = timestamp` | `deleted_at = null` | `restoreDocument()` | `ArchiveTable` | ⚠️ visibleTo فقط |
| `deleted_at = timestamp` | **حذف نهائي** | `forceDeleteDocument()` | `ArchiveTable` | ⚠️ visibleTo فقط |
| `is_archived = true` | **حذف نهائي** | `forceDelete()` | `DocumentArchive` | ⚠️ visibleTo فقط |

**⚠️ تحذير:** لا يوجد Policy للحذف!

### 2.4 قواعد العمل (Business Rules)

#### ✅ القواعد المطبقة:

1. **لا يمكن تغيير مرحلة وثيقة مؤرشفة**
   - **المصدر:** `WorkflowStageCard@advanceStage` و `WorkflowStageCard@rejectStage`
   - **الكود:**
   ```php
   if (!$document->is_archived) {
       $document->update(['current_stage' => $next]);
   }
   ```

2. **لا يمكن حذف وثيقة غير مؤرشفة (في DocumentArchive)**
   - **المصدر:** `DocumentArchive@forceDelete`
   - **الكود:**
   ```php
   if (!$document->is_archived) {
       $this->dispatch('show-toast', 
           message: 'لا يمكن حذف وثيقة غير مؤرشفة',
           type: 'error'
       );
       return;
   }
   ```

3. **لا يمكن إلغاء أرشفة وثيقة غير مؤرشفة**
   - **المصدر:** `DocumentArchive@unarchive`
   - **الكود:**
   ```php
   if (!$document->is_archived) {
       $this->dispatch('show-toast', 
           message: 'هذه الوثيقة غير مؤرشفة',
           type: 'error'
       );
       return;
   }
   ```

#### ❌ القواعد المفقودة:

1. **لا يوجد قيد على تغيير المرحلة عبر bulkAction**
   - يمكن لأي مستخدم يرى الوثيقة تغيير مرحلتها بدون Policy

2. **لا يوجد قيد على الأرشفة**
   - يمكن لأي مستخدم يرى الوثيقة أرشفتها بدون Policy

3. **لا يوجد قيد على الحذف**
   - يمكن لأي مستخدم يرى الوثيقة حذفها بدون Policy

---

## 3. الشروط (Conditions)

### 3.1 من يستطيع تنفيذ كل انتقال؟

#### انتقالات المراحل (Stage Transitions):

| الانتقال | المكون | الشرط الحالي | الشرط المطلوب |
|----------|--------|--------------|---------------|
| `forward()` | `DocumentDetail` | ✅ `DocumentPolicy@update` (المكلّف فقط) | ✅ صحيح |
| `approve()` | `DocumentDetail` | ✅ `DocumentPolicy@update` (المكلّف فقط) | ✅ صحيح |
| `reject()` | `DocumentDetail` | ✅ `DocumentPolicy@update` (المكلّف فقط) | ✅ صحيح |
| `advanceStage()` | `WorkflowStageCard` | ⚠️ `visibleTo` فقط | ❌ يجب استخدام `DocumentPolicy@update` |
| `rejectStage()` | `WorkflowStageCard` | ⚠️ `visibleTo` فقط | ❌ يجب استخدام `DocumentPolicy@update` |
| `bulkAction('stage_*')` | `DocumentTable` | ⚠️ `visibleTo` فقط | ❌ يجب استخدام `DocumentPolicy@update` |

#### انتقالات الأرشفة (Archive Transitions):

| الانتقال | المكون | الشرط الحالي | الشرط المطلوب |
|----------|--------|--------------|---------------|
| `archiveDocument()` | `DocumentTable` | ⚠️ `visibleTo` فقط | ❌ يجب إضافة `DocumentPolicy@archive` |
| `bulkAction('archive')` | `DocumentTable` | ⚠️ `visibleTo` فقط | ❌ يجب إضافة `DocumentPolicy@archive` |
| `unarchive()` | `DocumentArchive` | ⚠️ `visibleTo` فقط | ❌ يجب إضافة `DocumentPolicy@unarchive` |

#### انتقالات الحذف (Delete Transitions):

| الانتقال | المكون | الشرط الحالي | الشرط المطلوب |
|----------|--------|--------------|---------------|
| `bulkAction('delete')` | `DocumentTable` | ⚠️ `visibleTo` فقط | ❌ يجب إضافة `DocumentPolicy@delete` |
| `restoreDocument()` | `ArchiveTable` | ⚠️ `visibleTo` فقط | ❌ يجب إضافة `DocumentPolicy@restore` |
| `forceDeleteDocument()` | `ArchiveTable` | ⚠️ `visibleTo` فقط | ❌ يجب إضافة `DocumentPolicy@forceDelete` |
| `forceDelete()` | `DocumentArchive` | ⚠️ `visibleTo` فقط | ❌ يجب إضافة `DocumentPolicy@forceDelete` |

### 3.2 ملخص الشروط

| الإجراء | من يستطيع؟ (حالياً) | من يجب أن يستطيع؟ |
|---------|---------------------|-------------------|
| **تغيير المرحلة** | ⚠️ أي مستخدم يرى الوثيقة (في bulkAction) | ✅ المكلّف فقط |
| **الموافقة/الرفض** | ✅ المكلّف فقط | ✅ المكلّف فقط |
| **الأرشفة** | ⚠️ أي مستخدم يرى الوثيقة | ❓ المنشئ أو المكلّف؟ |
| **إلغاء الأرشفة** | ⚠️ أي مستخدم يرى الوثيقة | ❓ المنشئ أو المكلّف؟ |
| **الحذف** | ⚠️ أي مستخدم يرى الوثيقة | ❓ المنشئ فقط؟ |
| **الاستعادة** | ⚠️ أي مستخدم يرى الوثيقة | ❓ المنشئ فقط؟ |
| **الحذف النهائي** | ⚠️ أي مستخدم يرى الوثيقة | ❓ المنشئ فقط؟ |

---

## 4. الأحداث (Events)

### 4.1 الأحداث المرتبطة بكل انتقال

#### عند إنشاء الوثيقة:

| الحدث | المكون | الوصف |
|-------|--------|-------|
| `DocumentActivity::create` | `DocumentUpload` | `action_type = 'created'` |
| `DocumentActivity::create` | `DocumentUpload` | `action_type = 'uploaded'` |
| `redirect()` | `DocumentUpload` | إعادة توجيه إلى `/documents` |

#### عند تغيير المرحلة:

| الانتقال | الحدث | المكون |
|----------|-------|--------|
| `forward()` | `DocumentActivity::create` (`action_type = 'forwarded'`) | `DocumentDetail` |
| `forward()` | `show-toast` ("تم تحويل الوثيقة بنجاح") | `DocumentDetail` |
| `approve()` | `DocumentActivity::create` (`action_type = 'approved'`) | `DocumentDetail` |
| `approve()` | `show-toast` ("تم الموافقة على الوثيقة بنجاح") | `DocumentDetail` |
| `reject()` | `DocumentActivity::create` (`action_type = 'rejected'`) | `DocumentDetail` |
| `reject()` | `show-toast` ("تم رفض الوثيقة") | `DocumentDetail` |
| `advanceStage()` | `dispatch('document-stage-changed')` | `WorkflowStageCard` |
| `advanceStage()` | `show-toast` ("تم إرسال المستند للمرحلة التالية بنجاح") | `WorkflowStageCard` |
| `rejectStage()` | `dispatch('document-stage-changed')` | `WorkflowStageCard` |
| `rejectStage()` | `show-toast` ("تم إرجاع المستند للمسودة") | `WorkflowStageCard` |

#### عند الأرشفة:

| الانتقال | الحدث | المكون |
|----------|-------|--------|
| `archiveDocument()` | `show-toast` ("تم أرشفة الوثيقة بنجاح") | `DocumentTable` |
| `bulkAction('archive')` | `show-toast` ("تم تنفيذ الإجراء على X وثيقة") | `DocumentTable` |
| `unarchive()` | `show-toast` ("تم استعادة الوثيقة بنجاح") | `DocumentArchive` |

**⚠️ ملاحظة:** لا يتم إنشاء `DocumentActivity` عند الأرشفة!

#### عند الحذف:

| الانتقال | الحدث | المكون |
|----------|-------|--------|
| `bulkAction('delete')` | `show-toast` ("تم تنفيذ الإجراء على X وثيقة") | `DocumentTable` |
| `restoreDocument()` | `show-toast` ("تم استعادة الوثيقة بنجاح") | `ArchiveTable` |
| `forceDeleteDocument()` | `show-toast` ("تم حذف الوثيقة نهائياً") | `ArchiveTable` |
| `forceDelete()` | `show-toast` ("تم حذف الوثيقة نهائياً") | `DocumentArchive` |

**⚠️ ملاحظة:** لا يتم إنشاء `DocumentActivity` عند الحذف!

### 4.2 Cascade Effects

#### عند الحذف (Soft Delete):

- ✅ **Tasks**: `onDelete('set null')` - المهام المرتبطة لا تُحذف
- ✅ **DocumentTasks**: `onDelete('cascade')` - مهام الوثيقة تُحذف
- ✅ **DocumentActivities**: `onDelete('cascade')` - الأنشطة تُحذف

**المصدر:** `database/migrations/2025_12_15_004636_create_documents_table.php`

#### عند الحذف النهائي (Force Delete):

- ✅ **Tasks**: `document_id` يصبح `null`
- ✅ **DocumentTasks**: تُحذف نهائياً
- ✅ **DocumentActivities**: تُحذف نهائياً

---

## 5. المخططات

### 5.1 State Diagram - دورة حياة الوثيقة الكاملة

```mermaid
stateDiagram-v2
    [*] --> Draft: DocumentUpload::save<br/>current_stage = 'draft'<br/>is_archived = false
    
    Draft --> Review1: forward() / advanceStage()<br/>✅ المكلّف فقط
    Review1 --> Proofread: forward() / advanceStage()<br/>✅ المكلّف فقط
    Proofread --> FinalApproval: forward() / advanceStage()<br/>✅ المكلّف فقط
    
    Draft --> FinalApproval: approve()<br/>✅ المكلّف فقط
    Review1 --> FinalApproval: approve()<br/>✅ المكلّف فقط
    Proofread --> FinalApproval: approve()<br/>✅ المكلّف فقط
    
    Review1 --> Draft: reject() / rejectStage()<br/>✅ المكلّف فقط
    Proofread --> Draft: reject() / rejectStage()<br/>✅ المكلّف فقط
    FinalApproval --> Draft: reject() / rejectStage()<br/>✅ المكلّف فقط
    
    Draft --> Archived: archiveDocument() / bulkAction('archive')<br/>⚠️ visibleTo فقط
    Review1 --> Archived: archiveDocument() / bulkAction('archive')<br/>⚠️ visibleTo فقط
    Proofread --> Archived: archiveDocument() / bulkAction('archive')<br/>⚠️ visibleTo فقط
    FinalApproval --> Archived: archiveDocument() / bulkAction('archive')<br/>⚠️ visibleTo فقط
    
    Archived --> Draft: unarchive()<br/>⚠️ visibleTo فقط
    Archived --> Review1: unarchive()<br/>⚠️ visibleTo فقط
    Archived --> Proofread: unarchive()<br/>⚠️ visibleTo فقط
    Archived --> FinalApproval: unarchive()<br/>⚠️ visibleTo فقط
    
    Draft --> Deleted: bulkAction('delete')<br/>⚠️ visibleTo فقط
    Review1 --> Deleted: bulkAction('delete')<br/>⚠️ visibleTo فقط
    Proofread --> Deleted: bulkAction('delete')<br/>⚠️ visibleTo فقط
    FinalApproval --> Deleted: bulkAction('delete')<br/>⚠️ visibleTo فقط
    Archived --> Deleted: bulkAction('delete')<br/>⚠️ visibleTo فقط
    
    Deleted --> Draft: restoreDocument()<br/>⚠️ visibleTo فقط
    Deleted --> Review1: restoreDocument()<br/>⚠️ visibleTo فقط
    Deleted --> Proofread: restoreDocument()<br/>⚠️ visibleTo فقط
    Deleted --> FinalApproval: restoreDocument()<br/>⚠️ visibleTo فقط
    
    Archived --> [*]: forceDelete()<br/>⚠️ visibleTo فقط<br/>حذف نهائي
    Deleted --> [*]: forceDeleteDocument()<br/>⚠️ visibleTo فقط<br/>حذف نهائي
    
    note right of Draft
        المسودة
        ✅ DocumentPolicy@update
        ⚠️ bulkAction بدون Policy
    end note
    
    note right of Review1
        مراجعة أولى
        ✅ DocumentPolicy@update
        ⚠️ bulkAction بدون Policy
    end note
    
    note right of Proofread
        تدقيق
        ✅ DocumentPolicy@update
        ⚠️ bulkAction بدون Policy
    end note
    
    note right of FinalApproval
        موافقة نهائية
        ✅ DocumentPolicy@update
        ⚠️ bulkAction بدون Policy
    end note
    
    note right of Archived
        أرشيف
        ⚠️ لا يوجد Policy
        أي مستخدم يرى الوثيقة
    end note
    
    note right of Deleted
        محذوفة (soft delete)
        ⚠️ لا يوجد Policy
        أي مستخدم يرى الوثيقة
    end note
```

### 5.2 جدول الانتقالات الشامل

| من | الإجراء | إلى | المكون | الشرط | الأحداث | ⚠️ المشكلة |
|---|---------|-----|--------|-------|---------|-----------|
| `[*]` | `DocumentUpload::save` | `Draft` | `DocumentUpload` | `auth()->id()` | `DocumentActivity::create('created')` | - |
| `Draft` | `forward()` | `Review1` | `DocumentDetail` | ✅ `DocumentPolicy@update` | `DocumentActivity::create('forwarded')` | - |
| `Draft` | `advanceStage()` | `Review1` | `WorkflowStageCard` | ⚠️ `visibleTo` | `dispatch('document-stage-changed')` | ❌ لا Policy |
| `Draft` | `approve()` | `FinalApproval` | `DocumentDetail` | ✅ `DocumentPolicy@update` | `DocumentActivity::create('approved')` | - |
| `Review1` | `forward()` | `Proofread` | `DocumentDetail` | ✅ `DocumentPolicy@update` | `DocumentActivity::create('forwarded')` | - |
| `Review1` | `advanceStage()` | `Proofread` | `WorkflowStageCard` | ⚠️ `visibleTo` | `dispatch('document-stage-changed')` | ❌ لا Policy |
| `Review1` | `approve()` | `FinalApproval` | `DocumentDetail` | ✅ `DocumentPolicy@update` | `DocumentActivity::create('approved')` | - |
| `Review1` | `reject()` | `Draft` | `DocumentDetail` | ✅ `DocumentPolicy@update` | `DocumentActivity::create('rejected')` | - |
| `Review1` | `rejectStage()` | `Draft` | `WorkflowStageCard` | ⚠️ `visibleTo` | `dispatch('document-stage-changed')` | ❌ لا Policy |
| `Proofread` | `forward()` | `FinalApproval` | `DocumentDetail` | ✅ `DocumentPolicy@update` | `DocumentActivity::create('forwarded')` | - |
| `Proofread` | `advanceStage()` | `FinalApproval` | `WorkflowStageCard` | ⚠️ `visibleTo` | `dispatch('document-stage-changed')` | ❌ لا Policy |
| `Proofread` | `approve()` | `FinalApproval` | `DocumentDetail` | ✅ `DocumentPolicy@update` | `DocumentActivity::create('approved')` | - |
| `Proofread` | `reject()` | `Draft` | `DocumentDetail` | ✅ `DocumentPolicy@update` | `DocumentActivity::create('rejected')` | - |
| `Proofread` | `rejectStage()` | `Draft` | `WorkflowStageCard` | ⚠️ `visibleTo` | `dispatch('document-stage-changed')` | ❌ لا Policy |
| `FinalApproval` | `reject()` | `Draft` | `DocumentDetail` | ✅ `DocumentPolicy@update` | `DocumentActivity::create('rejected')` | - |
| `FinalApproval` | `rejectStage()` | `Draft` | `WorkflowStageCard` | ⚠️ `visibleTo` | `dispatch('document-stage-changed')` | ❌ لا Policy |
| **أي مرحلة** | `bulkAction('stage_*')` | **أي مرحلة** | `DocumentTable` | ⚠️ `visibleTo` | `show-toast` | ❌ لا Policy |
| **أي مرحلة نشطة** | `archiveDocument()` | `Archived` | `DocumentTable` | ⚠️ `visibleTo` | `show-toast` | ❌ لا Policy |
| **أي مرحلة نشطة** | `bulkAction('archive')` | `Archived` | `DocumentTable` | ⚠️ `visibleTo` | `show-toast` | ❌ لا Policy |
| `Archived` | `unarchive()` | **نفس المرحلة** | `DocumentArchive` | ⚠️ `visibleTo` | `show-toast` | ❌ لا Policy |
| **أي مرحلة نشطة** | `bulkAction('delete')` | `Deleted` | `DocumentTable` | ⚠️ `visibleTo` | `show-toast` | ❌ لا Policy |
| `Deleted` | `restoreDocument()` | **نفس المرحلة** | `ArchiveTable` | ⚠️ `visibleTo` | `show-toast` | ❌ لا Policy |
| `Archived` | `forceDelete()` | `[*]` | `DocumentArchive` | ⚠️ `visibleTo` | `show-toast` | ❌ لا Policy |
| `Deleted` | `forceDeleteDocument()` | `[*]` | `ArchiveTable` | ⚠️ `visibleTo` | `show-toast` | ❌ لا Policy |

---

## 6. التوصيات

### 6.1 أولويات عاجلة

1. ✅ **إضافة Policy للأرشفة**
   - `DocumentPolicy@archive` - المنشئ أو المكلّف
   - `DocumentPolicy@unarchive` - المنشئ أو المكلّف

2. ✅ **إضافة Policy للحذف**
   - `DocumentPolicy@delete` - المنشئ فقط
   - `DocumentPolicy@restore` - المنشئ فقط
   - `DocumentPolicy@forceDelete` - المنشئ فقط

3. ✅ **إصلاح WorkflowStageCard**
   - استخدام `DocumentPolicy@update` بدلاً من `visibleTo`

4. ✅ **إصلاح bulkAction**
   - استخدام `DocumentPolicy@update` لتغيير المرحلة
   - استخدام `DocumentPolicy@archive` للأرشفة
   - استخدام `DocumentPolicy@delete` للحذف

5. ✅ **إضافة DocumentActivity للأرشفة والحذف**
   - `DocumentActivity::create('archived')` عند الأرشفة
   - `DocumentActivity::create('deleted')` عند الحذف

### 6.2 تحسينات

1. ✅ **إضافة تأكيد قبل الحذف النهائي**
   - Modal تأكيد مع تحذير واضح

2. ✅ **إضافة قيود على الأرشفة**
   - لا يمكن أرشفة وثيقة في مرحلة `draft`؟
   - لا يمكن أرشفة وثيقة محذوفة؟

3. ✅ **إضافة سجل كامل**
   - تسجيل جميع الانتقالات في `DocumentActivity`

---

**تاريخ الإنشاء:** 2025-01-27  
**آخر تحديث:** 2025-01-27  
**الإصدار:** 1.0

