# سجل التغييرات

## 2025-12-24

### إضافة
- لوحة إدارة المستخدمين (Admin Panel)
  - إضافة موظفين جدد مع إرسال رابط تعيين كلمة المرور
  - تفعيل/تعطيل المستخدمين
  - البحث والفلترة
- Middleware ForcePasswordReset
  - إجبار المستخدمين الجدد على تغيير كلمة المرور
- حماية حقول Profile من التعديل
  - `role`, `is_active`, `password_changed_at` محمية

### تعديل
- تحديث ProfileController لدعم تحديث `password_changed_at`
- إضافة route `/admin/users` مع middleware `admin`
- تحديث Sidebar لإضافة "إدارة الموظفين" للمديرين فقط

## 2025-12-22

### إضافة
- نظام الأدوار والصلاحيات
- Middleware EnsureUserIsActive
- Migration لإضافة `password_changed_at`

### تعديل
- تحديث User model باستخدام `$guarded`
- تحديث Profile component لمنع تعديل role

## 2025-12-21

### إضافة
- إضافة رقم القضية للوثائق
- نظام الأرشيف

## 2025-12-20

### إضافة
- نظام المهام (Tasks)
- نظام الوثائق (Documents)
- نظام سير العمل (Workflow)

