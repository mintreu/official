<script setup lang="ts">
import { ref, reactive, computed, watch, inject, onMounted, onUnmounted, nextTick } from 'vue'
import type { QuoteRequest } from '~/types/api'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const currency = inject('currency', ref<'USD' | 'INR'>('USD'))
const { submitQuote } = useApi()

const currentStep = ref(0)
const sending = ref(false)
const sent = ref(false)
const errorMsg = ref<string | null>(null)
const direction = ref<'forward' | 'back'>('forward')

// ── Wizard Data ──
const wizard = reactive({
  // Step 0: Contact
  name: '',
  email: '',
  company: '',
  mobile: '',
  // Step 1: Category
  project_category: '',
  // Step 2: Type + Platform (dynamic based on category)
  project_type: '',
  platforms: [] as string[],
  // Step 3: Features (dynamic based on category+type)
  features: [] as string[],
  custom_features: '',
  // Step 4: Description + extras
  description: '',
  has_existing_project: false,
  existing_project_url: '',
  reference_urls: '',
  // Step 5: Budget + timeline + priority
  budget_range: '',
  timeline: '',
  priority: 'normal' as 'normal' | 'urgent',
})

const totalSteps = 6
const progress = computed(() => ((currentStep.value + 1) / totalSteps) * 100)

// ── Step Labels ──
const stepLabels = ['Contact', 'Category', 'Project', 'Features', 'Details', 'Budget']

// ── Dynamic Options ──
const categories = [
  { value: 'software', label: 'Software / Web App', icon: 'lucide:monitor', desc: 'Web applications, SaaS, portals' },
  { value: 'mobile', label: 'Mobile App', icon: 'lucide:smartphone', desc: 'iOS, Android, cross-platform' },
  { value: 'game', label: 'Game Development', icon: 'lucide:gamepad-2', desc: 'Web games, mobile games, 3D' },
  { value: 'plugin', label: 'Plugin / Extension', icon: 'lucide:puzzle', desc: 'WordPress, Laravel, browser extensions' },
  { value: 'customization', label: 'Customization / Update', icon: 'lucide:wrench', desc: 'Modify or upgrade existing project' },
  { value: 'api', label: 'API / Backend', icon: 'lucide:server', desc: 'REST API, microservices, integrations' },
  { value: 'design', label: 'UI/UX Design', icon: 'lucide:palette', desc: 'Design systems, prototypes, redesigns' },
  { value: 'other', label: 'Something Else', icon: 'lucide:sparkles', desc: 'Consulting, automation, AI/ML' },
]

const projectTypeOptions = computed(() => {
  const map: Record<string, { value: string; label: string }[]> = {
    software: [
      { value: 'saas', label: 'SaaS Product' },
      { value: 'ecommerce', label: 'E-Commerce' },
      { value: 'crm', label: 'CRM / ERP' },
      { value: 'dashboard', label: 'Admin Dashboard' },
      { value: 'marketplace', label: 'Marketplace' },
      { value: 'social', label: 'Social Platform' },
      { value: 'portal', label: 'Client / User Portal' },
      { value: 'other', label: 'Other' },
    ],
    mobile: [
      { value: 'native-ios', label: 'Native iOS' },
      { value: 'native-android', label: 'Native Android' },
      { value: 'cross-platform', label: 'Cross-Platform (Flutter/RN)' },
      { value: 'pwa', label: 'Progressive Web App' },
      { value: 'other', label: 'Other' },
    ],
    game: [
      { value: '2d-game', label: '2D Game' },
      { value: '3d-game', label: '3D Game' },
      { value: 'web-game', label: 'Browser / Web Game' },
      { value: 'mobile-game', label: 'Mobile Game' },
      { value: 'other', label: 'Other' },
    ],
    plugin: [
      { value: 'wp-plugin', label: 'WordPress Plugin' },
      { value: 'laravel-pkg', label: 'Laravel Package' },
      { value: 'browser-ext', label: 'Browser Extension' },
      { value: 'npm-pkg', label: 'NPM Package' },
      { value: 'other', label: 'Other' },
    ],
    customization: [
      { value: 'feature-add', label: 'Add New Feature' },
      { value: 'bug-fix', label: 'Bug Fix / Patch' },
      { value: 'redesign', label: 'UI Redesign' },
      { value: 'migration', label: 'Migration / Upgrade' },
      { value: 'performance', label: 'Performance Optimization' },
      { value: 'other', label: 'Other' },
    ],
    api: [
      { value: 'rest-api', label: 'REST API' },
      { value: 'graphql', label: 'GraphQL' },
      { value: 'integration', label: 'Third-Party Integration' },
      { value: 'microservice', label: 'Microservice' },
      { value: 'other', label: 'Other' },
    ],
    design: [
      { value: 'full-design', label: 'Full UI/UX Design' },
      { value: 'redesign', label: 'Redesign Existing' },
      { value: 'prototype', label: 'Prototype / Wireframe' },
      { value: 'design-system', label: 'Design System' },
      { value: 'other', label: 'Other' },
    ],
    other: [
      { value: 'consulting', label: 'Technical Consulting' },
      { value: 'automation', label: 'Automation / Scripts' },
      { value: 'ai-ml', label: 'AI / ML Solution' },
      { value: 'devops', label: 'DevOps / Infrastructure' },
      { value: 'other', label: 'Other' },
    ],
  }
  return map[wizard.project_category] || []
})

