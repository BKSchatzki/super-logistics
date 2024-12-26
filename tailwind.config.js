import primePlugin from 'tailwindcss-primeui'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
      "./view/dist/*.js",
      "./view/components/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [primePlugin]
}

