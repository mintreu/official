<template>
  <div class="min-h-screen bg-gradient-to-br from-mintreu-red-900/90 to-titanium-900 text-white">
    <div class="relative max-w-6xl mx-auto px-4 py-16 lg:py-24">
      <div class="max-w-5xl mx-auto grid gap-10 lg:grid-cols-2 bg-white/5 backdrop-blur-lg rounded-3xl border border-white/10 shadow-2xl overflow-hidden">
        <section class="p-10 space-y-8">
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-mintreu-red-200 font-semibold mb-3">Member access</p>
            <h1 class="text-4xl md:text-5xl font-heading font-black leading-tight text-white">
              Sign in to the Mintreu portal
            </h1>
          </div>
          <p class="text-white/70 text-lg font-medium leading-relaxed">
            Continue your journey for precision digital engineering, manage orders, download licenses, and unlock API dashboards.
          </p>
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
              <p class="text-xs uppercase tracking-wide text-white/60">Trusted partners</p>
              <p class="text-sm font-semibold text-white">200+</p>
            </div>
            <div class="space-y-1">
              <p class="text-xs uppercase tracking-wide text-white/60">Avg. response</p>
              <p class="text-sm font-semibold text-white">3 hrs</p>
            </div>
          </div>
        </section>

        <section class="p-10 bg-white dark:bg-titanium-950">
          <div class="flex flex-col gap-2 mb-6">
            <h2 class="text-3xl font-heading font-black text-titanium-900 dark:text-white">Sign in</h2>
            <p class="text-sm text-titanium-600 dark:text-titanium-300">
              Use your registered email and password.
            </p>
          </div>

          <form class="space-y-4" @submit.prevent="handleLogin">
            <label class="block space-y-1 text-sm font-semibold text-titanium-600 dark:text-titanium-400">
              Email address
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full px-4 py-3 rounded-2xl border border-titanium-200 dark:border-titanium-700 bg-white dark:bg-titanium-900 text-titanium-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-mintreu-red-500 transition"
                placeholder="you@email.com"
              />
            </label>

            <label class="block space-y-1 text-sm font-semibold text-titanium-600 dark:text-titanium-400">
              Password
              <input
                v-model="form.password"
                type="password"
                required
                class="w-full px-4 py-3 rounded-2xl border border-titanium-200 dark:border-titanium-700 bg-white dark:bg-titanium-900 text-titanium-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-mintreu-red-500 transition"
                placeholder="••••••••"
              />
            </label>

            <div class="flex items-center justify-between text-sm">
              <div class="text-xs text-titanium-500 dark:text-titanium-400">
                New here?
                <NuxtLink to="/auth/signup" class="text-mintreu-red-600 font-semibold hover:text-mintreu-red-700">
                  Create account
                </NuxtLink>
              </div>
              <NuxtLink to="/auth/forgot-password" class="text-xs text-mintreu-red-600 font-semibold hover:text-mintreu-red-700">
                Forgot password?
              </NuxtLink>
            </div>

            <div v-if="error" class="px-4 py-3 rounded-2xl bg-red-50 text-red-600 text-sm font-medium border border-red-100">
              {{ error }}
            </div>

            <div class="space-y-3">
              <p class="text-xs uppercase tracking-[0.5em] text-titanium-500">Or continue with</p>
              <div class="flex gap-3">
                <button
                  v-for="provider in socialProviders"
                  :key="provider.provider"
                  type="button"
                  class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-2xl border bg-white/90 text-sm font-semibold uppercase tracking-[0.4em] shadow-sm transition"
                  :class="provider.borderClass"
                  :style="provider.style"
                  @click="handleSocialLogin(provider.provider)"
                >
                  <Icon :name="provider.icon" class="w-4 h-4" />
                  <span>{{ provider.label }}</span>
                </button>
              </div>
            </div>

            <button
              type="submit"
              class="w-full px-4 py-3 rounded-2xl bg-mintreu-red-600 hover:bg-mintreu-red-700 transition text-white font-heading font-semibold shadow-lg"
              :disabled="loading"
            >
              <span v-if="loading">Signing in…</span>
              <span v-else>Sign In securely</span>
            </button>
          </form>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { resolveApiError } from '~/utils/api-error'

definePageMeta({
  middleware: ['$guest']
})

const config = useRuntimeConfig()
const { login, refreshUser } = useSanctum()
const router = useRouter()
const form = reactive({
  email: '',
  password: ''
})
const loading = ref(false)
const error = ref<string | null>(null)

const socialProviders = [
  {
    label: 'Google',
    icon: 'lucide:google',
    provider: 'google',
    borderClass: 'border-[#db4437]',
    style: 'color: #db4437'
  },
  {
    label: 'GitHub',
    icon: 'lucide:github',
    provider: 'github',
    borderClass: 'border-[#24292f]',
    style: 'color: #24292f'
  }
]

const handleLogin = async () => {
  if (!form.email || !form.password) {
    error.value = 'Email and password are required.'
    return
  }

  loading.value = true
  error.value = null

  try {
    await login({
      email: form.email,
      password: form.password
    })
    await refreshUser()
    await router.push('/dashboard')
  } catch (err: unknown) {
    error.value = resolveApiError(err, 'Unable to sign in with the provided credentials.')
  } finally {
    loading.value = false
  }
}

const handleSocialLogin = (provider: string) => {
  const redirectUrl = `${config.public.apiBase}/api/auth/oauth/${provider}`
  window.location.assign(redirectUrl)
}
</script>
