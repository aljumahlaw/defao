<div class="space-y-6">
    {{-- Profile Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">معلومات الملف الشخصي</h3>
        
        <form wire:submit.prevent="updateProfile" class="space-y-6">
            {{-- Avatar Upload --}}
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="relative">
                    @if($avatarPreview)
                        <img src="{{ $avatarPreview }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover">
                    @else
                        <div class="w-24 h-24 rounded-full bg-primary/10 flex items-center justify-center">
                            <x-heroicon-o-user class="w-12 h-12 text-primary" />
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        صورة الملف الشخصي
                    </label>
                    <div class="flex items-center gap-3">
                        <label for="avatar-upload" class="cursor-pointer">
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <x-heroicon-o-photo class="w-5 h-5" />
                                <span>اختر صورة</span>
                            </span>
                            <input type="file" id="avatar-upload" wire:model="avatar" accept="image/*" class="hidden">
                        </label>
                        @if($avatarPreview)
                            <button type="button" wire:click="removeAvatar" class="text-red-600 hover:text-red-800 dark:text-red-400">
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        @endif
                    </div>
                    @error('avatar')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">الحجم الأقصى: 2 ميجابايت</p>
                </div>
            </div>

            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    الاسم <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    wire:model.blur="name"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('name') border-red-500 @enderror"
                    placeholder="أدخل الاسم"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    البريد الإلكتروني <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    wire:model.blur="email"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('email') border-red-500 @enderror"
                    placeholder="أدخل البريد الإلكتروني"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end">
                <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                    <x-heroicon-o-check class="w-5 h-5" />
                    <span>حفظ التغييرات</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">تغيير كلمة المرور</h3>
        
        <form wire:submit.prevent="updatePassword" class="space-y-6">
            {{-- Current Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    كلمة المرور الحالية <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    wire:model="currentPassword"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('currentPassword') border-red-500 @enderror"
                    placeholder="أدخل كلمة المرور الحالية"
                >
                @error('currentPassword')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    كلمة المرور الجديدة <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    wire:model="newPassword"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('newPassword') border-red-500 @enderror"
                    placeholder="أدخل كلمة المرور الجديدة (8 أحرف على الأقل)"
                >
                @error('newPassword')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    تأكيد كلمة المرور <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    wire:model="confirmPassword"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('confirmPassword') border-red-500 @enderror"
                    placeholder="أعد إدخال كلمة المرور الجديدة"
                >
                @error('confirmPassword')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end">
                <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                    <x-heroicon-o-key class="w-5 h-5" />
                    <span>تغيير كلمة المرور</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Settings Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">الإعدادات</h3>
        
        <div class="space-y-6">
            {{-- Theme Toggle --}}
            <div x-data="{ 
                darkMode: localStorage.getItem('darkMode') === 'true',
                init() {
                    // Initialize dark mode on load
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    }
                },
                toggle() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);
                    document.documentElement.classList.toggle('dark', this.darkMode);
                }
            }" class="flex items-center justify-between py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-sun class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">الوضع الليلي</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">تبديل بين الوضع الفاتح والداكن</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input 
                        type="checkbox" 
                        class="sr-only peer"
                        :checked="darkMode"
                        @change="toggle()"
                    >
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary dark:peer-focus:ring-primary/50 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                </label>
            </div>

            {{-- Language Selector --}}
            <div x-data="{ 
                language: localStorage.getItem('language') || 'ar',
                init() {
                    // Initialize language on load
                    this.updateLanguage(this.language, false);
                },
                updateLanguage(lang, save = true) {
                    this.language = lang;
                    if (save) {
                        localStorage.setItem('language', lang);
                    }
                    
                    // Visual feedback - change dir attribute
                    document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
                    document.documentElement.setAttribute('lang', lang);
                    
                    // Trigger body class change
                    document.body.classList.toggle('rtl', lang === 'ar');
                    document.body.classList.toggle('ltr', lang === 'en');
                    
                    // Dispatch event for other components
                    window.dispatchEvent(new CustomEvent('language-changed', { detail: { language: lang } }));
                }
            }" class="flex items-center justify-between py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-globe-alt class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white" x-text="language === 'ar' ? 'اللغة' : 'Language'">اللغة</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="language === 'ar' ? 'اختر لغة الواجهة' : 'Choose interface language'">اختر لغة الواجهة</p>
                    </div>
                </div>
                <select 
                    :value="language"
                    @change="updateLanguage($event.target.value)"
                    class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary px-3 py-2 text-sm"
                >
                    <option value="ar">العربية</option>
                    <option value="en">English</option>
                </select>
            </div>

            {{-- Notifications Preferences --}}
            <div class="py-4">
                <div class="flex items-center gap-3 mb-4">
                    <x-heroicon-o-bell class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                    <p class="font-medium text-gray-900 dark:text-white">تفضيلات الإشعارات</p>
                </div>
                <div class="space-y-4 pr-8">
                    {{-- Email Notifications --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">الإشعارات عبر البريد</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">تلقي الإشعارات على البريد الإلكتروني</p>
                        </div>
                        <button 
                            wire:click="toggleNotification('email')"
                            type="button"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $notifications['email'] ? 'bg-primary' : 'bg-gray-300 dark:bg-gray-600' }}"
                        >
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $notifications['email'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>

                    {{-- Instant Notifications --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">الإشعارات الفورية</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">تلقي الإشعارات في المتصفح</p>
                        </div>
                        <button 
                            wire:click="toggleNotification('instant')"
                            type="button"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $notifications['instant'] ? 'bg-primary' : 'bg-gray-300 dark:bg-gray-600' }}"
                        >
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $notifications['instant'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>

                    {{-- SMS Notifications --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">الإشعارات النصية</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">تلقي الإشعارات عبر الرسائل النصية</p>
                        </div>
                        <button 
                            wire:click="toggleNotification('sms')"
                            type="button"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $notifications['sms'] ? 'bg-primary' : 'bg-gray-300 dark:bg-gray-600' }}"
                        >
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $notifications['sms'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
