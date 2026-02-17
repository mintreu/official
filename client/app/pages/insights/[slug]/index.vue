<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 py-8 relative">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <nav class="flex items-center space-x-2 text-sm mb-8">
        <NuxtLink to="/" class="text-titanium-500 hover:text-mintreu-red-600 font-subheading transition-colors">Home</NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-titanium-400" />
        <NuxtLink to="/insights" class="text-titanium-500 hover:text-mintreu-red-600 font-subheading transition-colors">Insights</NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-titanium-400" />
        <span class="text-titanium-900 dark:text-white font-heading font-bold text-xs uppercase tracking-wider truncate max-w-[200px]">
          {{ article?.title || 'Loading...' }}
        </span>
      </nav>

      <!-- Loading -->
      <div v-if="pending" class="space-y-6">
        <div class="h-80 bg-white dark:bg-titanium-900 rounded-3xl border border-dashed border-titanium-300 dark:border-titanium-700 animate-pulse"></div>
        <div class="h-8 bg-titanium-200 dark:bg-titanium-800 rounded w-3/4 animate-pulse"></div>
        <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded w-1/2 animate-pulse"></div>
      </div>

      <!-- Article Detail -->
      <div v-else-if="article" ref="sectionRef" class="space-y-10">
        <!-- Hero Image -->
        <div class="article-hero relative h-80 md:h-96 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 shadow-2xl">
          <img v-if="article.image" :src="article.image" :alt="article.title" class="w-full h-full object-cover" />
          <div v-else class="w-full h-full bg-gradient-to-br from-titanium-800 via-titanium-900 to-titanium-950 flex items-center justify-center relative">
            <div class="absolute inset-0 bg-blueprint opacity-20"></div>
            <Icon name="lucide:book-open" class="relative w-24 h-24 text-titanium-600" />
          </div>

          <div class="absolute top-6 left-6">
            <span class="px-4 py-2 bg-mintreu-red-600/90 backdrop-blur-sm text-white rounded-full text-sm font-heading font-bold">
              {{ article.category }}
            </span>
          </div>
        </div>

        <!-- Article Header -->
        <header class="article-header bg-white dark:bg-titanium-900 rounded-3xl shadow-2xl p-8 lg:p-12 border border-dashed border-titanium-300 dark:border-titanium-700 relative overflow-hidden">
          <div class="absolute inset-0 bg-blueprint opacity-10 pointer-events-none"></div>
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
          <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600/40 rounded-br-xl"></div>

          <div class="relative">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-heading font-black mb-6 text-titanium-900 dark:text-white leading-tight">
              {{ article.title }}
            </h1>

            <div class="flex flex-wrap items-center gap-4 text-sm text-titanium-500 font-subheading">
              <div v-if="article.author" class="flex items-center space-x-2">
                <Icon name="lucide:user" class="w-4 h-4" />
                <span>{{ article.author }}</span>
              </div>
              <div class="flex items-center space-x-2">
                <Icon name="lucide:calendar" class="w-4 h-4" />
                <span>{{ formatDate(article.created_at) }}</span>
              </div>
              <div v-if="article.reading_time" class="flex items-center space-x-2">
                <Icon name="lucide:clock" class="w-4 h-4" />
                <span>{{ article.reading_time }} min read</span>
              </div>
            </div>

            <div v-if="article.tags && article.tags.length > 0" class="flex flex-wrap gap-2 mt-6">
              <span v-for="tag in article.tags" :key="tag"
                class="px-3 py-1 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-lg text-sm font-heading font-semibold">
                #{{ tag }}
              </span>
            </div>
          </div>
        </header>

        <!-- Article Content -->
        <section class="article-content bg-white dark:bg-titanium-900 rounded-3xl shadow-xl p-8 lg:p-12 border border-dashed border-titanium-300 dark:border-titanium-700 relative">
          <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
          <div class="prose-content" v-html="article.content"></div>
        </section>

        <!-- GitHub Repository Section -->
        <section v-if="article.github_url" class="article-github relative overflow-hidden rounded-3xl">
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
                  <h3 class="text-2xl font-heading font-bold text-white mb-1">View Source Code</h3>
                  <p class="text-titanium-400 font-subheading">Access the complete code on GitHub</p>
                </div>
              </div>
              <a :href="article.github_url" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center space-x-2 px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-xl font-heading font-bold shadow-lg glow-red transform hover:scale-105 transition-all whitespace-nowrap">
                <Icon name="lucide:github" class="w-5 h-5" />
                <span>View on GitHub</span>
              </a>
            </div>
          </div>
        </section>

        <!-- Share Section -->
        <div class="article-share border-t border-b border-titanium-200 dark:border-titanium-800 py-8">
          <h3 class="text-lg font-heading font-bold mb-4 text-titanium-900 dark:text-white">Share this article</h3>
          <div class="flex items-center space-x-4">
            <a :href="twitterShareUrl" target="_blank" rel="noopener noreferrer"
              class="p-3 bg-titanium-800 hover:bg-mintreu-red-600 text-white rounded-xl transition-colors">
              <Icon name="lucide:twitter" class="w-5 h-5" />
            </a>
            <a :href="linkedinShareUrl" target="_blank" rel="noopener noreferrer"
              class="p-3 bg-titanium-800 hover:bg-mintreu-red-600 text-white rounded-xl transition-colors">
              <Icon name="lucide:linkedin" class="w-5 h-5" />
            </a>
            <button @click="copyLink" class="p-3 bg-titanium-800 hover:bg-mintreu-red-600 text-white rounded-xl transition-colors">
              <Icon name="lucide:link" class="w-5 h-5" />
            </button>
          </div>
        </div>

        <!-- Author Bio -->
        <div v-if="article.author" class="article-author bg-white dark:bg-titanium-900 rounded-3xl p-8 border border-dashed border-titanium-300 dark:border-titanium-700 shadow-xl">
          <div class="flex items-start space-x-4">
            <div class="w-20 h-20 bg-mintreu-red-600 rounded-2xl flex items-center justify-center text-white text-2xl font-heading font-black flex-shrink-0">
              {{ article.author.charAt(0) }}
            </div>
            <div>
              <h3 class="text-xl font-heading font-bold text-titanium-900 dark:text-white mb-2">{{ article.author }}</h3>
              <p class="text-titanium-600 dark:text-titanium-400 font-subheading leading-relaxed">
                Full-stack developer passionate about building scalable applications and sharing knowledge with the community.
              </p>
            </div>
          </div>
        </div>

        <!-- Related Articles -->
        <section v-if="relatedArticles.length > 0" class="space-y-6">
          <div class="flex items-center justify-between">
            <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white">Related Articles</h2>
            <NuxtLink to="/insights" class="text-mintreu-red-600 font-heading font-bold text-sm hover:underline">See all</NuxtLink>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <NuxtLink
              v-for="related in relatedArticles"
              :key="related.slug"
              :to="`/insights/${related.slug}`"
              class="related-card group block p-6 rounded-2xl bg-white dark:bg-titanium-900 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
            >
              <span v-if="related.category" class="px-3 py-1 bg-titanium-100 dark:bg-titanium-800 text-titanium-600 dark:text-titanium-400 rounded-full text-xs font-heading font-bold mb-3 inline-block">
                {{ related.category }}
              </span>
              <h3 class="text-lg font-heading font-bold text-titanium-900 dark:text-white mb-2 group-hover:text-mintreu-red-600 transition-colors">
                {{ related.title }}
              </h3>
              <p class="text-sm text-titanium-600 dark:text-titanium-400 line-clamp-2 font-subheading">{{ related.excerpt }}</p>
            </NuxtLink>
          </div>
        </section>
      </div>

      <!-- 404 State -->
      <div v-else class="max-w-3xl mx-auto text-center py-20">
        <Icon name="lucide:alert-circle" class="w-20 h-20 text-mintreu-red-500 mx-auto mb-4" />
        <h1 class="text-3xl font-heading font-bold text-titanium-900 dark:text-white mb-2">Article Not Found</h1>
        <p class="text-titanium-600 dark:text-titanium-400 mb-6 font-subheading">The article you're looking for doesn't exist or has been removed.</p>
        <NuxtLink to="/insights" class="px-6 py-3 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-xl font-heading font-bold shadow-lg glow-red transition-all">
          Back to Insights
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch, onUnmounted, nextTick } from 'vue'
import type { Article } from '~/types/api'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const route = useRoute()
const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const slug = computed(() => route.params.slug as string | undefined)
const article = ref<Article | null>(null)
const relatedArticles = ref<Article[]>([])
const pending = ref(false)
const fetchError = ref<Error | null>(null)

