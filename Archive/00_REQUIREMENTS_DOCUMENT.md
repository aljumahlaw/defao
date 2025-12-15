# 📋 وثيقة المتطلبات الشاملة - Requirements Document
## نظام إدارة المستندات والمهام - Document Management System

**الإصدار**: 2.0 (Laravel Stack)  
**التاريخ**: $(date)  
**الحالة**: ✅ جاهز للبدء بالبناء  
**Stack**: Laravel 11 + Livewire 3 + Breeze + Spatie + Custom Workflow + Redis + S3

---

## 🎯 الهدف من هذا المستند

هذا المستند يحدد **جميع المتطلبات والمواصفات** قبل البدء بالبرمجة. يجب:
- ✅ مراجعة كل بند بعناية
- ✅ الموافقة على كل متطلبات قبل البدء
- ✅ استخدامه كمرجع أثناء التطوير
- ❌ عدم البدء بالبرمجة قبل إكمال هذا المستند

---

## 📊 نظرة عامة على النظام

### الوصف:
نظام إدارة مستندات ومهام داخلي مُبسط لمكاتب صغيرة/متوسطة يضمن **مستند واحد = مصدر الحقيقة الوحيد**.

### الوظائف الجوهرية:
- ✅ إنشاء مهام (Draft → Review1 → Proofread → FinalApproval)
- ✅ معاينة PDF/Word/Excel + [📥تنزيل→⬆️رفع إصدار]
- ✅ أرشفة تلقائية (نقل + 🔒قفل مجلدات)
- ✅ فلاتر (📨وارد/📤صادر/⭐مفضلة شخصية)
- ✅ مشاركة آمنة + 🔔إشعارات داخلية
- ✅ تتبع مراحل Workflow (4 مراحل واضحة)

---

## 1️⃣ المتطلبات الوظيفية (Functional Requirements)

### 1.1 إدارة المستخدمين والأدوار (Spatie Laravel-Permission)

#### الأدوار المطلوبة:

| **الدور** | **إضافة/حذف** | **مهام** | **أرشفة** | **إعدادات** | **الاستخدام اليومي** |
|:----------|:--------------|:---------|:----------|:------------|:---------------------|
| **مدير (admin)** | ✅ نعم | ✅ نعم | ✅ نعم | ✅ كاملة | لوحة، اعتماد نهائي، إدارة |
| **موظف مخول (authorized)** | ❌ لا | ✅ نعم | ✅ نعم | ✅ جزئية | إنشاء مهام، أرشفة، توزيع |
| **موظف عادي (user)** | ❌ لا | ✅ نعم | ❌ لا | ❌ لا | رفع، تعليقات، معاينة |

#### تفاصيل الأدوار:

**1. مدير (admin)**
- ✅ إضافة/حذف/تعديل المستخدمين
- ✅ تغيير أدوار المستخدمين (Spatie Permission)
- ✅ الوصول الكامل لجميع الميزات
- ✅ إعدادات النظام الكاملة
- ✅ الوصول لـ Laravel Horizon (إدارة Queue)
- ✅ عرض جميع التقارير والإحصائيات

**2. موظف مخول (authorized)**
- ✅ إنشاء وتعديل المهام
- ✅ أرشفة المستندات
- ✅ توزيع المهام على الموظفين
- ✅ الوصول للإعدادات (جزئي - الوسوم، الفلاتر)
- ❌ لا يمكن إضافة/حذف مستخدمين
- ❌ لا يمكن الوصول لـ Horizon

**3. موظف عادي (user)**
- ✅ رفع المستندات
- ✅ إنشاء مهام شخصية
- ✅ إضافة تعليقات
- ✅ معاينة المستندات
- ✅ تنزيل المستندات
- ❌ لا يمكن أرشفة
- ❌ لا يمكن الوصول للإعدادات

#### شروط الحسابات (Laravel Breeze):

**Phase 1 (MVP)**: Laravel Breeze فقط (email/password)
- ✅ تسجيل الدخول/الخروج
- ✅ استعادة كلمة المرور
- ✅ بقية المستخدمين يصبحون "user" افتراضياً
- ✅ فقط Admin يمكنه تغيير الأدوار (Spatie)

**Phase 2 (بعد الإطلاق)**: إضافة OAuth Manus

##### Integration Guide (عند الاستعداد):

###### 1. التثبيت:
```bash
composer require laravel/socialite
```

###### 2. التكوين (.env):
```env
MANUS_CLIENT_ID=your_client_id
MANUS_CLIENT_SECRET=your_client_secret
MANUS_REDIRECT_URI=https://yourdomain.com/auth/manus/callback
OWNER_OPEN_ID=your_open_id
```

###### 3. Routes:
```php
Route::get('/auth/manus', [OAuthController::class, 'redirect']);
Route::get('/auth/manus/callback', [OAuthController::class, 'callback']);
```

###### 4. Controller:
```php
class OAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('manus')->redirect();
    }
    
    public function callback()
    {
        $manusUser = Socialite::driver('manus')->user();
        
        $user = User::updateOrCreate(
            ['email' => $manusUser->email],
            [
                'name' => $manusUser->name,
                'manus_id' => $manusUser->id,
                'manus_token' => $manusUser->token,
            ]
        );
        
        // منح Admin role إذا ownerOpenId يطابق
        if ($manusUser->id === config('manus.owner_open_id')) {
            $user->assignRole('admin');
        }
        
        Auth::login($user);
        return redirect('/dashboard');
    }
}
```

---

### 1.2 إدارة المهام (Tasks)

