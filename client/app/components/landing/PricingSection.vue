<template>
  <section id="hire" ref="sectionRef" class="py-20 lg:py-32 relative overflow-hidden bg-titanium-50 dark:bg-titanium-900">
    <div class="absolute inset-0 bg-blueprint pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="pricing-header max-w-3xl mx-auto text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
            Flexible Hiring
          </span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-black mb-6 text-titanium-900 dark:text-white">
          Choose Your
          <span class="text-mintreu-red-600">Engagement Model</span>
        </h2>
        <p class="text-lg md:text-xl text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">
          Tell us about your project and receive a custom quote tailored to your needs
        </p>
      </div>

      <!-- Engagement Cards -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
        <!-- Solo Developer Card -->
        <div class="pricing-card group relative bg-white dark:bg-titanium-900 rounded-3xl p-8 lg:p-10 border-2 border-titanium-200 dark:border-titanium-800 border-dashed hover:border-mintreu-red-600/50 dark:hover:border-mintreu-red-600/50 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-500">
          <!-- Corner marks -->
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
          <div class="absolute top-0 right-0 w-6 h-6 border-t-2 border-r-2 border-mintreu-red-600/40 rounded-tr-xl"></div>
          <div class="absolute bottom-0 left-0 w-6 h-6 border-b-2 border-l-2 border-mintreu-red-600/40 rounded-bl-xl"></div>
          <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600/40 rounded-br-xl"></div>

          <div class="relative">
            <div class="inline-block px-4 py-1.5 bg-titanium-100 dark:bg-titanium-800 text-titanium-700 dark:text-titanium-300 rounded-full text-sm font-heading font-bold uppercase tracking-wider mb-6">
              Individual Expert
            </div>

            <h3 class="text-3xl md:text-4xl font-heading font-black text-titanium-900 dark:text-white mb-4">
              Solo Developer
            </h3>

            <p class="text-titanium-600 dark:text-titanium-400 mb-6 leading-relaxed font-subheading">
              Perfect for startups and small businesses. Get dedicated expertise with flexible timelines and cost-effective solutions.
            </p>

            <ul class="space-y-4 mb-8">
              <li v-for="feature in soloFeatures" :key="feature" class="flex items-start space-x-3">
                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-mintreu-red-100 dark:bg-mintreu-red-900/30 flex items-center justify-center mt-0.5">
                  <Icon name="lucide:check" class="w-4 h-4 text-mintreu-red-600" />
                </div>
                <span class="text-titanium-700 dark:text-titanium-300 font-subheading">{{ feature }}</span>
              </li>
            </ul>

            <!-- Custom Quote CTA -->
            <div class="mb-6 p-4 bg-titanium-50 dark:bg-titanium-800/50 rounded-xl border border-dashed border-titanium-300 dark:border-titanium-700 text-center">
              <p class="text-sm text-titanium-500 font-subheading mb-1">Pricing based on project scope</p>
              <p class="text-lg font-heading font-bold text-titanium-900 dark:text-white">Custom Quote</p>
            </div>

            <NuxtLink
              to="#contact"
              class="block w-full text-center px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white text-lg font-heading font-bold rounded-xl shadow-lg glow-red hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-300"
            >
              Request a Quote
            </NuxtLink>
          </div>
        </div>

        <!-- Team Plan -->
        <div class="pricing-card group relative bg-gradient-to-br from-mintreu-red-600 via-mintreu-red-700 to-mintreu-red-800 rounded-3xl p-[2px] shadow-2xl glow-red-intense transform hover:-translate-y-2 transition-all duration-500">
          <div class="relative bg-white dark:bg-titanium-900 rounded-3xl p-8 lg:p-10 h-full">
            <!-- Performance badge -->
            <div class="absolute -top-4 right-8">
              <span class="px-4 py-1.5 bg-mintreu-red-600 text-white rounded-full text-sm font-heading font-bold shadow-lg glow-red uppercase tracking-wider shimmer-sweep">
                2-3x Faster
              </span>
            </div>

            <!-- Corner marks -->
            <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600 rounded-tl-xl"></div>
            <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600 rounded-br-xl"></div>

            <div class="inline-block px-4 py-1.5 bg-mintreu-red-600 text-white rounded-full text-sm font-heading font-bold uppercase tracking-wider mb-6">
              Full Team
            </div>

            <h3 class="text-3xl md:text-4xl font-heading font-black text-mintreu-red-600 mb-4">
              Expert Team
            </h3>

            <p class="text-titanium-600 dark:text-titanium-400 mb-6 leading-relaxed font-subheading">
              Enterprise-grade solution with specialized experts. Accelerated delivery for complex projects with strict deadlines.
            </p>

            <ul class="space-y-4 mb-8">
              <li v-for="feature in teamFeatures" :key="feature" class="flex items-start space-x-3">
                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-mintreu-red-600 flex items-center justify-center mt-0.5">
                  <Icon name="lucide:check" class="w-4 h-4 text-white" />
                </div>
                <span class="text-titanium-700 dark:text-titanium-300 font-subheading font-medium">{{ feature }}</span>
              </li>
            </ul>

            <!-- Custom Quote CTA -->
            <div class="mb-6 p-4 bg-mintreu-red-50 dark:bg-mintreu-red-900/20 rounded-xl border border-dashed border-mintreu-red-300 dark:border-mintreu-red-800 text-center">
              <p class="text-sm text-titanium-500 font-subheading mb-1">Accelerated timeline &bull; Priority support</p>
              <p class="text-lg font-heading font-bold text-mintreu-red-600">Custom Quote</p>
            </div>

            <NuxtLink
              to="#contact"
              class="block w-full text-center px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white text-lg font-heading font-bold rounded-xl shadow-lg glow-red hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-300"
            >
              Request a Quote
            </NuxtLink>
          </div>
        </div>
      </div>

      <!-- How It Works -->
      <div class="mt-20 max-w-4xl mx-auto">
        <h3 class="text-center text-2xl font-heading font-bold text-titanium-900 dark:text-white mb-10">
          How It Works
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="(step, index) in quoteSteps" :key="step.title" class="quote-step text-center">
            <div class="w-14 h-14 mx-auto mb-4 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 rounded-2xl flex items-center justify-center">
              <span class="text-2xl font-heading font-black text-mintreu-red-600">{{ index + 1 }}</span>
            </div>
            <h4 class="text-lg font-heading font-bold text-titanium-900 dark:text-white mb-2">{{ step.title }}</h4>
            <p class="text-sm text-titanium-600 dark:text-titanium-400 font-subheading leading-relaxed">{{ step.description }}</p>
          </div>
        </div>
      </div>

      <!-- Payment Methods -->
      <div class="payment-methods mt-16 text-center">
        <p class="text-sm text-titanium-500 mb-4 font-heading font-bold uppercase tracking-wider">Accepted Payment Methods</p>
        <div class="flex flex-wrap items-center justify-center gap-6">
          <div v-for="method in paymentMethods" :key="method.name" class="payment-method flex items-center space-x-2 px-4 py-2 bg-white dark:bg-titanium-900 rounded-lg border border-titanium-200 dark:border-titanium-800">
            <Icon :name="method.icon" class="w-5 h-5" :class="method.color" />
            <span class="text-sm font-subheading font-medium text-titanium-700 dark:text-titanium-300">{{ method.name }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const soloFeatures = [
  'Direct communication with expert developer',
  'Personalized attention to project needs',
  'Flexible working hours and timeline',
  'Cost-effective development solutions',
  'Full code ownership and documentation',
  'Ongoing support and maintenance',
]

const teamFeatures = [
  'Team of specialized experts (Laravel, Nuxt, Kotlin)',
  'Parallel development for faster delivery',
  'Enterprise-grade code quality',
  'Dedicated project manager',
  'Priority 24/7 support',
  'Cross-platform expertise (Web+Mobile+Desktop)',
]

const quoteSteps = [
  {
    title: 'Send Your Requirements',
    description: 'Share your project details, goals, and timeline. We\'ll review everything carefully.',
  },
  {
    title: 'Receive Custom Quote',
    description: 'Get a detailed proposal with scope, timeline, milestones, and transparent pricing.',
  },
  {
    title: 'Start Building',
    description: 'Once approved, we begin development with regular updates and deliverables.',
  },
]

const paymentMethods = [
  { name: 'Stripe', icon: 'lucide:credit-card', color: 'text-blueprint-600' },
  { name: 'PayPal', icon: 'lucide:wallet', color: 'text-blueprint-600' },
  { name: 'Razorpay', icon: 'lucide:indian-rupee', color: 'text-mintreu-red-600' },
  { name: 'Cashfree', icon: 'lucide:banknote', color: 'text-green-600' },
]

onMounted(() => {
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.pricing-header', {
      y: 40, opacity: 0, duration: 0.8, ease: 'power3.out',
      scrollTrigger: { trigger: '.pricing-header', start: 'top 85%' },
    })

    // Cards with blur entrance
    const cards = gsap.utils.toArray('.pricing-card') as HTMLElement[]
    cards.forEach((card, i) => {
      gsap.fromTo(card,
        { scale: 0.85, rotateX: 10, opacity: 0, filter: 'blur(10px)' },
        {
          scale: 1, rotateX: 0, opacity: 1, filter: 'blur(0px)',
          duration: 1, delay: i * 0.25, ease: 'back.out(1.2)',
          scrollTrigger: { trigger: card, start: 'top 85%' },
        }
      )

      // Feature check marks cascade within each card
      const checks = card.querySelectorAll('li')
      if (checks.length) {
        gsap.from(checks, {
          x: -20, opacity: 0, duration: 0.4,
          stagger: 0.06, ease: 'power2.out',
          scrollTrigger: { trigger: card, start: 'top 80%' },
        })
      }
    })

    // Quote steps stagger
    gsap.from('.quote-step', {
      y: 30, opacity: 0, scale: 0.95, duration: 0.6,
      stagger: 0.15, ease: 'back.out(1.3)',
      scrollTrigger: { trigger: '.quote-step', start: 'top 88%' },
    })

    // Payment method icons - elastic pop-in
    const methods = gsap.utils.toArray('.payment-method') as HTMLElement[]
    if (methods.length) {
      gsap.from(methods, {
        scale: 0, opacity: 0, duration: 0.5,
        stagger: 0.1, ease: 'elastic.out(1, 0.5)',
        scrollTrigger: { trigger: '.payment-methods', start: 'top 90%' },
      })
    }
  }, sectionRef.value)
})

onUnmounted(() => { ctx?.revert() })
</script>
