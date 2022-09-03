module.exports = {
  content: ["./static/*.js", "./templates/**/*.twig"],
  theme: {
    fontFamily: {
      sans: ["neue-haas-grotesk-text", "system-ui"],
      display: ["neue-haas-grotesk-display"],
    },
    extend: {
      colors: {
        "eazy-blue": "#002B63",
        "eazy-dark": "#282828",
        "eazy-cream": "#FAF8F5",
        "eazy-pink": "#d8af91",
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
};
