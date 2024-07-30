/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "node_modules/preline/dist/*.js",
    ],
    darkMode: "class", // class mean dark mode will be activated by adding class="dark" to the body tag
    theme: {
        extend: {},
    },
    plugins: [require("preline/plugin")],
};
