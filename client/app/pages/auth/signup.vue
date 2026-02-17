<template>
  <div class="min-h-screen bg-titanium-950 text-white">
    <div class="relative max-w-6xl mx-auto px-4 py-16 lg:py-24">
      <div class="grid gap-10 lg:grid-cols-2 bg-white/5 backdrop-blur-lg rounded-3xl border border-white/10 overflow-hidden shadow-2xl">
        <section class="p-8 lg:p-12 space-y-6 bg-gradient-to-br from-mintreu-red-600 to-mintreu-red-800">
          <p class="text-xs uppercase tracking-[0.4em] text-white/80 font-semibold">Create account</p>
          <h1 class="text-4xl md:text-5xl font-heading font-black leading-tight">
            Join Mintreu
          </h1>
          <p class="text-white/80 text-lg leading-relaxed">
            Get first dibs on new templates, APIs, and SaaS licenses. Manage your purchases, downloads, and API keys from one secure dashboard.
          </p>
          <div class="grid grid-cols-2 gap-4 text-sm text-white/70">
            <div>
              <p class="font-semibold text-white">New releases</p>
              <p>Weekly drops</p>
            </div>
            <div>
              <p class="font-semibold text-white">Support</p>
              <p>24/7 email response</p>
            </div>
          </div>
        </section>

        <section class="p-8 lg:p-12 bg-white dark:bg-titanium-950">
          <div class="space-y-3 mb-6">
            <h2 class="text-3xl font-heading font-black text-titanium-900 dark:text-white">Sign up</h2>
            <p class="text-sm text-titanium-600 dark:text-titanium-300">Create a secure account with email verification.</p>
          </div>

          <form class="space-y-4" @submit.prevent="handleRegister">
            <label class="block space-y-1 text-sm font-semibold text-titanium-600 dark:text-titanium-400">
              Full name
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="Jordan Reed"
                class="w-full rounded-2xl border border-titanium-200 dark:border-titanium-700 px-4 py-3 bg-white dark:bg-titanium-900 text-titanium-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-mintreu-red-500 transition"
              />
            </label>

            <label class="block space-y-1 text-sm font-semibold text-titanium-600 dark:text-titanium-400">
              Email
              <input
                v-model="form.email"
                type="email"
                required
                placeholder="you@email.com"
                class="w-full rounded-2xl border border-titanium-200 dark:border-titanium-700 px-4 py-3 bg-white dark:bg-titanium-900 text-titanium-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-mintreu-red-500 transition"
              />
            </label>

            <div class="grid gap-4 lg:grid-cols-2">
              <label class="block space-y-1 text-sm font-semibold text-titanium-600 dark:text-titanium-400">
                Password
                <input
                  v-model="form.password"
                  type="password"
                  required
                  placeholder="Create password"
                  class="w-full rounded-2xl border border-titanium-200 dark:border-titanium-700 px-4 py-3 bg-white dark:bg-titanium-900 text-titanium-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-mintreu-red-500 transition"
                />
              </label>

              <label class="block space-y-1 text-sm font-semibold text-titanium-600 dark:text-titanium-400">
                Confirm password
                <input
                  v-model="form.password_confirmation"
                  type="password"
                  required
                  placeholder="Repeat password"
                  class="w-full rounded-2xl border border-titanium-200 dark:border-titanium-700 px-4 py-3 bg-white dark:bg-titanium-900 text-titanium-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-mintreu-red-500 transition"
                />
              </label>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-titanium-600 dark:text-titanium-400">Email OTP</label>
              <div class="flex gap-3">
                <input
                  v-model="form.otp"
                  type="text"
                  maxlength="6"
                  required
                  class="flex-1 rounded-2xl border border-titanium-200 dark:border-titanium-700 px-4 py-3 bg-white dark:bg-titanium-900 text-titanium-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-mintreu-red-500 transition"
                  placeholder="Enter OTP"
                />
                <button
                  type="button"
                  @click="sendOtp"
                  :disabled="sendingOtp || otpCooldown > 0 || !form.email"
                  class="px-4 py-3 rounded-2xl border border-mintreu-red-200 bg-white text-sm font-semibold text-mintreu-red-600 hover:border-mintreu-red-400 transition disabled:opacity-60"
                >
                  <span v-if="otpCooldown === 0">
                    {{ sendingOtp ? 'Sending…' : 'Send OTP' }}
                  </span>
                  <span v-else>{{ otpCooldown }}s</span>
                </button>
              </div>
              <p v-if="otpSentMessage" class="text-xs text-mintreu-red-600">{{ otpSentMessage }}</p>
            </div>

            <div v-if="error" class="px-4 py-3 rounded-2xl bg-red-50 text-red-600 text-sm font-medium border border-red-100">
              {{ error }}
            </div>

            <button
              type="submit"
              class="w-full px-4 py-3 rounded-2xl bg-mintreu-red-600 hover:bg-mintreu-red-700 transition text-white font-heading font-semibold shadow-lg"
              :disabled="submitting"
            >
              <span v-if="submitting">Creating account…</span>
              <span v-else>Create account</span>
            </button>
          </form>

          <p class="text-xs text-titanium-500 dark:text-titanium-400 mt-6 text-center">
            Already registered?
            <NuxtLink to="/auth/signin" class="text-mintreu-red-600 font-semibold hover:text-mintreu-red-700">
              Sign in
            </NuxtLink>
          </p>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onUnmounted, ref, reactive } from 'vue'