const platformOptions = computed(() => {
  const cat = wizard.project_category
  if (cat === 'mobile') return [
    { value: 'ios', label: 'iOS' }, { value: 'android', label: 'Android' },
    { value: 'both', label: 'Both' },
  ]
  if (cat === 'game') return [
    { value: 'web', label: 'Web' }, { value: 'mobile', label: 'Mobile' },
    { value: 'desktop', label: 'Desktop' }, { value: 'console', label: 'Console' },
  ]
  if (cat === 'software' || cat === 'api') return [
    { value: 'web', label: 'Web' }, { value: 'desktop', label: 'Desktop' },
    { value: 'mobile', label: 'Mobile (companion)' },
  ]
  return []
})

const featureOptions = computed(() => {
  const base = [
    { value: 'auth', label: 'User Authentication' },
    { value: 'payment', label: 'Payment Integration' },
    { value: 'admin', label: 'Admin Panel' },
    { value: 'notifications', label: 'Notifications (Email/Push)' },
    { value: 'analytics', label: 'Analytics / Reporting' },
    { value: 'file-upload', label: 'File Upload / Storage' },
  ]
  const catExtras: Record<string, { value: string; label: string }[]> = {
    software: [
      { value: 'multi-tenant', label: 'Multi-Tenant' },
      { value: 'search', label: 'Search / Filtering' },
      { value: 'api-access', label: 'API Access for Clients' },
      { value: 'roles', label: 'Roles & Permissions' },
      { value: 'i18n', label: 'Multi-Language' },
      { value: 'realtime', label: 'Real-Time Updates' },
    ],
    mobile: [
      { value: 'offline', label: 'Offline Mode' },
      { value: 'camera', label: 'Camera / Media' },
      { value: 'gps', label: 'GPS / Location' },
      { value: 'biometric', label: 'Biometric Auth' },
    ],
    game: [
      { value: 'multiplayer', label: 'Multiplayer' },
      { value: 'leaderboard', label: 'Leaderboard' },
      { value: 'in-app-purchase', label: 'In-App Purchases' },
      { value: 'physics', label: 'Physics Engine' },
    ],
    api: [
      { value: 'rate-limit', label: 'Rate Limiting' },
      { value: 'webhooks', label: 'Webhooks' },
      { value: 'oauth', label: 'OAuth / SSO' },
      { value: 'caching', label: 'Caching Layer' },
    ],
  }
  return [...base, ...(catExtras[wizard.project_category] || [])]
})

