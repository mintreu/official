<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 py-8 relative">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <nav class="flex items-center space-x-2 text-sm mb-8">
        <NuxtLink to="/" class="text-titanium-500 hover:text-mintreu-red-600 font-subheading transition-colors">Home</NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-titanium-400" />
        <NuxtLink to="/work" class="text-titanium-500 hover:text-mintreu-red-600 font-subheading transition-colors">Our Work</NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-titanium-400" />
        <span class="text-titanium-900 dark:text-white font-heading font-bold text-xs uppercase tracking-wider truncate max-w-[200px]">
          {{ project?.title || 'Loading...' }}
        </span>
      </nav>

      <!-- Loading -->
      <div v-if="pending" class="space-y-6">
        <div class="h-64 bg-white dark:bg-titanium-900 rounded-3xl border border-dashed border-titanium-300 dark:border-titanium-700 animate-pulse"></div>
        <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded w-3/4 animate-pulse"></div>
        <div class="h-4 bg-titanium-200 dark:bg-titanium-800 rounded w-1/2 animate-pulse"></div>
      </div>

      <!-- Project Detail -->
      <div v-else-if="project" ref="sectionRef" class="space-y-12">
        <!-- Header Card -->
        <header class="project-header bg-white dark:bg-titanium-900 rounded-3xl shadow-2xl p-8 lg:p-12 border border-dashed border-titanium-300 dark:border-titanium-700 relative overflow-hidden">
          <div class="absolute inset-0 bg-blueprint opacity-10 pointer-events-none"></div>
          <!-- Corner marks -->
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
          <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600/40 rounded-br-xl"></div>

          <div class="relative flex flex-col lg:flex-row gap-8 lg:items-center">
            <div class="flex-1">
              <span class="label-schematic mb-3 block">PRJ-{{ project.slug.slice(0, 8).toUpperCase() }}</span>
              <h1 class="text-3xl md:text-4xl lg:text-5xl font-heading font-black text-titanium-900 dark:text-white mb-4">
                {{ project.title }}
              </h1>
              <p class="text-lg text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">
                {{ project.description }}
              </p>
            </div>
            <div class="flex-shrink-0 w-full lg:w-72 h-64 rounded-2xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700">
              <img v-if="project.image" :src="project.image" :alt="project.title" class="w-full h-full object-cover" />
              <div v-else class="w-full h-full bg-gradient-to-br from-titanium-800 via-titanium-900 to-titanium-950 flex items-center justify-center relative">
                <div class="absolute inset-0 bg-blueprint opacity-20"></div>
                <Icon name="lucide:folder-open" class="relative w-20 h-20 text-titanium-600" />
              </div>
            </div>
          </div>

          <div class="relative flex flex-wrap gap-3 mt-6">
            <span class="px-3 py-1.5 rounded-full bg-titanium-100 dark:bg-titanium-800 text-titanium-700 dark:text-titanium-300 text-sm font-heading font-bold">
              {{ project.category }}
            </span>
            <span v-if="project.featured" class="px-3 py-1.5 rounded-full bg-mintreu-red-600 text-white text-sm font-heading font-bold flex items-center gap-1">
              <Icon name="lucide:star" class="w-3 h-3" /> Featured
            </span>
          </div>

          <div class="relative flex flex-wrap gap-4 mt-4 text-xs text-titanium-500 font-subheading">
            <span class="flex items-center gap-1">
              <Icon name="lucide:calendar" class="w-4 h-4" />
              {{ formatDate(project.created_at) }}
            </span>
            <span class="flex items-center gap-1 uppercase font-heading font-bold text-mintreu-red-600">
              <Icon name="lucide:tag" class="w-4 h-4" />
              {{ project.status }}
            </span>
          </div>
        </header>

        <!-- Details Section -->
        <section class="project-details bg-white dark:bg-titanium-900 rounded-3xl shadow-xl p-8 border border-dashed border-titanium-300 dark:border-titanium-700 space-y-6 relative">
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>

          <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white">Project Details</h2>
          <div class="line-technical max-w-xs"></div>

          <div v-if="project.content" class="text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading prose-content" v-html="project.content"></div>
          <p v-else class="text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">{{ project.description }}</p>

          <div v-if="project.technologies?.length" class="space-y-3">
            <h3 class="text-sm font-heading font-bold text-titanium-500 uppercase tracking-wider">Technology Stack</h3>
            <div class="flex flex-wrap gap-2">
              <span v-for="tech in project.technologies" :key="tech"
                class="px-3 py-1.5 bg-titanium-100 dark:bg-titanium-800 text-titanium-700 dark:text-titanium-300 rounded-lg text-sm font-heading font-semibold">
                {{ tech }}
              </span>
            </div>
          </div>

          <div class="flex flex-wrap gap-4 pt-4">
            <a v-if="project.live_url" :href="project.live_url" target="_blank"
              class="inline-flex items-center px-6 py-3 rounded-xl bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white font-heading font-bold shadow-lg glow-red hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-300">
              <Icon name="lucide:external-link" class="w-4 h-4 mr-2" />
              Visit Live
            </a>
            <a v-if="project.github_url" :href="project.github_url" target="_blank"
              class="inline-flex items-center px-6 py-3 rounded-xl bg-titanium-800 hover:bg-titanium-700 text-white font-heading font-bold shadow-lg transition-all duration-300">
              <Icon name="lucide:github" class="w-4 h-4 mr-2" />
              Source Code
            </a>
          </div>
        </section>

        <!-- Related Projects -->
        <section v-if="relatedProjects.length" class="space-y-6">
          <div class="flex items-center justify-between">
            <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white">Related Projects</h2>
            <NuxtLink to="/work" class="text-mintreu-red-600 font-heading font-bold text-sm hover:underline">See all</NuxtLink>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <NuxtLink
              v-for="related in relatedProjects"
              :key="related.slug"
              :to="`/work/${related.slug}`"
              class="related-card group block p-6 rounded-2xl bg-white dark:bg-titanium-900 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
            >
              <h3 class="text-lg font-heading font-bold text-titanium-900 dark:text-white mb-2 group-hover:text-mintreu-red-600 transition-colors">
                {{ related.title }}
              </h3>
              <p class="text-sm text-titanium-600 dark:text-titanium-400 line-clamp-2 font-subheading">{{ related.description }}</p>
            </NuxtLink>
          </div>
        </section>
      </div>

      <!-- 404 State -->
      <div v-else class="max-w-3xl mx-auto text-center py-20">
        <Icon name="lucide:alert-circle" class="w-20 h-20 text-mintreu-red-500 mx-auto mb-4" />
        <h1 class="text-3xl font-heading font-bold text-titanium-900 dark:text-white mb-2">Project Not Found</h1>
        <p class="text-titanium-600 dark:text-titanium-400 mb-6 font-subheading">The project you're looking for either doesn't exist or has been archived.</p>
        <NuxtLink to="/work" class="px-6 py-3 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-xl font-heading font-bold shadow-lg glow-red transition-all">
          Back to Work
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch, onMounted, onUnmounted, nextTick } from 'vue'
import type { Project } from '~/types/api'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const route = useRoute()
const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const slug = computed(() => route.params.slug as string | undefined)
const project = ref<Project | null>(null)
const relatedProjects = ref<Project[]>([])
const pending = ref(false)
const fetchError = ref<Error | null>(null)