#### إنشاء مهمة:
```
المتطلبات:
- العنوان: مطلوب (1-255 حرف)
- الوصف: اختياري
- المسؤول: اختياري (يمكن تعيينه لاحقاً)
- الأولوية: low, medium, high (افتراضي: medium)
- تاريخ الاستحقاق: اختياري
- المستند المرتبط: اختياري (يمكن ربط مستند لاحقاً)

عند الإنشاء:
1. إنشاء سجل في جدول tasks
2. تعيين created_by = المستخدم الحالي
3. حفظ في قاعدة البيانات
4. إرسال إشعار للمسؤول (إن وجد)
5. إعادة توجيه إلى صفحة تفاصيل المهمة
```

#### حالة المهمة:
```
الحالات الممكنة:
- draft: مسودة (قيد الإنشاء)
- active: نشطة (قيد المعالجة)
- completed: مكتملة (انتهت جميع المراحل)
- archived: مؤرشفة (بعد الأرشفة التلقائية)

⚠️ ملاحظة: حالة المهمة يتم تحديثها تلقائياً بناءً على مراحل workflow
```

#### الفلاتر المطلوبة (Livewire Components):
- ✅ المفضلة الشخصية (isFavorite) - ⭐
- ✅ الحالة (status)
- ✅ الأولوية (priority)
- ✅ المسؤول (assigned_to)
- ✅ البحث (في العنوان والوصف)
- ✅ التاريخ (من - إلى)

---

### 1.3 إدارة المستندات (Documents)

#### رفع مستند:
```
الشروط:
- حجم الملف: حد أقصى 25 ميجابايت
- أنواع الملفات المسموحة:
  - PDF: application/pdf
  - Word: application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document
  - Excel: application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet

المعلومات المطلوبة:
- العنوان: مطلوب (1-255 حرف)
- الوصف: اختياري
- النوع: وارد (incoming) أو صادر (outgoing) - افتراضي: وارد
- المهمة المرتبطة: اختياري
- الوسوم: اختياري (multiple)

عملية الرفع (Redis Queue):
1. التحقق من الملف (الحجم، النوع، الاسم)
2. رفع الملف إلى Amazon S3 (ProcessDocumentJob)
3. إنشاء سجل في documents
4. إنشاء Version 1.0 في document_versions
5. إرسال إشعار للمستخدمين المعنيين
6. عرض رسالة نجاح
```

#### معاينة المستند (PDF.js):
```
- معاينة PDF: معاينة مباشرة باستخدام PDF.js في المتصفح

**Word/Excel (MVP - LibreOffice Headless Conversion)**:

##### المتطلبات:
```bash
sudo apt-get install libreoffice
```

##### التنفيذ:
```php
class DocumentConversionService
{
    public function convertToPdf(string $inputPath): string
    {
        $outputPath = storage_path('app/temp/' . uniqid() . '.pdf');
        
        $command = sprintf(
            'libreoffice --headless --convert-to pdf --outdir %s %s',
            dirname($outputPath),
            escapeshellarg($inputPath)
        );
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new \Exception('Conversion failed');
        }
        
        return $outputPath;
    }
}
```

##### القيود:
- يدعم: .docx, .xlsx, .pptx
- حجم الملف: حتى 25MB
- وقت التحويل: 5-30 ثانية (حسب حجم الملف)
- التخزين: PDF المحول يُخزن في S3 كنسخة مشتقة

##### خطة لاحقة (Phase 2):
- استخدام AWS Textract للـ OCR
- عرض مباشر دون تحويل باستخدام Office Online API
- قراءة فقط (لا يمكن التعديل)
- إمكانية التكبير/التصغير
- طباعة
- التنزيل (زر منفصل)
```

#### إدارة الإصدارات:
```
- عرض جميع الإصدارات مرتبة (الأحدث أولاً)
- لكل إصدار:
  - رقم الإصدار (1, 2, 3, ...)
  - التاريخ والوقت
  - المستخدم الذي رفع
  - حجم الملف
  - رابط التنزيل
  - ملاحظات (إن وجدت)

رفع إصدار جديد:
- فقط في المراحل: Draft, Review1, Proofread
- ❌ غير مسموح في FinalApproval (قراءة فقط)
- ❌ غير مسموح في Archived
- حساب versionNumber تلقائياً (N + 1)
```

---

### 1.4 نظام Workflow (Custom State Machine)

#### المراحل (4 مراحل خطية):

```
1. Draft (مسودة)
   - الحالة: inProgress (عند الإنشاء)
   - المسؤول: منشئ المهمة أو المسؤول المحدد
   - الإجراءات المسموحة:
     - رفع/تحميل مستندات
     - رفع إصدارات جديدة
     - إضافة تعليقات
     - إنهاء المرحلة (الانتقال إلى Review1)

2. Review1 (مراجعة أولى)
   - الحالة: pending → inProgress (عند إنهاء Draft)
   - المسؤول: يُحدد عند إنشاء المهمة
   - الإجراءات المسموحة:
     - معاينة المستندات
     - رفع إصدارات محررة
     - إضافة تعليقات وملاحظات
     - إنهاء المرحلة (الانتقال إلى Proofread)

3. Proofread (تدقيق)
   - الحالة: pending → inProgress (عند إنهاء Review1)
   - المسؤول: يُحدد عند إنشاء المهمة
   - الإجراءات المسموحة:
     - تدقيق نهائي
     - رفع إصدار نهائي
     - إضافة تعليقات
     - إنهاء المرحلة (الانتقال إلى FinalApproval)

4. FinalApproval (اعتماد نهائي)
   - الحالة: pending → inProgress (عند إنهاء Proofread)
   - المسؤول: المدير أو شخص محدد
   - الإجراءات المسموحة:
     - معاينة نهائية
     - ✅ اعتماد نهائي
     - ❌ لا يمكن رفع إصدارات جديدة (قراءة فقط)
   - عند الاعتماد:
     - تحديث task.status = "completed"
     - تفعيل الأرشفة التلقائية (ArchiveTaskJob)
```

