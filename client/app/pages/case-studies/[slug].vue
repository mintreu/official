<template>
  <div class="min-h-screen bg-white dark:bg-gray-950 py-20">
    <div v-if="pending" class="max-w-5xl mx-auto px-4 animate-pulse">
      <div class="h-96 bg-gray-200 dark:bg-gray-800 rounded-3xl mb-8"></div>
      <div class="h-12 bg-gray-200 dark:bg-gray-800 rounded mb-4"></div>
    </div>

    <div v-else-if="caseStudy" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Back Button -->
      <NuxtLink to="/case-studies" class="inline-flex items-center space-x-2 text-blue-600 dark:text-blue-400 hover:underline mb-8">
        <Icon name="lucide:arrow-left" class="w-4 h-4" />
        <span>Back to Case Studies</span>
      </NuxtLink>

      <!-- Hero Image -->
      <div class="relative h-96 bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 rounded-3xl mb-12 flex items-center justify-center overflow-hidden">
        <img
            v-if="caseStudy?.image"
            :src="caseStudy.image"
            :alt="caseStudy.title"
            class="w-full h-full object-cover"
        />
        <Icon v-else name="lucide:briefcase" class="w-32 h-32 text-white/20" />

        <!-- Case Study Badge -->
        <div class="absolute top-6 left-6">
          <span class="px-4 py-2 bg-orange-500 text-white rounded-full text-sm font-bold">
            Case Study
          </span>
        </div>

        <!-- GitHub Repo Button (if available) -->
        <div v-if="caseStudy.github_url" class="absolute top-6 right-6">
          <a
              :href="caseStudy.github_url"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center space-x-2 px-4 py-2 bg-black/80 hover:bg-black text-white rounded-xl font-semibold backdrop-blur-sm border border-white/20 transition-all hover:scale-105"
          >
            <Icon name="lucide:github" class="w-5 h-5" />
            <span>View on GitHub</span>
          </a>
        </div>
      </div>

      <!-- Client & Title -->
      <div class="mb-8">
        <div class="inline-block px-4 py-1.5 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-full text-sm font-bold mb-4">
          Client: {{ caseStudy.client }}
        </div>
        <h1 class="text-4xl sm:text-5xl font-black mb-6 text-gray-900 dark:text-white">
          {{ caseStudy.title }}
        </h1>
      </div>

      <!-- Key Results -->
      <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-12">
        <div v-for="(result, idx) in getResults(caseStudy)" :key="idx" class="text-center p-6 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-900 dark:to-gray-800 rounded-2xl border border-blue-100 dark:border-gray-800">
          <div class="text-3xl md:text-4xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
            {{ result.value || result }}
          </div>
          <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ result.label || 'Result' }}</div>
        </div>
      </div>

      <!-- Challenge Section -->
      <div class="mb-12">
        <h2 class="text-3xl font-bold mb-6 flex items-center">
          <Icon name="lucide:target" class="w-8 h-8 text-red-500 mr-3" />
          The Challenge
        </h2>
        <div class="bg-red-50 dark:bg-red-900/10 border-l-4 border-red-500 p-6 rounded-r-xl">
          <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
            {{ caseStudy.challenge }}
          </p>
        </div>
      </div>

      <!-- Solution Section -->
      <div class="mb-12">
        <h2 class="text-3xl font-bold mb-6 flex items-center">
          <Icon name="lucide:lightbulb" class="w-8 h-8 text-yellow-500 mr-3" />
          The Solution
        </h2>
        <div class="bg-yellow-50 dark:bg-yellow-900/10 border-l-4 border-yellow-500 p-6 rounded-r-xl">
          <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
            {{ caseStudy.solution }}
          </p>
        </div>
      </div>

      <!-- Technology Stack -->
      <div class="mb-12">
        <h2 class="text-3xl font-bold mb-6 flex items-center">
          <Icon name="lucide:code" class="w-8 h-8 text-blue-500 mr-3" />
          Technology Stack
        </h2>
        <div class="flex flex-wrap gap-3">
          <span
              v-for="tech in caseStudy?.technologies || []"
              :key="tech"
              class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold shadow-lg"
          >
            {{ tech }}
          </span>
        </div>
      </div>

      <!-- Content Section -->
      <div v-if="caseStudy?.content" class="mb-12 prose dark:prose-invert max-w-none" v-html="caseStudy.content"></div>

      <!-- GitHub Section (if repo available) -->
      <div v-if="caseStudy.github_url" class="mb-12">
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 dark:from-gray-800 dark:to-gray-900 rounded-3xl p-8 border border-gray-700">
          <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
              <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                <Icon name="lucide:github" class="w-8 h-8 text-gray-900" />
              </div>
              <div>
                <h3 class="text-2xl font-bold text-white mb-2">Open Source Repository</h3>
                <p class="text-gray-300">Explore the codebase and implementation details on GitHub</p>
              </div>
            </div>
            <a
                :href="caseStudy.github_url"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center space-x-2 px-8 py-4 bg-white hover:bg-gray-100 text-gray-900 rounded-xl font-bold shadow-2xl transform hover:scale-105 transition-all whitespace-nowrap"
            >
              <Icon name="lucide:github" class="w-5 h-5" />
              <span>View Repository</span>
              <Icon name="lucide:external-link" class="w-4 h-4" />
            </a>
          </div>
        </div>
      </div>

      <!-- Timeline (optional) -->
      <div v-if="caseStudy.timeline" class="mb-12">
        <h2 class="text-3xl font-bold mb-6">Project Timeline</h2>
        <div class="space-y-4">
          <div
              v-for="(phase, index) in caseStudy.timeline"
              :key="index"
              class="flex items-start space-x-4 p-6 bg-gray-50 dark:bg-gray-900 rounded-xl border-l-4 border-blue-500"
          >
            <div class="flex-shrink-0 w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">
              {{ index + 1 }}
            </div>
            <div>
              <h4 class="font-bold text-lg mb-1">{{ phase.title }}</h4>
              <p class="text-gray-600 dark:text-gray-400">{{ phase.description }}</p>
              <span class="text-sm text-gray-500 mt-2 inline-block">{{ phase.duration }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- CTA Section -->
      <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-3xl p-12 text-center">
        <h3 class="text-3xl font-black text-white mb-4">Need a Similar Solution?</h3>
        <p class="text-white/90 mb-8 max-w-2xl mx-auto text-lg">
          Let's discuss how we can help you achieve comparable results for your business.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
          <NuxtLink
              to="/contact"
              class="inline-flex items-center space-x-2 px-8 py-4 bg-white text-blue-600 hover:bg-gray-100 rounded-xl font-bold shadow-2xl transform hover:scale-105 transition-all"
          >
            <span>Get Started</span>
            <Icon name="lucide:arrow-right" class="w-5 h-5" />
          </NuxtLink>
          <a
              v-if="caseStudy.github_url"
              :href="caseStudy.github_url"
              target="_blank"
              class="inline-flex items-center space-x-2 px-8 py-4 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-bold shadow-2xl transform hover:scale-105 transition-all"
          >
            <Icon name="lucide:github" class="w-5 h-5" />
            <span>View Code</span>
          </a>
        </div>
      </div>
    </div>

    <!-- 404 State -->
    <div v-if="!caseStudy && !pending" class="max-w-5xl mx-auto px-4 text-center py-20">
      <Icon name="lucide:alert-circle" class="w-20 h-20 text-red-500 mx-auto mb-4" />
      <h1 class="text-3xl font-bold mb-2">Case Study Not Found</h1>
      <p class="text-gray-600 dark:text-gray-400 mb-4">The case study you're looking for doesn't exist or has been removed.</p>
      <NuxtLink to="/case-studies" class="text-blue-600 hover:underline">← Back to Case Studies</NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { CaseStudy, ApiResponse } from '~/types/api'

const route = useRoute()
const { getCaseStudy } = useApi()

const { data: response, pending, error } = await useAsyncData(
    `case-study-${route.params.slug}`,
    () => getCaseStudy(route.params.slug as string)
)

const caseStudy = computed(() => response.value?.data)

// Parse results if stored as JSON string
const getResults = (caseStudy: CaseStudy) => {
  if (!caseStudy?.results) return []
  if (typeof caseStudy.results === 'string') {
    try {
      return JSON.parse(caseStudy.results)
    } catch {
      return []
    }
  }
  return caseStudy.results || []
}

watchEffect(() => {
  if (caseStudy.value) {
    useSeoMeta({
      title: `${caseStudy.value.title} | Mintreu Case Studies`,
      description: caseStudy.value.description || caseStudy.value.challenge
    })
  }
})
</script>
