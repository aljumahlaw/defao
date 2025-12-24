# ✅ قائمة التحقق قبل البدء بالبناء - Pre-Build Checklist

**الإصدار**: 2.0 (Laravel Stack)  
**التاريخ**: $(date)  
**Stack**: Laravel 11 + Livewire 3 + Breeze + Spatie + Custom Workflow + Redis + S3

---

## 🎯 الهدف

هذه القائمة تضمن عدم البدء بالبناء قبل التأكد من:
- ✅ فهم جميع المتطلبات
- ✅ إعداد البيئة بشكل صحيح
- ✅ تجنب الأخطاء الشائعة
- ✅ وجود خطة واضحة

---

## 📋 القائمة

### المرحلة 1: قراءة الوثائق (Documentation Review)

#### الوثائق المطلوبة:
- [ ] ✅ قراءة `00_REQUIREMENTS_DOCUMENT.md` بالكامل
- [ ] ✅ قراءة `01_ARCHITECTURE_DESIGN.md` بالكامل
- [ ] ✅ قراءة `02_DATABASE_SCHEMA.md` بالكامل
- [ ] ✅ قراءة `00_STACK_FINAL_RECOMMENDATION.md`
- [ ] ✅ قراءة `04_COMMON_MISTAKES_SOLUTIONS.md`
- [ ] ✅ فهم جميع المتطلبات
- [ ] ✅ الموافقة على Stack التقنيات

**⚠️ لا تبدأ البرمجة قبل إكمال هذه المرحلة!**

---

### المرحلة 2: إعداد البيئة (Environment Setup)

#### Laravel & PHP:
- [ ] ✅ تثبيت PHP 8.2+ مع Extensions:
  - PDO
  - PDO_PGSQL
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PCRE
  - Tokenizer
  - XML
  - Redis extension
- [ ] ✅ تثبيت Composer
- [ ] ✅ إنشاء مشروع Laravel جديد: 
  ```bash
  composer create-project laravel/laravel document-management
  cd document-management
  ```
- [ ] ✅ التحقق من عمل Laravel: `php artisan serve`
- [ ] ✅ تثبيت Livewire 3 (يأتي مع Laravel 11)
- [ ] ✅ تثبيت Laravel Breeze: 
  ```bash
  composer require laravel/breeze --dev
  php artisan breeze:install blade
  npm install && npm run build
  ```
- [ ] ✅ تثبيت Spatie Permission:
  ```bash
  composer require spatie/laravel-permission
  php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
  ```
- [ ] ✅ Tailwind CSS (يأتي مع Breeze)
- [ ] ✅ تحديث نظام الألوان: راجع `00_REQUIREMENTS_DOCUMENT.md` قسم 5.1 للألوان الدقيقة
- [ ] ✅ Cards Design: استخدم `rounded-xl border-gray-100 shadow-sm` (قسم 5.5)
- [ ] ✅ Badge Classes: استخدم Helper Methods من `01_ARCHITECTURE_DESIGN.md` (TaskTable/DocumentTable)

#### قاعدة البيانات:
- [ ] ✅ تثبيت PostgreSQL 14+
- [ ] ✅ إنشاء قاعدة بيانات: `createdb document_management`
- [ ] ✅ تثبيت pgsql extension في PHP
- [ ] ✅ **تكوين PostgreSQL Connection Pool** ⚠️ مهم للإنتاج:
  ```bash
  sudo nano /etc/postgresql/14/main/postgresql.conf
  # زيادة max_connections:
  max_connections = 200  # من 100 إلى 200
  sudo systemctl restart postgresql
  ```
- [ ] ✅ التحقق من max_connections الجديد:
  ```bash
  psql -U postgres -c "SHOW max_connections;"
  # يجب أن يظهر: 200
  ```
- [ ] ✅ **(اختياري - موصى به للإنتاج) تثبيت PgBouncer**:
  ```bash
  sudo apt-get install pgbouncer
  # تكوين PgBouncer (راجع README_BUILD_GUIDE.md للتفاصيل الكاملة)
  # تحديث .env: DB_PORT=6432
  ```
