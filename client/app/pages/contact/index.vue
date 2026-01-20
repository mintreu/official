<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8">
    <!-- Breadcrumb -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
      <nav class="flex items-center space-x-2 text-sm">
        <NuxtLink to="/" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Home
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <span class="text-gray-900 dark:text-white font-medium">Contact</span>
      </nav>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-sm font-semibold">
            Get In Touch
          </span>
        </div>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black mb-6 text-gray-900 dark:text-white">
          Let's <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Connect</span>
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Have a project in mind? Let's discuss how we can help bring your ideas to life.
        </p>
      </div>

      <div class="grid lg:grid-cols-2 gap-12">
        <!-- Contact Form -->
        <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 lg:p-12 shadow-xl">
          <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Send us a message</h2>

          <form @submit.prevent="submitForm" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                  Your Name
                </label>
                <input
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="John Doe"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                  Email Address
                </label>
                <input
                    v-model="form.email"
                    type="email"
                    required
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="john@example.com"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                Project Type
              </label>
              <select
                  v-model="form.projectType"
                  class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Select a project type</option>
                <option value="web">Web Application</option>
                <option value="mobile">Mobile App</option>
                <option value="saas">SaaS Product</option>
                <option value="api">API Development</option>
                <option value="consulting">Consulting</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                Project Budget
              </label>
              <select
                  v-model="form.budget"
                  class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Select budget range</option>
                <option value="small">$1,000 - $5,000</option>
                <option value="medium">$5,000 - $20,000</option>
                <option value="large">$20,000 - $50,000</option>
                <option value="enterprise">$50,000+</option>
                <option value="not-sure">Not sure yet</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                Your Message
              </label>
              <textarea
                  v-model="form.message"
                  rows="4"
                  required
                  class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                  placeholder="Tell us about your project..."
              ></textarea>
            </div>

            <button
                type="submit"
                :disabled="sending"
                class="w-full px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="sending">Sending...</span>
              <span v-else>Send Message</span>
            </button>

            <!-- Success Message -->
            <div v-if="sent" class="p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-xl">
              <div class="flex items-center space-x-2">
                <Icon name="lucide:check-circle" class="w-5 h-5" />
                <span>Message sent successfully! We'll get back to you soon.</span>
              </div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="p-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-xl">
              <div class="flex items-center space-x-2">
                <Icon name="lucide:alert-circle" class="w-5 h-5" />
                <span>{{ error }}</span>
              </div>
            </div>
          </form>
        </div>

        <!-- Contact Info -->
        <div class="space-y-8">
          <!-- Contact Methods -->
          <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-xl">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Other ways to reach us</h2>

            <div class="space-y-4">
              <a
                  href="mailto:hello@mintreu.com"
                  class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
              >
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                  <Icon name="lucide:mail" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                  <div class="font-semibold text-gray-900 dark:text-white">Email</div>
                  <div class="text-gray-600 dark:text-gray-400">hello@mintreu.com</div>
                </div>
              </a>

              <a
                  href="https://github.com/mintreu"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
              >
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-xl flex items-center justify-center">
                  <Icon name="lucide:github" class="w-6 h-6 text-gray-700 dark:text-gray-300" />
                </div>
                <div>
                  <div class="font-semibold text-gray-900 dark:text-white">GitHub</div>
                  <div class="text-gray-600 dark:text-gray-400">github.com/mintreu</div>
                </div>
              </a>

              <a
                  href="https://twitter.com/mintreu"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
              >
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                  <Icon name="lucide:twitter" class="w-6 h-6 text-blue-500" />
                </div>
                <div>
                  <div class="font-semibold text-gray-900 dark:text-white">Twitter</div>
                  <div class="text-gray-600 dark:text-gray-400">@mintreu</div>
                </div>
              </a>
            </div>
          </div>

          <!-- Availability -->
          <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-3xl p-8 text-white">
            <h3 class="text-xl font-bold mb-4">Current Availability</h3>
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
              <span class="font-semibold">Accepting new projects</span>
            </div>
            <p class="text-white/90">
              We're currently taking on new projects starting from February 2026. Get in touch to discuss your timeline!
            </p>
          </div>

          <!-- Response Time -->
          <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-xl">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Typical Response Time</h3>
            <div class="grid grid-cols-2 gap-4">
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                <div class="text-2xl font-black text-blue-600 dark:text-blue-400">24h</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Email Response</div>
              </div>
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                <div class="text-2xl font-black text-purple-600 dark:text-purple-400">48h</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Project Quote</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- FAQ Section -->
      <div class="mt-20">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
          Frequently Asked Questions
        </h2>

        <div class="grid md:grid-cols-2 gap-6">
          <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-bold mb-3 text-gray-900 dark:text-white">
              What technologies do you work with?
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
              We specialize in Laravel, Nuxt, Vue.js, React, Node.js, and modern DevOps practices. We're always learning new technologies that benefit our clients.
            </p>
          </div>

          <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-bold mb-3 text-gray-900 dark:text-white">
              Do you work with startups?
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
              Absolutely! We love working with early-stage startups. We can help you build your MVP, iterate quickly, and scale as you grow.
            </p>
          </div>

          <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-bold mb-3 text-gray-900 dark:text-white">
              What is your typical project timeline?
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
              Timelines vary based on complexity. A simple web app might take 4-6 weeks, while a complex SaaS product could take 3-6 months. We'll provide a detailed timeline after understanding your requirements.
            </p>
          </div>

          <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-bold mb-3 text-gray-900 dark:text-white">
              Do you offer ongoing support?
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
              Yes! We offer maintenance packages and ongoing support to ensure your application stays secure, fast, and up-to-date.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
useSeoMeta({
  title: 'Contact | Mintreu - Get In Touch',
  description: 'Have a project in mind? Let\'s discuss how we can help bring your ideas to life. We\'re currently accepting new projects.'
})

const api = useApi()
const sending = ref(false)
const sent = ref(false)
const error = ref<string | null>(null)

const form = reactive({
  name: '',
  email: '',
  projectType: '',
  budget: '',
  message: ''
})

const submitForm = async () => {
  sending.value = true
  error.value = null

  try {
    await api.submitContact({
      name: form.name,
      email: form.email,
      project_type: form.projectType || undefined,
      budget: form.budget || undefined,
      message: form.message
    })

    sent.value = true

    // Reset form
    form.name = ''
    form.email = ''
    form.projectType = ''
    form.budget = ''
    form.message = ''

    // Reset success message after 5 seconds
    setTimeout(() => {
      sent.value = false
    }, 5000)
  } catch (err: any) {
    error.value = err.data?.message || 'Something went wrong. Please try again.'
  } finally {
    sending.value = false
  }
}
</script>
