/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './vendor/jaocero/radio-deck/resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                roboto: ["Roboto", "sans-serif"],
                lato: ["Lato", "sans-serif"],
                inter: ["Inter", "sans-serif"],
                raleway: ["Raleway", "sans-serif"],
                comfort: ["Comfortaa", "cursive"],
            },


        },
    },
    plugins: [

    ],
}
