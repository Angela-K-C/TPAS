import defaultTheme from 'tailwindcss/defaultTheme.js'

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
        'brand-primary': '#4F46E5',     // Indigo-600: For primary buttons, links, and headers
                'brand-secondary': '#10B981',   // Emerald-500: For success messages, or accents
                'brand-danger': '#EF4444',      // Red-500: For the 'Report Lost ID' button/errors
                'brand-text': '#1F2937',        // Gray-800: For primary text
                'brand-muted': '#6B7280',       // Gray-500: For subtle/secondary text
                'brand-bg': '#F9FAFB',
      },

      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

