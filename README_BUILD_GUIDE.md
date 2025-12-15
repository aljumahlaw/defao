# 📚 دليل البناء الشامل - Complete Build Guide

**نظام إدارة المستندات والمهام**  
**Document Management System**

**Stack**: Laravel 11 + Livewire 3 + Breeze + Spatie + Custom Workflow + Redis + S3  
**الإصدار**: 2.0  
**التاريخ**: $(date)

---

## 🎯 الهدف من هذا الدليل

هذا الدليل يوفر **جميع الوثائق والمتطلبات** قبل البدء بالبناء لضمان:
- ✅ بناء صحيح بدون أخطاء كبيرة
- ✅ فهم شامل للمتطلبات
- ✅ تجنب الأخطاء الشائعة
- ✅ بناء نظام قوي وآمن

---

## 📋 الوثائق المطلوبة (الترتيب الإجباري)

### ✅ اقرأ بالترتيب التالي:

#### 1️⃣ **00_REQUIREMENTS_DOCUMENT.md** (ابدأ هنا!)
```
📄 وثيقة المتطلبات الشاملة
├─ المتطلبات الوظيفية (17 واجهة)
├─ المتطلبات غير الوظيفية
├─ Stack التقنيات (Laravel + Livewire)
├─ قاعدة البيانات
├─ الواجهات المطلوبة
└─ قواعد التصميم

⏱️ وقت القراءة: 30-45 دقيقة
```

#### 2️⃣ **00_STACK_FINAL_RECOMMENDATION.md**
```
🎯 Stack المُعتمد والمُحكم
├─ Laravel 11 + Livewire 3
├─ Breeze + Spatie Permission
├─ Custom Workflow + Redis + S3
└─ التوصيات النهائية

⏱️ وقت القراءة: 15-20 دقيقة
```

#### 3️⃣ **01_ARCHITECTURE_DESIGN.md**
```
🏗️ البنية المعمارية والتصميم
├─ Laravel MVC Architecture
├─ هيكل المجلدات الكامل
├─ Models + Relationships
├─ Livewire Components
├─ Services + Jobs
└─ قواعد التطوير

⏱️ وقت القراءة: 20-30 دقيقة
```

#### 4️⃣ **02_DATABASE_SCHEMA.md**
```
🗄️ Schema قاعدة البيانات
├─ جميع الجداول (Laravel Migrations)
├─ العلاقات (Relationships)
├─ Indexes المطلوبة
└─ Enums + Seeders

⏱️ وقت القراءة: 20-30 دقيقة
```

#### 5️⃣ **03_PRE_BUILD_CHECKLIST.md**
```
✅ قائمة التحقق قبل البدء
├─ قراءة الوثائق
├─ إعداد البيئة (Laravel, PostgreSQL, Redis, S3)
├─ إعداد المشروع
├─ قاعدة البيانات
└─ البنية الأساسية

⏱️ وقت القراءة: 15-20 دقيقة
⏱️ وقت التنفيذ: 2-4 ساعات
```

#### 6️⃣ **04_COMMON_MISTAKES_SOLUTIONS.md** (مرجع سريع)
```
⚠️ الأخطاء الشائعة والحلول
├─ الأخطاء الحرجة
├─ الحلول الصحيحة (Laravel)
└─ القواعد الذهبية

⏱️ وقت القراءة: 15-20 دقيقة
📌 استخدمه كمرجع أثناء التطوير
```

---

## 🚀 خطة التنفيذ (Implementation Plan)

### المرحلة 0: التحضير (Pre-Build) - ⚠️ ضروري!

```
[ ] قراءة جميع الوثائق (1-2 ساعة)
[ ] إكمال Pre-Build Checklist
[ ] إعداد البيئة بالكامل (Laravel, PostgreSQL, Redis, S3)
[ ] اختبار كل شيء
[ ] ✅ الموافقة على البدء
```

**⏱️ الوقت المقدّر: 4-6 ساعات**

---

### المرحلة 1: الإعداد الأساسي (Foundation)

```
[ ] إعداد Laravel 11
[ ] إعداد Laravel Breeze
[ ] إعداد Spatie Permission
[ ] إعداد Livewire 3
[ ] إعداد PostgreSQL
[ ] إعداد Redis
[ ] إعداد Laravel Horizon
[ ] إعداد Amazon S3
```

**⏱️ الوقت المقدّر: 1-2 يوم**

---

### المرحلة 2: قاعدة البيانات (Database)

**ملاحظة**: للحصول على checklist تفصيلي، راجع `03_PRE_BUILD_CHECKLIST.md`