const budgetRanges = computed(() => {
  if (currency.value === 'INR') return [
    { value: 'under-25k', label: 'Under ₹25,000' },
    { value: '25k-1l', label: '₹25,000 – ₹1,00,000' },
    { value: '1l-5l', label: '₹1,00,000 – ₹5,00,000' },
    { value: '5l-15l', label: '₹5,00,000 – ₹15,00,000' },
    { value: '15l-plus', label: '₹15,00,000+' },
    { value: 'not-sure', label: 'Not sure yet' },
  ]
  return [
    { value: 'under-500', label: 'Under $500' },
    { value: '500-2k', label: '$500 – $2,000' },
    { value: '2k-10k', label: '$2,000 – $10,000' },
    { value: '10k-50k', label: '$10,000 – $50,000' },
    { value: '50k-plus', label: '$50,000+' },
    { value: 'not-sure', label: 'Not sure yet' },
  ]
})

const timelineOptions = [
  { value: 'asap', label: 'As soon as possible' },
  { value: '1-2-weeks', label: '1–2 weeks' },
  { value: '1-month', label: '~ 1 month' },
  { value: '1-3-months', label: '1–3 months' },
  { value: '3-6-months', label: '3–6 months' },
  { value: 'flexible', label: 'Flexible / No rush' },
]

// ── Reset dependent fields on category change ──
watch(() => wizard.project_category, () => {
  wizard.project_type = ''
  wizard.platforms = []
  wizard.features = []
  wizard.custom_features = ''
})

// ── Step Validation ──
const canProceed = computed(() => {
  switch (currentStep.value) {
    case 0: return wizard.name.trim() !== '' && wizard.email.trim() !== '' && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(wizard.email)
    case 1: return wizard.project_category !== ''
    case 2: return wizard.project_type !== ''
    case 3: return true // features are optional
    case 4: return wizard.description.trim().length >= 10
    case 5: return true // budget/timeline optional
    default: return false
  }
})

// ── Navigation ──
const animateStepTransition = (dir: 'forward' | 'back') => {
  direction.value = dir
  nextTick(() => {
    const stepEl = document.querySelector('.wizard-step-content')
    if (stepEl) {
      gsap.fromTo(stepEl,
        { opacity: 0, x: dir === 'forward' ? 60 : -60 },
        { opacity: 1, x: 0, duration: 0.4, ease: 'power3.out' }
      )
    }
  })
}

const nextStep = () => {
  if (!canProceed.value || currentStep.value >= totalSteps - 1) return
  currentStep.value++
  animateStepTransition('forward')
}

const prevStep = () => {
  if (currentStep.value <= 0) return
  currentStep.value--
  animateStepTransition('back')
}

const goToStep = (step: number) => {
  if (step > currentStep.value) return // can't skip ahead
  const dir = step > currentStep.value ? 'forward' : 'back'
  currentStep.value = step
  animateStepTransition(dir)
}

// ── Toggle helpers ──
const togglePlatform = (val: string) => {
  const idx = wizard.platforms.indexOf(val)
  if (idx >= 0) wizard.platforms.splice(idx, 1)
  else wizard.platforms.push(val)
}

const toggleFeature = (val: string) => {
  const idx = wizard.features.indexOf(val)
  if (idx >= 0) wizard.features.splice(idx, 1)
  else wizard.features.push(val)
}

// ── Submit ──
const handleSubmit = async () => {
  sending.value = true
  errorMsg.value = null

  const payload: QuoteRequest = {
    name: wizard.name,
    email: wizard.email,
    company: wizard.company || undefined,
    mobile: wizard.mobile || undefined,
    project_category: wizard.project_category,
    project_type: wizard.project_type,
    platforms: wizard.platforms,
    features: wizard.features,
    custom_features: wizard.custom_features || undefined,
    description: wizard.description,
    budget_range: wizard.budget_range || undefined,
    currency: currency.value,
    timeline: wizard.timeline || undefined,
    has_existing_project: wizard.has_existing_project,
    existing_project_url: wizard.existing_project_url || undefined,
    reference_urls: wizard.reference_urls || undefined,
    priority: wizard.priority,
  }

  try {
    await submitQuote(payload) as any
    sent.value = true
  } catch (err: any) {
    errorMsg.value = err.data?.message || 'Something went wrong. Please try again.'
  } finally {
    sending.value = false
  }
}

