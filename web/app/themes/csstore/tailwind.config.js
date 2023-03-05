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
        primary: colors.pink[400],
        alternative: colors.pink[500],
        secondary: colors.gray[500],
        success: colors.green[400],
        danger: colors.red[400],
        warning: colors.amber[400],
        info: colors.blue[400],
        light: colors.gray[100],
        dark: colors.gray[800],
        muted: colors.gray[300],
      },
      fontSize: {
        '2xs': '0.625rem'
      },
      spacing: {
        'shop-1/4': 'calc(25% - 0.375rem)',
        'shop-1/3': 'calc(33.33% - .5rem)',
        'shop-2/3': 'calc(66.66% - .5rem)',
        'shop-1/2': 'calc(50% - 0.25rem)',
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
