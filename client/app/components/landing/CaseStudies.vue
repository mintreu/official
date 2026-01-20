<script setup lang="ts">
import type { CaseStudy } from '~/types/api'

const { getCaseStudies } = useApi()

// Fetch featured case studies from API
const { data: caseStudiesData, pending, error } = await useAsyncData(
  'featured-case-studies',
  () => getCaseStudies({ featured: true, per_page: 3 })
)

const caseStudies = computed(() => caseStudiesData.value?.data || [])

// Parse results if stored as JSON string
const getResults = (caseStudy: CaseStudy) => {
  if (typeof caseStudy.results === 'string') {
    try {
      return JSON.parse(caseStudy.results)
    } catch {
      return []
    }
  }
  return caseStudy.results || []
}
</script>

<template>
  <!-- Case Studies Section -->
  <section id="case-studies" class="py-20 lg:py-32 relative overflow-hidden bg-white dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl mx-auto text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-full text-sm font-semibold">
            Success Stories
          </span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black mb-6 text-gray-900 dark:text-white">
          Client
          <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 dark:from-blue-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
            Success Stories
          </span>
        </h2>
        <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 leading-relaxed">
          Deep dive into real projects with measurable results and proven ROI
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="pending" class="space-y-16">
        <div v-for="i in 3" :key="i" class="animate-pulse">
          <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3 space-y-4">
              <div class="h-8 bg-gray-300 dark:bg-gray-700 rounded w-24"></div>
              <div class="h-12 bg-gray-300 dark:bg-gray-700 rounded"></div>
              <div class="h-20 bg-gray-300 dark:bg-gray-700 rounded"></div>
              <div class="grid grid-cols-3 gap-4">
                <div class="h-24 bg-gray-300 dark:bg-gray-700 rounded"></div>
                <div class="h-24 bg-gray-300 dark:bg-gray-700 rounded"></div>
                <div class="h-24 bg-gray-300 dark:bg-gray-700 rounded"></div>
              </div>
            </div>
            <div class="lg:col-span-2">
              <div class="aspect-[4/3] bg-gray-300 dark:bg-gray-700 rounded-2xl"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <div class="inline-flex items-center space-x-2 px-6 py-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg">
          <Icon name="lucide:alert-circle" class="w-5 h-5" />
          <span>Failed to load case studies. Please try again later.</span>
        </div>
      </div>

      <!-- Case Studies Content -->
      <div v-else-if="caseStudies.length > 0" class="space-y-16 lg:space-y-24">
        <div
          v-for="(caseStudy, index) in caseStudies"
          :key="caseStudy.slug"
          class="group"
        >
          <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12 items-start">
            <!-- Content Side -->
            <div class="lg:col-span-3" :class="{ 'lg:col-start-3 lg:row-start-1': index % 2 !== 0 }">
              <div class="inline-block px-4 py-1.5 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-full text-sm font-bold mb-4">
                Case Study #{{ index + 1 }}
              </div>

              <h3 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-4">
                {{ caseStudy.title }}
              </h3>

              <p class="text-xl text-gray-600 dark:text-gray-400 mb-6 leading-relaxed font-medium">
                {{ caseStudy.description }}
              </p>

              <!-- Client & Industry -->
              <div class="flex gap-4 mb-6">
                <div class="flex items-center gap-2 text-sm">
                  <Icon name="lucide:briefcase" class="w-4 h-4 text-gray-500" />
                  <span class="text-gray-600 dark:text-gray-400">{{ caseStudy.client }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                  <Icon name="lucide:building" class="w-4 h-4 text-gray-500" />
                  <span class="text-gray-600 dark:text-gray-400">{{ caseStudy.industry }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                  <Icon name="lucide:clock" class="w-4 h-4 text-gray-500" />
                  <span class="text-gray-600 dark:text-gray-400">{{ caseStudy.duration }}</span>
                </div>
              </div>

              <!-- Results Grid -->
              <div v-if="getResults(caseStudy).length > 0" class="grid grid-cols-3 gap-4 mb-8">
                <div v-for="(result, idx) in getResults(caseStudy)" :key="idx" class="text-center p-4 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                  <div class="text-2xl md:text-3xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-1">
                    {{ result.value || result }}
                  </div>
                  <div class="text-xs text-gray-600 dark:text-gray-400 font-medium">
                    {{ result.label || 'Result' }}
                  </div>
                </div>
              </div>

              <!-- Challenge -->
              <div v-if="caseStudy.challenge" class="mb-6">
                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Challenge</h4>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                  {{ caseStudy.challenge }}
                </p>
              </div>

              <!-- Solution -->
              <div v-if="caseStudy.solution" class="mb-6">
                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Solution Delivered</h4>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                  {{ caseStudy.solution }}
                </p>
              </div>

              <!-- Tech Stack -->
              <div v-if="caseStudy.technologies && caseStudy.technologies.length > 0" class="mb-8">
                <h4 class="text-sm font-bold text-gray-500 dark:text-gray-500 mb-3 uppercase tracking-wider">Technology Stack</h4>
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="tech in caseStudy.technologies"
                    :key="tech"
                    class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold"
                  >
                    {{ tech }}
                  </span>
                </div>
              </div>

              <NuxtLink
                :to="`/case-studies/${caseStudy.slug}`"
                class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-300"
              >
                <span>Read Full Story</span>
                <Icon name="lucide:arrow-right" class="w-5 h-5" />
              </NuxtLink>
            </div>

            <!-- Image Side -->
            <div class="lg:col-span-2" :class="{ 'lg:col-start-1 lg:row-start-1': index % 2 !== 0 }">
              <div class="sticky top-24">
                <div class="relative group/img overflow-hidden rounded-2xl shadow-2xl">
                  <div v-if="caseStudy.image" class="aspect-[4/3]">
                    <img
                      :src="caseStudy.image"
                      :alt="caseStudy.title"
                      class="w-full h-full object-cover"
                    />
                  </div>
                  <div v-else class="aspect-[4/3] bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 flex items-center justify-center">
                    <Icon name="lucide:file-text" class="w-24 h-24 text-white/20" />
                  </div>
                  <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover/img:opacity-100 transition-opacity"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <Icon name="lucide:file-text" class="w-16 h-16 mx-auto mb-4 text-gray-400" />
        <p class="text-gray-600 dark:text-gray-400">No case studies available at the moment.</p>
      </div>
    </div>

    <!-- CTA Button -->
    <div class="flex justify-center items-center w-full">
      <NuxtLink
        to="/case-studies"
        class="px-8 py-4 my-6 w-full md:w-auto rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-center font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
      >
        View More Case Studies
      </NuxtLink>
    </div>
  </section>
</template>

<style scoped>
</style>
