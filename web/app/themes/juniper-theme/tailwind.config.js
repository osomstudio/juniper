/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.twig",
    "./**/*.php",
    "./**/*.html",
    "!**/vendor/**"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

