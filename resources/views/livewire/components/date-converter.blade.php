<div>
    {{-- Date Converter Button --}}
    <button @click="showModal = true"
            class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white p-2 -m-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
        </svg>
        <span>تحويل التاريخ</span>
    </button>

    {{-- Date Converter Modal --}}
    <div x-show="showModal" 
         @click.away="showModal = false" 
         @keydown.escape.window="showModal = false" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm print:hidden" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         x-data="{
             showModal: false,
             hijriInput: @js($todayHijri ?? '1447-12-01'),
             gregorianInput: @js($todayGregorian ?? '2025-12-21'),
             init() {
                 // Ensure moment-hijri is loaded
                 if (typeof window.moment !== 'undefined' && window.moment.locale) {
                     window.moment.locale('ar-SA');
                 }
             },
             sanitizeDateInput(value) {
                 const v = (value ?? '').toString();
                 // Convert Arabic-Indic digits (٠-٩) to ASCII (0-9)
                 const arabicDigits = '٠١٢٣٤٥٦٧٨٩';
                 const ascii = v.replace(/[٠-٩]/g, (d) => {
                     const idx = arabicDigits.indexOf(d);
                     return idx !== -1 ? String(idx) : d;
                 });
                 // Keep only digits and dashes
                 return ascii.replace(/[^\d-]/g, '');
             },
             convertFromHijri() {
                 if (typeof window.moment === 'undefined') {
                     this.gregorianInput = '';
                     return;
                 }
                 const clean = this.sanitizeDateInput(this.hijriInput);
                 if (!clean) {
                     this.gregorianInput = '';
                     return;
                 }
                 try {
                     const hijriMoment = window.moment(clean, 'iYYYY-iMM-iDD', true);
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
                 if (!clean) {
                     this.hijriInput = '';
                     return;
                 }
                 try {
                     const gregMoment = window.moment(clean, 'YYYY-MM-DD', true);
                     if (gregMoment.isValid()) {
                         this.hijriInput = gregMoment.format('iYYYY-iMM-iDD');
                     } else {
                         this.hijriInput = '';
                     }
                 } catch (e) {
                     this.hijriInput = '';
                 }
             },
             toArabicDigits(num) {
                 const arabicDigits = '٠١٢٣٤٥٦٧٨٩';
                 return String(num).replace(/[0-9]/g, (d) => arabicDigits[parseInt(d)]);
             },
             formatHijriDisplay(value) {
                 if (!value) return '';
                 const parts = value.split('-');
                 if (parts.length === 3) {
                     return this.toArabicDigits(parts[0]) + '-' + this.toArabicDigits(parts[1]) + '-' + this.toArabicDigits(parts[2]);
                 }
                 return value;
             },
             formatGregorianDisplay(value) {
                 if (!value) return '';
                 const parts = value.split('-');
                 if (parts.length === 3) {
                     return this.toArabicDigits(parts[0]) + '-' + this.toArabicDigits(parts[1]) + '-' + this.toArabicDigits(parts[2]);
                 }
                 return value;
             }
         }">
        <div @click.away="null" 
             class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto p-1" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">تحويل التاريخ (أم القرى)</h3>
                    <button @click="showModal = false; hijriInput = @js($todayHijri ?? '1447-12-01'); gregorianInput = @js($todayGregorian ?? '2025-12-21')" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    {{-- Hijri Input --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">التاريخ الهجري</label>
                        <input x-model="hijriInput" 
                               @input.debounce.250ms="convertFromHijri" 
                               placeholder="1447-12-01" 
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-right focus:ring-2 focus:ring-primary focus:border-primary transition-all" 
                               dir="ltr">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="hijriInput">
                            <span x-text="formatHijriDisplay(hijriInput)"></span>
                        </p>
                    </div>
                    
                    {{-- Gregorian Input --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">التاريخ الميلادي</label>
                        <input x-model="gregorianInput" 
                               @input.debounce.250ms="convertFromGregorian" 
                               placeholder="2025-12-21" 
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-right focus:ring-2 focus:ring-primary focus:border-primary transition-all" 
                               dir="ltr">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="gregorianInput">
                            <span x-text="formatGregorianDisplay(gregorianInput)"></span>
                        </p>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end gap-3">
                    <button @click="showModal = false; hijriInput = @js($todayHijri ?? '1447-12-01'); gregorianInput = @js($todayGregorian ?? '2025-12-21')" 
                            class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>









