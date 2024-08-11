/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
  theme: {
    extend: {
        fontFamily: {
            sans:['Archivo'],
            serif:['Roboto Slab']
        }
    },
  },
  plugins: [],
}

