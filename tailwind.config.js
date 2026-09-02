/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./*.php', './includes/**/*.php'],
  theme: {
    extend: {
      colors: {
        emerald: { DEFAULT: '#173F35', dark: '#0d2a24', light: '#1f5245' },
        sage: '#8FAE5D',
        sand: '#F6F3EC',
        gold: '#D8C7A1'
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        serif: ['Playfair Display', 'serif']
      }
    }
  }
};