#### إنهاء مرحلة (Custom State Machine):
```
الشروط:
1. المستخدم الحالي = assigned_user_id للمرحلة
2. حالة المرحلة = inProgress
3. ❌ لا يمكن إنهاء مرحلة pending

العمليات (في Transaction واحدة):
1. تحديث المرحلة الحالية:
   - status = "completed"
   - completed_by = userId
   - completed_at = now()
   
2. إذا لم تكن آخر مرحلة:
   - تفعيل المرحلة التالية (status = "inProgress")
   - إرسال إشعار للمسؤول عن المرحلة التالية
   
3. إذا كانت آخر مرحلة (FinalApproval):
   - تحديث task.status = "completed"
   - task.completed_at = now()
   - تفعيل الأرشفة التلقائية (ArchiveTaskJob)
   
4. تسجيل في audit_log

⚠️ جميع العمليات يجب أن تكون في DB::transaction()!
```

---

### 1.5 الأرشفة (Archiving)

#### الأرشفة التلقائية (ArchiveTaskJob):
```
الحدث: عند اكتمال FinalApproval

الخطوات (Redis Queue):
1. تحديث document.is_archived = true
2. document.archived_at = now()
3. document.archived_by = userId (الذي أنهى FinalApproval)
4. إنشاء/نقل المستند إلى مجلد في الأرشيف
5. قفل المجلد (folder.is_locked = true)
6. تسجيل في audit_log

⚠️ لا يمكن تعديل مستند مؤرشف (قراءة فقط)
```

#### الأرشيف اليدوي:
```
المسموح للمستخدمين:
- Admin: يمكن أرشفة أي مستند
- Authorized: يمكن أرشفة المستندات غير المؤرشفة

الإجراء:
- اختيار المستند
- اختيار/إنشاء مجلد
- تأكيد الأرشفة
- تحديث الحالة (Queue Job)
- تسجيل في audit_log
```

#### الأرشفة vs الحذف:
```
- **الأرشفة**: إخفاء المستند من القوائم الرئيسية، لكن يمكن استرجاعه (للمستندات القديمة)
- **الحذف**: إزالة نهائية (admin فقط، للمستندات الخاطئة أو المكررة)

**القرار**: نستخدم `is_archived` فقط، بدون Soft Deletes
- `is_archived = true`: مستند مؤرشف (يمكن استرجاعه)
- `is_archived = false`: مستند نشط
- لا نستخدم `deleted_at` (Soft Deletes) لتجنب التعقيد والغموض
```

---

### 1.6 البحث (Search)

#### البحث البسيط:
```
- البحث في:
  - عنوان المستند
  - وصف المستند
  - عنوان المهمة
  - وصف المهمة
  
- الفلاتر:
  - النوع: وارد/صادر
  - الحالة: مؤرشف/غير مؤرشف
  - التاريخ (من - إلى)
  - الوسوم
```

#### البحث المتقدم (Meilisearch - اختياري):
```
- Full-text search
- البحث في محتوى PDF (بعد OCR)
- البحث في التعليقات
- ترتيب حسب الصلة (relevance)
- التصحيح التلقائي (typo tolerance)
```

---

### 1.7 التعليقات (Comments)

```
- إضافة تعليق على مستند
- عرض جميع التعليقات (الأقدم أولاً)
- عرض:
  - المؤلف (اسم المستخدم)
  - التاريخ والوقت
  - المحتوى
- ❌ لا يمكن تعديل/حذف التعليقات (immutable - حسب المتطلبات)
```

---

### 1.8 الإشعارات (Laravel Notifications + Livewire)

```
أنواع الإشعارات:
- task: عند تعيين مهمة جديدة
- workflow: عند تغيير مرحلة
- document: عند رفع مستند جديد
- system: إشعارات النظام

عند الإرسال:
- حفظ في جدول notifications (Laravel default)
- عرض في Dashboard (🔔) - Livewire Component
- تحديث العداد (Real-time مع Livewire)
- إمكانية وضع علامة كمقروءة
```

---

### 1.9 المشاركة (Laravel Signed Routes)

```
- إنشاء رابط مشفر للمستند (Signed Route)
- الخيارات:
  - صلاحية التنزيل (can_download)
  - صلاحية التعليق (can_comment)
  - تاريخ انتهاء الصلاحية (expires_at)
  - مشاركة مع مستخدم محدد (shared_with_user_id)
  
- الروابط:
  - share_token فريد (64 حرف)
  - رابط: /مشترك/{token}
  - عرض المستند بدون تسجيل دخول (إن لم يكن محدد مستخدم)
  - Signed Route للحماية
```

---

## 2️⃣ خريطة الواجهات (17 واجهة)

### Livewire Components (القائمة النهائية)

المشروع يحتوي على **7 مكونات Livewire**:

1. **TaskTable** - جدول المهام مع فلترة وبحث
2. **TaskForm** - نموذج إنشاء/تعديل مهمة (Modal)
3. **DocumentUpload** - رفع المستندات مع progress bar
4. **DocumentViewer** - معاينة PDF (PDF.js integration)
5. **WorkflowTracker** - تتبع مراحل العمل (4 stages visual)
6. **NotificationBell** - جرس الإشعارات (real-time updates)
7. **SearchBar** - بحث عام في النظام (autocomplete)

#### لماذا Livewire لهذه Components فقط؟
- تحتاج **real-time updates** (polling/events)
- تحتاج **interactivity** بدون page reload
- لا تحتاج **SEO** (صفحات داخلية محمية)
- تحتاج **form validation** تفاعلية

#### الصفحات الأخرى = Blade عادي:
- Dashboard (widgets ثابتة، لا تحتاج polling)
- Settings (نماذج بسيطة)
- Login/Register (Laravel Breeze - already optimized)
- Archive (صفحة قراءة فقط)
- Reports (صفحات ثابتة)
- User Management (Filament - if used)

