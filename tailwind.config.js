/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
        fontFamily: {
            primary: "Panton",
        },
        colors: {
            'gray': {
                'light': '#F2F2F2',
                'normal': '#707070',
                'dark': '#464646',
                'table': '#1E293A',
                'row': '#475569',
                'hoverBtn': '#374151',
            },
            'yellow': {
                'normal': '#FFD200',
                'dark': '#EEAA02',
            },
            'red': {
                'normal': '#D9262E'
            },
            'blue': {
                'normal': '#1e40af'
            },
            'cherry': '#841530',
            'green': {
                'normal': '#62A731'
            },
            'dark': {
                'fondo': '#111827',
                'fondo2': '#465569',
                'fondo3': '#495567',
                'boton': '#1E40AF',
            }
        },
    },
  },
  plugins: [],
}

