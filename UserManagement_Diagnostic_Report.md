# UserManagement Diagnostic Report
## تقرير تشخيص صفحة إدارة الموظفين

---

## 1. toggleActive (تفعيل/تعطيل)

### الكود الحالي:

**الزر (user-management.blade.php:166-175):**
```blade
<button 
    wire:click="toggleActive({{ $user->id }})"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-50"
    class="px-3 py-1 text-xs rounded-lg transition-colors {{ $user->is_active ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
    <span wire:loading.remove wire:target="toggleActive({{ $user->id }})">
        {{ $user->is_active ? 'تعطيل' : 'تفعيل' }}
    </span>
    <span wire:loading wire:target="toggleActive({{ $user->id }})">...</span>
</button>
```

**الدالة (UserManagement.php:92-106):**
```php
public function toggleActive($userId)
{
    $user = User::findOrFail($userId);
    
    // Prevent admin from deactivating themselves
    if ($user->id === auth()->id()) {
        session()->flash('error', 'لا يمكنك تعطيل حسابك الخاص.');
        return;
    }

    $user->update(['is_active' => !$user->is_active]);
    
    $status = $user->is_active ? 'تم تفعيل' : 'تم تعطيل';
    session()->flash('success', $status . ' المستخدم بنجاح.');
}
```

### الحالة:
❌ **تأكيد مفقود** - لا يوجد `wire:confirm` في الزر

### السلوك:
- **المتوقع:** يجب أن يظهر تأكيد قبل تعطيل/تفعيل المستخدم
- **الفعلي:** يتم التفعيل/التعطيل مباشرة بدون تأكيد

### التوصية:
```blade
<button 
    wire:click="toggleActive({{ $user->id }})"
    wire:confirm="{{ $user->is_active ? 'هل أنت متأكد من تعطيل هذا المستخدم؟' : 'هل أنت متأكد من تفعيل هذا المستخدم؟' }}"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-50"
    class="px-3 py-1 text-xs rounded-lg transition-colors {{ $user->is_active ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
    <span wire:loading.remove wire:target="toggleActive({{ $user->id }})">
        {{ $user->is_active ? 'تعطيل' : 'تفعيل' }}
    </span>
    <span wire:loading wire:target="toggleActive({{ $user->id }})">...</span>
</button>
```

---

## 2. Delete User (حذف موظف)

### الحالة:
❌ **غير موجود** - لا توجد دالة `deleteUser()` أو `forceDelete()` في `UserManagement.php`

### البحث في الكود:
- ✅ تم البحث في `app/Livewire/Admin/UserManagement.php` - لا توجد دالة حذف
- ✅ تم البحث في `app/Http/Controllers/` - لا يوجد controller خاص بإدارة المستخدمين
- ✅ تم البحث في `resources/views/livewire/admin/user-management.blade.php` - لا يوجد زر حذف في الجدول

### العلاقات المرتبطة:

**User Model Relationships:**
```php
// Documents created by user
public function documents() {
    return $this->hasMany(Document::class, 'user_id');
}

// Documents assigned to user
public function assignedDocuments() {
    return $this->hasMany(Document::class, 'assignee_id');
}

// Tasks created by user
public function tasks() {
    return $this->hasMany(Task::class, 'user_id');
}

// Tasks assigned to user
public function assignedTasks() {
    return $this->hasMany(Task::class, 'assignee_id');
}

// Document activities
public function documentActivities() {
    return $this->hasMany(DocumentActivity::class);
}

// Notification settings
public function notificationSetting() {
    return $this->hasOne(NotificationSetting::class);
}
```

### ملخص العلاقات:
- **documents:** ✅ موجود (user_id)
- **assignedDocuments:** ✅ موجود (assignee_id)
- **tasks:** ✅ موجود (user_id)
- **assignedTasks:** ✅ موجود (assignee_id)
- **documentActivities:** ✅ موجود
- **notificationSetting:** ✅ موجود (hasOne)

### التوصية:

**خيار 1: Soft Delete (موصى به)**
```php
// في UserManagement.php
public function deleteUser($userId)
{
    $user = User::findOrFail($userId);
    
    // Prevent self-deletion
    if ($user->id === auth()->id()) {
        session()->flash('error', 'لا يمكنك حذف حسابك الخاص.');
        return;
    }
    
    // Check for active documents/tasks
    $activeDocuments = $user->documents()->where('is_archived', false)->count();
    $activeTasks = $user->tasks()->where('status', '!=', 'completed')->count();
    
    if ($activeDocuments > 0 || $activeTasks > 0) {
        session()->flash('error', 'لا يمكن حذف المستخدم لأنه لديه مستندات أو مهام نشطة.');
        return;
    }
    
    $user->delete(); // Soft delete if SoftDeletes trait exists
    session()->flash('success', 'تم حذف المستخدم بنجاح.');
}
```

