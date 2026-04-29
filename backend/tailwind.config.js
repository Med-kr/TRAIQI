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
            colors: {
                traiqi: {
                    blue: '#0B4EA2',
                    navy: '#072B61',
                    green: '#17A34A',
                    gold: '#D4A017',
                    sand: '#F8FAFC',
                    ink: '#0F172A',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', 'Inter', ...defaultTheme.fontFamily.sans],
                arabic: ['Cairo', ...defaultTheme.fontFamily.sans],
                tifinagh: ['Noto Sans Tifinagh', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                glow: '0 24px 80px rgba(11, 78, 162, 0.18)',
                soft: '0 18px 45px rgba(15, 23, 42, 0.10)',
            },
            backgroundImage: {
                'traiqi-hero':
                    'linear-gradient(135deg, rgba(7,43,97,0.98) 0%, rgba(11,78,162,0.92) 48%, rgba(23,163,74,0.85) 100%)',
                'traiqi-gold':
                    'linear-gradient(135deg, rgba(11,78,162,0.16) 0%, rgba(212,160,23,0.18) 100%)',
                'traiqi-path':
                    'radial-gradient(circle at top, rgba(212,160,23,0.20), transparent 42%), linear-gradient(180deg, rgba(11,78,162,0.10), transparent 60%)',
            },
        },
    },

    plugins: [forms],
};
