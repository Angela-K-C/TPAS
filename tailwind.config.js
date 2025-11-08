import defaultTheme from 'tailwindcss/defaultTheme.js'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.js',
  ],
  theme: {
    extend: {

      colors: {
        'iris': '#5B61F6',
        'deep-slate': '#343645',
        'lilac': '#C8CCFF',
        'mint': '#52E0C4',
        'amber': '#FFB347',
        'warm-gray': '#9CA3AF',
        'canvas': '#F7F8FC',
        'stroke': '#E5E7EB',
      },

      fontFamily: {
        sans: ['Instrument Sans', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [
    forms,
  ],
}
