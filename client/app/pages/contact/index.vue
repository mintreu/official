<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 py-8 relative">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <nav class="flex items-center space-x-2 text-sm mb-8">
        <NuxtLink to="/" class="text-titanium-500 hover:text-mintreu-red-600 font-subheading transition-colors">Home</NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-titanium-400" />
        <span class="text-titanium-900 dark:text-white font-heading font-bold text-xs uppercase tracking-wider">Contact</span>
      </nav>

      <!-- Header -->
      <div ref="sectionRef" class="contact-header text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
            Get In Touch
          </span>
        </div>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-heading font-black mb-6 text-titanium-900 dark:text-white">
          Let's <span class="text-mintreu-red-600">Connect</span>
        </h1>
        <p class="text-lg text-titanium-600 dark:text-titanium-400 max-w-2xl mx-auto font-subheading">
          Have a project in mind? Let's discuss how we can help bring your ideas to life.
        </p>
        <div class="line-technical mt-8 mx-auto max-w-md"></div>
      </div>

      <div class="grid lg:grid-cols-2 gap-12">
        <!-- Contact Form -->
        <div class="contact-form bg-white dark:bg-titanium-900 rounded-3xl p-8 lg:p-12 shadow-xl border border-dashed border-titanium-300 dark:border-titanium-700 relative overflow-hidden">
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
          <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600/40 rounded-br-xl"></div>

          <div class="relative">
            <h2 class="text-2xl font-heading font-bold mb-6 text-titanium-900 dark:text-white">Send us a message</h2>

            <form @submit.prevent="submitForm" class="space-y-6">
              <div class="grid md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-heading font-semibold mb-2 text-titanium-700 dark:text-titanium-300">
                    Your Name
                  </label>
                  <input
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full px-4 py-3 bg-titanium-50 dark:bg-titanium-800 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 focus:border-mintreu-red-500 font-subheading text-titanium-900 dark:text-white placeholder-titanium-400"
                    placeholder="John Doe"
                  />
                </div>
                <div>
                  <label class="block text-sm font-heading font-semibold mb-2 text-titanium-700 dark:text-titanium-300">
                    Email Address
                  </label>
                  <input
                    v-model="form.email"
                    type="email"
                    required
                    class="w-full px-4 py-3 bg-titanium-50 dark:bg-titanium-800 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 focus:border-mintreu-red-500 font-subheading text-titanium-900 dark:text-white placeholder-titanium-400"
                    placeholder="john@example.com"
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-heading font-semibold mb-2 text-titanium-700 dark:text-titanium-300">
                  Project Type
                </label>
                <select
                  v-model="form.projectType"
                  class="w-full px-4 py-3 bg-titanium-50 dark:bg-titanium-800 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 focus:border-mintreu-red-500 font-subheading text-titanium-900 dark:text-white"
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
                <label class="block text-sm font-heading font-semibold mb-2 text-titanium-700 dark:text-titanium-300">
                  Project Budget
                </label>
                <select
                  v-model="form.budget"
                  class="w-full px-4 py-3 bg-titanium-50 dark:bg-titanium-800 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 focus:border-mintreu-red-500 font-subheading text-titanium-900 dark:text-white"
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
                <label class="block text-sm font-heading font-semibold mb-2 text-titanium-700 dark:text-titanium-300">
                  Your Message
                </label>
                <textarea
                  v-model="form.message"
                  rows="4"
                  required
                  class="w-full px-4 py-3 bg-titanium-50 dark:bg-titanium-800 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 focus:border-mintreu-red-500 font-subheading text-titanium-900 dark:text-white placeholder-titanium-400 resize-none"
                  placeholder="Tell us about your project..."
                ></textarea>
              </div>

              <button
                type="submit"
                :disabled="sending"
                class="w-full px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white font-heading font-bold rounded-xl shadow-lg glow-red hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
              >
                <span v-if="sending">Sending...</span>
                <span v-else>Send Message</span>
              </button>

              <!-- Success Message -->
              <div v-if="sent" class="p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-xl border border-green-300 dark:border-green-800">
                <div class="flex items-center space-x-2">
                  <Icon name="lucide:check-circle" class="w-5 h-5" />
                  <span class="font-subheading">Message sent successfully! We'll get back to you soon.</span>
                </div>
              </div>

              <!-- Error Message -->
              <div v-if="error" class="p-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-xl border border-red-300 dark:border-red-800">
                <div class="flex items-center space-x-2">
                  <Icon name="lucide:alert-circle" class="w-5 h-5" />
                  <span class="font-subheading">{{ error }}</span>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Contact Info -->
        <div class="space-y-8">
          <!-- Contact Methods -->
          <div class="contact-methods bg-white dark:bg-titanium-900 rounded-3xl p-8 shadow-xl border border-dashed border-titanium-300 dark:border-titanium-700 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
            <h2 class="text-2xl font-heading font-bold mb-6 text-titanium-900 dark:text-white">Other ways to reach us</h2>

            <div class="space-y-4">
              <a
                href="mailto:hello@mintreu.com"
                class="flex items-center space-x-4 p-4 rounded-xl hover:bg-titanium-50 dark:hover:bg-titanium-800 transition-colors"
              >
                <div class="w-12 h-12 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 rounded-xl flex items-center justify-center">
                  <Icon name="lucide:mail" class="w-6 h-6 text-mintreu-red-600" />
                </div>
                <div>
                  <div class="font-heading font-semibold text-titanium-900 dark:text-white">Email</div>
                  <div class="text-titanium-600 dark:text-titanium-400 font-subheading">hello@mintreu.com</div>
                </div>
              </a>

              <a
                href="https://github.com/mintreu"
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center space-x-4 p-4 rounded-xl hover:bg-titanium-50 dark:hover:bg-titanium-800 transition-colors"
              >
                <div class="w-12 h-12 bg-titanium-100 dark:bg-titanium-800 rounded-xl flex items-center justify-center">
                  <Icon name="lucide:github" class="w-6 h-6 text-titanium-700 dark:text-titanium-300" />
                </div>
                <div>
                  <div class="font-heading font-semibold text-titanium-900 dark:text-white">GitHub</div>
                  <div class="text-titanium-600 dark:text-titanium-400 font-subheading">github.com/mintreu</div>
                </div>
              </a>

              <a
                href="https://twitter.com/mintreu"
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center space-x-4 p-4 rounded-xl hover:bg-titanium-50 dark:hover:bg-titanium-800 transition-colors"
              >
                <div class="w-12 h-12 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 rounded-xl flex items-center justify-center">
                  <Icon name="lucide:twitter" class="w-6 h-6 text-mintreu-red-600" />
                </div>
                <div>
                  <div class="font-heading font-semibold text-titanium-900 dark:text-white">Twitter</div>
                  <div class="text-titanium-600 dark:text-titanium-400 font-subheading">@mintreu</div>
                </div>
              </a>
            </div>
          </div>

          <!-- Availability -->
          <div class="contact-availability relative overflow-hidden rounded-3xl">
            <div class="absolute inset-0 bg-titanium-900"></div>
            <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>
            <div class="relative p-8 border border-dashed border-titanium-700 rounded-3xl">
              <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600 rounded-tl-xl"></div>
              <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600 rounded-br-xl"></div>
              <h3 class="text-xl font-heading font-bold mb-4 text-white">Current Availability</h3>
              <div class="flex items-center space-x-3 mb-4">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                <span class="font-heading font-semibold text-white">Accepting new projects</span>
              </div>
              <p class="text-titanium-400 font-subheading leading-relaxed">
                We're currently taking on new projects starting from February 2026. Get in touch to discuss your timeline!
              </p>
            </div>
          </div>

          <!-- Response Time -->
          <div class="contact-response bg-white dark:bg-titanium-900 rounded-3xl p-8 shadow-xl border border-dashed border-titanium-300 dark:border-titanium-700">
            <h3 class="text-xl font-heading font-bold mb-4 text-titanium-900 dark:text-white">Typical Response Time</h3>
            <div class="grid grid-cols-2 gap-4">
              <div class="text-center p-4 bg-titanium-50 dark:bg-titanium-800 rounded-xl border border-dashed border-titanium-300 dark:border-titanium-700">
                <div class="text-2xl font-heading font-black text-mintreu-red-600">24h</div>
                <div class="text-sm text-titanium-600 dark:text-titanium-400 font-subheading">Email Response</div>
              </div>
              <div class="text-center p-4 bg-titanium-50 dark:bg-titanium-800 rounded-xl border border-dashed border-titanium-300 dark:border-titanium-700">
                <div class="text-2xl font-heading font-black text-mintreu-red-600">48h</div>
                <div class="text-sm text-titanium-600 dark:text-titanium-400 font-subheading">Project Quote</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- FAQ Section -->
      <div class="contact-faq mt-20">
        <h2 class="text-3xl font-heading font-bold text-center mb-12 text-titanium-900 dark:text-white">
          Frequently Asked Questions
        </h2>

        <div class="grid md:grid-cols-2 gap-6">
          <div v-for="faq in faqs" :key="faq.question" class="faq-card bg-white dark:bg-titanium-900 rounded-2xl p-6 shadow-lg border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 transition-colors">
            <h3 class="text-lg font-heading font-bold mb-3 text-titanium-900 dark:text-white">{{ faq.question }}</h3>
            <p class="text-titanium-600 dark:text-titanium-400 font-subheading leading-relaxed">{{ faq.answer }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, onUnmounted } from 'vue'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

