# 🔑 كيفية الدخول للتطبيق

## ✅ الحل السريع (تم إنشاء مستخدم Admin)

تم إنشاء مستخدم Admin جديد:

**📧 Email**: `admin@example.com`  
**🔑 Password**: `password`

---

## 🚀 خطوات الدخول

1. **شغّل التطبيق** (إذا لم يكن يعمل):
```bash
php artisan serve
```

2. **افتح المتصفح**:
```
http://127.0.0.1:8000/login
```

3. **أدخل البيانات**:
- Email: `admin@example.com`
- Password: `password`

4. **اضغط Login** ✅

---

## 📋 بيانات الدخول الأخرى المتاحة

### Option 1: Test User (موجود في قاعدة البيانات)
- **Email**: `test@example.com`
- **Password**: `password`

### Option 2: أي مستخدم آخر
إذا كان لديك مستخدمين آخرين، جميعهم يستخدمون:
- **Password**: `password`

---

## 🔧 إنشاء مستخدم جديد (إذا احتجت)

### الطريقة 1: استخدام Tinker
```bash
php artisan tinker
```

ثم:
```php
$user = App\Models\User::create([
    'name' => 'اسمك',
    'email' => 'your-email@example.com',
    'password' => bcrypt('your-password'),
    'email_verified_at' => now(),
]);
exit
```

### الطريقة 2: إعادة Seed (ينشئ 10 مستخدمين + test user)
```bash
php artisan migrate:fresh --seed
```

---

## 🔍 عرض جميع المستخدمين

```bash
php artisan tinker
```

```php
App\Models\User::all(['name', 'email']);
exit
```

---

## ⚠️ استكشاف الأخطاء

### خطأ: "These credentials do not match our records"
- تأكد من أنك تستخدم `admin@example.com` / `password`
- أو قم بإنشاء مستخدم جديد (الطريقة أعلاه)

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

---

## ✅ بعد الدخول

سترى:
- Dashboard الرئيسي
- قائمة الوثائق (Documents)
- قائمة المهام (Tasks)
- الإعدادات (Profile)

---

**🎯 جاهز للاستخدام!**
