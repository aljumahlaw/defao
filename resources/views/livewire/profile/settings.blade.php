<div class="space-y-6">
    {{-- Role & Status Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">الدور والحالة</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Role Badge --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الدور</label>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ auth()->user()->getRoleDisplay()['classes'] }}">
                    {{ auth()->user()->getRoleDisplay()['label'] }}
                </span>
            </div>

            {{-- Admin Status Toggle --}}
            @if(auth()->user()->isAdmin())
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">حالة الحساب</label>
                <label class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <input type="checkbox" wire:model.live="isActive" 
                           class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $isActive ? 'الحساب نشط ✓' : 'الحساب معطّل ✗' }}
                    </span>
                </label>
            </div>
            @endif
        </div>
    </div>

    {{-- Profile Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">معلومات الملف الشخصي</h3>
        
        <form wire:submit.prevent="updateProfile" class="space-y-6">
            {{-- Avatar Upload with Drag & Drop --}}
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="relative group"
                     x-data="{ isDragging: false }"
                     x-on:dragover.prevent="isDragging = true"
                     x-on:dragleave.prevent="isDragging = false"
                     x-on:drop.prevent="isDragging = false; $refs.avatarInput.files = $event.dataTransfer.files; $refs.avatarInput.dispatchEvent(new Event('change'))">
                    
                    {{-- Avatar Preview --}}
                    <label for="avatar-upload" class="cursor-pointer block">
                        <div class="relative w-28 h-28 rounded-full overflow-hidden border-4 border-gray-200 dark:border-gray-600 transition-all duration-300"
                             :class="{ 'border-primary border-dashed': isDragging }">
                            @if($avatarPreview)
                                <img src="{{ $avatarPreview }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center">
                                    <x-heroicon-o-user class="w-14 h-14 text-primary/60" />
                                </div>
                            @endif
                            
                            {{-- Hover Overlay --}}
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <div class="text-center text-white">
                                    <x-heroicon-o-camera class="w-6 h-6 mx-auto mb-1" />
                                    <span class="text-xs">تغيير</span>
                                </div>
                            </div>
                        </div>
                    </label>
                    
                    {{-- Remove Button --}}
                    @if($avatarPreview)
                        <button type="button" 
                                wire:click="removeAvatar" 
                                class="absolute -bottom-1 -right-1 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-colors">
                            <x-heroicon-o-x-mark class="w-4 h-4" />
                        </button>
                    @endif
                    
                    <input type="file" 
                           id="avatar-upload" 
                           x-ref="avatarInput"
                           wire:model="avatar" 
                           accept="image/*" 
                           class="hidden">
                </div>
                
                <div class="flex-1 text-center sm:text-right">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        صورة الملف الشخصي
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                        اسحب وأفلت الصورة أو انقر للاختيار
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        PNG, JPG, GIF — الحجم الأقصى: 2 ميجابايت
                    </p>
                    @error('avatar')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    
                    {{-- Loading indicator --}}
                    <div wire:loading wire:target="avatar" class="mt-2">
                        <span class="text-xs text-primary flex items-center gap-1 justify-center sm:justify-start">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            جاري الرفع...
                        </span>
                    </div>
                </div>
            </div>

            {{-- تفصيل الاسم (3 خانات) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- الاسم الأول --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        الاسم الأول <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model.blur="first_name"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('first_name') border-red-500 @enderror"
                        placeholder="رنيم"
                    >
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- الاسم الأوسط --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        الاسم الأوسط
                    </label>
                    <input 
                        type="text" 
                        wire:model.blur="middle_name"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('middle_name') border-red-500 @enderror"
                        placeholder="أحمد"
                    >
                    @error('middle_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- الاسم الأخير --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        الاسم الأخير
                    </label>
                    <input 
                        type="text" 
                        wire:model.blur="last_name"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('last_name') border-red-500 @enderror"
                        placeholder="محمد"
                    >
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- اللقب المهني + رقم الجوال --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- اللقب المهني --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        اللقب المهني
                    </label>
                    <select 
                        wire:model.blur="title"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('title') border-red-500 @enderror"
                    >
                        <option value="">اختر اللقب</option>
                        <option value="المحامي">المحامي</option>
                        <option value="المحامية">المحامية</option>
                        <option value="مساعد قانوني">مساعد قانوني</option>
                        <option value="مساعدة قانونية">مساعدة قانونية</option>
                        <option value="المدير">المدير</option>
                    </select>
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        سيظهر كـ "{{ $title ?: auth()->user()->role_title }} {{ $first_name }}" في القوائم
                    </p>
                </div>

                {{-- Saudi Phone: 05 ثابت + 8 أرقام --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        رقم الجوال
                    </label>
                    <div class="flex rounded-xl border-2 border-gray-200 dark:border-gray-600 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10 overflow-hidden bg-white dark:bg-gray-800">
                        {{-- Prefix 05 ثابت --}}
                        <span class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 text-lg font-semibold text-gray-900 dark:text-gray-200 border-l border-gray-200 dark:border-gray-600 whitespace-nowrap select-none">
                            05
                        </span>
                        {{-- 8 أرقام فقط --}}
                        <input 
                            type="text" 
                            wire:model.live="phoneDigits"
                            x-data
                            x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').substring(0, 8)"
                            maxlength="8"
                            inputmode="numeric"
                            pattern="[0-9]{8}"
                            placeholder="xxxxxxxx"
                            dir="ltr"
                            class="w-full px-4 py-3 text-lg font-medium text-left focus:outline-none focus:ring-0 bg-transparent border-0 @error('phoneDigits') text-red-500 @enderror"
                        >
                    </div>
                    @error('phoneDigits')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @if($phoneDigits && strlen($phoneDigits) === 8)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            الرقم الكامل: <strong class="text-gray-700 dark:text-gray-300">05{{ $phoneDigits }}</strong>
                        </p>
                    @endif
                </div>
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
            {{-- Password Fields - 3 Columns --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Current Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        الحالية <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            wire:model="currentPassword"
                            class="w-full pr-10 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('currentPassword') border-red-500 @enderror"
                            placeholder="كلمة المرور الحالية"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <x-heroicon-o-lock-closed class="w-4 h-4 text-gray-400" />
                        </div>
                    </div>
                    @error('currentPassword')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        الجديدة <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            wire:model="newPassword"
                            class="w-full pr-10 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary @error('newPassword') border-red-500 @enderror"
                            placeholder="8 أحرف على الأقل"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <x-heroicon-o-key class="w-4 h-4 text-gray-400" />
                        </div>
                    </div>
                    @error('newPassword')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        التأكيد <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            wire:model="new_password_confirmation"
                            class="w-full pr-10 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                            placeholder="أعد إدخال الجديدة"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <x-heroicon-o-check-circle class="w-4 h-4 text-gray-400" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end">
                <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-wait"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                    <span wire:loading.remove wire:target="updatePassword">
                        <x-heroicon-o-key class="w-5 h-5" />
                    </span>
                    <svg wire:loading wire:target="updatePassword" class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
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
