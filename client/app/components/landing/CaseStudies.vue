<script setup lang="ts">
import { onMounted, onUnmounted, ref, nextTick } from 'vue'
import type { CaseStudy } from '~/types/api'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const sectionRef = ref<HTMLElement | null>(null)
const caseStudies = ref<CaseStudy[]>([])
const pending = ref(false)
const error = ref<Error | null>(null)
let ctx: gsap.Context | null = null
const { getCaseStudies: fetchCaseStudies } = useApi()

const getResults = (caseStudy: CaseStudy) => {
  if (typeof caseStudy.results === 'string') {
    try { return JSON.parse(caseStudy.results) }
    catch { return [] }
  }
  return caseStudy.results || []
}

const loadCaseStudies = async () => {
  pending.value = true
  error.value = null
  try {
    const response = await fetchCaseStudies({ featured: true, per_page: 3 }) as any
    const items = response?.data ?? []
    caseStudies.value = items
  } catch (err) {
    error.value = err as Error
    caseStudies.value = []
  } finally {
    pending.value = false
  }
}

const initAnimations = () => {
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    // Header
    gsap.from('.casestudies-header', {
      y: 50, opacity: 0, duration: 0.9, ease: 'power3.out',
      scrollTrigger: { trigger: '.casestudies-header', start: 'top 85%' },
    })

    gsap.from('.casestudies-header .line-technical', {
      scaleX: 0, transformOrigin: 'left center', duration: 1.2, ease: 'power2.out',
      scrollTrigger: { trigger: '.casestudies-header .line-technical', start: 'top 90%' },
    })

    // Timeline connector line
    gsap.from('.timeline-connector', {
      scaleY: 0, transformOrigin: 'top center', duration: 2, ease: 'power2.out',
      scrollTrigger: { trigger: '.casestudies-content', start: 'top 80%', end: 'bottom 20%', scrub: 1 },
    })

    // Each case study - cinematic entrance
    const items = gsap.utils.toArray('.casestudy-item') as HTMLElement[]
    items.forEach((item, i) => {
      const contentSide = item.querySelector('.cs-content')
      const imageSide = item.querySelector('.cs-image')
      const isEven = i % 2 !== 0

      // Content slides from left/right
      if (contentSide) {
        gsap.from(contentSide, {
          x: isEven ? 80 : -80,
          opacity: 0,
          duration: 1,
          ease: 'power3.out',
          scrollTrigger: { trigger: item, start: 'top 80%' },
        })
      }

      // Image slides from opposite side
      if (imageSide) {
        gsap.from(imageSide, {
          x: isEven ? -60 : 60,
          opacity: 0,
          scale: 0.95,
          duration: 1,
          delay: 0.2,
          ease: 'power3.out',
          scrollTrigger: { trigger: item, start: 'top 80%' },
        })
      }

      // Results numbers countUp
      const resultValues = item.querySelectorAll('.result-value')
      resultValues.forEach((el) => {
        gsap.from(el, {
          y: 20, opacity: 0, duration: 0.6, ease: 'back.out(1.5)',
          scrollTrigger: { trigger: item, start: 'top 75%' },
        })
      })

      // Tech pills wave stagger
      const techPills = item.querySelectorAll('.tech-pill')
      if (techPills.length) {
        gsap.from(techPills, {
          y: 15, opacity: 0, scale: 0.85,
          duration: 0.4, stagger: 0.06, ease: 'back.out(1.5)',
          scrollTrigger: { trigger: item, start: 'top 70%' },
        })
      }
    })
  }, sectionRef.value)
}

onMounted(async () => {
  await loadCaseStudies()
  nextTick(() => { initAnimations() })
})

onUnmounted(() => { ctx?.revert() })
</script>

