/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './inc/**/*.php',
        './templates/**/*.php',
        './resources/js/**/*.{js,jsx}',
        './resources/views/**/*.php',
        './safelist.txt'
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#FF700A',
                    light: '#FF8B33',
                    dark: '#E65600'
                },
                secondary: {
                    DEFAULT: '#556677',
                    light: '#6B7D8F',
                    dark: '#3F4D5A'
                },
                dark: {
                    DEFAULT: '#2C3E50',
                    light: '#3D556F',
                    darker: '#1B2631'
                },
                light: {
                    DEFAULT: '#F6F6F6',
                    dark: '#EAECEE',
                    darker: '#CED3D9'
                }
            },
            fontFamily: {
                sans: ['Lato', 'ui-sans-serif', 'system-ui'],
                heading: ['Lato', 'ui-sans-serif', 'system-ui']
            },
            fontSize: {
                'xs': ['0.75rem', { lineHeight: '1rem' }],
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'base': ['1rem', { lineHeight: '1.5rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.25rem', { lineHeight: '1.75rem' }],
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
                '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
                '5xl': ['3rem', { lineHeight: '1.16' }]
            },
            spacing: {
                '128': '32rem',
                '144': '36rem',
            },
            container: {
                center: true,
                padding: {
                    DEFAULT: '1rem',
                    sm: '2rem',
                    lg: '4rem',
                    xl: '5rem',
                    '2xl': '6rem',
                },
            },
            borderRadius: {
                'sm': '0.25rem',
                DEFAULT: '0.375rem',
                'md': '0.5rem',
                'lg': '1rem',
                'xl': '1.5rem',
            },
            boxShadow: {
                'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                DEFAULT: '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1)',
                'md': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1)',
                'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1)',
                'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1)',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.5s ease-in-out',
                'slide-down': 'slideDown 0.5s ease-in-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        // Custom plugin for aspect ratios
        function({ addUtilities }) {
            const aspectRatios = {
                '.aspect-none': {
                    position: 'static',
                    paddingBottom: '0',
                },
                '.aspect-16/9': {
                    position: 'relative',
                    paddingBottom: '56.25%',
                },
                '.aspect-4/3': {
                    position: 'relative',
                    paddingBottom: '75%',
                },
                '.aspect-1/1': {
                    position: 'relative',
                    paddingBottom: '100%',
                },
            }
            addUtilities(aspectRatios, ['responsive'])
        },
    ],
    // Safelist classes that might be used in dynamic content
    safelist: [
        'bg-primary',
        'bg-secondary',
        'text-primary',
        'text-secondary',
        'border-primary',
        'border-secondary',
        {
            pattern: /^(p|m|mt|mb|ml|mr|mx|my)-[0-9]+$/,
            variants: ['sm', 'md', 'lg', 'xl'],
        },
    ],
}
