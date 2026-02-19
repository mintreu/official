<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 relative">
    <!-- 3D Hero -->
    <SharedPageHero
      badge="Portfolio"
      title="Our <span class='text-transparent bg-clip-text bg-gradient-to-r from-mintreu-red-400 via-mintreu-red-500 to-mintreu-red-600'>Work</span>"
      subtitle="Explore our portfolio of successful projects and digital solutions delivered to clients worldwide"
    />

    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none" style="top: 50vh;"></div>

    <div ref="sectionRef" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-1 pt-8">
      <!-- Filters -->
      <div class="mb-12">
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between mb-6">
          <div class="relative w-full lg:w-96">
            <Icon name="lucide:search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-titanium-400" />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search projects..."
              class="w-full pl-12 pr-4 py-3 bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 focus:border-mintreu-red-500 font-subheading text-titanium-900 dark:text-white placeholder-titanium-400"
            />
          </div>

          <select
            v-model="sortBy"
            class="px-4 py-3 bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 font-subheading text-titanium-900 dark:text-white"
          >
            <option value="latest">Latest First</option>
            <option value="oldest">Oldest First</option>
            <option value="title">Title A-Z</option>
          </select>
        </div>

        <div class="flex flex-wrap gap-3">
          <button
            v-for="category in categories"
            :key="category"
            @click="activeCategory = category"
            class="px-6 py-2 rounded-xl font-heading font-bold text-sm transition-all duration-300"
            :class="activeCategory === category
              ? 'bg-mintreu-red-600 text-white shadow-lg glow-red'
              : 'bg-white dark:bg-titanium-900 text-titanium-700 dark:text-titanium-300 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50'"
          >
            {{ category }}
          </button>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="n in 6" :key="n" class="bg-white dark:bg-titanium-900 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 shadow-xl animate-pulse">
          <div class="h-56 bg-titanium-200 dark:bg-titanium-800"></div>
          <div class="p-6 space-y-3">
            <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded w-3/4"></div>
            <div class="h-4 bg-titanium-200 dark:bg-titanium-800 rounded"></div>
            <div class="flex gap-2">
              <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded w-16"></div>
              <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded w-16"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Projects Grid -->
      <div v-else-if="projectItems.length > 0" class="perspective-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <NuxtLink
          v-for="project in projectItems"
          :key="project.slug"
          :to="`/work/${project.slug}`"
          class="project-card group relative bg-white dark:bg-titanium-900 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 dark:hover:border-mintreu-red-600/50 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
        >
          <!-- Project Image -->
          <div class="relative h-56 bg-titanium-800 overflow-hidden">
            <img v-if="project.image" :src="project.image" :alt="project.title"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
            <div v-else class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-titanium-800 via-titanium-900 to-titanium-950">
              <div class="absolute inset-0 bg-blueprint opacity-30"></div>
              <Icon name="lucide:folder-open" class="relative w-20 h-20 text-titanium-600 group-hover:text-mintreu-red-600/60 transition-colors duration-500" />
            </div>

            <!-- Hover overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
              <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between">
                <span class="text-white text-sm font-heading font-semibold">View Project</span>
                <Icon name="lucide:arrow-right" class="w-5 h-5 text-white transform group-hover:translate-x-1 transition-transform" />
              </div>
            </div>

            <!-- Category Badge -->
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 bg-titanium-900/60 backdrop-blur-sm border border-titanium-600 rounded-full text-white text-xs font-heading font-bold">
                {{ project.category || 'Project' }}
              </span>
            </div>

            <div v-if="project.featured" class="absolute top-4 left-4">
              <span class="px-3 py-1 bg-mintreu-red-600/90 backdrop-blur-sm rounded-full text-white text-xs font-heading font-bold flex items-center gap-1">
                <Icon name="lucide:star" class="w-3 h-3" />
                Featured
              </span>
            </div>
          </div>

          <!-- Project Info -->
          <div class="p-6">
            <h3 class="text-xl font-heading font-bold mb-2 text-titanium-900 dark:text-white group-hover:text-mintreu-red-600 transition-colors">
              {{ project.title }}
            </h3>
            <p class="text-sm text-titanium-600 dark:text-titanium-400 mb-4 line-clamp-2 font-subheading">
              {{ project.description }}
            </p>

            <div v-if="project.technologies?.length" class="flex flex-wrap gap-2 mb-4">
              <span v-for="tech in project.technologies.slice(0, 3)" :key="tech"
                class="px-2 py-1 bg-titanium-100 dark:bg-titanium-800 text-titanium-700 dark:text-titanium-300 rounded text-xs font-heading font-semibold">
                {{ tech }}
              </span>
              <span v-if="project.technologies.length > 3"
                class="px-2 py-1 bg-titanium-100 dark:bg-titanium-800 text-titanium-600 rounded text-xs font-heading">
                +{{ project.technologies.length - 3 }}
              </span>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-titanium-200 dark:border-titanium-800">
              <div class="flex items-center space-x-1 text-xs text-titanium-500 font-subheading">
                <Icon name="lucide:calendar" class="w-4 h-4" />
                <span>{{ formatDate(project.created_at) }}</span>
              </div>
              <div v-if="project.live_url" class="flex items-center space-x-1 text-xs text-green-600 dark:text-green-400 font-heading font-semibold">
                <Icon name="lucide:external-link" class="w-4 h-4" />
                <span>Live</span>
              </div>
            </div>
          </div>
        </NuxtLink>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20">
        <Icon name="lucide:folder-open" class="w-20 h-20 text-titanium-400 mx-auto mb-4" />
        <h3 class="text-xl font-heading font-bold mb-2 text-titanium-900 dark:text-white">No Projects Found</h3>
        <p class="text-titanium-600 dark:text-titanium-400 font-subheading">Try adjusting your search or filters</p>
      </div>

      <!-- Load More -->
      <div v-if="hasMore" class="mt-12 flex justify-center">
        <button
          @click="loadMore"
          :disabled="loadingMore"
          class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-mintreu-red-600 hover:bg-mintreu-red-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-heading font-bold shadow-lg transition-all duration-300"
        >
          <Icon v-if="loadingMore" name="lucide:loader-2" class="w-4 h-4 animate-spin" />
          <Icon v-else name="lucide:plus" class="w-4 h-4" />
          <span>{{ loadingMore ? 'Loading...' : 'Load More Projects' }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'
import type { PaginatedResponse, Project } from '~/types/api'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

useSeoMeta({
  title: 'Our Work | Mintreu Portfolio',
  description: 'Explore our portfolio of web, mobile, and desktop applications. See how we help businesses succeed.'
})

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const page = ref(1)
const searchQuery = ref('')
const activeCategory = ref('All')
const sortBy = ref<'latest' | 'oldest' | 'title'>('latest')
const categories = ref<string[]>(['All'])
const projectItems = ref<Project[]>([])
const paginationMeta = ref<PaginatedResponse<Project>['meta'] | null>(null)
const pending = ref(false)
const loadingMore = ref(false)
const fetchError = ref<Error | null>(null)

const { getProjects, getCategories } = useApi()

const loadCategories = async () => {
  try {
  const response = await getCategories({ type: 'projects' }) as any
  const items = Array.isArray(response)
    ? response
    : Array.isArray(response?.data)
      ? response.data
      : []
    if (items.length) {
      categories.value = ['All', ...items.map((c: any) => c.name)]
    }
  } catch (error) {
    console.error('Unable to load project categories', error)
  }
}

const normalizePaginated = (response: any): PaginatedResponse<Project> | null => {
  if (response?.data && Array.isArray(response.data) && response?.meta) return response as PaginatedResponse<Project>
  if (response?.data?.data && Array.isArray(response.data.data) && response?.data?.meta) return response.data as PaginatedResponse<Project>
  return null
}

const fetchProjectsList = async (append = false) => {
  if (append) {
    loadingMore.value = true
  } else {
    pending.value = true
  }
  fetchError.value = null
  try {
    const response = await getProjects({
      page: page.value,
      search: searchQuery.value || undefined,
      category: activeCategory.value !== 'All' ? activeCategory.value : undefined,
      sort: sortBy.value,
      per_page: 9
    }) as any
    const normalized = normalizePaginated(response)
    const items = normalized?.data ?? []

    projectItems.value = append ? [...projectItems.value, ...items] : items
    paginationMeta.value = normalized?.meta ?? null
  } catch (error) {
    fetchError.value = error as Error
    console.error('Unable to load projects', error)
  } finally {
    pending.value = false
    loadingMore.value = false
    initAnimations()
  }
}

const initAnimations = () => {
  ctx?.revert()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    const cards = gsap.utils.toArray('.project-card') as HTMLElement[]
    if (cards.length) {
      gsap.set(cards, { opacity: 0, y: 50, scale: 0.95 })
      ScrollTrigger.create({
        trigger: cards[0],
        start: 'top 92%',
        once: true,
        onEnter: () => {
          gsap.to(cards, { opacity: 1, y: 0, scale: 1, duration: 0.6, stagger: 0.08, ease: 'back.out(1.3)' })
        }
      })
    }
  }, sectionRef.value)
  ScrollTrigger.refresh()
}

onMounted(() => {
  loadCategories()
})

watch(
  [searchQuery, activeCategory, sortBy],
  () => {
    page.value = 1
    projectItems.value = []
    fetchProjectsList(false)
  },
  { immediate: true }
)

onUnmounted(() => { ctx?.revert() })

const hasMore = computed(() => {
  if (!paginationMeta.value) return false
  return paginationMeta.value.current_page < paginationMeta.value.last_page
})

const loadMore = async () => {
  if (!hasMore.value || loadingMore.value) return
  page.value += 1
  await fetchProjectsList(true)
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short'
  })
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