**خيار 2: Force Delete (حذف نهائي - محفوف بالمخاطر)**
```php
public function forceDeleteUser($userId)
{
    $user = User::findOrFail($userId);
    
    if ($user->id === auth()->id()) {
        session()->flash('error', 'لا يمكنك حذف حسابك الخاص.');
        return;
    }
    
    // Transfer ownership before deletion
    $adminUser = User::where('role', 'admin')->first();
    
    if ($adminUser) {
        // Transfer documents
        $user->documents()->update(['user_id' => $adminUser->id]);
        $user->assignedDocuments()->update(['assignee_id' => $adminUser->id]);
        
        // Transfer tasks
        $user->tasks()->update(['user_id' => $adminUser->id]);
        $user->assignedTasks()->update(['assignee_id' => $adminUser->id]);
    }
    
    $user->forceDelete();
    session()->flash('success', 'تم حذف المستخدم نهائياً.');
}
```

**الزر المقترح:**
```blade
<button 
    wire:click="deleteUser({{ $user->id }})"
    wire:confirm="هل أنت متأكد من حذف هذا المستخدم؟ سيتم نقل جميع المستندات والمهام إلى مدير النظام."
    wire:loading.attr="disabled"
    class="px-3 py-1 text-xs rounded-lg bg-red-100 text-red-800 hover:bg-red-200 transition-colors">
    <span wire:loading.remove wire:target="deleteUser({{ $user->id }})">حذف</span>
    <span wire:loading wire:target="deleteUser({{ $user->id }})">...</span>
</button>
```

---

## 3. createUser Security (أمان إنشاء المستخدم)

### الكود الحالي (UserManagement.php:56-90):

```php
public function createUser()
{
    $this->validate();

    try {
        // Generate random password
        $randomPassword = Str::random(32);
        
        // Create user with allowed fields only
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($randomPassword),
        ]);

        // Set guarded fields directly
        $user->role = $this->role;
        $user->is_active = true;
        $user->password_changed_at = null;
        $user->save();

        // Send password reset link
        $status = Password::sendResetLink(['email' => $user->email]);
        // ... rest of code
    }
}
```

### User Model $fillable:

```php
protected $fillable = [
    'name', 'title', 'email', 'role', 'is_active',
    'phone', 'department', 'position',
    'password', 'password_changed_at'
];
```

### تحليل الأمان:

**✅ role assignment:**
- **الطريقة الحالية:** `create()` بدون `role` → ثم `$user->role = $this->role;` → `save()`
- **السبب:** تجنب mass assignment للـ `role` (حتى لو كان في `$fillable`)
- **الحالة:** ✅ **آمن** - يتم تعيين `role` مباشرة بعد الإنشاء

**✅ $fillable يشمل role:**
- `role` موجود في `$fillable` (السطر 39)
- لكن الكود لا يستخدمه في `create()` - يضعه مباشرة

**✅ الحماية:**
- `$guarded = ['id']` - يحمي `id` فقط
- `role` في `$fillable` لكن لا يُمرر في `create()`
- يتم تعيين `role` مباشرة بعد الإنشاء

### الحالة:
✅ **آمن** - الكود الحالي آمن لأن:
1. لا يستخدم mass assignment للـ `role`
2. يضع `role` مباشرة بعد الإنشاء
3. `role` في `$fillable` لكن لا يُستخدم في `create()`

### تحسين محتمل (اختياري):

**الخيار 1: استخدام create() مع role (إذا كان role في $fillable):**
```php
$user = User::create([
    'name' => $this->name,
    'email' => $this->email,
    'password' => Hash::make($randomPassword),
    'role' => $this->role, // ✅ آمن لأن role في $fillable
    'is_active' => true,
    'password_changed_at' => null,
]);
```

**الخيار 2: الحفاظ على الكود الحالي (موصى به):**
- الكود الحالي آمن وواضح
- يمنع mass assignment للـ `role` حتى لو كان في `$fillable`
- يعطي تحكم أكبر في عملية الإنشاء

---

## Quick Fixes (حلول سريعة)

### Fix 1: إضافة wire:confirm لـ toggleActive

**الملف:** `resources/views/livewire/admin/user-management.blade.php:166`

```blade
<button 
    wire:click="toggleActive({{ $user->id }})"
    wire:confirm="{{ $user->is_active ? 'هل أنت متأكد من تعطيل هذا المستخدم؟' : 'هل أنت متأكد من تفعيل هذا المستخدم؟' }}"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-50"
    class="px-3 py-1 text-xs rounded-lg transition-colors {{ $user->is_active ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
    <span wire:loading.remove wire:target="toggleActive({{ $user->id }})">
        {{ $user->is_active ? 'تعطيل' : 'تفعيل' }}
    </span>
    <span wire:loading wire:target="toggleActive({{ $user->id }})">...</span>
</button>
```