useSeoMeta({
  title: 'Contact | Mintreu - Get In Touch',
  description: 'Have a project in mind? Let\'s discuss how we can help bring your ideas to life. We\'re currently accepting new projects.'
})

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const sending = ref(false)
const sent = ref(false)
const error = ref<string | null>(null)

const { submitContact } = useApi()

const form = reactive({
  name: '',
  email: '',
  projectType: '',
  budget: '',
  message: ''
})

const faqs = [
  {
    question: 'What technologies do you work with?',
    answer: 'We specialize in Laravel, Nuxt, Vue.js, React, Node.js, and modern DevOps practices. We\'re always learning new technologies that benefit our clients.'
  },
  {
    question: 'Do you work with startups?',
    answer: 'Absolutely! We love working with early-stage startups. We can help you build your MVP, iterate quickly, and scale as you grow.'
  },
  {
    question: 'What is your typical project timeline?',
    answer: 'Timelines vary based on complexity. A simple web app might take 4-6 weeks, while a complex SaaS product could take 3-6 months. We\'ll provide a detailed timeline after understanding your requirements.'
  },
  {
    question: 'Do you offer ongoing support?',
    answer: 'Yes! We offer maintenance packages and ongoing support to ensure your application stays secure, fast, and up-to-date.'
  }
]