#### قاعدة:
> إذا لم تكن الصفحة تحتاج **تحديثات فورية** أو **interactivity معقدة** → استخدم Blade عادي

---

### 1️⃣ المصادقة (1 واجهة)
```
/تسجيل-الدخول
- Laravel Breeze Authentication
- OAuth Manus (يضاف لاحقاً)
- إعادة توجيه للواجهة الرئيسية بعد تسجيل الدخول
```

### 2️⃣ لوحة التحكم (1 واجهة)
```
/
- الإحصائيات (Widgets):
  - عدد المهام النشطة
  - عدد المستندات غير المؤرشفة
  - المهام المستحقة اليوم
  
- الإشعارات (🔔):
  - عدد الإشعارات غير المقروءة
  - قائمة آخر 5 إشعارات (Livewire Component)
  
- روابط سريعة:
  - إنشاء مهمة
  - رفع مستند
  - البحث
```

### 3️⃣ المهام (3 واجهات)
```
/المهام
- جدول/بطاقات المهام (Livewire: TaskTable)
- الفلاتر:
  - المفضلة الشخصية (⭐)
  - الحالة (status)
  - الأولوية (priority)
  - البحث
- زر "إنشاء مهمة جديدة" (Modal)

/المهام/{id}
- معلومات المهمة:
  - العنوان، الوصف، الأولوية، التاريخ
  
- مراحل Workflow:
  - عرض المراحل الأربع (Livewire: WorkflowStageCard)
  - الحالة الحالية (pending/inProgress/completed)
  - اسم المسؤول عن كل مرحلة
  - زر "إنهاء المرحلة" (إذا كان المستخدم مسؤول)
  
- المستندات المرتبطة:
  - قائمة المستندات
  - زر "ربط مستند"
  - النقر على مستند → صفحة تفاصيل المستند
```

### 4️⃣ المستندات (3 واجهات)
```
/رفع-مستند
- نموذج رفع (Livewire: DocumentUpload):
  - اختيار الملف (drag & drop أو زر)
  - العنوان (مطلوب)
  - الوصف (اختياري)
  - النوع: وارد/صادر
  - المهمة المرتبطة (dropdown)
  - الوسوم (multi-select)
  
- التحقق:
  - حجم الملف (≤ 25MB)
  - نوع الملف (PDF/Word/Excel)
  - عرض progress bar أثناء الرفع (مرحلتان منفصلتان):
  - **1. رفع من المتصفح → Server (Client-side)**: استخدام `wire:model` مع `UploadProgress` event
  - **2. معالجة في Queue (Server-side)**: استخدام Laravel Echo + Redis Broadcasting (أو `wire:poll` بسيط في MVP)

/المستندات
- الفلاتر:
  - النوع: وارد/صادر
  - الحالة: مؤرشف/غير مؤرشف
  - المفضلة الشخصية (⭐)
  - البحث
  
- الجدول:
  - العنوان، النوع، التاريخ، الحجم
  - زر "تفاصيل" → صفحة التفاصيل

/المستندات/{id}
- معاينة المستند (PDF.js - Livewire: DocumentViewer)
- الأزرار:
  - [📥 تنزيل]
  - [⬆️ رفع إصدار جديد] (إذا كان في مرحلة مسموحة)
  
- التبويبات:
  1. إصدارات: قائمة جميع الإصدارات
  2. تعليقات: عرض وإضافة تعليقات
  3. سجل: audit log للمستند
  4. مراحل: عرض حالة workflow
  
- معلومات:
  - العنوان، الوصف، التاريخ، الحجم
  - الوسوم
```

### 5️⃣ البحث والأرشيف (2 واجهات)
```
/بحث
- حقل البحث
- البحث المتقدم:
  - النوع
  - الحالة
  - التاريخ
  - الوسوم
  
- النتائج:
  - مرتبة حسب الصلة (relevance)
  - عرض: العنوان، الوصف، التاريخ
  - النقر → صفحة التفاصيل

/الأرشيف
- عرض المجلدات:
  - شجرة المجلدات
  - المجلدات المقفلة (🔒)
  
- عرض المستندات في المجلد
- البحث داخل الأرشيف
```

### 6️⃣ الإدارة (4 واجهات)
```
/المستخدمين (Admin only)
- Filament Resource أو Livewire Table:
  - جدول المستخدمين
  - إضافة/تعديل/حذف
  - تغيير الأدوار (Spatie)
  - الفلاتر والبحث

/الإعدادات
- إدارة الوسوم (Tags)
- الإعدادات العامة

/الإعدادات/البريد
- إعدادات البريد (SMTP)

/الملف-الشخصي
- معلومات المستخدم
- تغيير كلمة المرور
```

### 7️⃣ إضافية (3 واجهات)
```
/مشاركة
- إنشاء رابط مشاركة
- إعدادات المشاركة:
  - صلاحيات
  - تاريخ الانتهاء

/مشترك/{token}
- عرض المستند (بدون تسجيل دخول)
- الأزرار حسب الصلاحيات:
  - [📥 تنزيل] (إن كان can_download = true)
  - [💬 تعليق] (إن كان can_comment = true)

/Laravel Horizon (Admin only)
- مراقبة Queue Jobs
- إحصائيات Redis
```

---

## 3️⃣ المتطلبات غير الوظيفية (Non-Functional Requirements)

### 3.1 الأداء (Performance)

```
- وقت تحميل الصفحة: < 2 ثانية
- وقت رفع ملف (25MB): < 30 ثانية (مع Queue)
- وقت البحث: < 1 ثانية
- دعم 100+ مستخدم متزامن
- Cache للاستعلامات المتكررة (Redis)
- Eager Loading لتجنب N+1 queries
```

