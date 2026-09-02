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
                    light: '#EAF3EF',
                },
                surface: {
                    DEFAULT: '#FFFFFF',
                    alt: '#FAFAFA',
                    dark: '#0A0A0A',
                },
                ink: {
                    DEFAULT: '#1A1A1A',
                    secondary: '#6B7280',
                },
                line: {
                    DEFAULT: '#E5E7EB',
                    light: '#F3F4F6',
                },
            },
            borderRadius: {
                none: '0px',
                sm: '2px',
                md: '4px',
                lg: '8px',
                full: '9999px',
            },
            boxShadow: {
                // Maintain flat design: no shadows on cards/buttons.
                // Only allow a hairline ring substitute where elevation is truly needed.
                ring: '0 0 0 1px rgba(229, 231, 235, 0.7)',
            },
            keyframes: {
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-in-down': {
                    '0%': { opacity: '0', transform: 'translateY(-16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'slide-in-left': {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                'slide-in-right': {
                    '0%': { transform: 'translateX(100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                'scale-in': {
                    '0%': { opacity: '0', transform: 'scale(0.96)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                'pop': {
                    '0%': { transform: 'scale(1)' },
                    '40%': { transform: 'scale(1.06)' },
                    '100%': { transform: 'scale(1)' },
                },
                'pulse-dot': {
                    '0%, 100%': { opacity: '1', transform: 'scale(1)' },
                    '50%': { opacity: '0.6', transform: 'scale(0.85)' },
                },
                'spin-slow': {
                    '0%': { transform: 'rotate(0deg)' },
                    '100%': { transform: 'rotate(360deg)' },
                },
            },
            animation: {
                'fade-in': 'fade-in 0.5s ease-out both',
                'fade-in-up': 'fade-in-up 0.5s ease-out both',
                'fade-in-down': 'fade-in-down 0.4s ease-out both',
                'slide-in-left': 'slide-in-left 0.35s ease-out both',
                'slide-in-right': 'slide-in-right 0.35s ease-out both',
                'scale-in': 'scale-in 0.3s ease-out both',
                'pop': 'pop 0.35s ease-out',
                'pulse-dot': 'pulse-dot 1.5s ease-in-out infinite',
                'spin-slow': 'spin-slow 3s linear infinite',
            },
        },
    },

    plugins: [forms],
};
