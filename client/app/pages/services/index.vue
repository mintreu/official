<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 relative">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <!-- Hero Section -->
    <section ref="sectionRef" class="services-hero py-20 lg:py-32 relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-4xl mx-auto">
          <div class="inline-block mb-6">
            <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
              Services
            </span>
          </div>
          <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-heading font-black mb-8 text-titanium-900 dark:text-white leading-tight">
            Premium <span class="text-mintreu-red-600">Development</span> Services
          </h1>
          <p class="text-xl md:text-2xl text-titanium-600 dark:text-titanium-400 leading-relaxed max-w-3xl mx-auto font-subheading">
            From concept to deployment, we deliver end-to-end software solutions tailored to your unique needs.
          </p>
          <div class="line-technical mt-8 mx-auto max-w-md"></div>
        </div>
      </div>
    </section>

    <!-- Services Grid -->
    <section class="services-grid py-20 bg-white dark:bg-titanium-900 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="service in services" :key="service.title" class="service-card bg-titanium-50 dark:bg-titanium-800 rounded-3xl p-8 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
            <div class="w-16 h-16 bg-mintreu-red-600 rounded-2xl flex items-center justify-center mb-6">
              <Icon :name="service.icon" class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-2xl font-heading font-bold mb-4 text-titanium-900 dark:text-white">{{ service.title }}</h3>
            <p class="text-titanium-600 dark:text-titanium-400 mb-6 font-subheading leading-relaxed">{{ service.description }}</p>
            <ul class="space-y-2">
              <li v-for="feature in service.features" :key="feature" class="flex items-center space-x-2 text-titanium-700 dark:text-titanium-300 font-subheading">
                <Icon name="lucide:check" class="w-5 h-5 text-mintreu-red-600 flex-shrink-0" />
                <span>{{ feature }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Process -->
    <section class="services-process py-20 bg-titanium-50 dark:bg-titanium-950 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
              Process
            </span>
          </div>
          <h2 class="text-4xl md:text-5xl font-heading font-black text-titanium-900 dark:text-white">
            How We <span class="text-mintreu-red-600">Work</span>
          </h2>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
          <div v-for="(step, index) in process" :key="step.title" class="process-step text-center">
            <div class="w-20 h-20 bg-titanium-900 border-2 border-dashed border-mintreu-red-600/50 rounded-full flex items-center justify-center mx-auto mb-6 relative">
              <span class="text-2xl font-heading font-black text-mintreu-red-500">{{ index + 1 }}</span>
            </div>
            <h3 class="text-xl font-heading font-bold mb-3 text-titanium-900 dark:text-white">{{ step.title }}</h3>
            <p class="text-titanium-600 dark:text-titanium-400 font-subheading leading-relaxed">{{ step.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Money-Back Guarantee -->
    <section class="services-guarantee relative overflow-hidden">
      <div class="absolute inset-0 bg-titanium-900"></div>
      <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>
      <div class="relative py-16 border-y border-dashed border-titanium-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="w-24 h-24 bg-mintreu-red-600 rounded-2xl flex items-center justify-center flex-shrink-0">
              <Icon name="lucide:shield-check" class="w-12 h-12 text-white" />
            </div>
            <div>
              <h3 class="text-3xl font-heading font-black text-white mb-2">30-Day Money-Back Guarantee</h3>
              <p class="text-titanium-400 font-subheading text-lg leading-relaxed">
                We stand behind every product and service we deliver. If you're not 100% satisfied, get a full refund within 30 days — no questions asked. This applies to all our products, flat and simple.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Quote CTA (replaces old pricing section) -->
    <section class="services-quote py-20 bg-white dark:bg-titanium-900 relative">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
              Pricing
            </span>
          </div>
          <h2 class="text-4xl md:text-5xl font-heading font-black text-titanium-900 dark:text-white">
            Custom <span class="text-mintreu-red-600">Quotes</span> for Every Project
          </h2>
          <p class="text-lg text-titanium-600 dark:text-titanium-400 max-w-2xl mx-auto mt-6 font-subheading leading-relaxed">
            Every project is unique. We provide transparent, custom quotes based on your specific requirements — no hidden fees, no surprises.
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div v-for="plan in plans" :key="plan.title" class="quote-card bg-titanium-50 dark:bg-titanium-800 rounded-3xl p-8 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 transition-all duration-300 relative overflow-hidden text-center">
            <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
            <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600/40 rounded-br-xl"></div>
            <div class="relative">
              <Icon :name="plan.icon" class="w-12 h-12 text-mintreu-red-600 mx-auto mb-4" />
              <h3 class="text-2xl font-heading font-bold mb-2 text-titanium-900 dark:text-white">{{ plan.title }}</h3>
              <p class="text-titanium-600 dark:text-titanium-400 mb-6 font-subheading">{{ plan.description }}</p>
              <ul class="space-y-3 mb-8 text-left">
                <li v-for="feature in plan.features" :key="feature" class="flex items-center space-x-2">
                  <Icon name="lucide:check" class="w-5 h-5 text-mintreu-red-600 flex-shrink-0" />
                  <span class="text-titanium-700 dark:text-titanium-300 font-subheading">{{ feature }}</span>
                </li>
              </ul>
              <NuxtLink to="/contact" class="inline-block w-full px-6 py-3 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white font-heading font-bold rounded-xl shadow-lg glow-red transform hover:scale-105 active:scale-95 transition-all">
                Request Quote
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="services-cta relative overflow-hidden">
      <div class="absolute inset-0 bg-titanium-900"></div>
      <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>
      <div class="relative py-20 border-t border-dashed border-titanium-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div class="absolute top-4 left-4 w-8 h-8 border-t-2 border-l-2 border-mintreu-red-600 rounded-tl-xl"></div>
          <div class="absolute bottom-4 right-4 w-8 h-8 border-b-2 border-r-2 border-mintreu-red-600 rounded-br-xl"></div>

          <h2 class="text-4xl md:text-5xl font-heading font-black mb-6 text-white">
            Ready to Start Your <span class="text-mintreu-red-500">Project?</span>
          </h2>
          <p class="text-xl text-titanium-400 mb-10 font-subheading">
            Let's discuss your project and find the perfect solution for your needs.
          </p>
          <NuxtLink
            to="/contact"
            class="inline-flex items-center space-x-2 px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white font-heading font-bold rounded-xl shadow-lg glow-red hover:shadow-xl transform hover:scale-105 transition-all"
          >
            <span>Get Free Consultation</span>
            <Icon name="lucide:arrow-right" class="w-5 h-5" />
          </NuxtLink>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

useSeoMeta({
  title: 'Services | Mintreu - Premium Development Services',
  description: 'Explore our premium development services including web development, mobile apps, SaaS products, and API development.'
})

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const services = [
  {
    icon: 'lucide:globe',
    title: 'Web Development',
    description: 'Custom web applications built with modern frameworks like Laravel, Nuxt, and React. Scalable, secure, and performant.',
    features: ['Custom Web Applications', 'SPA & SSR Solutions', 'API Development']
  },
  {
    icon: 'lucide:smartphone',
    title: 'Mobile Development',
    description: 'Native and cross-platform mobile applications for iOS and Android. Beautiful UI with native performance.',
    features: ['iOS & Android Apps', 'Cross-Platform (React Native)', 'App Store Optimization']
  },
  {
    icon: 'lucide:cloud',
    title: 'SaaS Development',
    description: 'Build recurring revenue with subscription-based software products. From MVP to enterprise scale.',
    features: ['SaaS MVP Development', 'Subscription Management', 'Multi-tenant Architecture']
  },
  {
    icon: 'lucide:server',
    title: 'API Development',
    description: 'Robust, scalable APIs designed for performance and security. RESTful and GraphQL implementations.',
    features: ['RESTful API Design', 'GraphQL APIs', 'API Documentation']
  },
  {
    icon: 'lucide:shopping-cart',
    title: 'E-Commerce',
    description: 'Complete e-commerce solutions with payment integration, inventory management, and analytics.',
    features: ['Custom Store Development', 'Payment Integration', 'Inventory Systems']
  },
  {
    icon: 'lucide:lightbulb',
    title: 'Tech Consulting',
    description: 'Strategic technology consulting to help you make informed decisions about your digital products.',
    features: ['Architecture Planning', 'Technology Selection', 'Code Review & Audit']
  }
]

const process = [
  {
    title: 'Discovery',
    description: 'We learn about your business, goals, and requirements to create a detailed project plan.'
  },
  {
    title: 'Design',
    description: 'Our designers create wireframes and prototypes to visualize the solution before development.'
  },
  {
    title: 'Development',
    description: 'Our developers build your solution using best practices and modern technologies.'
  },
  {
    title: 'Launch',
    description: 'We deploy your project and provide ongoing support to ensure success.'
  }
]

const plans = [
  {
    icon: 'lucide:zap',
    title: 'Starter',
    description: 'For small projects and MVPs',
    features: ['Single feature scope', 'Quick turnaround', 'No long-term commitment', '30-day money-back guarantee']
  },
  {
    icon: 'lucide:rocket',
    title: 'Growth',
    description: 'For well-defined projects',
    features: ['Fixed timeline & budget', 'Detailed proposal', 'Regular updates', 'Dedicated project manager', '30-day money-back guarantee']
  },
  {
    icon: 'lucide:building',
    title: 'Enterprise',
    description: 'For ongoing development needs',
    features: ['Dedicated team hours', 'Priority support', 'Flexible rollover', 'Architecture consulting', '30-day money-back guarantee']
  }
]

const initAnimations = () => {
  ctx?.revert()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.services-hero', { y: 40, opacity: 0, duration: 0.8, ease: 'power3.out' })

    const sections = ['.services-grid', '.services-process', '.services-guarantee', '.services-quote', '.services-cta']
    sections.forEach((sel) => {
      gsap.from(sel, {
        y: 40, opacity: 0, duration: 0.8, ease: 'power3.out',
        scrollTrigger: { trigger: sel, start: 'top 85%' }
      })
    })

    const serviceCards = gsap.utils.toArray('.service-card') as HTMLElement[]
    serviceCards.forEach((card, i) => {
      gsap.from(card, {
        y: 50, opacity: 0, scale: 0.95, duration: 0.7, delay: i * 0.08,
        ease: 'back.out(1.3)',
        scrollTrigger: { trigger: card, start: 'top 90%' }
      })
    })

    const processSteps = gsap.utils.toArray('.process-step') as HTMLElement[]
    processSteps.forEach((step, i) => {
      gsap.from(step, {
        y: 30, opacity: 0, duration: 0.6, delay: i * 0.15,
        ease: 'power3.out',
        scrollTrigger: { trigger: step, start: 'top 90%' }
      })
    })

    const quoteCards = gsap.utils.toArray('.quote-card') as HTMLElement[]
    quoteCards.forEach((card, i) => {
      gsap.from(card, {
        y: 40, opacity: 0, scale: 0.95, duration: 0.7, delay: i * 0.1,
        ease: 'back.out(1.3)',
        scrollTrigger: { trigger: card, start: 'top 90%' }
      })
    })
  }, sectionRef.value)
}

onMounted(() => { initAnimations() })
onUnmounted(() => { ctx?.revert() })
</script>
