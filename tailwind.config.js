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
                sans: ['Cairo', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#4C7FF1',
                    50: '#E8F1FC',
                    100: '#D1E3F9',
                    200: '#A3C7F3',
                    300: '#75ACED',
                    400: '#4C7FF1',
                    500: '#2563EB',
                    600: '#1E4FBC',
                    700: '#163B8D',
                    800: '#0F285E',
                    900: '#07142F',
                },
                // Custom action colors
                approve: '#93CFB5',
                'approve-hover': '#7DB89D',
                reject: '#E96B6B',
                'reject-hover': '#D95555',
                forward: '#6B95F1',
                'forward-hover': '#5580E0',
            },
        },
    },

    plugins: [
        forms,
    ],
}
