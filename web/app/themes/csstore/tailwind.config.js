const colors = require('tailwindcss/colors')
require('tailwindcss/')
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
      },
      spacing: {
        'shop-1/4': 'calc(25% - 0.375rem)',
        'shop-1/2': 'calc(50% - 0.25rem)',
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
