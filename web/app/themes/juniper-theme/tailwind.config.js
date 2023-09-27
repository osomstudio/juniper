/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.twig",
    "./**/*.php",
    "./**/*.html",
    "./**/*.js",
    "!**/node_modules/**",
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

