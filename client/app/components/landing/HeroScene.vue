<template>
  <section ref="heroRef" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-titanium-100 dark:bg-titanium-950">
    <!-- 3D Background Layer -->
    <div class="absolute inset-0 z-0 pointer-events-none">
      <ClientOnly>
        <TresCanvas
          :clear-color="clearColor"
          :alpha="true"
          window-size
          class="pointer-events-none"
        >
          <TresPerspectiveCamera :position="[0, 0, 8]" :fov="60" />
          <TresAmbientLight :intensity="0.4" />
          <TresDirectionalLight :position="[5, 5, 5]" :intensity="0.8" color="#DC2626" />
          <TresDirectionalLight :position="[-3, 2, 4]" :intensity="0.3" color="#3b82f6" />

          <ThreeBlueprintGrid :size="30" :divisions="60" color="#6b7280" :opacity="0.08" />
          <ThreeWireframeGears :position="[-5, 2.5, -3]" :scale="1.8" :speed="0.003" color="#DC2626" />
          <ThreeWireframeGears :position="[5, -1.5, -2]" :scale="1" :speed="-0.005" color="#A8A9AD" />
          <ThreeWireframeGears :position="[0, -3, -4]" :scale="2.5" :speed="0.002" color="#495057" />
          <ThreeParticleSparks :count="isMobile ? 80 : 150" :spread="20" color="#DC2626" />
        </TresCanvas>
      </ClientOnly>
    </div>

    <!-- Blueprint Grid CSS Overlay -->
    <div class="absolute inset-0 bg-blueprint z-[1] pointer-events-none"></div>

    <!-- Vignette Overlay -->
    <div class="absolute inset-0 z-[2] vignette-overlay pointer-events-none"></div>

    <!-- Content Layer -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
      <!-- Engineering Badge -->
      <div class="hero-badge inline-flex items-center space-x-2 px-5 py-2.5 mb-8 bg-titanium-200/60 dark:bg-titanium-900/60 backdrop-blur-xl rounded-full border border-mintreu-red-600/30 shadow-lg">
        <span class="relative flex h-2.5 w-2.5">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-mintreu-red-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-mintreu-red-600"></span>
        </span>
        <span class="text-sm font-subheading font-semibold tracking-wider uppercase text-titanium-700 dark:text-titanium-300">
          Engineering Lab &mdash; Open for Projects
        </span>
      </div>

      <!-- Main Heading -->
      <h1 class="hero-heading text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-heading font-black leading-tight mb-6">
        <span class="block text-titanium-900 dark:text-white">Build. Design. Ship.</span>
        <span class="block mt-2 text-mintreu-red-600 drop-shadow-[0_0_30px_rgba(220,38,38,0.4)]">
          Maintain. Scale.
        </span>
      </h1>

      <!-- Subheading -->
      <p class="hero-subheading text-lg sm:text-xl md:text-2xl text-titanium-600 dark:text-titanium-400 max-w-3xl mx-auto mb-10 font-subheading font-medium tracking-wide leading-relaxed">
        Complete digital solutions from concept to deployment. Web, Mobile & Desktop applications with ongoing support and maintenance.
      </p>

      <!-- CTA Buttons -->
      <div class="hero-cta flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
        <NuxtLink
          to="#hire"
          class="group relative inline-flex items-center space-x-2 px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white text-lg font-heading font-bold rounded-xl glow-red transform hover:scale-105 hover:-translate-y-1 active:scale-95 transition-all duration-300 w-full sm:w-auto"
        >
          <span>Start Your Project</span>
          <Icon name="lucide:rocket" class="w-5 h-5 group-hover:scale-110 transition-transform" />
        </NuxtLink>

        <NuxtLink
          to="#services"
          class="group inline-flex items-center space-x-2 px-8 py-4 bg-titanium-200/80 dark:bg-titanium-900/50 backdrop-blur-xl border-2 border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600 dark:hover:border-mintreu-red-600 text-titanium-900 dark:text-white text-lg font-heading font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:scale-105 hover:-translate-y-1 active:scale-95 transition-all duration-300 w-full sm:w-auto"
        >
          <span>Explore Services</span>
          <Icon name="lucide:sparkles" class="w-5 h-5 group-hover:rotate-12 transition-transform" />
        </NuxtLink>
      </div>

      <!-- Stats with GSAP CountUp -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6 max-w-4xl mx-auto">
        <div
          v-for="(stat, index) in stats"
          :key="stat.label"
          class="hero-stat group relative bg-titanium-200/60 dark:bg-titanium-900/60 backdrop-blur-xl rounded-2xl p-6 border border-titanium-300/50 dark:border-titanium-700/50 hover:border-mintreu-red-600/50 transition-all duration-300"
        >
          <!-- Corner marks -->
          <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-lg"></div>
          <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-mintreu-red-600/40 rounded-br-lg"></div>

          <div class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black text-titanium-900 dark:text-white mb-2">
            <span :ref="(el: any) => { if (el) statRefs[index] = el.$el || el }">0</span>{{ stat.suffix }}
          </div>
          <div class="text-sm font-subheading font-medium text-titanium-500 uppercase tracking-wider">
            {{ stat.label }}
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce-slow z-10 pointer-events-none">
      <div class="w-8 h-14 rounded-full border-2 border-titanium-400 dark:border-titanium-600 flex items-start justify-center p-2">
        <div class="w-1.5 h-3 bg-mintreu-red-600 rounded-full animate-pulse"></div>
      </div>
    </div>

    <!-- Technical dimension lines (decorative) -->
    <div class="absolute top-20 left-8 hidden lg:block z-10 pointer-events-none">
      <div class="w-px h-32 bg-gradient-to-b from-mintreu-red-600/0 via-mintreu-red-600/40 to-mintreu-red-600/0"></div>
      <div class="absolute top-1/2 left-2 label-schematic whitespace-nowrap transform -rotate-90 origin-left">SEC-001</div>
    </div>
    <div class="absolute top-20 right-8 hidden lg:block z-10 pointer-events-none">
      <div class="w-px h-32 bg-gradient-to-b from-blueprint-500/0 via-blueprint-500/40 to-blueprint-500/0"></div>
      <div class="absolute top-1/2 right-2 label-schematic whitespace-nowrap transform rotate-90 origin-right">REV-A</div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import gsap from 'gsap'

