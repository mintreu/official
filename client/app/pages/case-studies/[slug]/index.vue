<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 py-8 relative">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <nav class="flex items-center space-x-2 text-sm mb-8">
        <NuxtLink to="/" class="text-titanium-500 hover:text-mintreu-red-600 font-subheading transition-colors">Home</NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-titanium-400" />
        <NuxtLink to="/case-studies" class="text-titanium-500 hover:text-mintreu-red-600 font-subheading transition-colors">Case Studies</NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-titanium-400" />
        <span class="text-titanium-900 dark:text-white font-heading font-bold text-xs uppercase tracking-wider truncate max-w-[200px]">
          {{ caseStudy?.title || 'Loading...' }}
        </span>
      </nav>

      <!-- Loading -->
      <div v-if="pending" class="space-y-6">
        <div class="h-80 bg-white dark:bg-titanium-900 rounded-3xl border border-dashed border-titanium-300 dark:border-titanium-700 animate-pulse"></div>
        <div class="h-8 bg-titanium-200 dark:bg-titanium-800 rounded w-3/4 animate-pulse"></div>
        <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded w-1/2 animate-pulse"></div>
      </div>

      <!-- Case Study Detail -->
      <div v-else-if="caseStudy" ref="sectionRef" class="space-y-12">
        <!-- Hero Image -->
        <div class="cs-hero relative h-80 md:h-96 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 shadow-2xl">
          <img v-if="caseStudy.image" :src="caseStudy.image" :alt="caseStudy.title" class="w-full h-full object-cover" />
          <div v-else class="w-full h-full bg-gradient-to-br from-titanium-800 via-titanium-900 to-titanium-950 flex items-center justify-center relative">
            <div class="absolute inset-0 bg-blueprint opacity-20"></div>
            <div class="relative text-center">
              <Icon name="lucide:briefcase" class="w-24 h-24 text-titanium-600 mx-auto mb-4" />
              <span class="label-schematic text-lg">CS-{{ caseStudy.slug.slice(0, 8).toUpperCase() }}</span>
            </div>
          </div>

          <!-- Badge -->
          <div class="absolute top-6 left-6">
            <span class="px-4 py-2 bg-mintreu-red-600/90 backdrop-blur-sm text-white rounded-full text-sm font-heading font-bold">
              Case Study
            </span>
          </div>

          <!-- GitHub -->
          <div v-if="caseStudy.github_url" class="absolute top-6 right-6">
            <a :href="caseStudy.github_url" target="_blank" rel="noopener noreferrer"
              class="inline-flex items-center space-x-2 px-4 py-2 bg-titanium-900/80 hover:bg-titanium-900 text-white rounded-xl font-heading font-bold backdrop-blur-sm border border-titanium-600 transition-all hover:scale-105">
              <Icon name="lucide:github" class="w-5 h-5" />
              <span>View on GitHub</span>
            </a>
          </div>
        </div>

        <!-- Header Card -->
        <header class="cs-header bg-white dark:bg-titanium-900 rounded-3xl shadow-2xl p-8 lg:p-12 border border-dashed border-titanium-300 dark:border-titanium-700 relative overflow-hidden">
          <div class="absolute inset-0 bg-blueprint opacity-10 pointer-events-none"></div>
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
          <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600/40 rounded-br-xl"></div>

          <div class="relative">
            <div class="flex flex-wrap items-center gap-3 mb-4">
              <span class="px-4 py-1.5 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
                {{ caseStudy.industry || 'Case Study' }}
              </span>
              <span v-if="caseStudy.client" class="px-3 py-1.5 bg-titanium-100 dark:bg-titanium-800 text-titanium-600 dark:text-titanium-400 rounded-full text-xs font-heading font-bold flex items-center gap-1">
                <Icon name="lucide:briefcase" class="w-3 h-3" />
                {{ caseStudy.client }}
              </span>
              <span v-if="caseStudy.duration" class="px-3 py-1.5 bg-titanium-100 dark:bg-titanium-800 text-titanium-600 dark:text-titanium-400 rounded-full text-xs font-heading font-bold flex items-center gap-1">
                <Icon name="lucide:clock" class="w-3 h-3" />
                {{ caseStudy.duration }}
              </span>
            </div>

            <span class="label-schematic mb-3 block">CS-{{ caseStudy.slug.slice(0, 8).toUpperCase() }}</span>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-heading font-black text-titanium-900 dark:text-white mb-4">
              {{ caseStudy.title }}
            </h1>
            <p class="text-lg text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">
              {{ caseStudy.description }}
            </p>
          </div>
        </header>

        <!-- Key Results -->
        <section v-if="getResults(caseStudy).length > 0" class="cs-results grid grid-cols-2 md:grid-cols-3 gap-6">
          <div v-for="(result, idx) in getResults(caseStudy)" :key="idx"
            class="result-card text-center p-6 bg-white dark:bg-titanium-900 rounded-2xl border border-dashed border-titanium-300 dark:border-titanium-700 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-mintreu-red-600/30 rounded-tl-lg"></div>
            <div class="relative">
              <div class="text-3xl md:text-4xl font-heading font-black text-mintreu-red-600 mb-2">
                {{ result.value || result }}
              </div>
              <div class="text-sm text-titanium-500 font-subheading font-medium uppercase tracking-wider">
                {{ result.label || 'Result' }}
              </div>
            </div>
          </div>
        </section>

        <!-- Challenge Section -->
        <section v-if="caseStudy.challenge" class="cs-challenge bg-white dark:bg-titanium-900 rounded-3xl shadow-xl p-8 border border-dashed border-titanium-300 dark:border-titanium-700 relative">
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-amber-500/40 rounded-tl-xl"></div>
          <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white mb-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
              <Icon name="lucide:target" class="w-5 h-5 text-amber-600" />
            </div>
            The Challenge
          </h2>
          <div class="line-technical max-w-xs mb-6"></div>
          <p class="text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading text-lg">
            {{ caseStudy.challenge }}
          </p>
        </section>

        <!-- Solution Section -->
        <section v-if="caseStudy.solution" class="cs-solution bg-white dark:bg-titanium-900 rounded-3xl shadow-xl p-8 border border-dashed border-titanium-300 dark:border-titanium-700 relative">
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-green-500/40 rounded-tl-xl"></div>
          <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white mb-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
              <Icon name="lucide:check-circle" class="w-5 h-5 text-green-600" />
            </div>
            The Solution
          </h2>
          <div class="line-technical max-w-xs mb-6"></div>
          <p class="text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading text-lg">
            {{ caseStudy.solution }}
          </p>
        </section>

        <!-- Technology Stack -->
        <section v-if="caseStudy.technologies && caseStudy.technologies.length > 0" class="cs-tech bg-white dark:bg-titanium-900 rounded-3xl shadow-xl p-8 border border-dashed border-titanium-300 dark:border-titanium-700 relative">
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
          <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white mb-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-blueprint-100 dark:bg-blueprint-900/30 rounded-xl flex items-center justify-center">
              <Icon name="lucide:code" class="w-5 h-5 text-blueprint-600" />
            </div>
            Technology Stack
          </h2>
          <div class="line-technical max-w-xs mb-6"></div>
          <div class="flex flex-wrap gap-3">
            <span v-for="tech in caseStudy.technologies" :key="tech"
              class="px-4 py-2 bg-titanium-100 dark:bg-titanium-800 text-titanium-700 dark:text-titanium-300 rounded-xl font-heading font-semibold border border-titanium-200 dark:border-titanium-700">
              {{ tech }}
            </span>
          </div>
        </section>

        <!-- Content Section -->
        <section v-if="caseStudy.content" class="cs-content bg-white dark:bg-titanium-900 rounded-3xl shadow-xl p-8 border border-dashed border-titanium-300 dark:border-titanium-700 relative">
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
          <div class="prose-content" v-html="caseStudy.content"></div>
        </section>

        <!-- GitHub Section -->
        <section v-if="caseStudy.github_url" class="cs-github relative overflow-hidden rounded-3xl">
          <div class="absolute inset-0 bg-titanium-900"></div>
          <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>
          <div class="relative border border-dashed border-titanium-700 rounded-3xl p-8 lg:p-12">
            <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600 rounded-tl-xl"></div>
            <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600 rounded-br-xl"></div>
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
              <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-titanium-800 rounded-2xl flex items-center justify-center border border-titanium-700">
                  <Icon name="lucide:github" class="w-8 h-8 text-white" />
                </div>
                <div>
                  <h3 class="text-2xl font-heading font-bold text-white mb-1">Open Source Repository</h3>
                  <p class="text-titanium-400 font-subheading">Explore the codebase and implementation details</p>
                </div>
              </div>
              <a :href="caseStudy.github_url" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center space-x-2 px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-xl font-heading font-bold shadow-lg glow-red transform hover:scale-105 transition-all whitespace-nowrap">
                <Icon name="lucide:github" class="w-5 h-5" />
                <span>View Repository</span>
                <Icon name="lucide:external-link" class="w-4 h-4" />
              </a>
            </div>
          </div>
        </section>

        <!-- CTA Section -->
        <section class="cs-cta relative overflow-hidden rounded-3xl">
          <div class="absolute inset-0 bg-titanium-900"></div>
          <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>
          <div class="relative border border-dashed border-titanium-700 rounded-3xl p-12 lg:p-16 text-center">
            <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-mintreu-red-600 rounded-tl-xl"></div>
            <div class="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 border-mintreu-red-600 rounded-tr-xl"></div>
            <div class="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 border-mintreu-red-600 rounded-bl-xl"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-mintreu-red-600 rounded-br-xl"></div>

            <h3 class="text-3xl md:text-4xl font-heading font-black text-white mb-4">Need a Similar Solution?</h3>
            <p class="text-titanium-400 mb-8 max-w-2xl mx-auto text-lg font-subheading leading-relaxed">
              Let's discuss how we can help you achieve comparable results for your business.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
              <NuxtLink to="/contact"
                class="inline-flex items-center space-x-2 px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-xl font-heading font-bold shadow-lg glow-red transform hover:scale-105 active:scale-95 transition-all">
                <span>Request a Quote</span>
                <Icon name="lucide:arrow-right" class="w-5 h-5" />
              </NuxtLink>
            </div>
          </div>
        </section>

        <!-- Related Case Studies (single, fixed) -->
        <section v-if="relatedCaseStudies.length > 0" class="space-y-6">
          <div class="flex items-center justify-between">
            <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white">Related Case Studies</h2>
            <NuxtLink to="/case-studies" class="text-mintreu-red-600 font-heading font-bold text-sm hover:underline">See all</NuxtLink>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <NuxtLink
              v-for="related in relatedCaseStudies"
              :key="related.slug"
              :to="`/case-studies/${related.slug}`"
              class="related-card group block p-6 rounded-2xl bg-white dark:bg-titanium-900 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
            >
              <div class="flex items-center gap-2 mb-3">
                <span v-if="related.industry" class="px-3 py-1 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-xs font-heading font-bold">
                  {{ related.industry }}
                </span>
                <span v-if="related.duration" class="text-xs text-titanium-500 font-subheading">{{ related.duration }}</span>
              </div>
              <h3 class="text-lg font-heading font-bold text-titanium-900 dark:text-white mb-2 group-hover:text-mintreu-red-600 transition-colors">
                {{ related.title }}
              </h3>
              <p class="text-sm text-titanium-600 dark:text-titanium-400 line-clamp-2 font-subheading">
                {{ related.challenge || related.description }}
              </p>
            </NuxtLink>
          </div>
        </section>
      </div>

      <!-- 404 State -->
      <div v-else class="max-w-3xl mx-auto text-center py-20">
        <Icon name="lucide:alert-circle" class="w-20 h-20 text-mintreu-red-500 mx-auto mb-4" />
        <h1 class="text-3xl font-heading font-bold text-titanium-900 dark:text-white mb-2">Case Study Not Found</h1>
        <p class="text-titanium-600 dark:text-titanium-400 mb-6 font-subheading">The case study you're looking for doesn't exist or has been removed.</p>
        <NuxtLink to="/case-studies" class="px-6 py-3 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-xl font-heading font-bold shadow-lg glow-red transition-all">
          Back to Case Studies
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch, onUnmounted, nextTick } from 'vue'
import type { CaseStudy } from '~/types/api'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const route = useRoute()
const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const slug = computed(() => route.params.slug as string | undefined)
const caseStudy = ref<CaseStudy | null>(null)
const relatedCaseStudies = ref<CaseStudy[]>([])
const pending = ref(false)
const fetchError = ref<Error | null>(null)