- [ ] ✅ تحديث `.env`:
  ```env
  DB_CONNECTION=pgsql
  DB_HOST=127.0.0.1
  DB_PORT=5432  # أو 6432 إذا استخدمت PgBouncer
  DB_DATABASE=document_management
  DB_USERNAME=postgres
  DB_PASSWORD=your_password
  ```
- [ ] ✅ اختبار الاتصال: `php artisan migrate:status`
- [ ] ✅ إضافة Rate Limiting في `app/Providers/RouteServiceProvider.php`:
  ```php
  RateLimiter::for('uploads', function (Request $request) {
      return Limit::perHour(10)->by($request->user()->id);
  });
  ```

#### Redis:
- [ ] ✅ تثبيت Redis
- [ ] ✅ تشغيل Redis server
- [ ] ✅ **تفعيل Redis Persistence (AOF + RDB)** ⚠️ مهم لمنع فقدان Jobs:
  ```bash
  sudo nano /etc/redis/redis.conf
  # تفعيل:
  appendonly yes
  appendfilename "appendonly.aof"
  appendfsync everysec
  save 900 1
  save 300 10
  save 60 10000
  sudo systemctl restart redis
  ```
- [ ] ✅ تحديث `.env`:
  ```env
  CACHE_DRIVER=redis
  SESSION_DRIVER=redis
  QUEUE_CONNECTION=redis
  REDIS_HOST=127.0.0.1
  REDIS_PASSWORD=null
  REDIS_PORT=6379
  ```
- [ ] ✅ اختبار Redis: `php artisan tinker` → `Cache::put('test', 'value')`
- [ ] ✅ **التحقق من وجود ملف appendonly.aof** بعد إعادة تشغيل Redis

#### Laravel Horizon:
- [ ] ✅ تثبيت Laravel Horizon:
  ```bash
  composer require laravel/horizon
  php artisan horizon:install
  php artisan horizon:publish
  ```
- [ ] ✅ تحديث `.env`: `QUEUE_CONNECTION=redis`
- [ ] ✅ **تثبيت Supervisor لإدارة Horizon** (للإنتاج):
  ```bash
  sudo apt-get install supervisor
  sudo nano /etc/supervisor/conf.d/horizon.conf
  # إضافة تكوين Horizon
  sudo supervisorctl reread
  sudo supervisorctl update
  sudo supervisorctl start horizon
  ```
- [ ] ✅ **التحقق من Session Encryption** ⚠️ مهم للأمان:
  
  **في `config/session.php`**:
  ```php
  return [
      'encrypt' => true, // ✅ يجب أن تكون true (تشفير Session data)
      
      'cookie' => env('SESSION_COOKIE', 'laravel_session'),
      'secure' => env('SESSION_SECURE_COOKIE', true), // ✅ HTTPS فقط
      'http_only' => true, // ✅ منع JavaScript من الوصول
      'same_site' => 'lax', // ✅ حماية CSRF
  ];
  ```
  
  **في `.env.production`**:
  ```env
  SESSION_DRIVER=redis
  SESSION_ENCRYPT=true
  SESSION_SECURE_COOKIE=true
  ```
  
- [ ] ✅ اختبار: تأكد من أن Session data مشفرة في Redis:
  ```bash
  redis-cli
  > KEYS *session*
  > GET laravel_session:xxx  # يجب أن يظهر encrypted data (غير قابل للقراءة)
  ```

⚠️ **مهم**: Session encryption يحمي البيانات الحساسة (user data, CSRF tokens) إذا تم اختراق Redis.

#### Amazon S3:
- [ ] ✅ إنشاء حساب AWS (إن لم يكن موجود)
- [ ] ✅ إنشاء S3 Bucket
- [ ] ✅ **تفعيل Default Encryption على S3 Bucket (AES-256)** ⚠️ مهم للأمان
- [ ] ✅ إنشاء IAM User مع صلاحيات S3
- [ ] ✅ الحصول على Access Key ID & Secret Access Key
- [ ] ✅ تثبيت AWS SDK:
  ```bash
  composer require league/flysystem-aws-s3-v3 "^3.0"
  ```
