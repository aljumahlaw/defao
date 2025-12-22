<div x-data="{ sidebarOpen: false }">
    {{-- Mobile Hamburger Button --}}
    <button @click="sidebarOpen = !sidebarOpen" 
            class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 shadow-lg border border-gray-200 dark:border-gray-700">
        <x-heroicon-o-bars-3 class="w-6 h-6" />
    </button>

    {{-- Mobile Overlay --}}
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="lg:hidden fixed inset-0 bg-gray-900/50 z-40"
         style="display: none;">
    </div>

    {{-- Sidebar --}}
    <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
           class="lg:translate-x-0 fixed right-0 top-0 h-screen w-64 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 z-40 transform transition-transform duration-300 ease-in-out flex flex-col">
        {{-- Title Section - Centered at top with proper spacing --}}
        <div class="p-6 pb-4">
            <div class="relative flex items-center justify-center">
                <h1 class="text-[28px] leading-[32px] font-bold text-center" style="color: #206663 !important;">Defao</h1>
                <button @click="sidebarOpen = false" class="lg:hidden absolute left-0 p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>
        </div>
        {{-- Navigation --}}
        <nav class="flex-1 flex flex-col space-y-1 px-3">
            @php
            $menuItems = [
                ['route' => 'dashboard', 'icon' => 'home', 'label' => 'لوحة التحكم'],
                ['route' => 'tasks.index', 'icon' => 'clipboard-document-list', 'label' => 'المهام'],
                ['route' => 'documents.index', 'icon' => 'document-text', 'label' => 'الوثائق'],
                ['route' => 'workflow.index', 'icon' => 'arrow-path', 'label' => 'سير العمل'],
                ['route' => 'archive.index', 'icon' => 'archive-box', 'label' => 'الأرشيف'],
                ['route' => 'profile.settings', 'icon' => 'user-circle', 'label' => 'الملف الشخصي'],
            ];
            @endphp

            @foreach($menuItems as $item)
                @php
                $isActive = $item['route'] !== '#' && request()->routeIs($item['route']);
                $iconComponent = match($item['icon']) {
                    'home' => 'heroicon-o-home',
                    'clipboard-document-list' => 'heroicon-o-clipboard-document-list',
                    'document-text' => 'heroicon-o-document-text',
                    'arrow-path' => 'heroicon-o-arrow-path',
                    'archive-box' => 'heroicon-o-archive-box',
                    'cog-6-tooth' => 'heroicon-o-cog-6-tooth',
                    'user-circle' => 'heroicon-o-user-circle',
                    default => 'heroicon-o-home'
                };
                @endphp
                <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
                   @click="sidebarOpen = false"
                   @if($item['route'] === 'dashboard')
                       x-on:keydown.ctrl.d="window.location.href='{{ route('dashboard') }}'"
                       title="Ctrl+D - لوحة التحكم"
                   @elseif($item['route'] === 'documents.index')
                       x-on:keydown.ctrl.t="window.location.href='{{ route('documents.index') }}'"
                       title="Ctrl+T - الوثائق"
                   @endif
                   class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ $isActive ? 'bg-primary/10 text-primary' : 'hover:bg-primary/10 text-gray-700 dark:text-gray-300 hover:text-primary' }}">
                    @if($iconComponent === 'heroicon-o-home')
                        <x-heroicon-o-home @class(['w-5', 'h-5', 'text-primary' => $isActive, 'text-gray-500' => !$isActive, 'dark:text-gray-400' => !$isActive, 'group-hover:text-primary' => !$isActive]) />
                    @elseif($iconComponent === 'heroicon-o-clipboard-document-list')
                        <x-heroicon-o-clipboard-document-list @class(['w-5', 'h-5', 'text-primary' => $isActive, 'text-gray-500' => !$isActive, 'dark:text-gray-400' => !$isActive, 'group-hover:text-primary' => !$isActive]) />
                    @elseif($iconComponent === 'heroicon-o-document-text')
                        <x-heroicon-o-document-text @class(['w-5', 'h-5', 'text-primary' => $isActive, 'text-gray-500' => !$isActive, 'dark:text-gray-400' => !$isActive, 'group-hover:text-primary' => !$isActive]) />
                    @elseif($iconComponent === 'heroicon-o-arrow-path')
                        <x-heroicon-o-arrow-path @class(['w-5', 'h-5', 'text-primary' => $isActive, 'text-gray-500' => !$isActive, 'dark:text-gray-400' => !$isActive, 'group-hover:text-primary' => !$isActive]) />
                    @elseif($iconComponent === 'heroicon-o-archive-box')
                        <x-heroicon-o-archive-box @class(['w-5', 'h-5', 'text-primary' => $isActive, 'text-gray-500' => !$isActive, 'dark:text-gray-400' => !$isActive, 'group-hover:text-primary' => !$isActive]) />
                    @elseif($iconComponent === 'heroicon-o-cog-6-tooth')
                        <x-heroicon-o-cog-6-tooth @class(['w-5', 'h-5', 'text-primary' => $isActive, 'text-gray-500' => !$isActive, 'dark:text-gray-400' => !$isActive, 'group-hover:text-primary' => !$isActive]) />
                    @elseif($iconComponent === 'heroicon-o-user-circle')
                        <x-heroicon-o-user-circle @class(['w-5', 'h-5', 'text-primary' => $isActive, 'text-gray-500' => !$isActive, 'dark:text-gray-400' => !$isActive, 'group-hover:text-primary' => !$isActive]) />
                    @endif
                    @if($item['route'] === 'dashboard')
                        <span class="{{ $isActive ? 'text-primary' : '' }}">لوحة التحكم</span>
                    @elseif($item['route'] === 'documents.index')
                        <span class="{{ $isActive ? 'text-primary' : '' }}">الوثائق</span>
                    @else
                        <span class="{{ $isActive ? 'text-primary' : '' }}">{{ $item['label'] }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        {{-- Date Converter --}}
        <div class="px-3 pb-4 mt-auto" 
             x-data="{
                 showDateModal: false,
                 hijriInput: '',
                 gregorianInput: '',
                 todayHijri: '',
                 todayGregorian: '',
                 hijriMonths: ['محرم', 'صفر', 'ربيع الأول', 'ربيع الثاني', 'جمادى الأولى', 'جمادى الآخرة', 'رجب', 'شعبان', 'رمضان', 'شوال', 'ذو القعدة', 'ذو الحجة'],
                 gregorianMonths: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
                 initDates() {
                     if (typeof window.moment !== 'undefined' && typeof window.moment().iDate === 'function') {
                         const today = window.moment();
                         const iDay = today.iDate();
                         const iMonth = today.iMonth();
                         const iYear = today.iYear();
                         this.todayHijri = iDay + ' ' + this.hijriMonths[iMonth] + ' ' + iYear + ' هـ';
                         this.todayGregorian = today.date() + ' ' + this.gregorianMonths[today.month()] + ' ' + today.year() + ' م';
                         this.hijriInput = today.format('iYYYY-iMM-iDD');
                         this.gregorianInput = today.format('YYYY-MM-DD');
                     } else {
                         this.todayHijri = 'جاري التحميل...';
                         this.todayGregorian = '';
                         setTimeout(() => this.initDates(), 100);
                     }
                 },
                 sanitizeDateInput(value) {
                     const v = (value ?? '').toString();
                     const arabicDigits = '٠١٢٣٤٥٦٧٨٩';
                     const ascii = v.replace(/[٠-٩]/g, (d) => {
                         const idx = arabicDigits.indexOf(d);
                         return idx !== -1 ? String(idx) : d;
                     });
                     return ascii.replace(/[^\d-]/g, '');
                 },
                 convertFromHijri() {
                     if (typeof window.moment === 'undefined') {
                         this.gregorianInput = '';
                         return;
                     }
                     const clean = this.sanitizeDateInput(this.hijriInput);
                     if (!clean || clean.length < 10) {
                         this.gregorianInput = '';
                         return;
                     }
                     try {
                         const hijriMoment = window.moment(clean, 'iYYYY-iMM-iDD');
                         if (hijriMoment.isValid()) {
                             this.gregorianInput = hijriMoment.format('YYYY-MM-DD');
                         } else {
                             this.gregorianInput = '';
                         }
                     } catch (e) {
                         this.gregorianInput = '';
                     }
                 },
                 convertFromGregorian() {
                     if (typeof window.moment === 'undefined') {
                         this.hijriInput = '';
                         return;
                     }
                     const clean = this.sanitizeDateInput(this.gregorianInput);
                     if (!clean || clean.length < 10) {
                         this.hijriInput = '';
                         return;
                     }
                     try {
                         const gregMoment = window.moment(clean, 'YYYY-MM-DD');
                         if (gregMoment.isValid()) {
                             this.hijriInput = gregMoment.format('iYYYY-iMM-iDD');
                         } else {
                             this.hijriInput = '';
                         }
                     } catch (e) {
                         this.hijriInput = '';
                     }
                 }
             }"
             x-init="initDates()">
            {{-- Today's Date Button --}}
            <button @click="showDateModal = true"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors hover:bg-primary/10 text-gray-700 dark:text-gray-300 hover:text-primary">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <div class="flex-1 text-right">
                    <div class="text-xs text-gray-500 dark:text-gray-400">تاريخ اليوم</div>
                    <div class="text-sm font-medium" x-text="todayHijri"></div>
                    <div class="text-xs text-gray-400 dark:text-gray-500" x-text="todayGregorian"></div>
                </div>
            </button>

            {{-- Date Converter Modal - Teleported to body --}}
            <template x-teleport="body">
                <div x-show="showDateModal"
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @keydown.escape.window="showDateModal = false"
                     @click.self="showDateModal = false"
                     class="fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4 backdrop-blur-sm">
                    <div @click.stop
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">تحويل التاريخ (أم القرى)</h3>
                                <button @click="showDateModal = false" 
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">التاريخ الهجري</label>
                                    <input x-model="hijriInput" 
                                           @input="hijriInput = sanitizeDateInput($event.target.value); $nextTick(() => convertFromHijri())" 
                                           placeholder="1447-12-01" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-right focus:ring-2 focus:ring-primary focus:border-primary transition-all" 
                                           dir="ltr">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">التاريخ الميلادي</label>
                                    <input x-model="gregorianInput" 
                                           @input="gregorianInput = sanitizeDateInput($event.target.value); $nextTick(() => convertFromGregorian())" 
                                           placeholder="2025-12-21" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-right focus:ring-2 focus:ring-primary focus:border-primary transition-all" 
                                           dir="ltr">
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-end">
                                <button @click="showDateModal = false" 
                                        class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                    إغلاق
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </aside>
</div>

