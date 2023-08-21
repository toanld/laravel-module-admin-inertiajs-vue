const plugin = require('tailwindcss/plugin')

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './resources/js/**/*.{vue,js,ts,jsx,tsx}',
    './Modules/*/Resources/**/*.blade.php',
    './Modules/*/Resources/**/*.{vue,js,ts,jsx,tsx}',
    './Modules/**/Resources/**/*.*',
    './Modules/**/Resources/Vuejs/**/*.{vue,js,ts,jsx,tsx}',
    './resources/js/**/*.js',
  ],
  plugins: [
    require('@tailwindcss/forms'),
    plugin(function ({ matchUtilities, theme }) {
      matchUtilities(
        {
          'aside-scrollbars': value => {
            const track = value === 'light' ? '100' : '900'
            const thumb = value === 'light' ? '300' : '600'
            const color = value === 'light' ? 'gray' : value

            return {
              scrollbarWidth: 'thin',
              scrollbarColor: `${theme(`colors.${color}.${thumb}`)} ${theme(`colors.${color}.${track}`)}`,
              '&::-webkit-scrollbar': {
                width: '8px',
                height: '8px'
              },
              '&::-webkit-scrollbar-track': {
                backgroundColor: theme(`colors.${color}.${track}`)
              },
              '&::-webkit-scrollbar-thumb': {
                borderRadius: '0.25rem',
                backgroundColor: theme(`colors.${color}.${thumb}`)
              }
            }
          }
        },
        { values: theme('asideScrollbars') }
      )
    }),
    require('@tailwindcss/line-clamp')
  ]
}