### Fix 2: إضافة دالة deleteUser + زر حذف

**الملف:** `app/Livewire/Admin/UserManagement.php`

```php
public function deleteUser($userId)
{
    $user = User::findOrFail($userId);
    
    // Prevent self-deletion
    if ($user->id === auth()->id()) {
        session()->flash('error', 'لا يمكنك حذف حسابك الخاص.');
        return;
    }
    
    // Check for active documents/tasks
    $activeDocuments = $user->documents()->where('is_archived', false)->count();
    $activeTasks = $user->tasks()->where('status', '!=', 'completed')->count();
    
    if ($activeDocuments > 0 || $activeTasks > 0) {
        session()->flash('error', 'لا يمكن حذف المستخدم لأنه لديه مستندات أو مهام نشطة. يرجى أرشفة أو إكمال المهام أولاً.');
        return;
    }
    
    $user->delete();
    session()->flash('success', 'تم حذف المستخدم بنجاح.');
}
```

**الملف:** `resources/views/livewire/admin/user-management.blade.php:162-177`

```blade
<td class="px-6 py-4 whitespace-nowrap text-sm">
    @if($user->id === auth()->id())
        <span class="text-gray-400 dark:text-gray-500">حسابك</span>
    @else
        <div class="flex items-center gap-2">
            <button 
                wire:click="toggleActive({{ $user->id }})"
                wire:confirm="{{ $user->is_active ? 'هل أنت متأكد من تعطيل هذا المستخدم؟' : 'هل أنت متأكد من تفعيل هذا المستخدم؟' }}"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50"
                class="px-3 py-1 text-xs rounded-lg transition-colors {{ $user->is_active ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                <span wire:loading.remove wire:target="toggleActive({{ $user->id }})">
                    {{ $user->is_active ? 'تعطيل' : 'تفعيل' }}
                </span>
                <span wire:loading wire:target="toggleActive({{ $user->id }})">...</span>
            </button>
            
            <button 
                wire:click="deleteUser({{ $user->id }})"
                wire:confirm="هل أنت متأكد من حذف هذا المستخدم؟ سيتم حذف جميع بياناته نهائياً."
                wire:loading.attr="disabled"
                class="px-3 py-1 text-xs rounded-lg bg-red-100 text-red-800 hover:bg-red-200 transition-colors">
                <span wire:loading.remove wire:target="deleteUser({{ $user->id }})">حذف</span>
                <span wire:loading wire:target="deleteUser({{ $user->id }})">...</span>
            </button>
        </div>
    @endif
</td>
```

### Fix 3: تحسين createUser (اختياري - الكود الحالي آمن)

**الملف:** `app/Livewire/Admin/UserManagement.php:56-90`

```php
public function createUser()
{
    $this->validate();

    try {
        $randomPassword = Str::random(32);
        
        // ✅ استخدام create() مع role مباشرة (آمن لأن role في $fillable)
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($randomPassword),
            'role' => $this->role,
            'is_active' => true,
            'password_changed_at' => null,
        ]);

        // Send password reset link
        $status = Password::sendResetLink(['email' => $user->email]);
        // ... rest of code
    }
}
```

**ملاحظة:** هذا التحسين اختياري - الكود الحالي آمن بالفعل.

---

## ملخص النتائج

| النقطة | الحالة | الأولوية | التوصية |
|--------|--------|----------|----------|
| **toggleActive** | ❌ بدون تأكيد | 🔴 عالية | إضافة `wire:confirm` |
| **Delete User** | ❌ غير موجود | 🟡 متوسطة | إضافة دالة + زر مع فحص العلاقات |
| **createUser Security** | ✅ آمن | 🟢 منخفضة | الكود الحالي آمن (تحسين اختياري) |

---

## اختبار يدوي مطلوب

### 1. toggleActive
- [ ] انتقل إلى `/admin/users`
- [ ] انقر على زر "تعطيل" لأي مستخدم
- [ ] **النتيجة المتوقعة:** يجب أن يظهر تأكيد Livewire قبل التعطيل
- [ ] **النتيجة الحالية:** يتم التعطيل مباشرة بدون تأكيد ❌

### 2. Delete User
- [ ] انتقل إلى `/admin/users`
- [ ] ابحث عن زر "حذف" في عمود الإجراءات
- [ ] **النتيجة المتوقعة:** يجب أن يوجد زر حذف مع تأكيد
- [ ] **النتيجة الحالية:** لا يوجد زر حذف ❌

### 3. Console Errors
- [ ] افتح `/admin/users`
- [ ] اضغط F12 → Console
- [ ] **النتيجة المتوقعة:** لا توجد أخطاء Livewire
- [ ] **النتيجة الحالية:** [يحتاج اختبار]

---

**تاريخ التقرير:** {{ date('Y-m-d H:i:s') }}
**الحالة:** ✅ تحليل مكتمل - جاهز للإصلاحات