const { getProject } = useApi()

const loadProject = async () => {
  if (!slug.value) return
  pending.value = true
  fetchError.value = null
  try {
    const response = await getProject(slug.value) as any
    project.value = response?.data ?? null
    relatedProjects.value = response?.related ?? []
  } catch (error) {
    fetchError.value = error as Error
    console.error('Unable to load project', error)
  } finally {
    pending.value = false
    nextTick(() => initAnimations())
  }
}

const initAnimations = () => {
  ctx?.revert()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.project-header', {
      y: 40, opacity: 0, duration: 0.8, ease: 'power3.out',
    })
    gsap.from('.project-details', {
      y: 30, opacity: 0, duration: 0.7, delay: 0.2, ease: 'power3.out',
      scrollTrigger: { trigger: '.project-details', start: 'top 85%' },
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

watch(slug, () => { loadProject() }, { immediate: true })

watch(project, (value) => {
  if (value) {
    useSeoMeta({
      title: `${value.title} | Mintreu Work`,
      description: value.description
    })
  }
})

onUnmounted(() => { ctx?.revert() })

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short' })
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
.prose-content :deep(p) { @apply mb-4 leading-relaxed; }
.prose-content :deep(code) { @apply bg-titanium-100 dark:bg-titanium-800 px-2 py-1 rounded text-sm; }
.prose-content :deep(pre) { @apply bg-titanium-900 dark:bg-titanium-800 p-6 rounded-xl overflow-x-auto mb-6; }
.prose-content :deep(ul), .prose-content :deep(ol) { @apply mb-4 ml-6; }
.prose-content :deep(li) { @apply mb-2; }
</style>
