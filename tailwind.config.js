/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                ruby:  { DEFAULT: '#BE0822', light: '#d40f2a', dark: '#8c0416' },
                rose:  { DEFAULT: '#E86975', light: '#f08590', dark: '#d14d5b' },
                pink:  { DEFAULT: '#EFAAB0', light: '#f8d0d4', dark: '#d97c85' },
                cream: { DEFAULT: '#EED7C8', light: '#f8ede5', dark: '#d4b8a0' },
                ivory: { DEFAULT: '#FFF9F5', light: '#ffffff', dark: '#f0e8e0' },
                coral: { DEFAULT: '#FD9898', light: '#ffbebe', dark: '#e07070' },
            },

            fontFamily: {
                sans:    ['"DM Sans"', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                display: ['"Playfair Display"', 'Georgia', 'serif'],
            },

            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },

            backdropBlur: {
                xs: '2px',
                '4xl': '72px',
            },

            boxShadow: {
                'glass-sm': '0 4px 16px rgba(190, 8, 34, 0.10)',
                'glass':    '0 8px 32px rgba(190, 8, 34, 0.14)',
                'glass-lg': '0 16px 48px rgba(190, 8, 34, 0.18)',
                'btn':      '0 4px 20px rgba(190, 8, 34, 0.32)',
                'btn-hover':'0 8px 28px rgba(190, 8, 34, 0.44)',
            },

            animation: {
                'fade-up':   'fadeSlideUp 0.4s ease both',
                'spin-slow': 'spin 2s linear infinite',
                'pulse-gps': 'pulseGps 1.5s ease-out infinite',
            },

            keyframes: {
                fadeSlideUp: {
                    from: { opacity: '0', transform: 'translateY(20px)' },
                    to:   { opacity: '1', transform: 'translateY(0)' },
                },
                pulseGps: {
                    '0%, 100%': { transform: 'scale(1)',   opacity: '1' },
                    '50%':      { transform: 'scale(1.8)', opacity: '0' },
                },
            },
        },
    },

    plugins: [],
};
