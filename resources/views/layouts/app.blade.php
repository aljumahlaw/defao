<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Language & RTL/LTR Styles -->
        <style>
            .rtl {
                direction: rtl;
                text-align: right;
            }
            .ltr {
                direction: ltr;
                text-align: left;
            }
            [dir="rtl"] {
                direction: rtl;
                text-align: right;
            }
            [dir="ltr"] {
                direction: ltr;
                text-align: left;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <x-sidebar />
            
            <div class="lg:mr-64 pt-16 lg:pt-0">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
        
        {{-- Toast Notifications --}}
        <x-toast />
        
        {{-- Dark Mode & Language Initialization --}}
        <script>
            // Initialize dark mode and language from localStorage on page load
            (function() {
                // Dark mode
                const savedDarkMode = localStorage.getItem('darkMode');
                if (savedDarkMode === 'true') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                
                // Language
                const savedLanguage = localStorage.getItem('language') || 'ar';
                document.documentElement.setAttribute('dir', savedLanguage === 'ar' ? 'rtl' : 'ltr');
                document.documentElement.setAttribute('lang', savedLanguage);
                document.body.classList.toggle('rtl', savedLanguage === 'ar');
                document.body.classList.toggle('ltr', savedLanguage === 'en');
            })();
        </script>
    </body>
</html>
