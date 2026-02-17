<template>
  <section id="services" ref="sectionRef" class="py-20 lg:py-32 relative overflow-hidden bg-white dark:bg-titanium-950">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="services-header max-w-3xl mx-auto text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-blueprint-100 dark:bg-blueprint-900/30 text-blueprint-700 dark:text-blueprint-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
            Our Services
          </span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-black mb-6 text-titanium-900 dark:text-white">
          Complete Digital
          <span class="text-mintreu-red-600">Solutions</span>
        </h2>
        <p class="text-lg md:text-xl text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">
          From web to mobile and desktop &mdash; comprehensive solutions for every platform
        </p>
        <div class="line-technical mt-8 mx-auto max-w-md"></div>
      </div>

      <!-- Service Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        <div
          v-for="(service, index) in services"
          :key="service.title"
          class="service-card group relative bg-titanium-50/80 dark:bg-titanium-900/50 backdrop-blur-xl rounded-3xl p-8 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 dark:hover:border-mintreu-red-600/50 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer overflow-hidden"
        >
          <!-- Hover glow overlay -->
          <div class="absolute inset-0 bg-gradient-to-br from-mintreu-red-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-3xl"></div>

          <!-- Corner marks -->
          <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-mintreu-red-600/30 group-hover:border-mintreu-red-600/60 rounded-tl-lg transition-colors"></div>
          <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-mintreu-red-600/30 group-hover:border-mintreu-red-600/60 rounded-br-lg transition-colors"></div>

          <!-- Spec label -->
          <div class="absolute top-4 right-4 label-schematic">SVC-{{ String(index + 1).padStart(2, '0') }}</div>

          <!-- Icon -->
          <div class="service-icon relative w-16 h-16 bg-titanium-200 dark:bg-titanium-800 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-mintreu-red-600 transition-all duration-500 shadow-lg">
            <Icon :name="service.icon" class="relative w-8 h-8 text-titanium-600 dark:text-titanium-400 group-hover:text-white transition-colors duration-500" />
          </div>

          <h3 class="relative text-2xl font-heading font-bold mb-4 text-titanium-900 dark:text-white group-hover:text-mintreu-red-600 transition-colors">
            {{ service.title }}
          </h3>
          <p class="relative text-titanium-600 dark:text-titanium-400 mb-6 leading-relaxed font-subheading">
            {{ service.description }}
          </p>

          <ul class="relative space-y-3">
            <li
              v-for="feature in service.features"
              :key="feature"
              class="flex items-start space-x-3 text-sm"
            >
              <div class="flex-shrink-0 w-5 h-5 rounded-full bg-mintreu-red-100 dark:bg-mintreu-red-900/30 flex items-center justify-center mt-0.5">
                <Icon name="lucide:check" class="w-3 h-3 text-mintreu-red-600" />
              </div>
              <span class="text-titanium-700 dark:text-titanium-300 font-subheading">{{ feature }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Tech Stack Marquee -->
    <LandingTechMarquee class="mt-24" />
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const services = [
  {
    title: 'Web Development',
    icon: 'lucide:globe',
    description: 'Full-stack web applications with Laravel, Nuxt.js, Next.js, and FilamentPHP. Responsive, scalable, and SEO-optimized.',
    features: ['Laravel & FilamentPHP backends', 'Nuxt.js & Next.js frontends', 'Livewire reactive interfaces', 'RESTful & GraphQL APIs'],
  },
  {
    title: 'Mobile Development',
    icon: 'lucide:smartphone',
    description: 'Native Android apps with Kotlin and cross-platform solutions. Play Store deployment and ongoing maintenance.',
    features: ['Native Android with Kotlin', 'Cross-platform development', 'Material Design UI/UX', 'Play Store optimization'],
  },
  {
    title: 'Desktop Applications',
    icon: 'lucide:monitor',
    description: 'Cross-platform desktop apps for Windows, macOS, and Linux. Electron and native solutions available.',
    features: ['Electron applications', 'Native desktop solutions', 'Cross-platform compatibility', 'Auto-update systems'],
  },
  {
    title: 'Database Solutions',
    icon: 'lucide:database',
    description: 'Expert database design and optimization. Support for MySQL, PostgreSQL, MongoDB, Redis, and more.',
    features: ['MySQL & PostgreSQL', 'MongoDB & Redis', 'Database optimization', 'Migration services'],
  },
  {
    title: 'AI & ML Integration',
    icon: 'lucide:brain',
    description: 'Artificial Intelligence and Machine Learning solutions with Python. Predictive analytics and automation.',
    features: ['Python ML models', 'TensorFlow & PyTorch', 'Natural language processing', 'Computer vision'],
  },
  {
    title: 'Payment Integration',
    icon: 'lucide:credit-card',
    description: 'Complete payment gateway integration for global and Indian markets with secure transactions.',
    features: ['Stripe & PayPal (Global)', 'Razorpay & Cashfree (India)', 'Subscription billing', 'Secure PCI compliance'],
  },
]

onMounted(() => {
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    // Header
    gsap.from('.services-header', {
      y: 40, opacity: 0, duration: 0.8, ease: 'power3.out',
      scrollTrigger: { trigger: '.services-header', start: 'top 85%' },
    })

    gsap.from('.services-header .line-technical', {
      scaleX: 0, transformOrigin: 'left center', duration: 1.2, ease: 'power2.out',
      scrollTrigger: { trigger: '.services-header .line-technical', start: 'top 90%' },
    })

    // Cards - alternate from left/right
    const cards = gsap.utils.toArray('.service-card') as HTMLElement[]
    cards.forEach((card, i) => {
      gsap.from(card, {
        x: i % 2 === 0 ? -60 : 60,
        opacity: 0,
        scale: 0.95,
        duration: 0.8,
        ease: 'power3.out',
        scrollTrigger: { trigger: card, start: 'top 88%' },
      })

      // Icon container spin-in
      const icon = card.querySelector('.service-icon')
      if (icon) {
        gsap.from(icon, {
          rotation: -180, scale: 0, opacity: 0,
          duration: 0.7, ease: 'back.out(1.5)',
          scrollTrigger: { trigger: card, start: 'top 85%' },
        })
      }

      // Feature check marks cascade after card enters
      const features = card.querySelectorAll('li')
      if (features.length) {
        gsap.from(features, {
          x: -15, opacity: 0, duration: 0.35,
          stagger: 0.06, ease: 'power2.out',
          scrollTrigger: { trigger: card, start: 'top 80%' },
        })
      }
    })
  }, sectionRef.value)
})

onUnmounted(() => { ctx?.revert() })
</script>
