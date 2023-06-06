/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.twig",
    "./**/*.php",
    "./**/*.html",
    "!**/vendor/**"
  ],
  theme: {
    extend: {
      colors: {
        primary: '#B4D43D',
        dark: '#093642'
      },
    },
  },
  plugins: [],
}

