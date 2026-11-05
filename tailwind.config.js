/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/views/**/*.php",
    "./admin/views/**/*.php",
    "./assets/js/**/*.js",
    "./index.php"
  ],
  theme: {
    extend: {
      colors: {
        atelier: {
          stone: '#EAE8E3',
          frame: '#F7F7F5',
          card1: '#E5E4E0',
          card2: '#E1DFDB',
          card3: '#ECEAE6',
          ink: '#18181B',
          charcoal: '#18181B'
        },
        admin: {
          sidebar: '#0B0F19',
          dark: '#0F172A',
          primary: '#0F172A',
          accent: '#10B981',
          surface: '#F8FAFC',
          border: '#E2E8F0',
        }
      },
      fontFamily: {
        heading: ['"Outfit"', 'sans-serif'],
        sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
        mono: ['"JetBrains Mono"', 'monospace']
      }
    },
  },
  plugins: [],
}
