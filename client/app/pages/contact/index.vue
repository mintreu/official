<script setup lang="ts">
import { ref, reactive, onMounted, onUnmounted, nextTick } from 'vue'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

useSeoMeta({
  title: 'Contact | Mintreu - Get In Touch',
  description: 'Have a project in mind? Let\'s discuss how we can help bring your ideas to life. We\'re currently accepting new projects.'
})

const pageRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const sending = ref(false)
const sent = ref(false)
const error = ref<string | null>(null)
const activeAccordion = ref<number | null>(null)

const { submitContact } = useApi()

const form = reactive({
  name: '',
  email: '',
  company: '',
  mobile: '',
  message: ''
})

const stats = [
  { value: 'Full-Stack', label: 'Laravel & Nuxt', icon: 'lucide:code-2' },
  { value: 'Solo Dev', label: 'Direct Communication', icon: 'lucide:user' },
  { value: 'End-to-End', label: 'Design to Deployment', icon: 'lucide:layers' },
  { value: 'Flexible', label: 'Your Pace, Your Budget', icon: 'lucide:settings' },
]

const contactMethods = [
  { icon: 'lucide:mail', label: 'Email', value: 'hello@mintreu.com', href: 'mailto:hello@mintreu.com', color: 'mintreu-red' },
  { icon: 'lucide:github', label: 'GitHub', value: 'github.com/mintreu', href: 'https://github.com/mintreu', color: 'titanium' },
  { icon: 'lucide:twitter', label: 'Twitter / X', value: '@mintreu', href: 'https://twitter.com/mintreu', color: 'blue' },
  { icon: 'lucide:linkedin', label: 'LinkedIn', value: 'Mintreu', href: 'https://linkedin.com/company/mintreu', color: 'blue' },
]

const processSteps = [
  { num: '01', title: 'Discovery Call', desc: 'We discuss your vision, requirements, and goals in detail.' },
  { num: '02', title: 'Proposal & Timeline', desc: 'Detailed scope, tech stack, milestones, and transparent pricing.' },
  { num: '03', title: 'Build & Iterate', desc: 'Agile sprints with regular demos and feedback loops.' },
  { num: '04', title: 'Launch & Support', desc: 'Go live with confidence plus ongoing maintenance.' },
]

const faqs = [
  {
    question: 'What technologies do you specialize in?',
    answer: 'Our core stack is Laravel, Nuxt/Vue.js, React, Node.js, PostgreSQL, and modern DevOps (Docker, CI/CD). We pick the best tool for each problem.'
  },
  {
    question: 'Do you work with early-stage startups?',
    answer: 'Absolutely. We help startups go from idea to MVP fast, then iterate and scale. We understand the urgency and budget constraints of early-stage ventures.'
  },
  {
    question: 'What is the typical project timeline?',
    answer: 'A simple web app takes 4-6 weeks. Complex SaaS products can take 3-6 months. We provide a detailed milestone schedule after the discovery call.'
  },
  {
    question: 'Do you provide ongoing support after launch?',
    answer: 'Yes. We offer flexible maintenance packages covering security patches, performance monitoring, feature updates, and 24/7 incident response.'
  },
  {
    question: 'How do you handle communication and updates?',
    answer: 'We use Slack/Discord for daily communication, weekly video calls for progress reviews, and a shared project board (Linear/Jira) for complete transparency.'
  },
]

const toggleAccordion = (index: number) => {
  activeAccordion.value = activeAccordion.value === index ? null : index
}

const submitForm = async () => {
  sending.value = true
  error.value = null

  try {
    await submitContact({
      name: form.name,
      email: form.email,
      company: form.company || undefined,
      mobile: form.mobile || undefined,
      message: form.message
    }) as any

    sent.value = true
    form.name = ''
    form.email = ''
    form.company = ''
    form.mobile = ''
    form.message = ''

    setTimeout(() => { sent.value = false }, 6000)
  } catch (err: any) {
    error.value = err.data?.message || 'Something went wrong. Please try again.'
  } finally {
    sending.value = false
  }
}

