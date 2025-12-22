<?php

namespace App\Livewire\Profile;

use App\Models\NotificationSetting;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class Settings extends Component
{
    use WithFileUploads;

    // Profile info
    public $name = '';
    public $title = '';
    public $email = '';
    public $avatar = null;
    public $avatarPreview = null;

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

    public function mount()
    {
        $user = auth()->user()->load('notificationSetting');
        $this->name = $user->name;
        $this->title = $user->title ?? '';
        $this->email = $user->email;
        $this->avatarPreview = $user->avatar ? Storage::url($user->avatar) : null;
        $this->isActive = $user->is_active;

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

    public function updatedAvatar()
    {
        $this->validate(['avatar' => 'nullable|image|max:2048']);
        
        if ($this->avatar) {
            $this->avatarPreview = $this->avatar->temporaryUrl();
        }
    }

    public function removeAvatar()
    {
        $this->avatar = null;
        $this->avatarPreview = null;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|max:100',
            'title' => 'nullable|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $user->name = $this->name;
        $user->title = $this->title ?: null;
        $user->email = $this->email;

        // Handle avatar upload
        if ($this->avatar) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            
            // Store new avatar
            $user->avatar = $this->avatar->store('avatars', 'public');
        }

        $user->save();

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