const { getCaseStudy } = useApi()

const loadCaseStudy = async () => {
  if (!slug.value) return
  pending.value = true
  fetchError.value = null
  try {
    const response = await getCaseStudy(slug.value) as any
    caseStudy.value = response?.data ?? null
    relatedCaseStudies.value = response?.related ?? []
  } catch (error) {
    fetchError.value = error as Error
    console.error('Unable to load case study', error)
  } finally {
    pending.value = false
    nextTick(() => initAnimations())
  }
}

const initAnimations = () => {
  ctx?.revert()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.cs-hero', {
      y: 40, opacity: 0, duration: 0.8, ease: 'power3.out',
    })
    gsap.from('.cs-header', {
      y: 30, opacity: 0, duration: 0.7, delay: 0.15, ease: 'power3.out',
    })

    const resultCards = gsap.utils.toArray('.result-card') as HTMLElement[]
    resultCards.forEach((card, i) => {
      gsap.from(card, {
        y: 30, opacity: 0, scale: 0.9, duration: 0.6, delay: 0.3 + i * 0.1,
        ease: 'back.out(1.5)',
        scrollTrigger: { trigger: card, start: 'top 90%' },
      })
    })

    const sections = ['.cs-challenge', '.cs-solution', '.cs-tech', '.cs-content', '.cs-github', '.cs-cta']
    sections.forEach((sel) => {
      gsap.from(sel, {
        y: 30, opacity: 0, duration: 0.7, ease: 'power3.out',
        scrollTrigger: { trigger: sel, start: 'top 85%' },
      })
    })

    const relatedCards = gsap.utils.toArray('.related-card') as HTMLElement[]
    relatedCards.forEach((card, i) => {
      gsap.from(card, {
        y: 30, opacity: 0, duration: 0.6, delay: i * 0.1,
        ease: 'back.out(1.3)',
        scrollTrigger: { trigger: card, start: 'top 90%' },
      })
    })
  }, sectionRef.value)
}

