<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8">
    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
      <nav class="flex items-center space-x-2 text-sm">
        <NuxtLink to="/" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Home
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <span class="text-gray-900 dark:text-white font-medium">Insights</span>
      </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 rounded-full text-sm font-semibold">
            Latest Updates
          </span>
        </div>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black mb-6 text-gray-900 dark:text-white">
          <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Insights</span> & Tutorials
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Tutorials, guides, and resources to help you build better applications
        </p>
      </div>

      <!-- Search & Filters -->
      <div class="mb-12">
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between mb-6">
          <!-- Search -->
          <div class="relative w-full lg:w-96">
            <Icon name="lucide:search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search articles..."
                class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500"
            />
          </div>

          <!-- Sort -->
          <select
              v-model="sortBy"
              class="px-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500"
          >
            <option value="latest">Latest First</option>
            <option value="popular">Most Popular</option>
            <option value="title">Title A-Z</option>
          </select>
        </div>

        <!-- Category Pills -->
        <div class="flex flex-wrap gap-3">
          <button
              v-for="category in categories"
              :key="category"
              @click="activeCategory = category"
              class="px-6 py-2 rounded-lg font-semibold transition-all"
              :class="activeCategory === category
              ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg'
              : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'"
          >
            {{ category }}
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="n in 6" :key="n" class="bg-white dark:bg-gray-900 rounded-3xl h-96 animate-pulse"></div>
      </div>

      <!-- Articles Grid -->
      <div v-else-if="articles?.data && articles.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <NuxtLink
            v-for="article in articles.data"
            :key="article.slug"
            :to="`/insights/${article.slug}`"
            class="group bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-indigo-500 dark:hover:border-indigo-500 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
        >
          <!-- Featured Image -->
          <div class="relative h-48 bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-600 overflow-hidden">
            <img
                v-if="article.image"
                :src="article.image"
                :alt="article.title"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            />
            <div v-else class="absolute inset-0 flex items-center justify-center">
              <Icon name="lucide:book-open" class="w-20 h-20 text-white/20" />
            </div>

            <!-- Category Badge -->
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full text-white text-xs font-bold">
                {{ article.category }}
              </span>
            </div>
          </div>

          <!-- Content -->
          <div class="p-6">
            <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
              {{ article.title }}
            </h3>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
              {{ article.excerpt }}
            </p>

            <!-- Tags -->
            <div v-if="article.tags && article.tags.length > 0" class="flex flex-wrap gap-2 mb-4">
              <span
                  v-for="tag in (article.tags || []).slice(0, 3)"
                  :key="tag"
                  class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded text-xs font-medium"
              >
                #{{ tag }}
              </span>
            </div>

            <!-- Meta -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-800 text-xs text-gray-500">
              <div class="flex items-center space-x-4">
                <div v-if="article.reading_time" class="flex items-center space-x-1">
                  <Icon name="lucide:clock" class="w-4 h-4" />
                  <span>{{ article.reading_time }} min</span>
                </div>
                <div v-if="article.author" class="flex items-center space-x-1">
                  <Icon name="lucide:user" class="w-4 h-4" />
                  <span>{{ article.author }}</span>
                </div>
              </div>
              <div class="flex items-center space-x-1 text-indigo-600 dark:text-indigo-400 font-semibold">
                <span>Read</span>
                <Icon name="lucide:arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
              </div>
            </div>
          </div>
        </NuxtLink>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20">
        <Icon name="lucide:book-open" class="w-20 h-20 text-gray-300 dark:text-gray-700 mx-auto mb-4" />
        <h3 class="text-xl font-bold mb-2">No Articles Found</h3>
        <p class="text-gray-600 dark:text-gray-400">Try adjusting your search or filters</p>
      </div>

      <!-- Pagination -->
      <div v-if="articles && articles.meta && articles.meta.last_page > 1" class="mt-12 flex justify-center">
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
              :class="page === p ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white' : 'bg-white dark:bg-gray-900 border'"
          >
            {{ p }}
          </button>

          <button
              @click="page < articles.meta.last_page && page++"
              :disabled="page === articles.meta.last_page"
              class="px-4 py-2 rounded-lg bg-white dark:bg-gray-900 border disabled:opacity-50"
          >
            <Icon name="lucide:chevron-right" class="w-5 h-5" />
          </button>
        </nav>
      </div>

      <!-- Newsletter CTA -->
      <div class="mt-20 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-3xl p-12 text-center">
        <Icon name="lucide:mail" class="w-16 h-16 text-white/80 mx-auto mb-6" />
        <h3 class="text-3xl font-black text-white mb-4">Stay Updated</h3>
        <p class="text-white/90 mb-8 max-w-2xl mx-auto">
          Get the latest tutorials and resources delivered to your inbox weekly
        </p>
        <div class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
          <input
              type="email"
              placeholder="Enter your email"
              class="flex-1 px-6 py-4 rounded-xl focus:ring-2 focus:ring-white focus:outline-none"
          />
          <button class="px-8 py-4 bg-white hover:bg-gray-100 text-indigo-600 font-bold rounded-xl shadow-xl transform hover:scale-105 transition-all">
            Subscribe
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { PaginatedResponse, Article } from '~/types/api'

useSeoMeta({
  title: 'Insights | Mintreu - Tutorials & Resources',
  description: 'Learn from our collection of tutorials, guides, and resources for web and mobile development.'
})

const api = useApi()
const page = ref(1)
const searchQuery = ref('')
const activeCategory = ref('All')
const sortBy = ref('latest')

const categories = ['All', 'Laravel', 'Nuxt', 'React', 'Mobile', 'DevOps', 'Best Practices']

const { data: articles, pending } = await useAsyncData<PaginatedResponse<Article>>(
  'articles',
  () => api.getArticles({
    page: page.value,
    search: searchQuery.value || undefined,
    category: activeCategory.value !== 'All' ? activeCategory.value : undefined,
    sort: sortBy.value,
    per_page: 12
  }),
  { watch: [page, searchQuery, activeCategory, sortBy] }
)

const paginationRange = computed(() => {
  if (!articles.value?.meta) return []
  const total = articles.value.meta.last_page
  const current = page.value
  const range = []
  for (let i = Math.max(1, current - 2); i <= Math.min(total, current + 2); i++) {
    range.push(i)
  }
  return range
})
</script>
