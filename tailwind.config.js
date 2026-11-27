import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                eventra: {
                    navy: '#23238E',
                    blue: '#3C84D8',
                    pink: '#F5A4E3',
                    peach: '#FCE2C1',
                    blush: '#FAD0D8',
                    surface: '#F6F8FC',
                    ink: '#18184A',
                    muted: '#646B8A',
                    border: '#E2E7F5',
                },
            },
        },
    },

    plugins: [forms],
};