import { resolveApiError } from '~/utils/api-error'

definePageMeta({
  middleware: ['$guest']
})

const { login } = useSanctum()
const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  otp: ''
})
const sendingOtp = ref(false)
const otpCooldown = ref(0)
const otpSentMessage = ref('')
const submitting = ref(false)
const error = ref<string | null>(null)
let otpTimer: ReturnType<typeof setInterval> | null = null

const startCooldown = (duration: number) => {
  otpCooldown.value = duration
  if (otpTimer) {
    clearInterval(otpTimer)
  }
  otpTimer = setInterval(() => {
    if (otpCooldown.value <= 1) {
      clearInterval(otpTimer!)
      otpCooldown.value = 0
      otpTimer = null
      return
    }
    otpCooldown.value -= 1
  }, 1000)
}

const sendOtp = async () => {
  if (!form.email) {
    error.value = 'Enter your email address before requesting OTP.'
    return
  }

  if (otpCooldown.value > 0) {
    return
  }

  sendingOtp.value = true
  error.value = null
  otpSentMessage.value = ''

  try {
    await useSanctumFetch('/api/auth/otp/send', {
      method: 'POST',
      body: { email: form.email, purpose: 'registration' }
    })
    otpSentMessage.value = 'OTP sent to your email. It expires in 10 minutes.'
    startCooldown(30)
  } catch (err: unknown) {
    error.value = resolveApiError(err, 'Unable to send OTP. Try again in a moment.')
  } finally {
    sendingOtp.value = false
  }
}

const handleRegister = async () => {
  if (!form.name || !form.email || !form.password || !form.password_confirmation || !form.otp) {
    error.value = 'All fields including OTP are required.'
    return
  }

  submitting.value = true
  error.value = null

  try {
    await useSanctumFetch('/api/auth/register', {
      method: 'POST',
      body: {
        name: form.name,
        email: form.email,
        password: form.password,
        password_confirmation: form.password_confirmation,
        otp: form.otp
      }
    })

    await login({
      email: form.email,
      password: form.password
    })
  } catch (err: unknown) {
    error.value = resolveApiError(err, 'Registration failed. Please confirm your OTP.')
  } finally {
    submitting.value = false
  }
}

onUnmounted(() => {
  if (otpTimer) {
    clearInterval(otpTimer)
  }
})
</script>
