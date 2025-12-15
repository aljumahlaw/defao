<?php

namespace App\Livewire\Profile;

use App\Models\NotificationSetting;
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
    public $email = '';
    public $avatar = null;
    public $avatarPreview = null;

    // Password change
    public $currentPassword = '';
    public $newPassword = '';
    public $confirmPassword = '';

    // Settings
    public $notifications = [
        'email' => true,
        'instant' => true,
        'sms' => false,
    ];

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatarPreview = $user->avatar ? Storage::url($user->avatar) : null;
        
        // Load notification settings
        $notificationSetting = $user->notificationSetting;
        if ($notificationSetting) {
            $this->notifications = [
                'email' => $notificationSetting->email_notifications,
                'instant' => $notificationSetting->instant_notifications,
                'sms' => $notificationSetting->sms_notifications,
            ];
        }
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
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $user->name = $this->name;
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
            'newPassword' => ['required', 'min:8', 'confirmed', Password::defaults()],
            'confirmPassword' => 'required',
        ], [
            'currentPassword.required' => 'كلمة المرور الحالية مطلوبة',
            'newPassword.required' => 'كلمة المرور الجديدة مطلوبة',
            'newPassword.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'newPassword.confirmed' => 'كلمة المرور غير متطابقة',
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
        $this->reset(['currentPassword', 'newPassword', 'confirmPassword']);
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
        $notificationSetting = auth()->user()->notificationSetting;
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
