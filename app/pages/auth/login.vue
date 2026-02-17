<template>
  <div class="min-h-screen bg-titanium-950 text-white flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md bg-titanium-900/80 border border-titanium-800 rounded-3xl p-8 shadow-2xl backdrop-blur-xl">
      <div class="text-center mb-6">
        <p class="text-sm uppercase tracking-[0.4em] text-titanium-400">MintreuOfficial</p>
        <h1 class="text-3xl font-bold mt-2">Sign in</h1>
        <p class="text-titanium-400 text-sm mt-1">Access your dashboard and product licenses</p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-xs uppercase tracking-[0.3em] text-titanium-400">Email</label>
          <input
            v-model="email"
            type="email"
            required
            placeholder="you@example.com"
            class="mt-2 w-full rounded-2xl border border-titanium-700 bg-titanium-950/40 px-4 py-3 text-sm focus:outline-none focus:border-mintreu-red-600"
          />
        </div>

        <div>
          <label class="block text-xs uppercase tracking-[0.3em] text-titanium-400">Password</label>
          <input
            v-model="password"
            type="password"
            placeholder="••••••••"
            class="mt-2 w-full rounded-2xl border border-titanium-700 bg-titanium-950/40 px-4 py-3 text-sm focus:outline-none focus:border-mintreu-red-600"
          />
        </div>

        <div>
          <label class="block text-xs uppercase tracking-[0.3em] text-titanium-400">One-time code (optional)</label>
          <div class="mt-2 flex gap-3">
            <input
              v-model="otp"
              type="text"
              maxlength="6"
              placeholder="Enter OTP"
              class="flex-1 rounded-2xl border border-titanium-700 bg-titanium-950/40 px-4 py-3 text-sm focus:outline-none focus:border-mintreu-red-600"
            />
            <button
              type="button"
              @click="handleSendOtp"
              :disabled="sendingOtp || !email"
              class="px-4 py-3 rounded-2xl border border-titanium-700 bg-mintreu-red-600 hover:bg-mintreu-red-500 text-xs font-semibold uppercase tracking-[0.3em]"
            >
              {{ sendingOtp ? 'Sending…' : 'Send OTP' }}
            </button>
          </div>
        </div>

        <div v-if="message" class="text-sm text-emerald-300">{{ message }}</div>
        <div v-if="error" class="text-sm text-rose-400">{{ error }}</div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full rounded-2xl bg-gradient-to-r from-mintreu-red-600 to-mintreu-red-500 py-3 text-sm font-semibold uppercase tracking-[0.3em] text-white hover:opacity-90 transition-opacity"
        >
          {{ loading ? 'Signing in…' : 'Sign In' }}
        </button>
      </form>

      <div class="mt-6 text-center text-sm text-titanium-400">
        <NuxtLink to="/auth/register" class="text-white font-semibold">Create an account</NuxtLink>
        <span class="mx-2">•</span>
        <NuxtLink to="/auth/forgot-password" class="text-white font-semibold">Forgot password?</NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const { login } = useSanctum()
const config = useRuntimeConfig()
const email = ref('')
const password = ref('')
const otp = ref('')
const loading = ref(false)
const sendingOtp = ref(false)
const error = ref<string | null>(null)
const message = ref('')

const handleSendOtp = async () => {
  if (!email.value) {
    error.value = 'Enter your email to receive the OTP'
    return
  }

  sendingOtp.value = true
  error.value = null
  message.value = ''

  try {
    await useSanctumFetch(`${config.public.apiBase}/api/auth/otp/send`, {
      method: 'POST',
      body: {
        type: 'email',
        value: email.value,
        purpose: 'login'
      }
    })

    message.value = 'OTP sent to your inbox. Check for the code.'
  } catch (err: any) {
    error.value = err?.data?.message || 'Unable to send OTP right now.'
  } finally {
    sendingOtp.value = false
  }
}

const handleLogin = async () => {
  error.value = null
  loading.value = true

  try {
    await login({
      email: email.value,
      password: password.value || undefined,
      otp: otp.value || undefined,
      device_name: 'nuxt'
    })

    await router.push('/')
  } catch (err: any) {
    error.value = err?.data?.message || 'Login failed. Check your credentials.'
  } finally {
    loading.value = false
  }
}
</script>