- [ ] ✅ تحديث `config/filesystems.php` مع تشفير S3:
  ```php
  's3' => [
      'driver' => 's3',
      'encryption' => 'AES256',
      'options' => [
          'ServerSideEncryption' => 'AES256',
      ],
  ]
  ```
- [ ] ✅ تحديث `.env`:
  ```env
  FILESYSTEM_DISK=s3
  AWS_ACCESS_KEY_ID=your_key
  AWS_SECRET_ACCESS_KEY=your_secret
  AWS_DEFAULT_REGION=us-east-1
  AWS_BUCKET=your-bucket-name
  AWS_URL=https://your-bucket-name.s3.amazonaws.com
  ```
- [ ] ✅ اختبار S3: `php artisan tinker` → `Storage::disk('s3')->put('test.txt', 'test')`
- [ ] ✅ **التحقق من encryption headers في S3** (في AWS Console)
- [ ] ✅ **تكوين S3 CORS** لـ PDF.js:
  - S3 Console → Bucket → Permissions → CORS
  - إضافة AllowedOrigins (localhost + production domain)
  - AllowedMethods: GET, HEAD
- [ ] ✅ **إنشاء S3 Lifecycle Policies** (توفير التكلفة):
  - بعد 30 يوم → Intelligent-Tiering
  - بعد 90 يوم → Glacier Instant Retrieval
  - بعد 180 يوم → Glacier Flexible Retrieval
- [ ] ✅ تثبيت ClamAV لفحص الفيروسات:
  ```bash
  sudo apt-get install clamav clamav-daemon
  composer require xenolope/quahog
  ```

#### Meilisearch (اختياري):
- [ ] ✅ تثبيت Meilisearch
- [ ] ✅ تشغيل Meilisearch server
- [ ] ✅ تثبيت Laravel Scout:
  ```bash
  composer require laravel/scout
  ```
- [ ] ✅ تثبيت Meilisearch driver:
  ```bash
  composer require meilisearch/meilisearch-php
  ```
- [ ] ✅ تحديث `.env`:
  ```env
  SCOUT_DRIVER=meilisearch
  MEILISEARCH_HOST=http://127.0.0.1:7700
  MEILISEARCH_KEY=your_master_key
  ```

#### OAuth Manus (لاحقاً):
- [ ] ✅ الحصول على OAuth credentials
- [ ] ✅ إعداد Socialite أو package مشابه
- [ ] ✅ تحديث `.env`:
  ```env
  MANUS_CLIENT_ID=your_client_id
  MANUS_CLIENT_SECRET=your_client_secret
  MANUS_REDIRECT_URL=http://localhost/auth/manus/callback
  OWNER_OPEN_ID=your_open_id
  ```

---

### المرحلة 3: إعداد المشروع (Project Setup)

#### Git & Version Control:
- [ ] ✅ تهيئة Git repository
- [ ] ✅ إنشاء `.gitignore` (Laravel default موجود)
- [ ] ✅ إنشاء `.env.example` مع جميع المتغيرات المطلوبة
- [ ] ✅ Commit أولي: `git add . && git commit -m "Initial Laravel setup"`

#### Configuration:
- [ ] ✅ تحديث `config/app.php`:
  - `APP_NAME=Document Management`
  - `APP_URL`
  - `APP_LOCALE=ar` (للعربية)
  - `APP_TIMEZONE`
- [ ] ✅ تحديث `config/auth.php` (Breeze defaults)
- [ ] ✅ تحديث `config/filesystems.php` (S3)
- [ ] ✅ تحديث `config/queue.php` (Redis)
- [ ] ✅ تحديث `config/permission.php` (Spatie)

