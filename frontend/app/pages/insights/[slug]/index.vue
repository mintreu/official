<template>
  <div class="min-h-screen bg-white dark:bg-gray-950 py-8">
    <!-- Breadcrumb -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
      <nav class="flex items-center space-x-2 text-sm">
        <NuxtLink to="/" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Home
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <NuxtLink to="/insights" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Insights
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <span class="text-gray-900 dark:text-white font-medium truncate">{{ article?.title || 'Loading...' }}</span>
      </nav>
    </div>

    <div v-if="pending" class="max-w-4xl mx-auto px-4 animate-pulse">
      <div class="h-96 bg-gray-200 dark:bg-gray-800 rounded-3xl mb-8"></div>
      <div class="h-12 bg-gray-200 dark:bg-gray-800 rounded mb-4"></div>
    </div>

    <div v-else-if="article" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Back Button -->
      <NuxtLink to="/insights" class="inline-flex items-center space-x-2 text-indigo-600 dark:text-indigo-400 hover:underline mb-8">
        <Icon name="lucide:arrow-left" class="w-4 h-4" />
        <span>Back to Insights</span>
      </NuxtLink>

      <!-- Hero Image -->
      <div class="relative h-96 bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-600 rounded-3xl mb-8 overflow-hidden">
        <img
            v-if="article.image"
            :src="article.image"
            :alt="article.title"
            class="w-full h-full object-cover"
        />
        <div v-else class="absolute inset-0 flex items-center justify-center">
          <Icon name="lucide:book-open" class="w-32 h-32 text-white/20" />
        </div>

        <!-- Category Badge -->
        <div class="absolute top-6 left-6">
          <span class="px-4 py-2 bg-white/20 backdrop-blur-xl border border-white/30 rounded-full text-white font-bold">
            {{ article.category }}
          </span>
        </div>
      </div>

      <!-- Article Header -->
      <div class="mb-8">
        <h1 class="text-4xl sm:text-5xl font-black mb-6 text-gray-900 dark:text-white leading-tight">
          {{ article.title }}
        </h1>

        <!-- Meta Info -->
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
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

        <!-- Tags -->
        <div v-if="article.tags && article.tags.length > 0" class="flex flex-wrap gap-2 mt-6">
          <span
              v-for="tag in article.tags"
              :key="tag"
              class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 rounded-lg text-sm font-semibold"
          >
            #{{ tag }}
          </span>
        </div>
      </div>

      <!-- Article Content -->
      <div class="prose prose-lg dark:prose-invert max-w-none mb-12">
        <div v-html="article.content"></div>
      </div>

      <!-- GitHub Repository Section (if available) -->
      <div v-if="article.github_url" class="mb-12">
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 dark:from-gray-800 dark:to-gray-900 rounded-3xl p-8 border border-gray-700">
          <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
              <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                <Icon name="lucide:github" class="w-8 h-8 text-gray-900" />
              </div>
              <div>
                <h3 class="text-2xl font-bold text-white mb-2">View Source Code</h3>
                <p class="text-gray-300">Access the complete code implementation on GitHub</p>
              </div>
            </div>
            <a
                :href="article.github_url"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center space-x-2 px-8 py-4 bg-white hover:bg-gray-100 text-gray-900 rounded-xl font-bold shadow-2xl transform hover:scale-105 transition-all whitespace-nowrap"
            >
              <Icon name="lucide:github" class="w-5 h-5" />
              <span>View on GitHub</span>
              <Icon name="lucide:external-link" class="w-4 h-4" />
            </a>
          </div>
        </div>
      </div>

      <!-- Share Section -->
      <div class="border-t border-b border-gray-200 dark:border-gray-800 py-8 mb-12">
        <h3 class="text-lg font-bold mb-4">Share this article</h3>
        <div class="flex items-center space-x-4">
          <button class="p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
            <Icon name="lucide:twitter" class="w-5 h-5" />
          </button>
          <button class="p-3 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors">
            <Icon name="lucide:linkedin" class="w-5 h-5" />
          </button>
          <button class="p-3 bg-gray-700 hover:bg-gray-800 text-white rounded-lg transition-colors">
            <Icon name="lucide:link" class="w-5 h-5" />
          </button>
        </div>
      </div>

      <!-- Author Bio -->
      <div v-if="article.author" class="bg-gray-50 dark:bg-gray-900 rounded-3xl p-8 mb-12">
        <div class="flex items-start space-x-4">
          <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-black">
            {{ article.author.charAt(0) }}
          </div>
          <div>
            <h3 class="text-xl font-bold mb-2">{{ article.author }}</h3>
            <p class="text-gray-600 dark:text-gray-400">
              Full-stack developer passionate about building scalable applications and sharing knowledge with the community.
            </p>
          </div>
        </div>
      </div>

      <!-- Related Articles - Slider Style -->
      <div v-if="relatedArticles && relatedArticles.length > 0" class="mb-12">
        <h2 class="text-3xl font-bold mb-8 flex items-center">
          <Icon name="lucide:book-open" class="w-8 h-8 text-indigo-500 mr-3" />
          Related Articles
        </h2>
        <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide" style="scroll-snap-type: x mandatory">
          <NuxtLink
              v-for="related in relatedArticles"
              :key="related.slug"
              :to="`/insights/${related.slug}`"
              class="flex-shrink-0 w-72 group bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-indigo-500 dark:hover:border-indigo-500 shadow-lg hover:shadow-xl transition-all duration-500"
              style="scroll-snap-align: start"
          >
            <div class="relative h-40 bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-600 flex-shrink-0">
              <img
                  v-if="related.image"
                  :src="related.image"
                  :alt="related.title"
                  class="w-full h-full object-cover"
              />
              <div class="absolute top-3 right-3">
                <span class="px-2 py-1 bg-white/20 backdrop-blur-sm border border-white/30 rounded text-white text-xs font-bold">
                  {{ related.category }}
                </span>
              </div>
            </div>
            <div class="p-5 flex-shrink-0">
              <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
                {{ related.title }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">
                {{ related.excerpt }}
              </p>
            </div>
          </NuxtLink>
        </div>
      </div>
    </div>

    <!-- 404 State -->
    <div v-else class="max-w-4xl mx-auto px-4 text-center py-20">
      <Icon name="lucide:alert-circle" class="w-20 h-20 text-red-500 mx-auto mb-4" />
      <h1 class="text-3xl font-bold mb-2">Article Not Found</h1>
      <p class="text-gray-600 dark:text-gray-400 mb-4">The article you're looking for doesn't exist or has been removed.</p>
      <NuxtLink to="/insights" class="text-indigo-600 hover:underline">Back to Insights</NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Article, ApiResponse } from '~/types/api'

const route = useRoute()
const { getArticle } = useApi()

const { data: response, pending } = await useAsyncData(
  `article-${route.params.slug}`,
  () => getArticle(route.params.slug as string)
)

const article = computed(() => response.value?.data)
const relatedArticles = computed(() => response.value?.related || [])

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

watchEffect(() => {
  if (article.value) {
    useSeoMeta({
      title: `${article.value.title} | Mintreu Insights`,
      description: article.value.excerpt
    })
  }
})
</script>

<style scoped>
/* Prose styles for article content */
.prose {
  @apply text-gray-700 dark:text-gray-300;
}

.prose h2 {
  @apply text-3xl font-bold mt-12 mb-6 text-gray-900 dark:text-white;
}

.prose h3 {
  @apply text-2xl font-bold mt-8 mb-4 text-gray-900 dark:text-white;
}

.prose p {
  @apply mb-6 leading-relaxed;
}

.prose code {
  @apply bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-sm;
}

.prose pre {
  @apply bg-gray-900 dark:bg-gray-800 p-6 rounded-xl overflow-x-auto mb-6;
}

.prose ul, .prose ol {
  @apply mb-6 ml-6;
}

.prose li {
  @apply mb-2;
}
</style>
