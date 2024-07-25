/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
  ],
    theme: {
        fontSize: {
            'xs': '.75rem',
            'sm': '.875rem',
            'tiny': '.875rem',
            'base': '1rem',
            'md': '1.25rem',
            'lg': '1.5rem',
            'xl': '1.6rem',
            '1xl': '1.7rem',
            '2xl': '1.8rem',
            '3xl': '1.9rem',
            '4xl': '2.25rem',
            '5xl': '3rem',
            '6xl': '4rem',
            '7xl': '5rem',
            '8xl': '6rem',
            '9xl': '7rem',
    },
    extend: {
        fontFamily: {
            source: ['Source Code Pro', 'monospace'],
        },
        boxShadow: {
            'inner-shadow': 'inset 4px 4px 8px 2px rgb(0 0 0 / 28%)',
        },
    },
},
    plugins: [],
}