#### Dependencies:
- [ ] ✅ تثبيت جميع packages:
  ```bash
  composer require laravel/breeze --dev
  composer require spatie/laravel-permission
  composer require laravel/horizon
  composer require league/flysystem-aws-s3-v3
  composer require laravel/scout  # optional
  composer require meilisearch/meilisearch-php  # optional
  ```

---

### المرحلة 4: قاعدة البيانات (Database)

#### Migrations:
- [ ] ✅ إنشاء جميع Migrations حسب `02_DATABASE_SCHEMA.md`
- [ ] ✅ ترتيب Migrations بشكل صحيح (Foreign Keys بعد الجداول المرجعية)
- [ ] ✅ إضافة جميع Indexes
- [ ] ✅ إضافة جميع Constraints
- [ ] ✅ اختبار Migrations: `php artisan migrate:fresh`
- [ ] ✅ التحقق من الجداول في database

#### Models:
- [ ] ✅ إنشاء جميع Models:
  - User (HasRoles trait)
  - Task
  - Document
  - WorkflowStage
  - DocumentVersion
  - Comment
  - AuditLog
  - Folder
  - DocumentShare
  - Tag
- [ ] ✅ إضافة Relationships في Models
- [ ] ✅ إضافة Fillable/Casts
- [ ] ✅ إضافة Scopes (مثل `inProgress()`, `archived()`)

#### Enums:
- [ ] ✅ إنشاء Enums:
  - WorkflowStageEnum
  - TaskStatusEnum
  - DocumentTypeEnum

#### Seeders:
- [ ] ✅ إنشاء RoleSeeder (Spatie: admin, authorized, user)
- [ ] ✅ إنشاء UserSeeder (إن لزم)
- [ ] ✅ إنشاء SettingsSeeder (للإعدادات الافتراضية)
- [ ] ✅ اختبار Seeders: `php artisan db:seed`

---

### المرحلة 5: البنية الأساسية (Core Infrastructure)

#### Repositories:
- [ ] ✅ إنشاء Repository Interfaces
- [ ] ✅ إنشاء Repository Implementations (Eloquent)
- [ ] ✅ تسجيل Repositories في ServiceProvider

#### Services:
- [ ] ✅ إنشاء Service classes:
  - WorkflowService (Custom State Machine)
  - StorageService (S3)
  - NotificationService
  - ArchiveService

#### Policies:
- [ ] ✅ إنشاء Policy classes:
  - TaskPolicy
  - DocumentPolicy
  - UserPolicy
- [ ] ✅ تسجيل Policies في AuthServiceProvider
- [ ] ✅ كتابة جميع methods (view, create, update, delete, etc.)

#### Exceptions:
- [ ] ✅ إنشاء Custom Exceptions:
  - NotFoundException
  - ForbiddenException
  - ValidationException
- [ ] ✅ تحديث Handler.php للتعامل مع Exceptions

#### Middleware:
- [ ] ✅ إنشاء EnsureUserHasRole middleware (Spatie)
- [ ] ✅ تسجيل Middleware في Kernel.php

---

### المرحلة 6: Controllers & Routes

#### Routes:
- [ ] ✅ إنشاء جميع Routes في `routes/web.php`
- [ ] ✅ تنظيم Routes (groups, middleware)
- [ ] ✅ اختبار Routes: `php artisan route:list`

#### Controllers:
- [ ] ✅ إنشاء جميع Controllers:
  - DashboardController
  - TaskController
  - DocumentController
  - ArchiveController
  - UserController
  - SettingsController
  - ProfileController
  - ShareController
- [ ] ✅ كتابة Form Requests للـ Validation
- [ ] ✅ استخدام Policies في Controllers

---

### المرحلة 7: Livewire Components

#### Components:
- [ ] ✅ إنشاء Livewire Components:
  - Tasks/TaskTable
  - Tasks/WorkflowStageCard
  - Documents/DocumentUpload
  - Documents/DocumentTable
  - Documents/DocumentViewer (PDF.js)
  - Dashboard/NotificationCenter
  - Shared/FavoriteToggle