const initAnimations = () => {
  ctx?.revert()
  if (!pageRef.value) return

  ctx = gsap.context(() => {
    const stDefaults = { toggleActions: 'play none none none' }

    // Hero - immediate, no scroll trigger
    const heroTl = gsap.timeline({ delay: 0.1 })
    heroTl.from('.hero-badge', { y: 20, opacity: 0, duration: 0.6, ease: 'power3.out' })
      .from('.hero-title', { y: 40, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.35')
      .from('.hero-desc', { y: 30, opacity: 0, duration: 0.7, ease: 'power3.out' }, '-=0.35')
      .from('.hero-line', { scaleX: 0, transformOrigin: 'center', duration: 1, ease: 'power2.out' }, '-=0.3')

    // Stats - staggered pop-in
    const statItems = gsap.utils.toArray('.stat-item') as HTMLElement[]
    if (statItems.length) {
      gsap.set(statItems, { opacity: 0, y: 40 })
      ScrollTrigger.create({
        trigger: '.stats-row',
        start: 'top 90%',
        once: true,
        onEnter: () => {
          gsap.to(statItems, {
            opacity: 1, y: 0, duration: 0.6, stagger: 0.12, ease: 'back.out(1.5)'
          })
        }
      })
    }

    // Form card
    const formCard = pageRef.value?.querySelector('.form-card')
    if (formCard) {
      gsap.set(formCard, { opacity: 0, x: -50 })
      ScrollTrigger.create({
        trigger: formCard,
        start: 'top 85%',
        once: true,
        onEnter: () => { gsap.to(formCard, { opacity: 1, x: 0, duration: 0.8, ease: 'power3.out' }) }
      })
    }

    // Right info panels
    const infoPanels = gsap.utils.toArray('.info-panel') as HTMLElement[]
    if (infoPanels.length) {
      gsap.set(infoPanels, { opacity: 0, x: 50 })
      ScrollTrigger.create({
        trigger: '.info-panels',
        start: 'top 85%',
        once: true,
        onEnter: () => {
          gsap.to(infoPanels, { opacity: 1, x: 0, duration: 0.7, stagger: 0.12, ease: 'power3.out' })
        }
      })
    }

    // Process steps
    const processStepEls = gsap.utils.toArray('.process-step') as HTMLElement[]
    if (processStepEls.length) {
      gsap.set(processStepEls, { opacity: 0, y: 40 })
      ScrollTrigger.create({
        trigger: '.process-section',
        start: 'top 85%',
        once: true,
        onEnter: () => {
          gsap.to(processStepEls, {
            opacity: 1, y: 0, duration: 0.6, stagger: 0.15, ease: 'back.out(1.3)'
          })
        }
      })
    }

    // FAQ items
    const faqItems = gsap.utils.toArray('.faq-item') as HTMLElement[]
    if (faqItems.length) {
      gsap.set(faqItems, { opacity: 0, y: 30 })
      ScrollTrigger.create({
        trigger: '.faq-section',
        start: 'top 85%',
        once: true,
        onEnter: () => {
          gsap.to(faqItems, { opacity: 1, y: 0, duration: 0.5, stagger: 0.08, ease: 'power3.out' })
        }
      })
    }

    // CTA bottom
    const ctaBottom = pageRef.value?.querySelector('.cta-bottom')
    if (ctaBottom) {
      gsap.set(ctaBottom, { opacity: 0, y: 50 })
      ScrollTrigger.create({
        trigger: ctaBottom,
        start: 'top 90%',
        once: true,
        onEnter: () => { gsap.to(ctaBottom, { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' }) }
      })
    }
  }, pageRef.value)

  // Force ScrollTrigger to recalculate after SPA render
  ScrollTrigger.refresh()
}

onMounted(() => { nextTick(() => { setTimeout(initAnimations, 50) }) })
onUnmounted(() => { ctx?.revert() })
</script>

<template>
  <div ref="pageRef" class="relative overflow-hidden">

    <!-- ============================================ -->
    <!-- HERO SECTION with 3D Scene                   -->
    <!-- ============================================ -->
    <section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden bg-titanium-950">
      <!-- 3D Background -->
      <div class="absolute inset-0 z-0">
        <ClientOnly>
          <TresCanvas :clear-color="'#1a1d1e'" :alpha="false" window-size>
            <TresPerspectiveCamera :position="[0, 0, 8]" :fov="55" />
            <TresAmbientLight :intensity="0.3" />
            <TresDirectionalLight :position="[5, 3, 5]" :intensity="0.8" color="#DC2626" />
            <TresDirectionalLight :position="[-4, 2, 3]" :intensity="0.4" color="#3b82f6" />
            <TresPointLight :position="[0, 0, 3]" :intensity="0.5" color="#DC2626" :distance="10" />

            <ThreeContactOrb color="#DC2626" wire-color="#3b82f6" :speed="0.3" />
            <ThreeFloatingNodes :count="80" :spread="14" color="#DC2626" />
            <ThreeBlueprintGrid :size="30" :divisions="60" color="#4b5563" :opacity="0.06" />
          </TresCanvas>
        </ClientOnly>
      </div>

      <!-- Gradient overlays -->
      <div class="absolute inset-0 bg-gradient-to-b from-titanium-950/40 via-transparent to-titanium-950/80 z-[1]"></div>
      <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-titanium-50 dark:from-titanium-950 z-[2]"></div>

      <!-- Hero Content -->
      <div class="relative z-10 text-center px-4 sm:px-6 max-w-3xl mx-auto py-20">
        <div class="hero-badge inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/10 mb-6">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
          <span class="text-sm font-heading font-bold uppercase tracking-wider text-titanium-200">Available for new projects</span>
        </div>

        <h1 class="hero-title text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-heading font-black text-white mb-6 leading-[1.1]">
          Let's Build<br>
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-mintreu-red-400 via-mintreu-red-500 to-mintreu-red-600">Something Great</span>
        </h1>

        <p class="hero-desc text-lg sm:text-xl text-titanium-300 max-w-xl mx-auto font-subheading leading-relaxed">
          Precision-engineered digital solutions from concept to production. Tell us about your project.
        </p>

        <div class="hero-line line-technical mt-10 mx-auto max-w-md"></div>
      </div>
    </section>

    <!-- ============================================ -->
    <!-- STATS BAR                                    -->
    <!-- ============================================ -->
    <section class="relative bg-titanium-50 dark:bg-titanium-950 py-0 -mt-1">
      <div class="stats-row max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -translate-y-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <div
            v-for="stat in stats"
            :key="stat.label"
            class="stat-item bg-white dark:bg-titanium-900 border border-titanium-200 dark:border-titanium-800 rounded-2xl p-5 text-center shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
          >
            <div class="w-10 h-10 rounded-xl bg-mintreu-red-100 dark:bg-mintreu-red-900/30 flex items-center justify-center mx-auto mb-3">
              <Icon :name="stat.icon" class="w-5 h-5 text-mintreu-red-600 dark:text-mintreu-red-400" />
            </div>
            <div class="text-2xl font-heading font-black text-titanium-900 dark:text-white">{{ stat.value }}</div>
            <div class="text-xs font-subheading font-semibold text-titanium-500 dark:text-titanium-400 uppercase tracking-wider mt-1">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================ -->
    <!-- MAIN CONTENT: Form + Info                    -->
    <!-- ============================================ -->
    <section class="relative bg-titanium-50 dark:bg-titanium-950 pb-20">
      <div class="absolute inset-0 bg-blueprint-fine pointer-events-none opacity-40"></div>
      <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-10 lg:gap-14">

          <!-- ===== LEFT: Contact Form ===== -->
          <div class="form-card">
            <div class="bg-white dark:bg-titanium-900 rounded-3xl shadow-2xl border border-titanium-200 dark:border-titanium-800 overflow-hidden">
              <!-- Form header -->
              <div class="px-8 pt-8 pb-2">
                <div class="flex items-center gap-3 mb-1">
                  <div class="w-10 h-10 rounded-xl bg-mintreu-red-600 flex items-center justify-center">
                    <Icon name="lucide:send" class="w-5 h-5 text-white" />
                  </div>
                  <div>
                    <h2 class="text-xl font-heading font-bold text-titanium-900 dark:text-white">Send a message</h2>
                    <p class="text-xs text-titanium-500 dark:text-titanium-400 font-subheading">We'll respond within 24 hours</p>
                  </div>
                </div>
              </div>

              <form @submit.prevent="submitForm" class="px-8 pb-8 pt-4 space-y-5">
                <div class="grid md:grid-cols-2 gap-5">
                  <div class="space-y-1.5">
                    <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Your Name</label>
                    <input
                      v-model="form.name"
                      type="text"
                      required
                      class="input-dashboard"
                      placeholder="John Doe"
                    />
                  </div>
                  <div class="space-y-1.5">
                    <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Email Address</label>
                    <input
                      v-model="form.email"
                      type="email"
                      required
                      class="input-dashboard"
                      placeholder="john@example.com"
                    />
                  </div>
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                  <div class="space-y-1.5">
                    <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Company Name <span class="text-titanium-300 dark:text-titanium-600 normal-case tracking-normal">(optional)</span></label>
                    <input
                      v-model="form.company"
                      type="text"
                      class="input-dashboard"
                      placeholder="Acme Inc."
                    />
                  </div>
                  <div class="space-y-1.5">
                    <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Mobile <span class="text-titanium-300 dark:text-titanium-600 normal-case tracking-normal">(optional)</span></label>
                    <input
                      v-model="form.mobile"
                      type="tel"
                      class="input-dashboard"
                      placeholder="+91 98765 43210"
                    />
                  </div>
                </div>

                <div class="space-y-1.5">
                  <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Your Message</label>
                  <textarea
                    v-model="form.message"
                    rows="5"
                    required
                    class="input-dashboard resize-none"
                    placeholder="Tell us about your project, goals, and timeline..."
                  ></textarea>
                </div>

                <button
                  type="submit"
                  :disabled="sending"
                  class="w-full relative px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white font-heading font-bold text-base rounded-xl shadow-lg glow-red hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none group"
                >
                  <span class="flex items-center justify-center gap-2">
                    <Icon v-if="!sending" name="lucide:send" class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                    <Icon v-else name="lucide:loader-2" class="w-5 h-5 animate-spin" />
                    {{ sending ? 'Sending...' : 'Send Message' }}
                  </span>
                </button>

                <!-- Success -->
                <Transition
                  enter-active-class="transition duration-300 ease-out"
                  enter-from-class="opacity-0 translate-y-2"
                  enter-to-class="opacity-100 translate-y-0"
                  leave-active-class="transition duration-200 ease-in"
                  leave-from-class="opacity-100"
                  leave-to-class="opacity-0"
                >
                  <div v-if="sent" class="flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 rounded-xl border border-emerald-200 dark:border-emerald-800">
                    <Icon name="lucide:check-circle-2" class="w-5 h-5 flex-shrink-0" />
                    <span class="text-sm font-subheading font-semibold">Message sent! We'll get back to you within 24 hours.</span>
                  </div>
                </Transition>

                <!-- Error -->
                <Transition
                  enter-active-class="transition duration-300 ease-out"
                  enter-from-class="opacity-0 translate-y-2"
                  enter-to-class="opacity-100 translate-y-0"
                  leave-active-class="transition duration-200 ease-in"
                  leave-from-class="opacity-100"
                  leave-to-class="opacity-0"
                >
                  <div v-if="error" class="flex items-center gap-3 p-4 bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-400 rounded-xl border border-rose-200 dark:border-rose-800">
                    <Icon name="lucide:alert-circle" class="w-5 h-5 flex-shrink-0" />
                    <span class="text-sm font-subheading">{{ error }}</span>
                  </div>
                </Transition>
              </form>
            </div>
          </div>

          <!-- ===== RIGHT: Info Panels ===== -->
          <div class="info-panels space-y-6">
            <!-- Contact Methods -->
            <div class="info-panel bg-white dark:bg-titanium-900 rounded-2xl shadow-xl border border-titanium-200 dark:border-titanium-800 p-6">
              <h3 class="text-sm font-heading font-bold uppercase tracking-wider text-titanium-400 dark:text-titanium-500 mb-4">Reach us directly</h3>
              <div class="space-y-2">
                <a
                  v-for="method in contactMethods"
                  :key="method.label"
                  :href="method.href"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="flex items-center gap-4 p-3 rounded-xl hover:bg-titanium-50 dark:hover:bg-titanium-800/60 transition-colors group"
                >
                  <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110"
                    :class="method.color === 'mintreu-red'
                      ? 'bg-mintreu-red-100 dark:bg-mintreu-red-900/30'
                      : method.color === 'blue'
                        ? 'bg-blue-100 dark:bg-blue-900/30'
                        : 'bg-titanium-100 dark:bg-titanium-800'"
                  >
                    <Icon :name="method.icon" class="w-5 h-5"
                      :class="method.color === 'mintreu-red'
                        ? 'text-mintreu-red-600 dark:text-mintreu-red-400'
                        : method.color === 'blue'
                          ? 'text-blue-600 dark:text-blue-400'
                          : 'text-titanium-600 dark:text-titanium-300'"
                    />
                  </div>
                  <div class="min-w-0">
                    <div class="text-sm font-semibold text-titanium-900 dark:text-white">{{ method.label }}</div>
                    <div class="text-xs text-titanium-500 dark:text-titanium-400 truncate">{{ method.value }}</div>
                  </div>
                  <Icon name="lucide:arrow-up-right" class="w-4 h-4 text-titanium-300 dark:text-titanium-600 ml-auto flex-shrink-0 group-hover:text-mintreu-red-500 transition-colors" />
                </a>
              </div>
            </div>

            <!-- Availability -->
            <div class="info-panel bg-white dark:bg-titanium-900 rounded-2xl shadow-xl border border-titanium-200 dark:border-titanium-800 p-6">
              <div class="flex items-center gap-3 mb-4">
                <div class="relative">
                  <div class="w-3 h-3 bg-emerald-400 rounded-full"></div>
                  <div class="absolute inset-0 w-3 h-3 bg-emerald-400 rounded-full animate-ping opacity-50"></div>
                </div>
                <span class="text-sm font-heading font-bold text-titanium-900 dark:text-white uppercase tracking-wider">Open for work</span>
              </div>
              <p class="text-sm text-titanium-600 dark:text-titanium-400 font-subheading leading-relaxed mb-5">
                Currently accepting new projects and customizations. Timeline depends on your requirements — drop a message and let's figure it out together.
              </p>
              <div class="space-y-2.5">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-titanium-50 dark:bg-titanium-800/50 border border-titanium-100 dark:border-titanium-700/50">
                  <Icon name="lucide:wrench" class="w-4 h-4 text-mintreu-red-500 dark:text-mintreu-red-400 flex-shrink-0" />
                  <span class="text-sm text-titanium-700 dark:text-titanium-300 font-subheading"><strong class="font-heading text-titanium-900 dark:text-white">Customizations</strong> — Quick turnaround for existing project tweaks & plugins</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-titanium-50 dark:bg-titanium-800/50 border border-titanium-100 dark:border-titanium-700/50">
                  <Icon name="lucide:layers" class="w-4 h-4 text-blue-500 dark:text-blue-400 flex-shrink-0" />
                  <span class="text-sm text-titanium-700 dark:text-titanium-300 font-subheading"><strong class="font-heading text-titanium-900 dark:text-white">New projects</strong> — Scoped and estimated after understanding your needs</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-titanium-50 dark:bg-titanium-800/50 border border-titanium-100 dark:border-titanium-700/50">
                  <Icon name="lucide:message-circle" class="w-4 h-4 text-emerald-500 dark:text-emerald-400 flex-shrink-0" />
                  <span class="text-sm text-titanium-700 dark:text-titanium-300 font-subheading"><strong class="font-heading text-titanium-900 dark:text-white">Free consultation</strong> — No commitment, let's just talk about your idea</span>
                </div>
              </div>
            </div>

            <!-- Tech Stack mini -->
            <div class="info-panel bg-white dark:bg-titanium-900 rounded-2xl shadow-xl border border-titanium-200 dark:border-titanium-800 p-6">
              <h3 class="text-sm font-heading font-bold uppercase tracking-wider text-titanium-400 dark:text-titanium-500 mb-4">Core tech stack</h3>
              <div class="flex flex-wrap gap-2">
                <span v-for="tech in ['Laravel', 'Nuxt', 'Vue.js', 'React', 'Node.js', 'PostgreSQL', 'Docker', 'AWS', 'Tailwind CSS', 'TypeScript']"
                  :key="tech"
                  class="px-3 py-1.5 rounded-lg bg-titanium-100 dark:bg-titanium-800 text-xs font-semibold text-titanium-700 dark:text-titanium-300 border border-titanium-200 dark:border-titanium-700 hover:border-mintreu-red-300 dark:hover:border-mintreu-red-800 transition-colors"
                >
                  {{ tech }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================ -->
    <!-- PROCESS SECTION                              -->
    <!-- ============================================ -->
    <section class="process-section relative bg-white dark:bg-titanium-900 py-20 border-t border-titanium-200 dark:border-titanium-800">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <span class="inline-flex px-3 py-1.5 rounded-full bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 text-xs font-heading font-bold uppercase tracking-wider mb-4">
            How we work
          </span>
          <h2 class="text-3xl sm:text-4xl font-heading font-black text-titanium-900 dark:text-white">Our Process</h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="step in processSteps"
            :key="step.num"
            class="process-step group relative bg-titanium-50 dark:bg-titanium-800/50 rounded-2xl p-6 border border-titanium-200 dark:border-titanium-700 hover:border-mintreu-red-300 dark:hover:border-mintreu-red-800 hover:shadow-lg transition-all duration-300"
          >
            <div class="text-4xl font-heading font-black text-mintreu-red-600/20 dark:text-mintreu-red-400/15 mb-3 group-hover:text-mintreu-red-600/40 transition-colors">{{ step.num }}</div>
            <h3 class="text-base font-heading font-bold text-titanium-900 dark:text-white mb-2">{{ step.title }}</h3>
            <p class="text-sm text-titanium-500 dark:text-titanium-400 font-subheading leading-relaxed">{{ step.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================ -->
    <!-- FAQ SECTION - Accordion                      -->
    <!-- ============================================ -->
    <section class="faq-section relative bg-titanium-50 dark:bg-titanium-950 py-20">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <span class="inline-flex px-3 py-1.5 rounded-full bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 text-xs font-heading font-bold uppercase tracking-wider mb-4">
            FAQ
          </span>
          <h2 class="text-3xl sm:text-4xl font-heading font-black text-titanium-900 dark:text-white">Common Questions</h2>
        </div>

        <div class="space-y-3">
          <div
            v-for="(faq, index) in faqs"
            :key="index"
            class="faq-item bg-white dark:bg-titanium-900 rounded-2xl border border-titanium-200 dark:border-titanium-800 overflow-hidden transition-all duration-300"
            :class="activeAccordion === index ? 'shadow-lg border-mintreu-red-200 dark:border-mintreu-red-900' : 'hover:border-titanium-300 dark:hover:border-titanium-700'"
          >
            <button
              type="button"
              class="w-full flex items-center justify-between p-5 text-left"
              @click="toggleAccordion(index)"
            >
              <span class="text-base font-heading font-bold text-titanium-900 dark:text-white pr-4">{{ faq.question }}</span>
              <Icon
                name="lucide:chevron-down"
                class="w-5 h-5 text-titanium-400 flex-shrink-0 transition-transform duration-300"
                :class="activeAccordion === index ? 'rotate-180 text-mintreu-red-500' : ''"
              />
            </button>
            <Transition
              enter-active-class="transition-all duration-300 ease-out"
              enter-from-class="max-h-0 opacity-0"
              enter-to-class="max-h-40 opacity-100"
              leave-active-class="transition-all duration-200 ease-in"
              leave-from-class="max-h-40 opacity-100"
              leave-to-class="max-h-0 opacity-0"
            >
              <div v-if="activeAccordion === index" class="overflow-hidden">
                <p class="px-5 pb-5 text-sm text-titanium-600 dark:text-titanium-400 font-subheading leading-relaxed -mt-1">
                  {{ faq.answer }}
                </p>
              </div>
            </Transition>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================ -->
    <!-- BOTTOM CTA                                   -->
    <!-- ============================================ -->
    <section class="relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-br from-titanium-900 via-titanium-950 to-titanium-900"></div>
      <div class="absolute inset-0 bg-blueprint opacity-10 pointer-events-none"></div>
      <div class="cta-bottom relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <div class="w-16 h-16 rounded-2xl bg-mintreu-red-600 flex items-center justify-center mx-auto mb-6 shadow-lg glow-red">
          <Icon name="lucide:zap" class="w-8 h-8 text-white" />
        </div>
        <h2 class="text-3xl sm:text-4xl font-heading font-black text-white mb-4">Ready to get started?</h2>
        <p class="text-titanium-400 font-subheading text-lg mb-8 max-w-lg mx-auto">
          Let's turn your idea into a production-grade digital product. No long proposals - just results.
        </p>
        <a
          href="#"
          @click.prevent="window.scrollTo({ top: 0, behavior: 'smooth' })"
          class="inline-flex items-center gap-2 px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white font-heading font-bold rounded-xl shadow-lg glow-red transform hover:scale-105 active:scale-95 transition-all"
        >
          <Icon name="lucide:arrow-up" class="w-5 h-5" />
          Back to form
        </a>
      </div>
    </section>

  </div>
</template>
