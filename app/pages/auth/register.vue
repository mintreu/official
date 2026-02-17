<template>
  <div class="min-h-screen bg-titanium-950 text-white flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg bg-titanium-900/90 border border-titanium-800 rounded-3xl p-8 shadow-2xl backdrop-blur-xl">
      <div class="text-center mb-6">
        <p class="text-sm uppercase tracking-[0.4em] text-titanium-400">MintreuOfficial</p>
        <h1 class="text-3xl font-bold mt-2">Create account</h1>
        <p class="text-titanium-400 text-sm mt-1">Secure access to downloads, licenses and dashboard</p>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div>
          <label class="block text-xs uppercase tracking-[0.3em] text-titanium-400">Name</label>
          <input
            v-model="name"
            type="text"
            required
            placeholder="Jane Doe"
            class="mt-2 w-full rounded-2xl border border-titanium-700 bg-titanium-950/40 px-4 py-3 text-sm focus:outline-none focus:border-mintreu-red-600"
          />
        </div>

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
            required
            placeholder="Choose a secure password"
            class="mt-2 w-full rounded-2xl border border-titanium-700 bg-titanium-950/40 px-4 py-3 text-sm focus:outline-none focus:border-mintreu-red-600"
          />
        </div>

        <div>
          <label class="block text-xs uppercase tracking-[0.3em] text-titanium-400">Confirm password</label>
          <input
            v-model="passwordConfirmation"
            type="password"
            required
            placeholder="Repeat password"
            class="mt-2 w-full rounded-2xl border border-titanium-700 bg-titanium-950/40 px-4 py-3 text-sm focus:outline-none focus:border-mintreu-red-600"
          />
        </div>

        <div>
          <label class="block text-xs uppercase tracking-[0.3em] text-titanium-400">Referral (optional)</label>
          <input
            v-model="referral"
            type="text"
            placeholder="Enter referral code"
            class="mt-2 w-full rounded-2xl border border-titanium-700 bg-titanium-950/40 px-4 py-3 text-sm focus:outline-none focus:border-mintreu-red-600"
          />
        </div>

        <div>
          <label class="block text-xs uppercase tracking-[0.3em] text-titanium-400">OTP code</label>
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
              @click="sendOtp"
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
          {{ loading ? 'Creating account…' : 'Create account' }}
        </button>
      </form>

      <div class="mt-6 text-center text-sm text-titanium-400">
        Already registered?
        <NuxtLink to="/auth/login" class="text-white font-semibold">Sign in</NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const config = useRuntimeConfig()
const router = useRouter()
const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const referral = ref('')
const otp = ref('')
const loading = ref(false)
const sendingOtp = ref(false)
const message = ref('')
const error = ref<string | null>(null)

const sendOtp = async () => {
  if (!email.value) {
    error.value = 'Provide your email before requesting the OTP.'
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
        purpose: 'registration'
      }
    })

    message.value = 'OTP sent. Check your inbox.'
  } catch (err: any) {
    error.value = err?.data?.message || 'Unable to send OTP right now.'
  } finally {
    sendingOtp.value = false
  }
}

const handleRegister = async () => {
  error.value = null
  message.value = ''
  loading.value = true

  try {
    await useSanctumFetch(`${config.public.apiBase}/api/auth/register`, {
      method: 'POST',
      body: {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value,
        referral_code: referral.value || undefined,
        otp: otp.value
      }
    })

    message.value = 'Account created. Please sign in.'
    await router.push('/auth/login')
  } catch (err: any) {
    error.value = err?.data?.message || 'Registration failed. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>
