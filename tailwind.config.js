/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{html,js}","./layout/**/*.{html,js,php}","./*.{html,js,php}"],
  theme: {
    extend: {
        fontFamily: {
          poppins: ['Poppins', 'sans-serif'],
        }
    }
},
  plugins: [
    require("daisyui"),
    require("@tailwindcss/typography"),
  ]
};