const heroRef = ref<HTMLElement | null>(null)
const statRefs = ref<(HTMLElement | null)[]>([])
const colorMode = useColorMode()
const isMobile = ref(false)
let ctx: gsap.Context | null = null
const { homeData, loadHomeData } = useHomeData()

const clearColor = computed(() =>
  colorMode.value === 'dark' ? '#1a1d1e' : '#f1f3f5'
)

const stats = computed(() => homeData.value?.stats ?? [])

onMounted(async () => {
  await loadHomeData()
  isMobile.value = window.innerWidth < 768
  if (!heroRef.value) return

  ctx = gsap.context(() => {
    // Hero entrance animation timeline
    const tl = gsap.timeline({ delay: 0.3 })

    tl.from('.hero-badge', { y: -30, opacity: 0, scale: 0.9, duration: 0.6, ease: 'back.out(1.4)' })
      .from('.hero-heading', { y: 40, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.3')
      .from('.hero-subheading', { y: 30, opacity: 0, duration: 0.7, ease: 'power3.out' }, '-=0.4')
      .from('.hero-cta', { y: 20, opacity: 0, duration: 0.6, ease: 'power3.out' }, '-=0.3')
      .from('.hero-stat', {
        y: 40, opacity: 0, scale: 0.9, duration: 0.7,
        stagger: 0.12, ease: 'back.out(1.3)',
      }, '-=0.2')

    // Count up stats
    stats.forEach((stat, i) => {
      const el = statRefs.value[i]
      if (!el) return
      const obj = { value: 0 }
      gsap.to(obj, {
        value: stat.value,
        duration: 2.5,
        delay: 1,
        ease: 'power1.out',
        onUpdate: () => {
          el.textContent = String(Math.round(obj.value))
        },
      })
    })
  }, heroRef.value)
})

onUnmounted(() => { ctx?.revert() })
</script>

<style>
.vignette-overlay {
  background: radial-gradient(ellipse at center, transparent 0%, transparent 40%, rgba(241, 243, 245, 0.6) 100%);
}

.dark .vignette-overlay {
  background: radial-gradient(ellipse at center, transparent 0%, transparent 40%, rgba(26, 29, 30, 0.6) 100%);
}
</style>
