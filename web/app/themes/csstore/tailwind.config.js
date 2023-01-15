/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './web/app/themes/di-flowers/templates/**/*.twig',
    './web/app/themes/di-flowers/views/**/*.twig',
    // './web/app/themes/di-flowers/blocks/**/*.php',
    './web/app/themes/di-flowers/templates/**/*.js',
    // "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
