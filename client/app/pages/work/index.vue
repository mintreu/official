<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8">
    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
      <nav class="flex items-center space-x-2 text-sm">
        <NuxtLink to="/" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Home
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <span class="text-gray-900 dark:text-white font-medium">Our Work</span>
      </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-sm font-semibold">
            Portfolio
          </span>
        </div>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black mb-6 text-gray-900 dark:text-white">
          Our <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Work</span>
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Explore our portfolio of successful projects and digital solutions delivered to clients worldwide
        </p>
      </div>

      <!-- Filters -->
      <div class="mb-12">
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between mb-6">
          <div class="relative w-full lg:w-96">
            <Icon name="lucide:search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search projects..."
                class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <select
              v-model="sortBy"
              class="px-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500"
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
              class="px-6 py-2 rounded-lg font-semibold transition-all"
              :class="activeCategory === category
              ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
              : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'"
          >
            {{ category }}
          </button>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="n in 6" :key="n" class="bg-white dark:bg-gray-900 rounded-3xl h-96 animate-pulse"></div>
      </div>

      <!-- Projects Grid -->
      <div v-else-if="projects?.data && projects.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <NuxtLink
            v-for="project in projects.data"
            :key="project.slug"
            :to="`/work/${project.slug}`"
            class="group bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-blue-500 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
        >
          <div class="relative h-56 bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600">
            <img
                v-if="project.image"
                :src="project.image"
                :alt="project.title"
                class="w-full h-full object-cover"
            />
            <div v-else class="absolute inset-0 flex items-center justify-center">
              <Icon name="lucide:code" class="w-24 h-24 text-white/20" />
            </div>

            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full text-white text-xs font-bold">
                {{ project.category || 'Project' }}
              </span>
            </div>

            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
              <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between">
                <span class="text-white font-semibold">View Project</span>
                <Icon name="lucide:arrow-right" class="w-5 h-5 text-white" />
              </div>
            </div>
          </div>

          <div class="p-6">
            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
              {{ project.title }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
              {{ project.description }}
            </p>

            <div class="flex flex-wrap gap-2 mb-4">
              <span
                  v-for="tech in (project.technologies || []).slice(0, 3)"
                  :key="tech"
                  class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded text-xs font-semibold"
              >
                {{ tech }}
              </span>
              <span v-if="(project.technologies || []).length > 3" class="px-2 py-1 text-gray-500 text-xs">
                +{{ (project.technologies || []).length - 3 }}
              </span>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-800">
              <div class="flex items-center space-x-1 text-xs text-gray-500">
                <Icon name="lucide:calendar" class="w-4 h-4" />
                <span>{{ formatDate(project.created_at) }}</span>
              </div>
              <div v-if="project.live_url" class="flex items-center space-x-1 text-xs text-green-600 dark:text-green-400 font-semibold">
                <Icon name="lucide:external-link" class="w-4 h-4" />
                <span>Live</span>
              </div>
            </div>
          </div>
        </NuxtLink>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20">
        <Icon name="lucide:folder-open" class="w-20 h-20 text-gray-300 dark:text-gray-700 mx-auto mb-4" />
        <h3 class="text-xl font-bold mb-2">No Projects Found</h3>
        <p class="text-gray-600 dark:text-gray-400">Try adjusting your search or filters</p>
      </div>

      <!-- Pagination -->
      <div v-if="projects && projects.meta && projects.meta.last_page > 1" class="mt-12 flex justify-center">
        <nav class="flex items-center space-x-2">
          <button
              @click="page > 1 && page--"
              :disabled="page === 1"
              class="px-4 py-2 rounded-lg bg-white dark:bg-gray-900 border disabled:opacity-50"
          >
            <Icon name="lucide:chevron-left" class="w-5 h-5" />
          </button>

          <button
              v-for="p in paginationRange"
              :key="p"
              @click="page = p"
              class="px-4 py-2 rounded-lg font-semibold"
              :class="page === p ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'bg-white dark:bg-gray-900 border'"
          >
            {{ p }}
          </button>

          <button
              @click="page < projects.meta.last_page && page++"
              :disabled="page === projects.meta.last_page"
              class="px-4 py-2 rounded-lg bg-white dark:bg-gray-900 border disabled:opacity-50"
          >
            <Icon name="lucide:chevron-right" class="w-5 h-5" />
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { PaginatedResponse, Project } from '~/types/api'

useSeoMeta({
  title: 'Our Work | Mintreu Portfolio',
  description: 'Explore our portfolio of web, mobile, and desktop applications. See how we help businesses succeed.'
})

const api = useApi()
const page = ref(1)
const searchQuery = ref('')
const activeCategory = ref('All')
const sortBy = ref('latest')

const { data: rawCategories } = await useAsyncData('categories-projects', () => api.getCategories({ type: 'projects' }));\nconst categories = computed(() => ['All', ...(rawCategories.value || []).map(c => c.name)])

const { data: projects, pending } = await useAsyncData<PaginatedResponse<Project>>(
  'projects',
  () => api.getProjects({
    page: page.value,
    search: searchQuery.value || undefined,
    category: activeCategory.value !== 'All' ? activeCategory.value : undefined,
    sort: sortBy.value,
    per_page: 9
  }),
  { watch: [page, searchQuery, activeCategory, sortBy] }
)

const paginationRange = computed(() => {
  if (!projects.value?.meta) return []
  const total = projects.value.meta.last_page
  const current = page.value
  const range = []
  for (let i = Math.max(1, current - 2); i <= Math.min(total, current + 2); i++) {
    range.push(i)
  }
  return range
})

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short'
  })
}
</script>
