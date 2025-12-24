<?php

namespace App\Livewire\Profile;

use App\Models\NotificationSetting;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class Settings extends Component
{
    // Profile info
    public $name = '';
    public $first_name = '';
    public $middle_name = '';
    public $last_name = '';
    public $title = '';
    public $phone = '';
    public $phoneDigits = ''; // 8 أرقام فقط (بعد 05)
    public $email = '';

    // Password change
    public $currentPassword = '';
    public $newPassword = '';
    public $new_password_confirmation = '';

    // Settings
    public $notifications = [
        'email' => true,
        'instant' => true,
        'sms' => false,
    ];

    // Role/Status
    public $isActive = true;

    // Validation Rules
    protected $rules = [
        'first_name' => 'required|string|max:50',
        'middle_name' => 'nullable|string|max:50',
        'last_name' => 'nullable|string|max:50',
        'title' => 'nullable|in:المحامي,المحامية,مساعد قانوني,مساعدة قانونية,المدير',
        'phoneDigits' => 'nullable|string|size:8|regex:/^[0-9]+$/',
        'email' => 'required|email|max:255|unique:users,email',
    ];

    protected $messages = [
        'phoneDigits.size' => 'رقم الجوال يجب أن يكون 8 أرقام',
        'phoneDigits.regex' => 'رقم الجوال يجب أن يحتوي على أرقام فقط',
        'first_name.required' => 'الاسم الأول مطلوب',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
    ];

    public function mount()
    {
        $user = auth()->user()->load('notificationSetting');
        $this->name = $user->name;
        $this->title = $user->title ?? '';
        $this->phone = $user->phone ?? '';
        $this->email = $user->email;
        $this->isActive = $user->is_active;

        // Phone: استخراج 8 أرقام بعد 05
        if ($user->phone && strlen($user->phone) >= 10) {
            $digits = preg_replace('/[^0-9]/', '', $user->phone); // إزالة أي غير أرقام
            if (strlen($digits) >= 10) {
                $this->phoneDigits = substr($digits, 2, 8); // آخر 8 أرقام بعد 05
            }
        }

        // تفكيك الاسم إلى 3 أجزاء
        $names = explode(' ', trim($user->name));
        $this->first_name = $names[0] ?? '';
        $this->middle_name = $names[1] ?? '';
        $this->last_name = implode(' ', array_slice($names, 2)) ?: '';

        $notificationSetting = $user->notificationSetting;
        if ($notificationSetting) {
            $this->notifications = [
                'email' => $notificationSetting->email_notifications,
                'instant' => $notificationSetting->instant_notifications,
                'sms' => $notificationSetting->sms_notifications,
            ];
        }
    }

    public function updatedIsActive($value)
    {
        // Only admins can toggle account status
        if (!auth()->user()->isAdmin()) {
            return;
        }

        auth()->user()->update(['is_active' => $value]);
        $this->isActive = $value;

        $this->dispatch('show-toast', 
            message: $value ? 'تم تفعيل الحساب' : 'تم تعطيل الحساب',
            type: 'success'
        );
    }

    public function updateProfile()
    {
        $user = Auth::user();
        
        // Update email rule to exclude current user
        $this->rules['email'] = 'required|email|max:255|unique:users,email,' . $user->id;
        
        $this->validate();

        // جمع 05 + 8 أرقام
        if ($this->phoneDigits && strlen($this->phoneDigits) === 8) {
            $this->phone = '05' . $this->phoneDigits;
        } else {
            $this->phone = null;
        }
        
        // منع التكرار الذكي v1.0 - الكود الأصلي المثالي
        $firstNameCount = User::where('name', $this->first_name)->count();
        if ($firstNameCount > 0 && $this->middle_name) {
            $fullName = trim("{$this->first_name} {$this->middle_name}");
        } else {
            $fullName = trim($this->first_name ?: $this->name);
        }
        
        // Update all fields at once using update()
        $user->update([
            'name' => $fullName,
            'title' => $this->title ?: null,
            'phone' => $this->phone ?: null,
            'email' => $this->email,
        ]);
        
        $this->name = $fullName; // sync the old property

        $this->dispatch('show-toast', 
            message: 'تم تحديث الملف الشخصي بنجاح',
            type: 'success'
        );
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => ['required', 'min:8', Password::defaults(), 'confirmed'],
        ], [
            'newPassword.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'كلمة المرور الحالية غير صحيحة');
            return;
        }

        // Update password
        $user->password = Hash::make($this->newPassword);
        $user->save();

        $this->dispatch('show-toast', 
            message: 'تم تغيير كلمة المرور بنجاح',
            type: 'success'
        );

        // Reset form
        $this->reset(['currentPassword', 'newPassword', 'new_password_confirmation']);
    }

    public function toggleNotification($type)
    {
        $this->notifications[$type] = !$this->notifications[$type];
        
        // Map to database field names
        $fieldMap = [
            'email' => 'email_notifications',
            'instant' => 'instant_notifications',
            'sms' => 'sms_notifications',
        ];

        $fieldName = $fieldMap[$type] ?? null;
        if (!$fieldName) {
            return;
        }

        // Update or create notification setting
        $notificationSetting = auth()->user()->load('notificationSetting')->notificationSetting;
        if (!$notificationSetting) {
            $notificationSetting = NotificationSetting::create([
                'user_id' => auth()->id(),
                'email_notifications' => $this->notifications['email'],
                'instant_notifications' => $this->notifications['instant'],
                'sms_notifications' => $this->notifications['sms'],
            ]);
        } else {
            $notificationSetting->update([
                $fieldName => $this->notifications[$type],
            ]);
        }
        
        $this->dispatch('show-toast', 
            message: 'تم تحديث تفضيلات الإشعارات بنجاح',
            type: 'success'
        );
    }

    public function render()
    {
        return view('livewire.profile.settings');
    }
}
