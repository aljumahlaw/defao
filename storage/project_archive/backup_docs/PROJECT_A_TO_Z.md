# 🏛️ Defao / DefandoDB - نظام إدارة وثائق ومهام قانونية متكامل

## 📊 نظرة عامة

**LegalTech شامل لمكاتب المحاماة** | Laravel 11.47 + Livewire 3 + PostgreSQL 18

**57 مستند نشط** | **إنتاج جاهز 100%** | **Real-time UX** | **114 MB نظيف**

---

## ✅ الميزات الرئيسية (مكتملة)

### 📊 لوحة سير العمل (Workflow)

- **WorkflowOverview**: إجمالي + متأخرة + 4 مراحل (real-time)
  - عدادات تلقائية لكل مرحلة (draft, review1, proofread, finalapproval)
  - بطاقة "المستندات المتأخرة" مع روابط مباشرة
  - تحديث فوري عند تغيير المراحل
  
- **WorkflowStageCard**: أحدث 3 مستندات + Stage Transitions
  - عرض أحدث 3 مستندات لكل مرحلة
  - أزرار تحكم: إرسال للمرحلة التالية / إرجاع للمسودة
  - Confirm dialogs + Toast notifications
  - Real-time refresh للعدادات
  
- **تقرير PDF**: تصدير عدادات سير العمل
  - تقرير شامل للعدادات والتوزيع
  - تصميم احترافي قابل للطباعة

### 📋 إدارة الوثائق (Documents)

- **DocumentTable**: فلترة متقدمة
  - فلترة: stage/overdue/type/date/search
  - Pagination + Search debounce (300ms)
  - Conditional eager loading للأداء
  
- **Bulk Actions**: تحديد متعدد + إجراءات جماعية
  - تحديد الكل / تحديد فردي
  - أرشفة/حذف/تغيير مرحلة جماعي
  - Confirm dialogs قبل الإجراءات الخطيرة
  
- **Export**: Excel + PDF Print
  - Excel: تصدير مع نفس الفلاتر الحالية
  - PDF: طباعة مباشرة من المتصفح
  - أعمدة: title, stage, creator, assignee, dates, status

### 📂 إدارة الأرشيف

- **`/documents/archive`**: عرض/استعادة/حذف نهائي
  - جدول المستندات المؤرشفة فقط
  - فلترة: search + date range
  - أزرار: استعادة (unarchive) / حذف نهائي (forceDelete)
  - Confirm dialogs + Toast notifications

### 📝 مهام الوثائق (Document Tasks)

- **CRUD كامل**: إضافة/تم/إعادة فتح/حذف لكل مستند
  - نموذج إضافة: title, notes, due_date, assigned_to
  - قائمة مرتبة: open أولاً ثم done
  - أزرار: تم / إعادة فتح / حذف
  
- **تاريخ استحقاق + إسناد مستخدمين**
  - عرض تاريخ الاستحقاق مع تحذير "متأخرة"
  - إسناد للمستخدمين الآخرين
  - عرض منشئ المهمة ومسندها
  
- **جدول `document_tasks`** مع cascade delete
  - Foreign keys مع `onDelete('cascade')`
  - Indexes على document_id, created_by, assigned_to, status

### 🛡️ الأمان + UX

- **`visibleTo()` scope**: موحّد (user_id OR assignee_id)
  - Scope مشترك في `Document` model
  - استخدام في `DocumentTable`, `WorkflowOverview`, `WorkflowStageCard`
  
- **Real-time**: Livewire reactivity + Toasts + Confirm dialogs
  - `wire:loading` states للأزرار
  - Toast notifications لكل action
  - Confirm dialogs قبل delete/archive
  
- **Responsive**: Mobile-first + Dark Mode كامل
  - Desktop table + Mobile cards
  - Dark mode support في كل المكونات
  
- **Performance**: Computed properties + Indexed queries
  - `#[Computed]` properties للاستعلامات
  - Database indexes على foreign keys
  - Conditional eager loading

---

## 🛠️ Tech Stack

```
Backend: Laravel 11.47 + Livewire 3 + PostgreSQL 18
Frontend: Tailwind CSS + Heroicons + Alpine.js
Export: Laravel Excel + DomPDF
Auth: Laravel Breeze + Spatie Permission
Deployment: DigitalOcean Ready
```

---

## 🚀 حالة النظام

