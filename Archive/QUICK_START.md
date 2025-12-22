# 🚀 Quick Start Guide - الدخول للتطبيق

## 🔑 إنشاء مستخدم جديد للدخول

### الطريقة 1: استخدام Tinker (الأسرع)

```bash
php artisan tinker
```

ثم في Tinker:
```php
$user = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
]);
echo "User created: " . $user->email;
exit
```

### الطريقة 2: إعادة Seed قاعدة البيانات

```bash
php artisan migrate:fresh
php artisan db:seed
```

هذا سينشئ 10 مستخدمين عشوائيين + مستخدم test.

### الطريقة 3: استخدام السكربت الجاهز

```bash
php create-admin-user.php
```

سيُنشئ مستخدم:
- **Email**: `admin@example.com`
- **Password**: `password`

---

## 📝 بيانات الدخول الافتراضية

بعد إعادة Seed، يمكنك استخدام:

**Option 1: Test User**
- Email: `test@example.com`
- Password: `password`

**Option 2: أي مستخدم من Factory**
- Email: أي email تم إنشاؤه عشوائياً
- Password: `password` (لجميع المستخدمين)

---

## 🔍 التحقق من المستخدمين الموجودين

```bash
php artisan tinker
```

```php
App\Models\User::all(['name', 'email']);
exit
```

---

## ⚠️ ملاحظة مهمة

إذا كنت تستخدم **SQLite** محلياً:
```bash
# تأكد من وجود ملف قاعدة البيانات
touch database/database.sqlite

# ثم قم بالـ migrate و seed
php artisan migrate
php artisan db:seed
```

إذا كنت تستخدم **PostgreSQL** (Railway):
```bash
# تأكد من DATABASE_URL في .env
php artisan migrate
php artisan db:seed
```

---

## 🎯 الخطوات السريعة

1. **تأكد من قاعدة البيانات**:
```bash
php artisan migrate:status
```

2. **أنشئ مستخدم جديد**:
```bash
php artisan tinker
# ثم انسخ الكود أعلاه
```

3. **أو أعد Seed كل شيء**:
```bash
php artisan migrate:fresh --seed
```

4. **دخل للتطبيق**:
- افتح: `http://127.0.0.1:8000/login`
- استخدم: `admin@example.com` / `password`

---

## 🔧 استكشاف الأخطاء

### خطأ: "No application encryption key"
```bash
php artisan key:generate
```

### خطأ: "Database connection"
تحقق من `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### خطأ: "Table doesn't exist"
```bash
php artisan migrate
```

---

**✅ بعد إنشاء المستخدم، يمكنك الدخول فوراً!**