// ── GSAP entrance ──
onMounted(() => {
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.wizard-intro', {
      y: 40, opacity: 0, duration: 0.8, ease: 'power3.out',
      scrollTrigger: { trigger: sectionRef.value, start: 'top 80%' }
    })
    gsap.from('.wizard-card', {
      y: 60, opacity: 0, duration: 0.9, delay: 0.15, ease: 'power3.out',
      scrollTrigger: { trigger: sectionRef.value, start: 'top 75%' }
    })
  }, sectionRef.value)
})

onUnmounted(() => { ctx?.revert() })
</script>

<template>
  <section
    id="contact"
    ref="sectionRef"
    class="relative py-20 lg:py-32 overflow-hidden"
  >
    <!-- Background -->
    <div class="absolute inset-0 bg-titanium-900 dark:bg-titanium-950 pointer-events-none"></div>
    <div class="absolute inset-0 bg-blueprint opacity-15 pointer-events-none"></div>

    <!-- 3D Canvas Background -->
    <div class="absolute inset-0 z-0 opacity-40 pointer-events-none">
      <ClientOnly>
        <TresCanvas :clear-color="'#1a1d1e'" :alpha="false" window-size class="pointer-events-none">
          <TresPerspectiveCamera :position="[0, 0, 10]" :fov="50" />
          <TresAmbientLight :intensity="0.2" />
          <TresDirectionalLight :position="[5, 3, 5]" :intensity="0.5" color="#DC2626" />
          <ThreeFloatingNodes :count="50" :spread="16" color="#DC2626" />
          <ThreeBlueprintGrid :size="30" :divisions="60" color="#4b5563" :opacity="0.04" />
        </TresCanvas>
      </ClientOnly>
    </div>

    <!-- Decorative blurs -->
    <div class="absolute top-20 -left-32 w-96 h-96 bg-mintreu-red-600/8 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 -right-32 w-96 h-96 bg-blue-600/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Intro -->
      <div class="wizard-intro text-center mb-12">
        <div class="label-schematic mb-4">SEC-CONTACT // REV-A</div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-black text-white mb-4 leading-tight">
          Get a Free Quote
        </h2>
        <p class="text-lg text-titanium-400 font-subheading max-w-xl mx-auto">
          Tell us about your project step by step. The more details you share, the better we can understand your vision.
        </p>
      </div>

      <!-- ═══ SUCCESS STATE ═══ -->
      <Transition
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
      >
        <div v-if="sent" class="wizard-card bg-white dark:bg-titanium-900 rounded-3xl shadow-2xl border border-titanium-200 dark:border-titanium-800 p-10 text-center">
          <div class="w-20 h-20 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-6">
            <Icon name="lucide:check-circle-2" class="w-10 h-10 text-emerald-600 dark:text-emerald-400" />
          </div>
          <h3 class="text-2xl font-heading font-black text-titanium-900 dark:text-white mb-3">Quote Request Sent!</h3>
          <p class="text-titanium-600 dark:text-titanium-400 font-subheading max-w-md mx-auto mb-6">
            Thank you for sharing your project details. We'll review everything carefully and get back to you with a detailed proposal.
          </p>
          <button
            @click="sent = false; currentStep = 0"
            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-titanium-100 dark:bg-titanium-800 text-titanium-700 dark:text-titanium-300 font-heading font-bold text-sm hover:bg-titanium-200 dark:hover:bg-titanium-700 transition-colors"
          >
            <Icon name="lucide:plus" class="w-4 h-4" />
            Submit Another
          </button>
        </div>
      </Transition>

      <!-- ═══ WIZARD CARD ═══ -->
      <div v-if="!sent" class="wizard-card bg-white dark:bg-titanium-900 rounded-3xl shadow-2xl border border-titanium-200 dark:border-titanium-800 overflow-hidden">

        <!-- ── Progress Bar ── -->
        <div class="h-1 bg-titanium-100 dark:bg-titanium-800">
          <div
            class="h-full bg-gradient-to-r from-mintreu-red-500 to-mintreu-red-600 transition-all duration-500 ease-out"
            :style="{ width: `${progress}%` }"
          ></div>
        </div>

        <!-- ── Step Indicators ── -->
        <div class="px-6 pt-6 pb-2">
          <div class="flex items-center justify-between max-w-lg mx-auto">
            <button
              v-for="(label, i) in stepLabels"
              :key="i"
              type="button"
              class="flex flex-col items-center gap-1.5 group"
              :class="i <= currentStep ? 'cursor-pointer' : 'cursor-default'"
              @click="goToStep(i)"
            >
              <div
                class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-heading font-bold transition-all duration-300"
                :class="
                  i < currentStep
                    ? 'bg-emerald-500 text-white scale-90'
                    : i === currentStep
                      ? 'bg-mintreu-red-600 text-white scale-110 shadow-lg'
                      : 'bg-titanium-100 dark:bg-titanium-800 text-titanium-400 dark:text-titanium-500'
                "
              >
                <Icon v-if="i < currentStep" name="lucide:check" class="w-4 h-4" />
                <span v-else>{{ i + 1 }}</span>
              </div>
              <span
                class="text-[10px] font-heading font-semibold uppercase tracking-wider hidden sm:block transition-colors"
                :class="i === currentStep ? 'text-mintreu-red-600 dark:text-mintreu-red-400' : 'text-titanium-400 dark:text-titanium-500'"
              >{{ label }}</span>
            </button>
          </div>
        </div>

        <!-- ── Step Content ── -->
        <div class="px-6 sm:px-10 py-8 min-h-[380px]">
          <div class="wizard-step-content">

            <!-- ═══ STEP 0: Contact Info ═══ -->
            <div v-if="currentStep === 0" class="space-y-6">
              <div>
                <h3 class="text-xl font-heading font-bold text-titanium-900 dark:text-white mb-1">Let's start with you</h3>
                <p class="text-sm text-titanium-500 dark:text-titanium-400 font-subheading">How can we reach you?</p>
              </div>

              <div class="grid sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                  <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Your Name *</label>
                  <input v-model="wizard.name" type="text" required class="input-dashboard" placeholder="John Doe" />
                </div>
                <div class="space-y-1.5">
                  <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Email *</label>
                  <input v-model="wizard.email" type="email" required class="input-dashboard" placeholder="john@example.com" />
                </div>
              </div>
              <div class="grid sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                  <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Company <span class="text-titanium-300 dark:text-titanium-600 normal-case tracking-normal">(optional)</span></label>
                  <input v-model="wizard.company" type="text" class="input-dashboard" placeholder="Acme Inc." />
                </div>
                <div class="space-y-1.5">
                  <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Mobile <span class="text-titanium-300 dark:text-titanium-600 normal-case tracking-normal">(optional)</span></label>
                  <input v-model="wizard.mobile" type="tel" class="input-dashboard" placeholder="+91 98765 43210" />
                </div>
              </div>
            </div>

            <!-- ═══ STEP 1: Project Category ═══ -->
            <div v-if="currentStep === 1" class="space-y-6">
              <div>
                <h3 class="text-xl font-heading font-bold text-titanium-900 dark:text-white mb-1">What are you looking for?</h3>
                <p class="text-sm text-titanium-500 dark:text-titanium-400 font-subheading">Pick the category that best describes your project.</p>
              </div>

              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <button
                  v-for="cat in categories"
                  :key="cat.value"
                  type="button"
                  class="group relative flex flex-col items-center text-center p-4 rounded-2xl border-2 transition-all duration-200"
                  :class="
                    wizard.project_category === cat.value
                      ? 'border-mintreu-red-500 bg-mintreu-red-50 dark:bg-mintreu-red-900/20 shadow-lg'
                      : 'border-titanium-200 dark:border-titanium-700 bg-titanium-50 dark:bg-titanium-800/50 hover:border-titanium-300 dark:hover:border-titanium-600 hover:shadow-md'
                  "
                  @click="wizard.project_category = cat.value"
                >
                  <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center mb-2 transition-colors"
                    :class="
                      wizard.project_category === cat.value
                        ? 'bg-mintreu-red-600 text-white'
                        : 'bg-titanium-200 dark:bg-titanium-700 text-titanium-500 dark:text-titanium-400 group-hover:bg-titanium-300 dark:group-hover:bg-titanium-600'
                    "
                  >
                    <Icon :name="cat.icon" class="w-5 h-5" />
                  </div>
                  <span class="text-sm font-heading font-bold text-titanium-900 dark:text-white leading-tight">{{ cat.label }}</span>
                  <span class="text-[10px] text-titanium-400 dark:text-titanium-500 mt-1 leading-tight">{{ cat.desc }}</span>
                </button>
              </div>
            </div>

            <!-- ═══ STEP 2: Project Type + Platforms ═══ -->
            <div v-if="currentStep === 2" class="space-y-6">
              <div>
                <h3 class="text-xl font-heading font-bold text-titanium-900 dark:text-white mb-1">Tell us more about the project</h3>
                <p class="text-sm text-titanium-500 dark:text-titanium-400 font-subheading">Select the type and target platforms.</p>
              </div>

              <!-- Project Type -->
              <div class="space-y-3">
                <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Project Type *</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                  <button
                    v-for="opt in projectTypeOptions"
                    :key="opt.value"
                    type="button"
                    class="px-4 py-3 rounded-xl border-2 text-sm font-semibold transition-all duration-200"
                    :class="
                      wizard.project_type === opt.value
                        ? 'border-mintreu-red-500 bg-mintreu-red-50 dark:bg-mintreu-red-900/20 text-mintreu-red-700 dark:text-mintreu-red-400'
                        : 'border-titanium-200 dark:border-titanium-700 text-titanium-700 dark:text-titanium-300 hover:border-titanium-300 dark:hover:border-titanium-600'
                    "
                    @click="wizard.project_type = opt.value"
                  >
                    {{ opt.label }}
                  </button>
                </div>
              </div>

              <!-- Platforms (if applicable) -->
              <div v-if="platformOptions.length" class="space-y-3">
                <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Target Platforms <span class="text-titanium-300 dark:text-titanium-600 normal-case tracking-normal">(select all that apply)</span></label>
                <div class="flex flex-wrap gap-2.5">
                  <button
                    v-for="opt in platformOptions"
                    :key="opt.value"
                    type="button"
                    class="px-4 py-2.5 rounded-xl border-2 text-sm font-semibold transition-all duration-200"
                    :class="
                      wizard.platforms.includes(opt.value)
                        ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'
                        : 'border-titanium-200 dark:border-titanium-700 text-titanium-700 dark:text-titanium-300 hover:border-titanium-300 dark:hover:border-titanium-600'
                    "
                    @click="togglePlatform(opt.value)"
                  >
                    {{ opt.label }}
                  </button>
                </div>
              </div>
            </div>

            <!-- ═══ STEP 3: Features ═══ -->
            <div v-if="currentStep === 3" class="space-y-6">
              <div>
                <h3 class="text-xl font-heading font-bold text-titanium-900 dark:text-white mb-1">Key features you need</h3>
                <p class="text-sm text-titanium-500 dark:text-titanium-400 font-subheading">Select the features that apply. You can add custom ones below.</p>
              </div>

              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                <button
                  v-for="feat in featureOptions"
                  :key="feat.value"
                  type="button"
                  class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl border-2 text-sm font-semibold transition-all duration-200 text-left"
                  :class="
                    wizard.features.includes(feat.value)
                      ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400'
                      : 'border-titanium-200 dark:border-titanium-700 text-titanium-700 dark:text-titanium-300 hover:border-titanium-300 dark:hover:border-titanium-600'
                  "
                  @click="toggleFeature(feat.value)"
                >
                  <Icon
                    :name="wizard.features.includes(feat.value) ? 'lucide:check-square' : 'lucide:square'"
                    class="w-4 h-4 flex-shrink-0"
                  />
                  <span>{{ feat.label }}</span>
                </button>
              </div>

              <div class="space-y-1.5">
                <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Anything else? <span class="text-titanium-300 dark:text-titanium-600 normal-case tracking-normal">(optional)</span></label>
                <input v-model="wizard.custom_features" type="text" class="input-dashboard" placeholder="e.g., AI chatbot, video streaming, inventory sync..." />
              </div>
            </div>

            <!-- ═══ STEP 4: Description + Extras ═══ -->
            <div v-if="currentStep === 4" class="space-y-6">
              <div>
                <h3 class="text-xl font-heading font-bold text-titanium-900 dark:text-white mb-1">Describe your idea</h3>
                <p class="text-sm text-titanium-500 dark:text-titanium-400 font-subheading">The more detail you share, the better we can understand your vision.</p>
              </div>

              <div class="space-y-1.5">
                <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Project Description *</label>
                <textarea
                  v-model="wizard.description"
                  rows="5"
                  class="input-dashboard resize-none"
                  placeholder="Describe what you want to build, the problem it solves, who the users are, and any key workflows or screens you have in mind..."
                ></textarea>
                <p class="text-[10px] text-titanium-400 dark:text-titanium-500">Minimum 10 characters</p>
              </div>

              <!-- Existing project toggle -->
              <div class="flex items-start gap-3 p-4 rounded-xl bg-titanium-50 dark:bg-titanium-800/50 border border-titanium-200 dark:border-titanium-700">
                <button
                  type="button"
                  class="w-5 h-5 rounded flex-shrink-0 mt-0.5 border-2 flex items-center justify-center transition-colors"
                  :class="wizard.has_existing_project ? 'bg-mintreu-red-600 border-mintreu-red-600' : 'border-titanium-300 dark:border-titanium-600'"
                  @click="wizard.has_existing_project = !wizard.has_existing_project"
                >
                  <Icon v-if="wizard.has_existing_project" name="lucide:check" class="w-3 h-3 text-white" />
                </button>
                <div class="min-w-0">
                  <span class="text-sm font-heading font-bold text-titanium-900 dark:text-white">I have an existing project</span>
                  <p class="text-xs text-titanium-500 dark:text-titanium-400">If this is a customization, update, or extension of something already built.</p>
                </div>
              </div>

              <div v-if="wizard.has_existing_project" class="space-y-1.5">
                <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Existing Project URL</label>
                <input v-model="wizard.existing_project_url" type="url" class="input-dashboard" placeholder="https://myapp.com" />
              </div>

              <div class="space-y-1.5">
                <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Reference Links <span class="text-titanium-300 dark:text-titanium-600 normal-case tracking-normal">(optional)</span></label>
                <input v-model="wizard.reference_urls" type="text" class="input-dashboard" placeholder="Links to similar apps, designs, Figma files..." />
              </div>
            </div>

            <!-- ═══ STEP 5: Budget + Timeline + Priority ═══ -->
            <div v-if="currentStep === 5" class="space-y-6">
              <div>
                <h3 class="text-xl font-heading font-bold text-titanium-900 dark:text-white mb-1">Budget & timeline</h3>
                <p class="text-sm text-titanium-500 dark:text-titanium-400 font-subheading">
                  These are optional — just helps us tailor the proposal.
                  <span class="text-titanium-300 dark:text-titanium-600">(Currency: {{ currency }})</span>
                </p>
              </div>

              <!-- Budget -->
              <div class="space-y-3">
                <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Budget Range</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                  <button
                    v-for="opt in budgetRanges"
                    :key="opt.value"
                    type="button"
                    class="px-4 py-3 rounded-xl border-2 text-sm font-semibold transition-all duration-200"
                    :class="
                      wizard.budget_range === opt.value
                        ? 'border-mintreu-red-500 bg-mintreu-red-50 dark:bg-mintreu-red-900/20 text-mintreu-red-700 dark:text-mintreu-red-400'
                        : 'border-titanium-200 dark:border-titanium-700 text-titanium-700 dark:text-titanium-300 hover:border-titanium-300 dark:hover:border-titanium-600'
                    "
                    @click="wizard.budget_range = wizard.budget_range === opt.value ? '' : opt.value"
                  >
                    {{ opt.label }}
                  </button>
                </div>
              </div>

              <!-- Timeline -->
              <div class="space-y-3">
                <label class="block text-xs font-heading font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Preferred Timeline</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                  <button
                    v-for="opt in timelineOptions"
                    :key="opt.value"
                    type="button"
                    class="px-4 py-3 rounded-xl border-2 text-sm font-semibold transition-all duration-200"
                    :class="
                      wizard.timeline === opt.value
                        ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'
                        : 'border-titanium-200 dark:border-titanium-700 text-titanium-700 dark:text-titanium-300 hover:border-titanium-300 dark:hover:border-titanium-600'
                    "
                    @click="wizard.timeline = wizard.timeline === opt.value ? '' : opt.value"
                  >
                    {{ opt.label }}
                  </button>
                </div>
              </div>

              <!-- Priority -->
              <div class="flex items-start gap-3 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/30">
                <button
                  type="button"
                  class="w-5 h-5 rounded flex-shrink-0 mt-0.5 border-2 flex items-center justify-center transition-colors"
                  :class="wizard.priority === 'urgent' ? 'bg-amber-500 border-amber-500' : 'border-titanium-300 dark:border-titanium-600'"
                  @click="wizard.priority = wizard.priority === 'urgent' ? 'normal' : 'urgent'"
                >
                  <Icon v-if="wizard.priority === 'urgent'" name="lucide:check" class="w-3 h-3 text-white" />
                </button>
                <div>
                  <span class="text-sm font-heading font-bold text-titanium-900 dark:text-white">Mark as urgent</span>
                  <p class="text-xs text-titanium-500 dark:text-titanium-400">We'll prioritize this request and get back to you faster.</p>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- ── Footer: Navigation ── -->
        <div class="px-6 sm:px-10 pb-8 pt-2">
          <!-- Error -->
          <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
          >
            <div v-if="errorMsg" class="flex items-center gap-3 p-4 mb-4 bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-400 rounded-xl border border-rose-200 dark:border-rose-800">
              <Icon name="lucide:alert-circle" class="w-5 h-5 flex-shrink-0" />
              <span class="text-sm font-subheading">{{ errorMsg }}</span>
            </div>
          </Transition>

          <div class="flex items-center justify-between">
            <button
              v-if="currentStep > 0"
              type="button"
              class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-heading font-bold text-titanium-600 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
              @click="prevStep"
            >
              <Icon name="lucide:arrow-left" class="w-4 h-4" />
              Back
            </button>
            <div v-else></div>

            <!-- Next / Submit -->
            <button
              v-if="currentStep < totalSteps - 1"
              type="button"
              :disabled="!canProceed"
              class="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-heading font-bold text-sm transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed"
              :class="canProceed
                ? 'bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white shadow-lg glow-red hover:scale-[1.02] active:scale-[0.98]'
                : 'bg-titanium-200 dark:bg-titanium-700 text-titanium-400 dark:text-titanium-500'"
              @click="nextStep"
            >
              Continue
              <Icon name="lucide:arrow-right" class="w-4 h-4" />
            </button>

            <button
              v-else
              type="button"
              :disabled="sending"
              class="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-heading font-bold text-sm bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg transform hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed"
              @click="handleSubmit"
            >
              <Icon v-if="!sending" name="lucide:send" class="w-4 h-4" />
              <Icon v-else name="lucide:loader-2" class="w-4 h-4 animate-spin" />
              {{ sending ? 'Submitting...' : 'Submit Quote Request' }}
            </button>
          </div>
        </div>

      </div>
    </div>
  </section>
</template>