✅ **إنتاج جاهز 100%** | ✅ **Cache نظيف** | ✅ **57 مستند واقعي**

---

## 📁 الشجرة الرئيسية

```
app/
├── Livewire/Documents/        ← DocumentTable + Detail + Upload + Archive + Tasks
├── Livewire/Tasks/            ← TaskList + TaskForm  
├── Livewire/Workflow/         ← WorkflowOverview + WorkflowStageCard
├── Models/                    ← Document + DocumentTask + User + Task
├── Exports/                   ← DocumentsExport (Excel)
├── Policies/                  ← DocumentPolicy
database/migrations/           ← document_tasks + workflow stages + indexes
resources/views/livewire/      ← كل الـ components
resources/views/reports/       ← workflow-report.blade.php (PDF)
routes/web.php                ← documents + tasks + workflow + archive
```

---

## 🎯 Quick Start

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
npm run build && php artisan serve
```

**Defao جاهز للعملاء الحقيقيين 🚀**

---

## 📊 إحصائيات المشروع

- **إجمالي الملفات**: 14,105 ملف
- **حجم المشروع**: 114.54 MB
- **قاعدة البيانات**: 57 مستند (15 مؤرشف، 42 نشط)
- **Test Files**: 25 ملف اختبار
- **Archive Docs**: 13 ملف توثيق

---

## 🔐 الأمان والصلاحيات

- **DocumentPolicy**: Authorization للوثائق
- **visibleTo() Scope**: فلترة تلقائية (user_id OR assignee_id)
- **Confirm Dialogs**: تأكيد قبل الإجراءات الخطيرة
- **Toast Notifications**: إشعارات فورية لكل action

---

## 📈 الأداء والتحسينات

- **Caching**: TaskList statusCounts (300s cache)
- **Database Indexes**: على foreign keys + status + priority
- **Conditional Eager Loading**: فقط عند الحاجة
- **Computed Properties**: للاستعلامات المكلفة
- **Search Debounce**: 300ms لتقليل الاستعلامات

---

## 🎨 الواجهة والتجربة

- **Responsive Design**: Desktop table + Mobile cards
- **Dark Mode**: دعم كامل في كل المكونات
- **Loading States**: `wire:loading` للأزرار
- **Keyboard Shortcuts**: Ctrl+D (Dashboard), Ctrl+T (Documents)
- **Print Styles**: تنسيق احترافي للطباعة

---

## 📦 Export & Reports

- **Excel Export**: تصدير الوثائق مع الفلاتر
- **PDF Reports**: تقارير سير العمل
- **Print Support**: طباعة مباشرة من المتصفح

---

## 🔄 Workflow Stages

- **draft** → **review1** → **proofread** → **finalapproval**
- **Stage Transitions**: أزرار تحكم في WorkflowStageCard
- **Overdue Detection**: تتبع المستندات المتأخرة
- **Real-time Updates**: تحديث فوري للعدادات

---

## 📝 Document Tasks

- **CRUD Complete**: إضافة/تم/إعادة فتح/حذف
- **Due Dates**: تتبع تواريخ الاستحقاق
- **User Assignment**: إسناد للمستخدمين
- **Cascade Delete**: حذف تلقائي عند حذف المستند

---

## 🗂️ Archive Management

- **Archive View**: `/documents/archive`
- **Restore**: استعادة المستندات المؤرشفة
- **Force Delete**: حذف نهائي مع تأكيد
- **Filters**: search + date range

---

## 🧪 Testing

- **Feature Tests**: 25 ملف اختبار
- **DocumentPolicy Tests**: اختبارات الصلاحيات
- **Performance Tests**: اختبارات الأداء والكاش
- **UX Tests**: اختبارات تجربة المستخدم

---

## 📚 Documentation

- **Archive/**: 13 ملف توثيق تاريخي
- **README.md**: دليل شامل
- **DEPLOYMENT_CHECKLIST.md**: قائمة النشر
- **RAILWAY_ENV_TEMPLATE.md**: قالب البيئة

---

## 🚢 Deployment

- **DigitalOcean Ready**: إعدادات جاهزة
- **Railway Support**: ملفات إعداد متوفرة
- **Production Config**: إعدادات إنتاج افتراضية
- **Optimization Commands**: `optimize:production`

---

**Defao - نظام إدارة وثائق ومهام قانونية متكامل** 🏛️