#### ملخص سريع:
```
✅ PostgreSQL 14+ (مع Connection Pool)
✅ جميع Migrations (راجع 02_DATABASE_SCHEMA.md)
✅ جميع Models + Relationships
✅ Eloquent Scopes
✅ Enums (WorkflowStageEnum, TaskStatusEnum, DocumentTypeEnum)
✅ Seeders (Roles, Users, Settings)
✅ Performance Indexes (راجع 02_DATABASE_SCHEMA.md)
```

#### التفاصيل الكاملة:
- 📄 **Schema & Tables**: `02_DATABASE_SCHEMA.md`
- ✅ **خطوات الإعداد**: `03_PRE_BUILD_CHECKLIST.md` (قسم Database)
- 🏗️ **Models & Relationships**: `01_ARCHITECTURE_DESIGN.md` (قسم 3️⃣)

**⏱️ الوقت المقدّر**: 1-2 يوم

---

### المرحلة 3: البنية الأساسية (Core Infrastructure)

```
[ ] إنشاء Repositories
[ ] إنشاء Services (WorkflowService, StorageService)
[ ] إنشاء Policies
[ ] إنشاء Exceptions
[ ] إنشاء Middleware
[ ] إنشاء Form Requests
```

**⏱️ الوقت المقدّر: 2-3 أيام**

---

### المرحلة 4: Controllers & Routes

```
[ ] إنشاء جميع Controllers
[ ] إنشاء جميع Routes
[ ] إضافة Validation (Form Requests)
[ ] إضافة Authorization (Policies)
[ ] اختبار Routes
```

**⏱️ الوقت المقدّر: 2-3 أيام**

---

### المرحلة 5: Livewire Components

```
[ ] Tasks/TaskTable
[ ] Tasks/WorkflowStageCard
[ ] Documents/DocumentUpload
[ ] Documents/DocumentTable
[ ] Documents/DocumentViewer (PDF.js)
[ ] Dashboard/NotificationCenter
[ ] Shared/FavoriteToggle
```

**⏱️ الوقت المقدّر: 3-4 أيام**

---

### المرحلة 6: Jobs & Queue (Redis)

```
[ ] ProcessDocumentJob (رفع 25MB)
[ ] ArchiveTaskJob
[ ] SendNotificationJob
[ ] إعداد Supervisor
[ ] اختبار Jobs (Horizon)
```

**⏱️ الوقت المقدّر: 1-2 يوم**

---

### المرحلة 7: Events & Listeners

```
[ ] DocumentUploaded Event
[ ] WorkflowStageCompleted Event
[ ] TaskCompleted Event
[ ] Listeners للـ Events
```

**⏱️ الوقت المقدّر: 1 يوم**

---

### المرحلة 8: Testing

```
[ ] Unit Tests (Models, Services)
[ ] Feature Tests (Workflow, File upload, Authorization)
[ ] Browser Tests (Laravel Dusk - اختياري)
[ ] Coverage ≥ 70%
```

**⏱️ الوقت المقدّر: 2-3 أيام**

---

### المرحلة 9: Polish & Documentation

```
[ ] PHPDoc comments
[ ] README.md
[ ] User documentation
[ ] Bug fixes
[ ] Performance optimization
```

**⏱️ الوقت المقدّر: 1-2 يوم**

---

## ⏱️ الوقت الإجمالي المتوقع

```
المرحلة 0: التحضير          4-6 ساعات
المرحلة 1: الإعداد الأساسي    1-2 يوم
المرحلة 2: قاعدة البيانات     1-2 يوم
المرحلة 3: البنية الأساسية    2-3 أيام
المرحلة 4: Controllers       2-3 أيام
المرحلة 5: Livewire         3-4 أيام
المرحلة 6: Jobs              1-2 يوم
المرحلة 7: Events            1 يوم
المرحلة 8: Testing           2-3 أيام
المرحلة 9: Polish            1-2 يوم
──────────────────────────────────
المجموع:                  15-25 يوم عمل
```

---

## 🚨 قواعد مهمة جداً

### ❌ لا تفعل:

1. ❌ **لا تبدأ البرمجة قبل قراءة جميع الوثائق**
2. ❌ **لا تبدأ قبل إكمال Pre-Build Checklist**
3. ❌ **لا تتخطى Transactions للعمليات المركبة**
4. ❌ **لا تنسى التحقق من الصلاحيات (Policies/Spatie)**
5. ❌ **لا تستخدم N+1 queries**
6. ❌ **لا تثق في File Upload بدون validation**
7. ❌ **لا تنسى Indexes**
8. ❌ **لا ترفع ملفات كبيرة بدون Queue Jobs**

### ✅ افعل:

1. ✅ **اقرأ جميع الوثائق بالترتيب**
2. ✅ **اكمل Pre-Build Checklist**
3. ✅ **استخدم Transactions للعمليات المركبة** (`DB::transaction()`)
4. ✅ **تحقق من الصلاحيات في كل endpoint** (Policies/Spatie)
5. ✅ **استخدم Eager Loading** (`with()`)
6. ✅ **Validate كل File Upload** (Form Requests)
7. ✅ **أضف Indexes على الأعمدة المستخدمة**
8. ✅ **اكتب Tests مع الكود**
9. ✅ **Log الأخطاء والعمليات المهمة**
10. ✅ **استخدم Jobs للمهام الثقيلة** (Redis Queue)

---

## 📚 مراجع إضافية

### Laravel Documentation:
- [Laravel 11 Docs](https://laravel.com/docs/11.x)
- [Livewire 3 Docs](https://livewire.laravel.com/docs)
- [Laravel Breeze](https://laravel.com/docs/11.x/starter-kits#laravel-breeze)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)

### Best Practices:
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [PHP The Right Way](https://phptherightway.com/)

---

## ✅ قائمة التحقق النهائية

### قبل البدء:
- [ ] ✅ قرأت جميع الوثائق
- [ ] ✅ فهمت جميع المتطلبات
- [ ] ✅ أكملت Pre-Build Checklist
- [ ] ✅ البيئة جاهزة (Laravel, PostgreSQL, Redis, S3)
- [ ] ✅ قاعدة البيانات جاهزة
- [ ] ✅ فهمت الأخطاء الشائعة
- [ ] ✅ لدي خطة واضحة

### أثناء التطوير:
- [ ] ✅ أستخدم Transactions (`DB::transaction()`)
- [ ] ✅ أتحقق من الصلاحيات (Policies/Spatie)
- [ ] ✅ أستخدم Eager Loading (`with()`)
- [ ] ✅ أتحقق من File Upload (Form Requests)
- [ ] ✅ أكتب Tests
- [ ] ✅ أسجل Logs
- [ ] ✅ أستخدم Jobs للمهام الثقيلة (Redis Queue)

---

## 🎯 الهدف النهائي

بناء نظام:
- ✅ **قوي** (strong architecture - Laravel MVC)
- ✅ **آمن** (security first - Policies, Spatie, Validation)
- ✅ **سريع** (performance optimized - Redis, Cache, Indexes)
- ✅ **سهل الاستخدام** (user-friendly - Livewire, Tailwind)
- ✅ **سهل الصيانة** (maintainable - Clean Code, Tests)
- ✅ **خالي من الأخطاء الكبيرة** (minimal critical bugs)

---

## 📞 الدعم

إذا واجهت مشاكل:
1. راجع الوثائق ذات الصلة
2. راجع `04_COMMON_MISTAKES_SOLUTIONS.md`
3. راجع Laravel/Livewire/Breeze/Spatie documentation
4. ابحث في Stack Overflow

---

## 🎉 النجاح

عندما تصل هنا:
- ✅ النظام يعمل بشكل صحيح
- ✅ جميع Tests تمر
- ✅ الأداء جيد
- ✅ الأمان محكم
- ✅ الوثائق مكتملة

**🎊 تهانينا!**

---

## 📦 أوامر Laravel مفيدة

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

# Supervisor (للإنتاج - لإدارة Horizon)
sudo supervisorctl status horizon
sudo supervisorctl restart horizon
sudo supervisorctl tail horizon

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

# Spatie Permission
php artisan permission:create-role admin
php artisan permission:create-role authorized
php artisan permission:create-role user
```

---

## 🔐 إعدادات الأمان الإضافية

### S3 Encryption Configuration:

1. **تفعيل Default Encryption على S3 Bucket**:
   - AWS Console → S3 → Bucket → Properties → Default encryption
   - Enable: Server-side encryption with Amazon S3 managed keys (SSE-S3)
   - Encryption type: AES-256

2. **التأكد من التكوين في Laravel**:
   ```php
   // config/filesystems.php
   's3' => [
       'driver' => 's3',
       'encryption' => 'AES256',
       'options' => [
           'ServerSideEncryption' => 'AES256',
       ],
   ]
   ```

### S3 CORS Configuration (لـ PDF.js):

1. **افتح S3 Console** → اختر bucket → Permissions → CORS

2. **أضف هذا التكوين**:
   ```json
   [
     {
       "AllowedHeaders": ["*"],
       "AllowedMethods": ["GET", "HEAD"],
       "AllowedOrigins": [
         "http://localhost:8000",
         "https://yourdomain.com"
       ],
       "ExposeHeaders": [
         "Content-Length",
         "Content-Type",
         "ETag"
       ],
       "MaxAgeSeconds": 3600
     }
   ]
   ```

3. **للإنتاج**: استبدل `yourdomain.com` بدومين فعلي

4. **اختبار**:
   ```javascript
   // في Developer Console
   fetch('https://your-bucket.s3.amazonaws.com/test.pdf')
     .then(r => console.log('CORS OK'))
     .catch(e => console.error('CORS Failed', e));
   ```

### S3 Lifecycle Policies (توفير التكلفة):

1. **إنشاء Lifecycle Rule**:
   - S3 Console → bucket → Management → Create lifecycle rule
   - Rule name: `archive-old-documents`

2. **الانتقال إلى Storage Classes الأرخص**:
   - بعد 30 يوم: انقل إلى S3 Intelligent-Tiering (توفير 40%)
   - بعد 90 يوم: انقل إلى S3 Glacier Instant Retrieval (توفير 68%)
   - بعد 180 يوم: انقل إلى S3 Glacier Flexible Retrieval (توفير 82%)

3. **مثال Lifecycle Rule (JSON)**:
   ```json
   {
     "Rules": [
       {
         "Id": "archive-policy",
         "Status": "Enabled",
         "Transitions": [
           {
             "Days": 30,
             "StorageClass": "INTELLIGENT_TIERING"
           },
           {
             "Days": 90,
             "StorageClass": "GLACIER_IR"
           },
           {
             "Days": 180,
             "StorageClass": "GLACIER"
           }
         ]
       }
     ]
   }
   ```

**التوفير المتوقع**: 70% على تكاليف S3 للملفات القديمة

### Session Encryption Configuration:

في `config/session.php`، تأكد من:

```php
return [
    'encrypt' => true, // ✅ يجب أن تكون true (تشفير Session data)
    
    'cookie' => env('SESSION_COOKIE', 'laravel_session'),
    'secure' => env('SESSION_SECURE_COOKIE', true), // ✅ HTTPS فقط
    'http_only' => true, // ✅ منع JavaScript من الوصول
    'same_site' => 'lax', // ✅ حماية CSRF
];
```

في `.env.production`:
```env
SESSION_DRIVER=redis
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
```

⚠️ **مهم**: Session encryption يحمي البيانات الحساسة (user data, CSRF tokens) إذا تم اختراق Redis.

---

### Redis Persistence Configuration:

1. **تعديل redis.conf**:
   ```bash
   sudo nano /etc/redis/redis.conf
   ```

2. **تفعيل AOF (Append Only File)**:
   ```conf
   appendonly yes
   appendfilename "appendonly.aof"
   appendfsync everysec
   ```

3. **تفعيل RDB Snapshots** (backup):
   ```conf
   save 900 1      # حفظ إذا تغير 1 key في 15 دقيقة
   save 300 10     # حفظ إذا تغيرت 10 keys في 5 دقائق
   save 60 10000   # حفظ إذا تغيرت 10000 keys في دقيقة
   ```

4. **إعادة تشغيل Redis**:
   ```bash
   sudo systemctl restart redis
   ```

5. **التحقق**:
   ```bash
   redis-cli CONFIG GET appendonly
   # يجب أن يظهر: "yes"
   ```

### PostgreSQL Connection Pool:

1. **زيادة max_connections**:
   ```bash
   sudo nano /etc/postgresql/14/main/postgresql.conf
   # max_connections = 200  # من 100 إلى 200
   sudo systemctl restart postgresql
   ```

2. **تثبيت PgBouncer** (موصى به للإنتاج):
   ```bash
   sudo apt-get install pgbouncer
   ```

3. **تكوين PgBouncer** (`/etc/pgbouncer/pgbouncer.ini`):
   ```ini
   [databases]
   document_management = host=127.0.0.1 port=5432 dbname=document_management

   [pgbouncer]
   listen_port = 6432
   listen_addr = 127.0.0.1
   auth_type = md5
   auth_file = /etc/pgbouncer/userlist.txt
   pool_mode = transaction
   max_client_conn = 1000
   default_pool_size = 25
   ```

4. **تحديث .env**:
   ```env
   DB_PORT=6432  # استخدم PgBouncer بدلاً من PostgreSQL مباشرة
   ```

### Rate Limiting Configuration:

في `app/Providers/RouteServiceProvider.php`:

```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

public function boot()
{
    // Rate Limiting لرفع الملفات
    RateLimiter::for('uploads', function (Request $request) {
        return Limit::perHour(10)
            ->by($request->user()->id)
            ->response(function () {
                return response()->json([
                    'message' => 'تجاوزت الحد المسموح لرفع الملفات (10 ملفات/ساعة)',
                    'retry_after' => 3600
                ], 429);
            });
    });
    
    // Rate Limiting للـ API
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(100)->by($request->ip());
    });
}
```

في `routes/web.php`:

```php
Route::middleware(['auth', 'throttle:uploads'])->group(function () {
    Route::post('/documents/upload', [DocumentController::class, 'upload']);
});
```

---

**آخر تحديث**: $(date)  
**الإصدار**: 2.0 (Laravel Stack)
