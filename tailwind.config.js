/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    theme: {
      extend: {
        colors: {
            primary: '#4f46e5',
        },
        fontFamily: {
            sans: ['Inter', 'Kantumruy Pro', 'sans-serif'],
            heading: ['Figtree', 'sans-serif'],
        }
      },
    },
    plugins: [],
}