const { getArticle } = useApi()

const loadArticle = async () => {
  if (!slug.value) return
  pending.value = true
  fetchError.value = null
  try {
    const response = await getArticle(slug.value) as any
    article.value = response?.data ?? null
    relatedArticles.value = response?.related ?? []
  } catch (error) {
    fetchError.value = error as Error
    console.error('Unable to load article', error)
  } finally {
    pending.value = false
    nextTick(() => initAnimations())
  }
}

const initAnimations = () => {
  ctx?.revert()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.article-hero', { y: 40, opacity: 0, duration: 0.8, ease: 'power3.out' })
    gsap.from('.article-header', { y: 30, opacity: 0, duration: 0.7, delay: 0.15, ease: 'power3.out' })

    const sections = ['.article-content', '.article-github', '.article-share', '.article-author']
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

watch(slug, () => { loadArticle() }, { immediate: true })

watch(article, (value) => {
  if (value) {
    useSeoMeta({
      title: `${value.title} | Mintreu Insights`,
      description: value.excerpt
    })
  }
})

onUnmounted(() => { ctx?.revert() })

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
}

const currentUrl = computed(() => {
  if (typeof window !== 'undefined') return window.location.href
  return ''
})

const twitterShareUrl = computed(() => {
  return `https://twitter.com/intent/tweet?url=${encodeURIComponent(currentUrl.value)}&text=${encodeURIComponent(article.value?.title || '')}`
})

const linkedinShareUrl = computed(() => {
  return `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(currentUrl.value)}`
})

const copyLink = async () => {
  try {
    await navigator.clipboard.writeText(currentUrl.value)
  } catch {
    // Fallback silently
  }
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
