<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 relative">
    <!-- 3D Hero -->
    <SharedPageHero
      badge="Latest Updates"
      title="<span class='text-transparent bg-clip-text bg-gradient-to-r from-mintreu-red-400 via-mintreu-red-500 to-mintreu-red-600'>Insights</span> & Tutorials"
      subtitle="Tutorials, guides, and resources to help you build better applications"
      node-color="#3b82f6"
    />

    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none" style="top: 50vh;"></div>

    <div ref="sectionRef" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-1 pt-8">
      <!-- Search & Filters -->
      <div class="mb-12">
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between mb-6">
          <div class="relative w-full lg:w-96">
            <Icon name="lucide:search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-titanium-400" />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search articles..."
              class="w-full pl-12 pr-4 py-3 bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 focus:border-mintreu-red-500 font-subheading text-titanium-900 dark:text-white placeholder-titanium-400"
            />
          </div>

          <select
            v-model="sortBy"
            class="px-4 py-3 bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 font-subheading text-titanium-900 dark:text-white"
          >
            <option value="latest">Latest First</option>
            <option value="popular">Most Popular</option>
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
          <div class="h-48 bg-titanium-200 dark:bg-titanium-800"></div>
          <div class="p-6 space-y-3">
            <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded w-3/4"></div>
            <div class="h-4 bg-titanium-200 dark:bg-titanium-800 rounded"></div>
            <div class="h-4 bg-titanium-200 dark:bg-titanium-800 rounded w-1/2"></div>
          </div>
        </div>
      </div>

      <!-- Articles Grid -->
      <div v-else-if="articles?.data && articles.data.length > 0" class="perspective-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <NuxtLink
          v-for="article in articles.data"
          :key="article.slug"
          :to="`/insights/${article.slug}`"
          class="article-card group bg-white dark:bg-titanium-900 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 dark:hover:border-mintreu-red-600/50 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
        >
          <div class="relative h-48 bg-titanium-800 overflow-hidden">
            <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>
            <img v-if="article.image" :src="article.image" :alt="article.title"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
            <div v-else class="absolute inset-0 flex items-center justify-center">
              <Icon name="lucide:book-open" class="w-20 h-20 text-titanium-600 group-hover:text-mintreu-red-600/60 transition-colors duration-500" />
            </div>

            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 bg-titanium-900/60 backdrop-blur-sm border border-titanium-600 rounded-full text-white text-xs font-heading font-bold">
                {{ article.category }}
              </span>
            </div>
          </div>

          <div class="p-6">
            <h3 class="text-xl font-heading font-bold mb-3 text-titanium-900 dark:text-white group-hover:text-mintreu-red-600 transition-colors line-clamp-2">
              {{ article.title }}
            </h3>
            <p class="text-sm text-titanium-600 dark:text-titanium-400 mb-4 line-clamp-3 font-subheading leading-relaxed">
              {{ article.excerpt }}
            </p>

            <div v-if="article.tags && article.tags.length > 0" class="flex flex-wrap gap-2 mb-4">
              <span v-for="tag in (article.tags || []).slice(0, 3)" :key="tag"
                class="px-2 py-1 bg-titanium-100 dark:bg-titanium-800 text-titanium-600 dark:text-titanium-400 rounded text-xs font-heading font-semibold">
                #{{ tag }}
              </span>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-titanium-200 dark:border-titanium-800 text-xs text-titanium-500 font-subheading">
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
              <div class="flex items-center space-x-1 text-mintreu-red-600 font-heading font-bold">
                <span>Read</span>
                <Icon name="lucide:arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
              </div>
            </div>
          </div>
        </NuxtLink>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20">
        <Icon name="lucide:book-open" class="w-20 h-20 text-titanium-400 mx-auto mb-4" />
        <h3 class="text-xl font-heading font-bold mb-2 text-titanium-900 dark:text-white">No Articles Found</h3>
        <p class="text-titanium-600 dark:text-titanium-400 font-subheading">Try adjusting your search or filters</p>
      </div>

      <!-- Pagination -->
      <div v-if="articles && articles.meta && articles.meta.last_page > 1" class="mt-12 flex justify-center">
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
            @click="page < articles.meta.last_page && page++"
            :disabled="page === articles.meta.last_page"
            class="px-4 py-2 rounded-xl bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 disabled:opacity-50 text-titanium-700 dark:text-titanium-300 hover:border-mintreu-red-600/50 transition-colors"
          >
            <Icon name="lucide:chevron-right" class="w-5 h-5" />
          </button>
        </nav>
      </div>

      <!-- Newsletter CTA -->
      <div class="newsletter-cta mt-20 relative overflow-hidden rounded-3xl">
        <div class="absolute inset-0 bg-titanium-900"></div>
        <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>
        <div class="relative border border-dashed border-titanium-700 rounded-3xl p-12 text-center">
          <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-mintreu-red-600 rounded-tl-xl"></div>
          <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-mintreu-red-600 rounded-br-xl"></div>

          <Icon name="lucide:mail" class="w-16 h-16 text-mintreu-red-600 mx-auto mb-6" />
          <h3 class="text-3xl font-heading font-black text-white mb-4">Stay Updated</h3>
          <p class="text-titanium-400 mb-8 max-w-2xl mx-auto font-subheading leading-relaxed">
            Get the latest tutorials and resources delivered to your inbox weekly
          </p>
          <div class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <input
              type="email"
              placeholder="Enter your email"
              class="flex-1 px-6 py-4 rounded-xl bg-titanium-800 border border-titanium-700 text-white placeholder-titanium-500 focus:ring-2 focus:ring-mintreu-red-500 focus:border-mintreu-red-500 font-subheading"
            />
            <button class="px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white font-heading font-bold rounded-xl shadow-lg glow-red transform hover:scale-105 active:scale-95 transition-all">
              Subscribe
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'
import type { Article, PaginatedResponse } from '~/types/api'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

