<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 py-8 relative">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <nav class="flex items-center space-x-2 text-sm mb-8">
        <NuxtLink to="/" class="text-titanium-500 hover:text-mintreu-red-600 font-subheading transition-colors">
          Home
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-titanium-400" />
        <span class="text-titanium-900 dark:text-white font-heading font-bold text-xs uppercase tracking-wider">Case Studies</span>
      </nav>

      <!-- Header -->
      <div ref="sectionRef" class="cs-header text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
            Success Stories
          </span>
        </div>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-heading font-black mb-6 text-titanium-900 dark:text-white">
          Client <span class="text-mintreu-red-600">Case Studies</span>
        </h1>
        <p class="text-lg text-titanium-600 dark:text-titanium-400 max-w-2xl mx-auto font-subheading">
          Real projects with measurable results and proven ROI for our clients worldwide
        </p>
        <div class="line-technical mt-8 mx-auto max-w-md"></div>
      </div>

      <!-- Filters -->
      <div class="flex flex-wrap gap-3 justify-center mb-12">
        <button
          v-for="filter in filters"
          :key="filter"
          @click="activeFilter = filter"
          class="px-6 py-2 rounded-xl font-heading font-bold text-sm transition-all duration-300"
          :class="activeFilter === filter
            ? 'bg-mintreu-red-600 text-white shadow-lg glow-red'
            : 'bg-white dark:bg-titanium-900 text-titanium-700 dark:text-titanium-300 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50'"
        >
          {{ filter }}
        </button>
      </div>

      <!-- Loading -->
      <div v-if="pending" class="space-y-8">
        <div v-for="n in 3" :key="n" class="bg-white dark:bg-titanium-900 rounded-3xl border border-dashed border-titanium-300 dark:border-titanium-700 shadow-xl animate-pulse">
          <div class="grid lg:grid-cols-5 gap-8 p-8 lg:p-12">
            <div class="lg:col-span-2">
              <div class="h-64 bg-titanium-200 dark:bg-titanium-800 rounded-2xl"></div>
            </div>
            <div class="lg:col-span-3 space-y-4">
              <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded w-24"></div>
              <div class="h-10 bg-titanium-200 dark:bg-titanium-800 rounded w-3/4"></div>
              <div class="h-20 bg-titanium-200 dark:bg-titanium-800 rounded"></div>
              <div class="grid grid-cols-3 gap-4">
                <div class="h-16 bg-titanium-200 dark:bg-titanium-800 rounded-xl"></div>
                <div class="h-16 bg-titanium-200 dark:bg-titanium-800 rounded-xl"></div>
                <div class="h-16 bg-titanium-200 dark:bg-titanium-800 rounded-xl"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Case Studies List -->
      <div v-else-if="caseStudies?.data && caseStudies.data.length > 0" class="space-y-16">
        <div
          v-for="(study, index) in caseStudies.data"
          :key="study.slug"
          class="cs-card group relative bg-white dark:bg-titanium-900 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 dark:hover:border-mintreu-red-600/50 shadow-xl hover:shadow-2xl transition-all duration-500"
        >
          <!-- Corner marks -->
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
          <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600/40 rounded-br-xl"></div>

          <div class="relative grid lg:grid-cols-5 gap-8 p-8 lg:p-12">
            <!-- Image -->
            <div class="lg:col-span-2" :class="{ 'lg:order-2': index % 2 !== 0 }">
              <div class="relative h-64 lg:h-full rounded-2xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700">
                <img v-if="study.image" :src="study.image" :alt="study.title" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full bg-gradient-to-br from-titanium-800 via-titanium-900 to-titanium-950 flex items-center justify-center relative">
                  <div class="absolute inset-0 bg-blueprint opacity-20"></div>
                  <div class="relative text-center">
                    <Icon name="lucide:briefcase" class="w-20 h-20 text-titanium-600 mx-auto mb-3" />
                    <span class="label-schematic">CS-{{ String(index + 1).padStart(3, '0') }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Content -->
            <div class="lg:col-span-3">
              <div class="flex items-center gap-3 mb-4">
                <span class="px-4 py-1.5 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
                  {{ study.industry || 'Case Study' }}
                </span>
                <span v-if="study.duration" class="px-3 py-1.5 bg-titanium-100 dark:bg-titanium-800 text-titanium-600 dark:text-titanium-400 rounded-full text-xs font-heading font-bold flex items-center gap-1">
                  <Icon name="lucide:clock" class="w-3 h-3" />
                  {{ study.duration }}
                </span>
              </div>

              <h3 class="text-2xl md:text-3xl font-heading font-black mb-4 text-titanium-900 dark:text-white group-hover:text-mintreu-red-600 transition-colors">
                {{ study.title }}
              </h3>

              <p class="text-titanium-600 dark:text-titanium-400 mb-6 leading-relaxed font-subheading">
                {{ study.challenge || study.description }}
              </p>

              <!-- Results -->
              <div v-if="getResults(study).length > 0" class="grid grid-cols-3 gap-4 mb-6">
                <div v-for="(result, idx) in getResults(study).slice(0, 3)" :key="idx"
                  class="text-center p-4 bg-titanium-100 dark:bg-titanium-800/50 rounded-xl border border-dashed border-titanium-300 dark:border-titanium-700">
                  <div class="text-2xl font-heading font-black text-mintreu-red-600 mb-1">
                    {{ result.value || result }}
                  </div>
                  <div class="text-xs text-titanium-500 font-subheading font-medium uppercase tracking-wider">
                    {{ result.label || 'Result' }}
                  </div>
                </div>
              </div>

              <!-- Tech Stack -->
              <div v-if="study.technologies && study.technologies.length > 0" class="flex flex-wrap gap-2 mb-6">
                <span
                  v-for="tech in study.technologies.slice(0, 5)"
                  :key="tech"
                  class="px-3 py-1.5 bg-titanium-100 dark:bg-titanium-800 text-titanium-700 dark:text-titanium-300 rounded-lg text-sm font-heading font-semibold"
                >
                  {{ tech }}
                </span>
                <span v-if="study.technologies.length > 5" class="px-3 py-1.5 text-titanium-500 text-sm font-heading">
                  +{{ study.technologies.length - 5 }}
                </span>
              </div>

              <!-- CTA -->
              <NuxtLink
                :to="`/case-studies/${study.slug}`"
                class="inline-flex items-center space-x-2 px-6 py-3 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-xl font-heading font-bold shadow-lg glow-red hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-300"
              >
                <span>Read Full Story</span>
                <Icon name="lucide:arrow-right" class="w-5 h-5" />
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20">
        <Icon name="lucide:book-open" class="w-20 h-20 text-titanium-400 mx-auto mb-4" />
        <h3 class="text-xl font-heading font-bold mb-2 text-titanium-900 dark:text-white">No Case Studies Found</h3>
        <p class="text-titanium-600 dark:text-titanium-400 font-subheading">Check back soon for new success stories</p>
      </div>

      <!-- Pagination -->
      <div v-if="caseStudies && caseStudies.meta && caseStudies.meta.last_page > 1" class="mt-12 flex justify-center">
        <nav class="flex items-center space-x-2">
          <button
            @click="page > 1 && page--"
            :disabled="page === 1"
            class="px-4 py-2 rounded-xl bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 disabled:opacity-50 text-titanium-700 dark:text-titanium-300 hover:border-mintreu-red-600/50 transition-colors"
          >
            <Icon name="lucide:chevron-left" class="w-5 h-5" />
          </button>

          <button
            v-for="p in paginationRange"
            :key="p"
            @click="page = p"
            class="px-4 py-2 rounded-xl font-heading font-bold text-sm transition-all"
            :class="page === p
              ? 'bg-mintreu-red-600 text-white shadow-lg glow-red'
              : 'bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 text-titanium-700 dark:text-titanium-300 hover:border-mintreu-red-600/50'"
          >
            {{ p }}
          </button>

          <button
            @click="page < caseStudies.meta.last_page && page++"
            :disabled="page === caseStudies.meta.last_page"
            class="px-4 py-2 rounded-xl bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 disabled:opacity-50 text-titanium-700 dark:text-titanium-300 hover:border-mintreu-red-600/50 transition-colors"
          >
            <Icon name="lucide:chevron-right" class="w-5 h-5" />
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'
import type { PaginatedResponse, CaseStudy } from '~/types/api'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

useSeoMeta({
  title: 'Case Studies | Mintreu - Client Success Stories',
  description: 'Explore our detailed case studies showcasing real projects with measurable results and proven ROI.'
})

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const page = ref(1)
const activeFilter = ref('All')
const filters = ref<string[]>(['All'])
const caseStudies = ref<PaginatedResponse<CaseStudy> | null>(null)
const pending = ref(false)
const fetchError = ref<Error | null>(null)

const { getCaseStudies: fetchCaseStudiesApi, getCategories } = useApi()

const loadCategories = async () => {
  try {
    const response = await getCategories({ type: 'case-studies' }) as any
    const items = Array.isArray(response)
      ? response
      : Array.isArray(response?.data)
        ? response.data
        : []
    if (items.length) {
      filters.value = ['All', ...items.map((c: any) => c.name)]
    }
  } catch (error) {
    console.error('Unable to load case study filters', error)
  }
}

const fetchCaseStudies = async () => {
  pending.value = true
  fetchError.value = null
  try {
    const response = await fetchCaseStudiesApi({
      page: page.value,
      category: activeFilter.value !== 'All' ? activeFilter.value : undefined,
      per_page: 6
    }) as any
    caseStudies.value = response ?? null
  } catch (error) {
    fetchError.value = error as Error
    console.error('Unable to load case studies', error)
  } finally {
    pending.value = false
    initAnimations()
  }
}

const initAnimations = () => {
  ctx?.revert()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.cs-header', {
      y: 40, opacity: 0, duration: 0.8, ease: 'power3.out',
      scrollTrigger: { trigger: '.cs-header', start: 'top 85%' },
    })

    const cards = gsap.utils.toArray('.cs-card') as HTMLElement[]
    cards.forEach((card, i) => {
      gsap.from(card, {
        y: 60, opacity: 0, scale: 0.97,
        duration: 0.8, delay: i * 0.1,
        ease: 'back.out(1.2)',
        scrollTrigger: { trigger: card, start: 'top 88%' },
      })
    })
  }, sectionRef.value)
}

onMounted(() => {
  loadCategories()
})

watch(
  [page, activeFilter],
  () => { fetchCaseStudies() },
  { immediate: true }
)

onUnmounted(() => { ctx?.revert() })

const paginationRange = computed(() => {
  if (!caseStudies.value?.meta) return []
  const total = caseStudies.value.meta.last_page
  const current = page.value
  const range = []
  for (let i = Math.max(1, current - 2); i <= Math.min(total, current + 2); i++) {
    range.push(i)
  }
  return range
})

const getResults = (caseStudy: CaseStudy) => {
  if (!caseStudy?.results) return []
  if (typeof caseStudy.results === 'string') {
    try { return JSON.parse(caseStudy.results) }
    catch { return [] }
  }
  return caseStudy.results || []
}
</script>
