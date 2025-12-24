---
**Updated:** 2025-12-22 - Defao v1.0.1  
**Status:** ✅ Production Ready  
**Features:** Workflow, Reports link, Arabic toasts  
---

# ملخص سريع - دورة حياة الوثيقة

## 📊 الحالات (States)

### حالات المرحلة:
- `draft` - مسودة (افتراضي)
- `review1` - مراجعة أولى
- `proofread` - تدقيق
- `finalapproval` - موافقة نهائية

### حالات الأرشفة:
- `is_archived = false` - نشط (افتراضي)
- `is_archived = true` - مؤرشف

### حالات الحذف:
- `deleted_at = null` - نشط
- `deleted_at = timestamp` - محذوف (soft delete)
- `forceDelete()` - محذوف نهائياً

---

## 🔄 الانتقالات الرئيسية

### ✅ الانتقالات المحمية (بـ Policy):

| من | إلى | الإجراء | الشرط |
|---|-----|---------|-------|
| `draft` | `review1` | `forward()` | ✅ المكلّف فقط |
| `review1` | `proofread` | `forward()` | ✅ المكلّف فقط |
| `proofread` | `finalapproval` | `forward()` | ✅ المكلّف فقط |
| أي مرحلة | `finalapproval` | `approve()` | ✅ المكلّف فقط |
| أي مرحلة | `draft` | `reject()` | ✅ المكلّف فقط |

### ⚠️ الانتقالات غير المحمية:

| من | إلى | الإجراء | الشرط |
|---|-----|---------|-------|
| أي مرحلة | أي مرحلة | `bulkAction('stage_*')` | ⚠️ visibleTo فقط |
| أي مرحلة | `Archived` | `archiveDocument()` | ⚠️ visibleTo فقط |
| `Archived` | أي مرحلة | `unarchive()` | ⚠️ visibleTo فقط |
| أي مرحلة | `Deleted` | `bulkAction('delete')` | ⚠️ visibleTo فقط |
| `Deleted` | أي مرحلة | `restoreDocument()` | ⚠️ visibleTo فقط |
| `Archived`/`Deleted` | `[*]` | `forceDelete()` | ⚠️ visibleTo فقط |

---

## 🚨 المشاكل الحرجة

### 1. عدم وجود Policy للأرشفة
- **المشكلة:** أي مستخدم يرى الوثيقة يمكنه أرشفتها
- **الحل:** إضافة `DocumentPolicy@archive` و `DocumentPolicy@unarchive`

### 2. عدم وجود Policy للحذف
- **المشكلة:** أي مستخدم يرى الوثيقة يمكنه حذفها
- **الحل:** إضافة `DocumentPolicy@delete`, `DocumentPolicy@restore`, `DocumentPolicy@forceDelete`

### 3. عدم وجود Policy في WorkflowStageCard
- **المشكلة:** `advanceStage()` و `rejectStage()` يستخدمان `visibleTo` فقط
- **الحل:** استخدام `DocumentPolicy@update`

### 4. عدم وجود Policy في bulkAction
- **المشكلة:** `bulkAction` لتغيير المرحلة لا يستخدم Policy
- **الحل:** استخدام `DocumentPolicy@update` لكل وثيقة

### 5. عدم وجود DocumentActivity للأرشفة والحذف
- **المشكلة:** لا يتم تسجيل عمليات الأرشفة والحذف
- **الحل:** إضافة `DocumentActivity::create` عند الأرشفة والحذف

---

## 📋 قواعد العمل (Business Rules)

### ✅ مطبقة:
1. لا يمكن تغيير مرحلة وثيقة مؤرشفة
2. لا يمكن حذف وثيقة غير مؤرشفة (في DocumentArchive)
3. لا يمكن إلغاء أرشفة وثيقة غير مؤرشفة

### ❌ مفقودة:
1. لا يوجد قيد على bulkAction
2. لا يوجد DocumentActivity للأرشفة
3. لا يوجد DocumentActivity للحذف

---

## 🎯 التوصيات السريعة

### أولويات عاجلة:
1. ✅ إضافة `DocumentPolicy@archive`, `DocumentPolicy@unarchive`
2. ✅ إضافة `DocumentPolicy@delete`, `DocumentPolicy@restore`, `DocumentPolicy@forceDelete`
3. ✅ إصلاح `WorkflowStageCard` لاستخدام `DocumentPolicy@update`
4. ✅ إصلاح `bulkAction` لاستخدام Policy
5. ✅ إضافة `DocumentActivity` للأرشفة والحذف

---

## 📁 الملفات المرجعية

- **`DOCUMENT_LIFECYCLE.md`** - المستند التفصيلي الكامل
- **`DOCUMENT_LIFECYCLE_DIAGRAMS.md`** - المخططات التفصيلية
- **`DOCUMENT_LIFECYCLE_TRANSITIONS_TABLE.csv`** - جدول الانتقالات
- **`DOCUMENT_LIFECYCLE_SUMMARY.md`** - هذا الملف (الملخص)

---

**آخر تحديث:** 2025-01-27