const submitForm = async () => {
  sending.value = true
  error.value = null

  try {
    await submitContact({
      name: form.name,
      email: form.email,
      project_type: form.projectType || undefined,
      budget: form.budget || undefined,
      message: form.message
    }) as any

    sent.value = true
    form.name = ''
    form.email = ''
    form.projectType = ''
    form.budget = ''
    form.message = ''

    setTimeout(() => { sent.value = false }, 5000)
  } catch (err: any) {
    error.value = err.data?.message || 'Something went wrong. Please try again.'
  } finally {
    sending.value = false
  }
}

const initAnimations = () => {
  ctx?.revert()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.contact-header', { y: 40, opacity: 0, duration: 0.8, ease: 'power3.out' })

    gsap.from('.contact-form', {
      x: -40, opacity: 0, duration: 0.8, ease: 'power3.out',
      scrollTrigger: { trigger: '.contact-form', start: 'top 85%' }
    })

    const rightPanels = ['.contact-methods', '.contact-availability', '.contact-response']
    rightPanels.forEach((sel, i) => {
      gsap.from(sel, {
        x: 40, opacity: 0, duration: 0.7, delay: i * 0.1,
        ease: 'power3.out',
        scrollTrigger: { trigger: sel, start: 'top 85%' }
      })
    })

    gsap.from('.contact-faq', {
      y: 40, opacity: 0, duration: 0.8, ease: 'power3.out',
      scrollTrigger: { trigger: '.contact-faq', start: 'top 85%' }
    })

    const faqCards = gsap.utils.toArray('.faq-card') as HTMLElement[]
    faqCards.forEach((card, i) => {
      gsap.from(card, {
        y: 30, opacity: 0, duration: 0.6, delay: i * 0.1,
        ease: 'back.out(1.3)',
        scrollTrigger: { trigger: card, start: 'top 90%' }
      })
    })
  }, sectionRef.value)
}

onMounted(() => { initAnimations() })
onUnmounted(() => { ctx?.revert() })
</script>
