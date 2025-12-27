# DEFAO - نظام إدارة الوثائق القانونية

## نظرة عامة

**DEFAO** هو نظام إدارة الوثائق والمهام القانونية مبني على Laravel 11 + Livewire. يوفر النظام إدارة شاملة للوثائق القانونية، المهام، سير العمل، والأرشيف.

## المتطلبات

- PHP 8.3+
- Composer
- Node.js & NPM
- MySQL 8.0+ (الافتراضي والمُوصى به) أو PostgreSQL/SQLite (اختياري)

## التشغيل المحلي

### 1. تثبيت الاعتماديات

```bash
composer install
npm install
```

### 2. إعداد البيئة

```bash
cp .env.example .env
php artisan key:generate
```

### 3. قاعدة البيانات

1. قم بإنشاء قاعدة بيانات MySQL
2. قم بتكوين قاعدة البيانات في `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=اسم_قاعدة_البيانات
   DB_USERNAME=اسم_المستخدم
   DB_PASSWORD=كلمة_المرور
   ```
3. قم بتشغيل migrations و seeders:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

### 4. البناء والتشغيل

```bash
npm run build
php artisan serve
```

افتح المتصفح على: `http://127.0.0.1:8000`

## الأوامر الأساسية

```bash
# Migration
php artisan migrate              # تشغيل migrations
php artisan migrate:fresh        # إعادة قاعدة البيانات بالكامل
php artisan migrate:rollback     # التراجع عن آخر migration

# Seeding
php artisan db:seed              # تشغيل seeders
php artisan migrate:fresh --seed # إعادة DB + seed

# الخادم
php artisan serve                # تشغيل الخادم المحلي
php artisan serve --port=8001    # على منفذ محدد

# Cache
php artisan view:clear           # مسح compiled views
php artisan route:clear          # مسح routes cache
php artisan config:clear         # مسح config cache
php artisan event:clear          # مسح events cache
```

> ملاحظة: تجنّب `php artisan optimize:clear` إلا عند الحاجة لأنه يمسح عدة أنواع كاش وقد يفرّغ الكاش الافتراضي.

## الوثائق

- [جدول المحتويات](_index.md)
- [التغييرات](_changelog.md)
- [الإعداد](general/README.md)
- [التقارير](reports/README.md)
- [خرائط النظام](system-maps/README.md)

## الدعم

للأسئلة أو المساعدة، راجع الوثائق في مجلد `docs/`.