- [ ] ✅ كتابة Logic للـ Components
- [ ] ✅ إنشاء Blade views للـ Components
- [ ] ✅ اختبار Components

---

### المرحلة 8: Jobs & Queue

#### Jobs:
- [ ] ✅ إنشاء Jobs:
  - ProcessDocumentJob (رفع 25MB)
  - ArchiveTaskJob
  - SendNotificationJob
- [ ] ✅ إضافة Jobs للـ Queue
- [ ] ✅ اختبار Jobs: `php artisan queue:work` أو `php artisan horizon`

---

### المرحلة 9: Events & Listeners

#### Events:
- [ ] ✅ إنشاء Events:
  - DocumentUploaded
  - WorkflowStageCompleted
  - TaskCompleted

#### Listeners:
- [ ] ✅ إنشاء Listeners:
  - NotifyReviewers
  - MoveToNextStage
  - ArchiveTask

---

### المرحلة 10: Testing

#### Tests:
- [ ] ✅ إنشاء Unit Tests:
  - Models tests
  - Services tests
  - Repositories tests
- [ ] ✅ إنشاء Feature Tests:
  - Workflow tests
  - File upload tests
  - Authorization tests
- [ ] ✅ تشغيل Tests: `php artisan test`
- [ ] ✅ التحقق من Coverage: ≥ 70%

---

### المرحلة 11: Documentation

#### Code Documentation:
- [ ] ✅ إضافة PHPDoc comments على جميع Methods
- [ ] ✅ تحديث README.md
- [ ] ✅ إنشاء API documentation (إن لزم)

#### User Documentation:
- [ ] ✅ إنشاء دليل المستخدم (بالعربية)
- [ ] ✅ إنشاء screenshots أو فيديوهات (اختياري)

---

## 🚨 علامات الخطر (Red Flags)

### إذا ظهرت هذه العلامات، توقف وأصلحها:

- ❌ Migrations لا تعمل بشكل صحيح
- ❌ Foreign Keys فاشلة
- ❌ Indexes مفقودة
- ❌ Tests لا تمر
- ❌ S3 upload لا يعمل
- ❌ Queue jobs لا تعمل
- ❌ الصلاحيات لا تعمل بشكل صحيح (Spatie)
- ❌ Transactions لا تعمل
- ❌ Livewire Components لا تعمل
- ❌ Redis connection فاشل

---

## ✅ علامة البدء (Go Signal)

### يمكنك البدء بالبناء عندما:

1. ✅ جميع عناصر المراحل 1-4 مكتملة
2. ✅ البيئة جاهزة بالكامل
3. ✅ قاعدة البيانات جاهزة
4. ✅ جميع الوثائق مقروءة ومفهومة
5. ✅ خطة واضحة للمراحل القادمة
6. ✅ Laravel يعمل بشكل صحيح
7. ✅ Breeze يعمل (تسجيل دخول/خروج)
8. ✅ Spatie Permission يعمل
9. ✅ Redis يعمل
10. ✅ S3 connection يعمل

---

## 📝 ملاحظات

- ✅ لا تستعجل - الإعداد الصحيح يوفر وقتاً لاحقاً
- ✅ اختبر كل شيء قبل الانتقال للتالي
- ✅ استخدم Git commits صغيرة وواضحة
- ✅ اكتب Tests أثناء التطوير (TDD)
- ✅ استخدم Laravel Horizon لمراقبة Queue
- ✅ استخدم `php artisan tinker` للاختبار السريع

---

## 🔧 أوامر مفيدة

```bash
# Laravel
php artisan serve
php artisan migrate
php artisan migrate:fresh --seed
php artisan route:list
php artisan tinker

# Queue
php artisan queue:work
php artisan horizon

# Tests
php artisan test
php artisan test --coverage

# Livewire
php artisan livewire:make ComponentName

# Cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

**⚠️ لا تبدأ البرمجة قبل إكمال جميع عناصر المراحل 1-4!**
