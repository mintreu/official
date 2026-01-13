// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  ssr: false,

  modules: ['@nuxt/icon', '@nuxtjs/tailwindcss', '@nuxtjs/color-mode', '@qirolab/nuxt-sanctum-authentication'],
  tailwindcss: {
    // Options
  },

  // ✅ Laravel Sanctum Configuration
  laravelSanctum: {
    apiUrl: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000',
    authMode: 'token',
    userResponseWrapperKey: 'data',
    token: {
      storageKey: 'mintreu_auth_token',
      provider: 'cookie',
      responseKey: 'token'
    },
    sanctumEndpoints: {
      login: '/api/login',
      logout: '/api/logout',
      user: '/api/user'
    },
    redirect: {
      enableIntendedRedirect: true,
      loginPath: '/auth/login',
      guestOnlyRedirect: '/',
      redirectToAfterLogin: '/',
      redirectToAfterLogout: '/'
    },
    globalMiddleware: {
      enabled: false
    }
  },

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000'
    }
  },

  app: {
    head: {
      title: 'Portfolio - Creative Digital Agency',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Premium design and development services for modern businesses' },
        { name: 'format-detection', content: 'telephone=no' }
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
      ]
    },
    pageTransition: { name: 'page', mode: 'out-in' }
  }

})
