// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  app: {
    head: {
      title: 'Parkly',
      meta: [
        { name: 'description', content: 'Application de gestion et réservation de places de parking' }
      ]
    }
  }
})