watch(slug, () => { loadCaseStudy() }, { immediate: true })

watch(caseStudy, (value) => {
  if (value) {
    useSeoMeta({
      title: `${value.title} | Mintreu Case Studies`,
      description: value.description || value.challenge
    })
  }
})

onUnmounted(() => { ctx?.revert() })

const getResults = (cs: CaseStudy) => {
  if (!cs?.results) return []
  if (typeof cs.results === 'string') {
    try { return JSON.parse(cs.results) }
    catch { return [] }
  }
  return cs.results || []
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.prose-content :deep(h2) { @apply text-2xl font-bold mt-8 mb-4 text-titanium-900 dark:text-white font-heading; }
.prose-content :deep(h3) { @apply text-xl font-bold mt-6 mb-3 text-titanium-900 dark:text-white font-heading; }
.prose-content :deep(p) { @apply mb-4 leading-relaxed text-titanium-600 dark:text-titanium-400 font-subheading; }
.prose-content :deep(code) { @apply bg-titanium-100 dark:bg-titanium-800 px-2 py-1 rounded text-sm; }
.prose-content :deep(pre) { @apply bg-titanium-900 dark:bg-titanium-800 p-6 rounded-xl overflow-x-auto mb-6; }
.prose-content :deep(ul), .prose-content :deep(ol) { @apply mb-4 ml-6; }
.prose-content :deep(li) { @apply mb-2 text-titanium-600 dark:text-titanium-400; }
</style>
