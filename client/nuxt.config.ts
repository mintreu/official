// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: false },
  ssr: false,

  modules: [
    '@tresjs/nuxt',
    '@nuxt/icon',
    '@nuxtjs/tailwindcss',
    '@nuxtjs/color-mode',
    '@qirolab/nuxt-sanctum-authentication',
  ],
  css: ['~/assets/css/tailwind.css'],
  tailwindcss: {
    // Options
  },

  // Laravel Sanctum Configuration
  laravelSanctum: {
    apiUrl: process.env.NUXT_PUBLIC_API_BASE || 'https://account.mintreu.com',
    authMode: 'token',
    userResponseWrapperKey: null,
    token: {
      storageKey: 'mintreu_auth_token',
      provider: 'localStorage',
      responseKey: 'token'
    },
    sanctumEndpoints: {
      login: '/api/auth/login',
      logout: '/api/auth/logout',
      user: '/api/user'
    },
    redirect: {
      enableIntendedRedirect: true,
      loginPath: '/auth/signin',
      guestOnlyRedirect: '/dashboard',
      redirectToAfterLogin: '/dashboard',
      redirectToAfterLogout: '/'
    },
    globalMiddleware: {
      enabled: false
    }
  },

  runtimeConfig: {
    public: {
      // API configuration
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'https://account.mintreu.com',
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'https://mintreu.com',

      // Branding
      appName: process.env.NUXT_PUBLIC_APP_NAME || 'Mintreu',
      appShortName: process.env.NUXT_PUBLIC_APP_SHORT_NAME || 'Mintreu',
      companyName: process.env.NUXT_PUBLIC_COMPANY_NAME || 'Mintreu LLC',
      companyLegalName: process.env.NUXT_PUBLIC_COMPANY_LEGAL_NAME || 'Mintreu LLC',
      tagline: process.env.NUXT_PUBLIC_TAGLINE || 'Precision digital engineering for SaaS + digital products',

      // Contact
      supportEmail: process.env.NUXT_PUBLIC_SUPPORT_EMAIL || 'support@mintreu.com',
      supportPhone: process.env.NUXT_PUBLIC_SUPPORT_PHONE || '+1-415-555-0100',
      companyAddress: process.env.NUXT_PUBLIC_COMPANY_ADDRESS || 'San Francisco, CA',

      // Currency & theming
      currencyCode: process.env.NUXT_PUBLIC_CURRENCY_CODE || 'USD',
      currencySymbol: process.env.NUXT_PUBLIC_CURRENCY_SYMBOL || '$',
      primaryColor: process.env.NUXT_PUBLIC_PRIMARY_COLOR || '#0f172a',
      secondaryColor: process.env.NUXT_PUBLIC_SECONDARY_COLOR || '#0ea5e9',
      themeName: process.env.NUXT_PUBLIC_THEME || 'default',

      // Feature flags
      enablePwa: process.env.NUXT_PUBLIC_ENABLE_PWA !== 'false',
      enableDarkMode: process.env.NUXT_PUBLIC_ENABLE_DARK_MODE !== 'false',
      enableAds: process.env.NUXT_PUBLIC_ENABLE_ADS === 'true',

      // Auth defaults
      signupMode: process.env.NUXT_PUBLIC_SIGNUP_MODE || 'email',

      // Social links
      socialFacebook: process.env.NUXT_PUBLIC_SOCIAL_FACEBOOK || '',
      socialTwitter: process.env.NUXT_PUBLIC_SOCIAL_TWITTER || '',
      socialInstagram: process.env.NUXT_PUBLIC_SOCIAL_INSTAGRAM || '',
      socialLinkedin: process.env.NUXT_PUBLIC_SOCIAL_LINKEDIN || '',
      socialYoutube: process.env.NUXT_PUBLIC_SOCIAL_YOUTUBE || ''
    }
  },

  app: {
    head: {
      title: 'Mintreu | Precision Digital Engineering',
      titleTemplate: '%s | Mintreu Official',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Precision digital engineering for web, mobile & desktop applications' },
        { name: 'format-detection', content: 'telephone=no' }
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
        { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
        { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
        {
          rel: 'stylesheet',
          href: 'https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap'
        }
      ]
    },
    pageTransition: { name: 'page', mode: 'out-in' }
  }

})
