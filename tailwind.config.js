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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                primary: {
                    DEFAULT: '#398263',
                    hover: '#2C6B4F',
                },
                surface: {
                    DEFAULT: '#FFFFFF',
                    dark: '#0A0A0A',
                }
            },
            borderRadius: {
                none: '0px',
                sm: '2px',
                md: '4px',
                lg: '8px',
                full: '9999px',
            },
        },
    },

    plugins: [forms],
};