### 3.2 الأمان (Security)

```
- ✅ HTTPS فقط (في الإنتاج)
- ✅ Laravel Breeze Authentication
- ✅ CSRF Protection (Laravel افتراضي)
- ✅ Signed Routes للمشاركة
- ✅ Policy Classes (Spatie Permission)
- ✅ S3 Signed URLs للملفات
- ✅ File upload validation (النوع، الحجم، الاسم)
- ✅ Rate limiting على API
- ✅ SQL Injection protection (Eloquent ORM)
- ✅ XSS protection (Blade escaping)

#### تشفير الملفات في S3:
- **S3 Server-Side Encryption (SSE-S3)**: جميع الملفات المرفوعة يجب تشفيرها تلقائياً باستخدام AES-256
- **Encryption at Rest**: تفعيل Default Encryption على S3 Bucket
- **Encryption in Transit**: استخدام HTTPS فقط للرفع والتحميل
- **التكوين المطلوب**:
  ```php
  // في config/filesystems.php
  's3' => [
      'driver' => 's3',
      'encryption' => 'AES256', // إجباري
      'ServerSideEncryption' => 'AES256',
      'options' => [
          'ServerSideEncryption' => 'AES256',
      ],
  ]
  ```

#### فحص الفيروسات:
- **ClamAV Integration**: جميع الملفات المرفوعة يجب فحصها قبل الحفظ في S3
- **Quarantine**: الملفات المشبوهة تُعزل في مجلد مؤقت ولا تُرفع
- **User Notification**: إشعار المستخدم إذا فشل الفحص
- **Admin Alert**: تنبيه الـAdmin إذا تم اكتشاف malware
- **التكامل المطلوب**:
  ```bash
  # تثبيت ClamAV
  sudo apt-get install clamav clamav-daemon
  
  # Laravel Package
  composer require xenolope/quahog
  ```

#### Rate Limiting:
- **رفع الملفات**: 10 ملفات / ساعة / مستخدم
- **API Requests**: 100 طلب / دقيقة / IP
- **تسجيل الدخول**: 5 محاولات فاشلة / 15 دقيقة
- **Throttle Response**: 429 Too Many Requests مع Retry-After header
```

### 3.3 التوافقية (Compatibility)

```
- المتصفحات:
  - Chrome (آخر نسختين)
  - Firefox (آخر نسختين)
  - Safari (آخر نسختين)
  - Edge (آخر نسختين)
  
- الأجهزة:
  - Desktop (≥ 1024px)
  - Tablet (≥ 768px)
  - Mobile (≥ 320px) - responsive design
```

### 3.4 إمكانية الوصول (Accessibility)

```
- دعم RTL (العربية)
- لوحة مفاتيح (keyboard navigation)
- Screen readers (ARIA labels)
- Contrast ratios (WCAG AA)
```

---

## 4️⃣ Stack التقنيات (Technology Stack)

### Backend:
```
Laravel 11 (PHP 8.2+)
├─ Laravel Breeze (Authentication)
├─ Spatie Laravel-Permission (Roles)
└─ Custom State Machine (Workflow)
```

### Frontend:
```
Livewire 3 (Server-side Components)
├─ Alpine.js (مدمج مع Livewire)
└─ Tailwind CSS (Styling)
```

### Database & Storage:
```
PostgreSQL 14+ (Database)
Redis (Cache + Queue + Session)
Amazon S3 (File Storage)
```

### Additional:
```
Laravel Horizon (Queue Monitoring)
PDF.js (PDF Viewer)
Meilisearch (Search - اختياري)
Tesseract OCR (OCR - اختياري)
```

---

## 5️⃣ قواعد التصميم (Design Guidelines)

### 5.1 حالات الواجهة الإلزامية

يجب تصميم وتنفيذ هذه الحالات لكل واجهة:

#### Empty State (حالة فارغة):
- **متى**: عندما لا توجد بيانات (مهام، مستندات، إشعارات)
- **التصميم**:
  - أيقونة مناسبة (Heroicon)
  - نص توضيحي: "لا توجد مهام حالياً"
  - زر إجراء: "إنشاء مهمة جديدة"
- **مثال**:
  ```html
  <div class="text-center py-12">
    <svg class="mx-auto h-12 w-12 text-gray-400">...</svg>
    <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد مهام</h3>
    <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء مهمة جديدة</p>
    <button class="mt-6">إنشاء مهمة</button>
  </div>
  ```

#### Loading State (جارٍ التحميل):
- **متى**: أثناء تحميل البيانات أو معالجة طلب
- **التصميم**:
  - Skeleton screens للجداول (shimmer effect)
  - Spinner للأزرار
  - Progress bar لرفع الملفات
- **مثال Livewire**:
  ```html
  <div wire:loading class="fixed inset-0 bg-gray-500 bg-opacity-75">
    <div class="flex items-center justify-center h-screen">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2"></div>
    </div>
  </div>
  ```

#### Error State (حالة خطأ):
- **متى**: عند فشل طلب أو validation
- **التصميم**:
  - Toast notification (أحمر)
  - رسالة واضحة: "فشل رفع الملف: حجم الملف أكبر من المسموح"
  - زر إعادة المحاولة
- **مثال**:
  ```html
  @if (session('error'))
    <div class="rounded-md bg-red-50 p-4 mb-4">
      <div class="flex">
        <svg class="h-5 w-5 text-red-400">...</svg>
        <p class="text-sm text-red-800">{{ session('error') }}</p>
      </div>
    </div>
  @endif
  ```

#### Unauthorized State (غير مصرح):
- **متى**: عند محاولة الوصول لصفحة بدون صلاحية
- **التصميم**:
  - صفحة 403 مخصصة
  - رسالة: "ليس لديك صلاحية للوصول لهذه الصفحة"
  - زر العودة للصفحة الرئيسية

