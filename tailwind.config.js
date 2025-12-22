import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                cairo: ['Cairo', 'sans-serif'],
            },
            colors: {
                primary: {
                    50: '#f8fcfb',
                    100: '#e6f7f5',
                    200: '#cbeeec',
                    300: '#a8e4e1',
                    400: '#7fdad5',
                    500: '#58BEBA',
                    600: '#3fa8a6',
                    700: '#329492',
                    800: '#257f7d',
                    900: '#1d6a68',
                    950: '#0e3837',
                    DEFAULT: '#58BEBA',
                },
                success: '#059669',
                danger: '#dc2626',
                warning: '#d97706',
            },
        },
    },

    plugins: [
        forms,
    ],
}