<template>
  <section id="case-studies" ref="sectionRef" class="py-20 lg:py-32 relative overflow-hidden bg-white dark:bg-titanium-950">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="casestudies-header max-w-3xl mx-auto text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
            Success Stories
          </span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-black mb-6 text-titanium-900 dark:text-white">
          Client
          <span class="text-mintreu-red-600">Success Stories</span>
        </h2>
        <p class="text-lg md:text-xl text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">
          Deep dive into real projects with measurable results and proven ROI
        </p>
        <div class="line-technical mt-8 mx-auto max-w-md"></div>
      </div>

      <!-- Loading State -->
      <div v-if="pending" class="space-y-16">
        <div v-for="i in 3" :key="i" class="animate-pulse">
          <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3 space-y-4">
              <div class="h-8 bg-titanium-200 dark:bg-titanium-800 rounded w-24"></div>
              <div class="h-12 bg-titanium-200 dark:bg-titanium-800 rounded"></div>
              <div class="h-20 bg-titanium-200 dark:bg-titanium-800 rounded"></div>
            </div>
            <div class="lg:col-span-2">
              <div class="aspect-[4/3] bg-titanium-200 dark:bg-titanium-800 rounded-2xl"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Case Studies Content -->
      <div v-else-if="caseStudies.length > 0" class="casestudies-content relative space-y-16 lg:space-y-24">
        <!-- Timeline connector -->
        <div class="timeline-connector hidden lg:block absolute left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-mintreu-red-600/0 via-mintreu-red-600/30 to-mintreu-red-600/0"></div>

        <div
          v-for="(caseStudy, index) in caseStudies"
          :key="caseStudy.slug"
          class="casestudy-item group relative"
        >
          <!-- Timeline dot -->
          <div class="hidden lg:flex absolute left-1/2 top-8 -translate-x-1/2 z-10 w-4 h-4 rounded-full bg-mintreu-red-600 border-4 border-white dark:border-titanium-950 shadow-lg"></div>

          <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12 items-start">
            <!-- Content Side -->
            <div class="cs-content lg:col-span-3" :class="{ 'lg:col-start-3 lg:row-start-1': index % 2 !== 0 }">
              <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold mb-4">
                <span>Case Study #{{ index + 1 }}</span>
                <span class="label-schematic">CS-{{ String(index + 1).padStart(3, '0') }}</span>
              </div>

              <h3 class="text-3xl md:text-4xl font-heading font-black text-titanium-900 dark:text-white mb-4">
                {{ caseStudy.title }}
              </h3>

              <p class="text-xl text-titanium-600 dark:text-titanium-400 mb-6 leading-relaxed font-subheading font-medium">
                {{ caseStudy.description }}
              </p>

              <!-- Client & Industry -->
              <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex items-center gap-2 text-sm font-subheading">
                  <Icon name="lucide:briefcase" class="w-4 h-4 text-titanium-500" />
                  <span class="text-titanium-600 dark:text-titanium-400">{{ caseStudy.client }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm font-subheading">
                  <Icon name="lucide:building" class="w-4 h-4 text-titanium-500" />
                  <span class="text-titanium-600 dark:text-titanium-400">{{ caseStudy.industry }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm font-subheading">
                  <Icon name="lucide:clock" class="w-4 h-4 text-titanium-500" />
                  <span class="text-titanium-600 dark:text-titanium-400">{{ caseStudy.duration }}</span>
                </div>
              </div>

              <!-- Results Grid -->
              <div v-if="getResults(caseStudy).length > 0" class="grid grid-cols-3 gap-4 mb-8">
                <div v-for="(result, idx) in getResults(caseStudy)" :key="idx"
                  class="text-center p-4 bg-titanium-100 dark:bg-titanium-800/50 rounded-xl border border-dashed border-titanium-300 dark:border-titanium-700">
                  <div class="result-value text-2xl md:text-3xl font-heading font-black text-mintreu-red-600 mb-1">
                    {{ result.value || result }}
                  </div>
                  <div class="text-xs text-titanium-500 font-subheading font-medium uppercase tracking-wider">
                    {{ result.label || 'Result' }}
                  </div>
                </div>
              </div>

              <!-- Challenge -->
              <div v-if="caseStudy.challenge" class="mb-6">
                <h4 class="text-lg font-heading font-bold text-titanium-900 dark:text-white mb-3 flex items-center gap-2">
                  <Icon name="lucide:alert-triangle" class="w-5 h-5 text-amber-500" />
                  Challenge
                </h4>
                <p class="text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">{{ caseStudy.challenge }}</p>
              </div>

              <!-- Solution -->
              <div v-if="caseStudy.solution" class="mb-6">
                <h4 class="text-lg font-heading font-bold text-titanium-900 dark:text-white mb-3 flex items-center gap-2">
                  <Icon name="lucide:check-circle" class="w-5 h-5 text-green-500" />
                  Solution Delivered
                </h4>
                <p class="text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">{{ caseStudy.solution }}</p>
              </div>

              <!-- Tech Stack -->
              <div v-if="caseStudy.technologies?.length" class="mb-8">
                <h4 class="text-sm font-heading font-bold text-titanium-500 mb-3 uppercase tracking-wider">Technology Stack</h4>
                <div class="flex flex-wrap gap-2">
                  <span v-for="tech in caseStudy.technologies" :key="tech"
                    class="tech-pill px-3 py-1.5 bg-titanium-100 dark:bg-titanium-800 text-titanium-700 dark:text-titanium-300 rounded-lg text-sm font-heading font-semibold">
                    {{ tech }}
                  </span>
                </div>
              </div>

              <NuxtLink
                :to="`/case-studies/${caseStudy.slug}`"
                class="inline-flex items-center space-x-2 px-6 py-3 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-xl font-heading font-bold shadow-lg glow-red hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-300"
              >
                <span>Read Full Story</span>
                <Icon name="lucide:arrow-right" class="w-5 h-5" />
              </NuxtLink>
            </div>

            <!-- Image Side -->
            <div class="cs-image lg:col-span-2" :class="{ 'lg:col-start-1 lg:row-start-1': index % 2 !== 0 }">
              <div class="sticky top-24">
                <div class="relative overflow-hidden rounded-2xl shadow-2xl border border-dashed border-titanium-300 dark:border-titanium-700">
                  <div v-if="caseStudy.image" class="aspect-[4/3]">
                    <img :src="caseStudy.image" :alt="caseStudy.title" class="w-full h-full object-cover" />
                  </div>
                  <div v-else class="aspect-[4/3] bg-gradient-to-br from-titanium-800 via-titanium-900 to-titanium-950 flex items-center justify-center relative">
                    <div class="absolute inset-0 bg-blueprint opacity-20"></div>
                    <div class="relative text-center">
                      <Icon name="lucide:file-text" class="w-20 h-20 text-titanium-600 mx-auto mb-3" />
                      <span class="label-schematic">CS-{{ String(index + 1).padStart(3, '0') }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <Icon name="lucide:file-text" class="w-16 h-16 mx-auto mb-4 text-titanium-400" />
        <p class="text-titanium-600 dark:text-titanium-400 font-subheading">No case studies available at the moment.</p>
      </div>

      <!-- CTA Button -->
      <div class="flex justify-center items-center w-full">
        <NuxtLink
          to="/case-studies"
          class="px-8 py-4 my-8 w-full md:w-auto rounded-2xl bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white text-center font-heading font-bold shadow-lg glow-red hover:shadow-xl transform hover:scale-105 transition-all duration-300"
        >
          View More Case Studies
        </NuxtLink>
      </div>
    </div>
  </section>
</template>