### 5.2 Icons System

#### المكتبة المعتمدة: **Heroicons**

Heroicons هي مكتبة icons مفتوحة المصدر من Tailwind Labs.

**المزايا**:
- ✅ تصميم متناسق مع Tailwind CSS
- ✅ أحجام متعددة (outline, solid, mini)
- ✅ SVG نظيف وخفيف
- ✅ Laravel Blade Components جاهزة

#### التثبيت:
```bash
# عبر NPM (للـ JS)
npm install @heroicons/react

# أو Laravel Package (موصى به)
composer require blade-ui-kit/blade-heroicons
```

#### الاستخدام في Blade:
```blade
<!-- Outline (للأزرار، القوائم، borders) -->
<x-heroicon-o-document class="w-5 h-5 text-gray-500" />
<x-heroicon-o-folder class="w-5 h-5" />
<x-heroicon-o-bell class="w-5 h-5" />

<!-- Solid (للـ badges، notifications، filled buttons) -->
<x-heroicon-s-bell class="w-4 h-4 text-white" />
<x-heroicon-s-star class="w-4 h-4 text-yellow-500" />
<x-heroicon-s-check-circle class="w-4 h-4 text-green-600" />

<!-- Mini (20x20 - للـ inline icons) -->
<x-heroicon-m-check class="w-5 h-5" />
```

#### الأحجام القياسية:

| الحجم | Tailwind | الاستخدام | أمثلة |
|-------|----------|-----------|-------|
| **xs** | `w-3 h-3` (12px) | Badges, Tags | Status indicators |
| **sm** | `w-4 h-4` (16px) | Table actions, Small buttons | Edit, Delete icons |
| **md** | `w-5 h-5` (20px) | **الافتراضي** - Buttons, Forms | Primary actions |
| **lg** | `w-6 h-6` (24px) | Page headers, Cards | Section icons |
| **xl** | `w-8 h-8` (32px) | Empty states, Hero sections | Large illustrations |
| **2xl** | `w-12 h-12` (48px) | Dashboard widgets | Feature highlights |

#### الألوان (Semantic Colors):

| اللون | Tailwind | الاستخدام | أمثلة |
|-------|----------|-----------|-------|
| **Primary** | `text-blue-600` | أزرار رئيسية، روابط | Edit, View |
| **Success** | `text-green-600` | إنجاز، تأكيد، موافقة | Complete, Approve |
| **Warning** | `text-yellow-600` | تحذيرات، انتباه | Pending, Review |
| **Danger** | `text-red-600` | حذف، رفض، أخطاء | Delete, Reject |
| **Gray** | `text-gray-500` | محايد، ثانوي | Info, Metadata |
| **White** | `text-white` | على خلفيات داكنة | Dark buttons |

#### أمثلة عملية:

**1. زر مع Icon**:
```blade
<button class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded">
    <x-heroicon-o-plus class="w-5 h-5" />
    إنشاء مهمة
</button>
```

**2. Table Actions**:
```blade
<div class="flex gap-2">
    <button class="text-blue-600 hover:text-blue-800">
        <x-heroicon-o-pencil class="w-4 h-4" />
    </button>
    <button class="text-red-600 hover:text-red-800">
        <x-heroicon-o-trash class="w-4 h-4" />
    </button>
</div>
```

**3. Status Badge**:
```blade
<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-green-100 text-green-800">
    <x-heroicon-s-check-circle class="w-4 h-4" />
    مكتمل
</span>
```

**4. Empty State**:
```blade
<div class="text-center py-12">
    <x-heroicon-o-document-text class="w-12 h-12 mx-auto text-gray-400" />
    <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد مستندات</h3>
    <p class="mt-1 text-sm text-gray-500">ابدأ برفع مستند جديد</p>
</div>
```

#### Icons الأساسية للمشروع:

**Documents**:
- `document-text` - مستند
- `folder` - مجلد
- `archive-box` - أرشيف
- `arrow-down-tray` - تنزيل
- `arrow-up-tray` - رفع

**Actions**:
- `pencil` - تعديل
- `trash` - حذف
- `check` - تأكيد
- `x-mark` - إلغاء
- `plus` - إضافة

**Status**:
- `check-circle` - مكتمل
- `clock` - قيد الانتظار
- `exclamation-triangle` - تحذير
- `x-circle` - خطأ

**Navigation**:
- `home` - الرئيسية
- `magnifying-glass` - بحث
- `bell` - إشعارات
- `user` - المستخدم
- `cog-6-tooth` - إعدادات

**Workflow**:
- `arrow-right` - التالي
- `arrow-path` - تحديث
- `paper-clip` - مرفق
- `chat-bubble-left` - تعليق
- `eye` - معاينة

#### قواعد الاستخدام:
1. ✅ استخدم **Outline** للأزرار والقوائم (default)
2. ✅ استخدم **Solid** للـ badges والحالات النشطة
3. ✅ استخدم الحجم **md (w-5 h-5)** افتراضياً
4. ✅ استخدم **Semantic Colors** حسب السياق
5. ❌ لا تخلط بين مكتبات icons مختلفة
6. ❌ لا تستخدم أحجام عشوائية (التزم بالـ scale)

#### التوثيق الكامل:
https://heroicons.com

### 5.3 Responsive Tables

#### الحل المعتمد: **Cards على Mobile**

Tables على شاشات صغيرة صعبة القراءة. الحل: تحويل كل صف إلى Card.

