/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./mockup/index.html"],
    theme: {
        extend: {
            backgroundImage: {
                "gradient-news":
                    "radial-gradient(circle, rgba(246,141,72,1) 0%, rgba(237,30,99,1) 53%, rgba(55,37,105,1) 100%)",
            },
        },
        fontFamily: {
            sans: ["banana-grotesk", "sans-serif"],
        },
    },
    plugins: [],
};
