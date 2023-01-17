const colors = require('tailwindcss/colors')
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.twig',
    './views/woocommerce/**/*.twig',
    // './blocks/**/*.php',
    // './templates/**/*.js',
    // "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'primary': colors.pink[500],
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