##### Desktop (≥768px):
```html
<table class="min-w-full divide-y divide-gray-300 hidden md:table">
  <thead>
    <tr>
      <th>العنوان</th>
      <th>الحالة</th>
      <th>التاريخ</th>
      <th>الإجراءات</th>
    </tr>
  </thead>
  <tbody>
    @foreach($tasks as $task)
      <tr>
        <td>{{ $task->title }}</td>
        <td>{{ $task->status }}</td>
        <td>{{ $task->created_at->diffForHumans() }}</td>
        <td>
          <button>عرض</button>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
```

##### Mobile (<768px):
```html
<!-- تتحول كل صف إلى Card -->
<div class="space-y-4 md:hidden">
  @foreach($tasks as $task)
    <div class="bg-white shadow rounded-lg p-4">
      <!-- Header -->
      <div class="flex justify-between items-start mb-2">
        <h3 class="font-semibold text-gray-900">{{ $task->title }}</h3>
        <span class="badge badge-{{ $task->status_color }}">
          {{ $task->status_label }}
        </span>
      </div>
      
      <!-- Description -->
      <p class="text-sm text-gray-600 mb-3 line-clamp-2">
        {{ $task->description }}
      </p>
      
      <!-- Metadata -->
      <div class="flex justify-between items-center text-xs text-gray-500 mb-3">
        <div class="flex items-center gap-1">
          <x-heroicon-o-user class="w-3 h-3" />
          {{ $task->creator->name }}
        </div>
        <div class="flex items-center gap-1">
          <x-heroicon-o-clock class="w-3 h-3" />
          {{ $task->created_at->diffForHumans() }}
        </div>
      </div>
      
      <!-- Actions -->
      <div class="flex gap-2">
        <button class="flex-1 btn-primary">عرض</button>
        <button class="btn-secondary">
          <x-heroicon-o-ellipsis-horizontal class="w-5 h-5" />
        </button>
      </div>
    </div>
  @endforeach
</div>
```

#### الأعمدة المخفية على Mobile:

**إخفاء**:
- ❌ Created By (يظهر في metadata)
- ❌ Updated At (غير ضروري)
- ❌ الأعمدة الثانوية (Department، Category، etc.)

**الإبقاء**:
- ✅ Title (في Header)
- ✅ Status (Badge)
- ✅ Date (في metadata كـ relative time)
- ✅ Actions (buttons في أسفل Card)

#### Actions على Mobile:

**Desktop**: أزرار منفصلة في عمود Actions

**Mobile**: خياران:

**Option 1: Expanded Buttons**
```html
<div class="flex gap-2">
  <button class="flex-1 btn-primary">عرض</button>
  <button class="flex-1 btn-secondary">تعديل</button>
</div>
```

**Option 2: Kebab Menu (للـ actions الكثيرة)**
```html
<div class="dropdown">
  <button class="btn-icon">
    <x-heroicon-o-ellipsis-horizontal class="w-5 h-5" />
  </button>
  <div class="dropdown-menu">
    <a href="#">عرض</a>
    <a href="#">تعديل</a>
    <a href="#">حذف</a>
  </div>
</div>
```

#### Empty State (Responsive):
```html
<div class="text-center py-12">
  <x-heroicon-o-document-text class="w-12 h-12 md:w-16 md:h-16 mx-auto text-gray-400" />
  <h3 class="mt-2 text-sm md:text-base font-medium text-gray-900">لا توجد مهام</h3>
  <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء مهمة جديدة</p>
  <button class="mt-6 btn-primary">إنشاء مهمة</button>
</div>
```

#### Pagination (Responsive):
```html
<!-- Desktop -->
<div class="hidden md:flex items-center justify-between">
  <div>عرض 1 إلى 20 من 100 نتيجة</div>
  <div class="flex gap-2">
    <button>السابق</button>
    <button>1</button>
    <button>2</button>
    <button>3</button>
    <button>التالي</button>
  </div>
</div>

<!-- Mobile -->
<div class="flex md:hidden items-center justify-between">
  <button>السابق</button>
  <span>1 / 5</span>
  <button>التالي</button>
</div>
```

#### Performance Tips:

1. **Lazy Loading Images**: إذا كانت Cards تحتوي على صور
```html
<img src="{{ $task->thumbnail }}" loading="lazy" alt="{{ $task->title }}">
```

2. **Virtual Scrolling**: للقوائم الطويلة جداً (1000+ items)
```bash
npm install @tanstack/vue-virtual
# أو استخدم Livewire Pagination (موصى به)
```

3. **Skeleton Loading**:
```html
@foreach(range(1, 5) as $i)
  <div class="bg-white shadow rounded-lg p-4 animate-pulse">
    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
  </div>
@endforeach
```

#### قواعد Responsive:

| Breakpoint | الاستخدام | Classes |
|------------|-----------|---------|
| **sm** | ≥640px | Phones (landscape) |
| **md** | ≥768px | **Tablets** - تحول من Cards → Table |
| **lg** | ≥1024px | Small desktops |
| **xl** | ≥1280px | Large desktops |
| **2xl** | ≥1536px | Very large screens |

**القاعدة الأساسية**:
- `md:hidden` → يخفي على الشاشات ≥768px (للـ Cards)
- `hidden md:table` → يظهر Table فقط على الشاشات ≥768px

#### Testing Responsive:

1. **Chrome DevTools**: F12 → Toggle device toolbar (Ctrl+Shift+M)
2. **اختبر على**:
   - iPhone SE (375px)
   - iPad (768px)
   - Desktop (1280px)
3. **تأكد من**:
   - Cards تظهر بشكل صحيح على mobile
   - Table تظهر بشكل صحيح على desktop
   - Pagination تعمل على كلا الحالتين
   - Touch targets ≥44px على mobile

---

### 5.1 الألوان (Tailwind CSS)

