/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Sora', 'sans-serif']
      },
    },
    screens: {
      'xxs': { 'max': '290px' },
      'xss': { 'max': '320px' },
      'xs': { 'max': '480px' },
      'ss': { 'max': '620px' },
      'ss+': { 'max': '697px' },
      'sm': { 'max': '768px' },
      'mds': { 'max': '860px' }, // En dessous de 860px de large par exemple "hidden"
      'md': { 'max': '1060px' },
      'lg': { 'max': '1200px' },
      'lg-xl': { 'max': '1325px' },
      'xl': { 'max': '9999px' },
      'xxs-min': { 'min': '290px' },
      'xss-min': { 'min': '320px' },
      'xs-min': { 'min': '480px' },
      'ss-min': { 'min': '620px' },
      'ss+-min': { 'min': '697px' },
      'sm-min': { 'min': '768px' },
      'mds-min': { 'min': '860px' }, // Au dessus de 860px de large par exemple "hidden"
      'md-min': { 'min': '1060px' },
      'lg-min': { 'min': '1200px' },
      'xl-min': { 'min': '1700px' },
    }
  },
  plugins: [require("daisyui")],
  daisyui: {
    themes: [
      {
        night: {
          ...require("daisyui/src/theming/themes")["[data-theme=night]"],
          "primary": "#3ABEF7",
          "primary-focus": "#18B3F7",
          "primary-content": "#182730",
          "secondary": "#9882f8",
          "secondary-focus": "#7c60f7",
          "secondary-content": "#1E1E2E",
          "accent": "#F470B4",
          "accent-focus": "#F250A3",
          "accent-content": "#301C25",
          "base-content": "#FFFFFF",
          "base-100": "#11111a",
          "base-200": "#212133",
          "base-300": "#32324d",
        },
        light: {
          ...require("daisyui/src/theming/themes")["[data-theme=light]"],
          "primary": "#3ABEF7",
          "primary-focus": "#18B3F7",
          "primary-content": "#182730",
          "secondary": "#9882f8",
          "secondary-focus": "#7c60f7",
          "secondary-content": "#1E1E2E",
          "accent": "#F470B4",
          "accent-focus": "#F250A3",
          "accent-content": "#301C25",
          "base-content": "#11111a",
          "base-100": "#FFFFFF",
          "base-200": "#e6e6e6",
          "base-300": "#cccccc",
        },
      },
    ],
  },
}