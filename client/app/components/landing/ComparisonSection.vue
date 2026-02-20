<template>
  <section id="comparison" ref="sectionRef" class="py-20 lg:py-32 relative overflow-hidden bg-white dark:bg-titanium-950">
    <!-- Blueprint grid overlay -->
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="comparison-header max-w-3xl mx-auto text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
            Why Choose Mintreu
          </span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-black mb-6 text-titanium-900 dark:text-white">
          The
          <span class="text-mintreu-red-600">Mintreu</span>
          Advantage
        </h2>
        <p class="text-lg md:text-xl text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">
          See why businesses choose Mintreu over traditional agencies and freelance platforms
        </p>

        <!-- Technical divider -->
        <div class="line-technical mt-8 mx-auto max-w-md comparison-divider"></div>
      </div>

      <!-- Industrial Gauge Comparison -->
      <div class="comparison-gauges max-w-5xl mx-auto space-y-6">
        <div
          v-for="(row, index) in comparisonData"
          :key="index"
          class="comparison-row group bg-titanium-50/80 dark:bg-titanium-900/40 backdrop-blur-sm rounded-2xl p-6 border border-titanium-200 dark:border-titanium-800/50 hover:border-mintreu-red-600/30 transition-all duration-300"
        >
          <!-- Feature Label -->
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-heading font-bold text-sm uppercase tracking-wider text-titanium-900 dark:text-white">
              {{ row.feature }}
            </h3>
            <span class="label-schematic">SPEC-{{ String(index + 1).padStart(3, '0') }}</span>
          </div>

          <!-- Gauge Bars -->
          <div class="space-y-3">
            <!-- Mintreu -->
            <div class="flex items-center gap-4">
              <span class="w-24 text-sm font-subheading font-semibold text-mintreu-red-600 text-right shrink-0">Mintreu</span>
              <div class="flex-1 h-8 bg-titanium-200/50 dark:bg-titanium-800/50 rounded-lg overflow-hidden relative">
                <div
                  class="gauge-fill-mintreu h-full bg-gradient-to-r from-mintreu-red-600 to-mintreu-red-500 rounded-lg flex items-center justify-end pr-3"
                  :style="{ width: row.mintScore + '%' }"
                >
                  <span class="text-xs font-heading font-bold text-white whitespace-nowrap">{{ row.mintreu }}</span>
                </div>
              </div>
            </div>

            <!-- Agency -->
            <div class="flex items-center gap-4">
              <span class="w-24 text-sm font-subheading text-titanium-500 text-right shrink-0">Agency</span>
              <div class="flex-1 h-6 bg-titanium-200/50 dark:bg-titanium-800/50 rounded-lg overflow-hidden">
                <div
                  class="gauge-fill-other h-full bg-titanium-400/60 dark:bg-titanium-600/60 rounded-lg flex items-center justify-end pr-3"
                  :style="{ width: row.agencyScore + '%' }"
                >
                  <span class="text-xs font-subheading text-titanium-700 dark:text-titanium-300 whitespace-nowrap">{{ row.agency }}</span>
                </div>
              </div>
            </div>

            <!-- Freelance -->
            <div class="flex items-center gap-4">
              <span class="w-24 text-sm font-subheading text-titanium-500 text-right shrink-0">Freelance</span>
              <div class="flex-1 h-6 bg-titanium-200/50 dark:bg-titanium-800/50 rounded-lg overflow-hidden">
                <div
                  class="gauge-fill-other h-full bg-titanium-300/60 dark:bg-titanium-700/60 rounded-lg flex items-center justify-end pr-3"
                  :style="{ width: row.freelanceScore + '%' }"
                >
                  <span class="text-xs font-subheading text-titanium-700 dark:text-titanium-300 whitespace-nowrap">{{ row.platform }}</span>
                </div>
              </div>
            </div>
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
const { homeData, loadHomeData } = useHomeData()

const comparisonData = computed(() => (homeData.value?.comparisonData ?? []).map((row) => ({
  ...row,
  mintScore: 95,
  agencyScore: 55,
  freelanceScore: 45,
})))

onMounted(async () => {
  await loadHomeData()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.comparison-header', {
      y: 40, opacity: 0, duration: 0.8, ease: 'power3.out',
      scrollTrigger: { trigger: '.comparison-header', start: 'top 85%' },
    })

    gsap.from('.comparison-divider', {
      scaleX: 0, transformOrigin: 'left center', duration: 1.2, ease: 'power2.out',
      scrollTrigger: { trigger: '.comparison-divider', start: 'top 90%' },
    })

    // Rows alternate from left/right
    const rows = gsap.utils.toArray('.comparison-row') as HTMLElement[]
    rows.forEach((row, i) => {
      gsap.from(row, {
        x: i % 2 === 0 ? -60 : 60,
        opacity: 0, duration: 0.7,
        ease: 'power3.out',
        scrollTrigger: { trigger: row, start: 'top 88%' },
      })
    })

    // Mintreu gauge bars - dramatic fill
    gsap.from('.gauge-fill-mintreu', {
      scaleX: 0, transformOrigin: 'left center', duration: 1.5, stagger: 0.1, ease: 'power4.out',
      scrollTrigger: { trigger: '.comparison-gauges', start: 'top 80%' },
    })

    // Other gauge bars
    gsap.from('.gauge-fill-other', {
      scaleX: 0, transformOrigin: 'left center', duration: 1.1, stagger: 0.08, ease: 'power3.out',
      scrollTrigger: { trigger: '.comparison-gauges', start: 'top 80%' },
    })
  }, sectionRef.value)
})

onUnmounted(() => { ctx?.revert() })
</script>
