<template>
  <div class="min-h-screen bg-titanium-950 text-white flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md bg-titanium-900/90 border border-titanium-800 rounded-3xl p-8 shadow-2xl backdrop-blur-xl">
      <div class="text-center mb-6">
        <p class="text-sm uppercase tracking-[0.4em] text-titanium-400">MintreuOfficial</p>
        <h1 class="text-3xl font-bold mt-2">Forgot password</h1>
        <p class="text-titanium-400 text-sm mt-1">We will email you a secure reset link.</p>
      </div>

      <form @submit.prevent="handleForgot" class="space-y-4">
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

        <div v-if="message" class="text-sm text-emerald-300">{{ message }}</div>
        <div v-if="error" class="text-sm text-rose-400">{{ error }}</div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full rounded-2xl bg-gradient-to-r from-mintreu-red-600 to-mintreu-red-500 py-3 text-sm font-semibold uppercase tracking-[0.3em] text-white hover:opacity-90 transition-opacity"
        >
          {{ loading ? 'Sending link…' : 'Send reset link' }}
        </button>
      </form>

      <div class="mt-6 text-center text-sm text-titanium-400">
        Back to
        <NuxtLink to="/auth/login" class="text-white font-semibold">Sign in</NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const config = useRuntimeConfig()
const email = ref('')
const loading = ref(false)
const message = ref('')
const error = ref<string | null>(null)

const handleForgot = async () => {
  error.value = null
  message.value = ''
  loading.value = true

  try {
    await useSanctumFetch(`${config.public.apiBase}/api/auth/password/forgot`, {
      method: 'POST',
      body: { email: email.value }
    })

    message.value = 'Check your inbox for the reset link.'
  } catch (err: any) {
    error.value = err?.data?.message || 'Unable to send reset link.'
  } finally {
    loading.value = false
  }
}
</script>
