<template>
  <div class="min-h-screen bg-gradient-to-br from-titanium-950 to-titanium-900 text-white">
    <div class="max-w-4xl mx-auto px-4 py-16">
      <div class="bg-white/5 border border-white/10 rounded-3xl shadow-2xl p-10 space-y-8">
        <div>
          <p class="text-xs uppercase tracking-[0.4em] text-mintreu-red-200 font-semibold mb-2">Need help?</p>
          <h1 class="text-4xl font-heading font-black leading-tight">Forgot your password?</h1>
          <p class="text-white/70 mt-3 text-lg">
            Enter your email and we will send a reset token to get you back into the dashboard securely.
          </p>
        </div>

        <form class="space-y-4" @submit.prevent="handleSubmit">
          <label class="block text-sm font-semibold text-white/70">
            Email address
            <input
              v-model="email"
              type="email"
              required
              placeholder="you@email.com"
              class="w-full mt-2 rounded-2xl border border-white/20 bg-white/80 text-titanium-900 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-mintreu-red-500 transition"
            />
          </label>

          <div v-if="successMessage" class="px-4 py-3 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100 text-sm">
            {{ successMessage }}
          </div>

          <div v-if="error" class="px-4 py-3 bg-red-50 text-red-600 rounded-2xl border border-red-100 text-sm">
            {{ error }}
          </div>

          <button
            type="submit"
            class="w-full px-4 py-3 rounded-2xl bg-white text-mintreu-red-700 font-heading font-semibold shadow-lg hover:bg-white/90 transition"
            :disabled="loading"
          >
            <span v-if="loading">Sending instructions…</span>
            <span v-else>Send reset link</span>
          </button>
        </form>

        <p class="text-xs text-white/60">Remembered? <NuxtLink to="/auth/signin" class="text-mintreu-red-400 font-semibold">Sign in</NuxtLink></p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { resolveApiError } from '~/utils/api-error'

definePageMeta({
  middleware: ['$guest']
})

const email = ref('')
const loading = ref(false)
const error = ref<string | null>(null)
const successMessage = ref('')

const handleSubmit = async () => {
  if (!email.value) {
    error.value = 'Please enter your email address.'
    return
  }

  loading.value = true
  error.value = null
  successMessage.value = ''

  try {
    const response = await useSanctumFetch('/api/auth/password/forgot', {
      method: 'POST',
      body: { email: email.value }
    })

    successMessage.value = (response as any)?.message ?? 'Check your inbox for the link.'
  } catch (err: unknown) {
    error.value = resolveApiError(err, 'Unable to send reset email.')
  } finally {
    loading.value = false
  }
}
</script>
