/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./**/*.{html,js,php}', './php/**/*.{html,js,php}'],
  theme: {
    extend: {
      height: {
          300: '300px',
          82: '82px',
      },
      width: {
          450: '450px',
      },
      margin:{
          415: '415px',
          50 : '50px'
      },
      backgroundColor: {
          mainColor: '#2f3e46',
          // mainColor: '#138275',
          // mainColor : '#028910',
          // mainColor : '#3cec97',
          loginHereBtn : '#198754',
          teleCreateAccColor : '#e6e6e6'
      },
      borderColor: {
          // loginBorder: '#f2f2f2',
          // mainColor: '#2f3e46',
          subDivColor: '#2f3e46',
          // titleDivColor : '#94abb8'
          titleDivColor : '#3cec97',
          sdnRegistraionColor : '#999999',
      },
      borderWidth:{
          415 : '415px'
      }
  } 
  },
  plugins: [],
}