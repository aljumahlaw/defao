<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showCreateForm = false;
    
    // Create user form fields
    public $name = '';
    public $email = '';
    public $role = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:admin,lawyer,assistant',
        ];
    }

    protected $messages = [
        'name.required' => 'الاسم مطلوب',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'email.email' => 'البريد الإلكتروني غير صحيح',
        'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
        'role.required' => 'الدور مطلوب',
        'role.in' => 'الدور يجب أن يكون: admin, lawyer, أو assistant',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        if (!$this->showCreateForm) {
            $this->reset(['name', 'email', 'role']);
            $this->resetValidation();
        }
    }

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

            if ($status === Password::RESET_LINK_SENT) {
                session()->flash('success', 'تم إنشاء المستخدم بنجاح وإرسال رابط تعيين كلمة المرور إلى بريده الإلكتروني.');
                $this->reset(['name', 'email', 'role', 'showCreateForm']);
                $this->resetValidation();
            } else {
                session()->flash('error', 'تم إنشاء المستخدم ولكن فشل إرسال رابط تعيين كلمة المرور.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء إنشاء المستخدم: ' . $e->getMessage());
        }
    }

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

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('role', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ])->layout('layouts.app');
    }
}
