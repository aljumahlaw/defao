# جدول محتويات الوثائق

## Setup & Configuration

- [إعداد المشروع](general/README.md) - دليل الإعداد الأساسي
- [النشر](general/DEPLOYMENT_CHECKLIST.md) - قائمة تحقق للنشر
- [Railway Deployment](general/railway-setup.md) - النشر على Railway
- [مشروع A to Z](general/PROJECT_A_TO_Z.md) - دليل شامل للمشروع

## Features

- [خرائط النظام](system-maps/README.md) - خرائط تفصيلية للنظام
  - [دورة حياة الوثيقة](system-maps/document-lifecycle/)
  - [الهيكل التنظيمي](system-maps/organizational-chart/)
  - [رحلات المستخدم](system-maps/user-journeys/)
  - [سير العمل](system-maps/workflow-swimlane/)

## Admin

- لوحة الإدارة: `/admin/users` (Admin only)
  - الاسم: `admin.users`
  - إضافة موظفين جدد
  - تفعيل/تعطيل المستخدمين

## Security

- **Middleware:**
  - `user.active` - التحقق من حالة المستخدم النشطة
  - `admin` - التحقق من صلاحيات المدير
  - `force-password` - إجبار تغيير كلمة المرور للمستخدمين الجدد

- **Protected Fields:**
  - `role` - محمي من التعديل عبر Profile
  - `is_active` - محمي من التعديل عبر Profile
  - `password_changed_at` - تتبع تغيير كلمة المرور

## Reports

- [التقارير](reports/README.md) - تقارير النظام
  - [تقرير التشخيص](reports/DIAGNOSTIC_REPORT.md)
  - [الفحص النهائي](reports/FINAL_HEALTH_CHECK.md)
  - [تقرير Phase 0](reports/DIAGNOSTIC_REPORT_PHASE0.md)

## Archive

- [الأرشيف](_archive/) - وثائق قديمة مؤرشفة