useSeoMeta({
  title: 'Insights | Mintreu - Tutorials & Resources',
  description: 'Learn from our collection of tutorials, guides, and resources for web and mobile development.'
})

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const page = ref(1)
const searchQuery = ref('')
const activeCategory = ref('All')
const sortBy = ref<'latest' | 'popular' | 'title'>('latest')
const categories = ref<string[]>(['All'])
const articles = ref<PaginatedResponse<Article> | null>(null)
const pending = ref(false)
const fetchError = ref<Error | null>(null)

const { getArticles, getCategories } = useApi()

const loadCategories = async () => {
  try {
  const response = await getCategories({ type: 'articles' }) as any
  const items = Array.isArray(response)
    ? response
    : Array.isArray(response?.data)
      ? response.data
      : []
    if (items.length) {
      categories.value = ['All', ...items.map((c: any) => c.name)]
    }
  } catch (error) {
    console.error('Unable to load article categories', error)
  }
}

const fetchArticlesList = async () => {
  pending.value = true
  fetchError.value = null
  try {
    const response = await getArticles({
      page: page.value,
      search: searchQuery.value || undefined,
      category: activeCategory.value !== 'All' ? activeCategory.value : undefined,
      sort: sortBy.value,
      per_page: 12
    }) as any
    articles.value = response ?? null
  } catch (error) {
    fetchError.value = error as Error
    console.error('Unable to load insights', error)
  } finally {
    pending.value = false
    initAnimations()
  }
}

const initAnimations = () => {
  ctx?.revert()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    const cards = gsap.utils.toArray('.article-card') as HTMLElement[]
    if (cards.length) {
      gsap.set(cards, { opacity: 0, y: 50, scale: 0.95 })
      ScrollTrigger.create({
        trigger: cards[0],
        start: 'top 92%',
        once: true,
        onEnter: () => {
          gsap.to(cards, { opacity: 1, y: 0, scale: 1, duration: 0.6, stagger: 0.06, ease: 'back.out(1.3)' })
        }
      })
    }

    const newsletter = sectionRef.value?.querySelector('.newsletter-cta')
    if (newsletter) {
      gsap.set(newsletter, { opacity: 0, y: 60, scale: 0.95 })
      ScrollTrigger.create({
        trigger: newsletter,
        start: 'top 90%',
        once: true,
        onEnter: () => { gsap.to(newsletter, { opacity: 1, y: 0, scale: 1, duration: 0.8, ease: 'power3.out' }) }
      })
    }
  }, sectionRef.value)
  ScrollTrigger.refresh()
}

onMounted(() => {
  loadCategories()
})

watch(
  [page, searchQuery, activeCategory, sortBy],
  () => { fetchArticlesList() },
  { immediate: true }
)

onUnmounted(() => { ctx?.revert() })

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

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