| اللون | Hex | Tailwind | الاستخدام |
|-------|-----|----------|-----------|
| Primary | #4C7FF1 | bg-[#4C7FF1] | الأزرار الأساسية |
| Secondary | #4ECDC4 | bg-[#4ECDC4] | عناصر ثانوية |
| Success | #1FCDC7 | bg-[#1FCDC7] | حالة مكتمل |
| Success BG | #E8F9F8 | bg-[#E8F9F8] | خلفية Badge |
| Success Text | #065F46 | text-[#065F46] | نص Badge |
| Warning | #FFC23A | bg-[#FFC23A] | حالة مسودة |
| Warning BG | #FFF8E8 | bg-[#FFF8E8] | خلفية Badge |
| Warning Text | #92400E | text-[#92400E] | نص Badge |
| Error | #FF6AF2 | bg-[#FF6AF2] | حالة خطأ |
| Error BG | #FFE8FD | bg-[#FFE8FD] | خلفية Badge |
| Error Text | #991B1B | text-[#991B1B] | نص Badge |

#### مثال Badge نشط:
```xml
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#E8F9F8] text-[#0891B2]">
    نشط
</span>
```

### 5.2 Typography
```
الخط: Noto Sans Arabic (RTL Support)
الأحجام:
  - H1: 32px (text-3xl)
  - H2: 24px (text-2xl)
  - H3: 20px (text-xl)
  - Body: 16px (text-base)
  - Small: 14px (text-sm)
```

### 5.3 Spacing (Tailwind CSS)
```
استخدام Tailwind default spacing scale
```

### 5.4 Components
```
الأزرار: 120×40px (w-[120px] h-10)
الجداول: row height 56px (h-14)
المنبثقات: 500×600px (w-[500px] h-[600px])
```

### 5.5 Cards Design

#### Card Style Standard:
```xml
<div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
    <!-- المحتوى -->
</div>
```

#### قواعد Cards:
- ✅ rounded-xl - زوايا ناعمة 16px
- ✅ border-gray-100 - حواف رقيقة جداً
- ✅ shadow-sm - ظل خفيف
- ✅ hover:shadow-md - ظل أوضح عند hover
- ✅ p-6 - padding داخلي 24px
- ✅ transition-shadow duration-200 - انتقال سلس

#### Stats Cards (بطاقات الإحصائيات):
```xml
<div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-2">المهام النشطة</p>
            <p class="text-3xl font-bold text-gray-900">24</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-[#E8F1FC] flex items-center justify-center">
            <svg class="w-6 h-6 text-[#4C7FF1]" fill="currentColor" viewBox="0 0 20 20">
                <!-- أيقونة -->
            </svg>
        </div>
    </div>
</div>
```

#### ملاحظات:
- خلفية الأيقونة: استخدم نفس لون الـ Badge المناسب (Primary Light, Success BG, Warning BG, إلخ)
- الأيقونة: استخدم Heroicons من `blade-ui-kit/blade-heroicons`

### 5.6 RTL Support
```
- دعم كامل للعربية
- Tailwind CSS RTL plugin
- Blade components تدعم RTL
```

---

## 6️⃣ تدفقات المستخدم (User Flows)

### سيناريو 1: رفع مستند
```
لوحة التحكم → [📤رفع مستند] → 
نموذج رفع (Livewire: DocumentUpload) → 
اختيار ملف + معلومات → 
حفظ → 
Queue Job (ProcessDocumentJob) → 
رفع إلى S3 → 
قائمة 📨وارد
```

### سيناريو 2: عملية مستند كاملة
```
لوحة التحكم (🔔) → 
المهام → 
تفاصيل المهمة → 
[مستند] → 
تفاصيل المستند (#8):
  📋مراحل: Draft(محمد✅) → Review1(رنيم⏳) → Proofread → Final
  → [📥تنزيل] → 
  تعديل محلي → 
  [⬆️رفع إصدار] → 
  [✅إنهاء المرحلة] (Livewire: WorkflowStageCard) →
  تلقائي → 
  أرشفة (ArchiveTaskJob) → 
  🔒أرشيف
```

### سيناريو 3: بحث ومشاركة
```
🔍 البحث → 
فلتر ⭐ → 
تفاصيل المستند (#8) → 
[📋مرحلة الحالية] → 
مشاركة → 
إنشاء Signed Route → 
رابط مشاركة
```

---

## ✅ قائمة التحقق قبل البدء (Pre-Build Checklist)

### قبل البدء بالبرمجة، تأكد من:

- [ ] ✅ قراءة هذا المستند بالكامل
- [ ] ✅ الموافقة على جميع المتطلبات
- [ ] ✅ فهم Stack التقنيات
- [ ] ✅ إعداد البيئة (Laravel, PostgreSQL, Redis, S3)
- [ ] ✅ إعداد OAuth Manus
- [ ] ✅ إنشاء قاعدة البيانات الفارغة
- [ ] ✅ إعداد Git repository
- [ ] ✅ إنشاء `.env.example` مع جميع المتغيرات
- [ ] ✅ الموافقة على Schema قاعدة البيانات
- [ ] ✅ الموافقة على تصميم الواجهات

---

## 📌 ملاحظات مهمة

### ⚠️ قبل البدء:
1. **لا تبدأ البرمجة** قبل إكمال هذا المستند
2. **راجع كل بند** مع الفريق
3. **حدد الأولويات** إذا كان هناك وقت محدود
4. **استخدم هذا المستند** كمرجع أثناء التطوير

### ✅ أثناء التطوير:
1. اتبع المتطلبات بدقة
2. استخدم Transactions للعمليات الحرجة
3. اختبر كل ميزة قبل الانتقال للتالية
4. اكتب Tests مع الكود

### 🎯 الهدف:
بناء نظام **قوي، آمن، سهل الاستخدام، وسهل الصيانة** بدون أخطاء كبيرة.

---

**هذا المستند يجب أن يكون المرجع الأساسي لكل التطوير!**
