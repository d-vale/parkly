import tailwindcss from "@tailwindcss/vite";
// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: "2025-07-15",
  devtools: { enabled: true },
  css: ["~/assets//main.css"],
  app: {
    head: {
      title: "Parkly",
      meta: [
        {
          name: "description",
          content: "Application de gestion et réservation de places de parking",
        },
      ],
    },
  },
  vite: {
    plugins: [tailwindcss()],
  },
});
