<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8">
    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
      <nav class="flex items-center space-x-2 text-sm">
        <NuxtLink to="/" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Home
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <span class="text-gray-900 dark:text-white font-medium">Case Studies</span>
      </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-full text-sm font-semibold">
            Success Stories
          </span>
        </div>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black mb-6 text-gray-900 dark:text-white">
          Client <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Case Studies</span>
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Real projects with measurable results and proven ROI for our clients worldwide
        </p>
      </div>

      <!-- Filters -->
      <div class="flex flex-wrap gap-3 justify-center mb-12">
        <button
            v-for="filter in filters"
            :key="filter"
            @click="activeFilter = filter"
            class="px-6 py-2 rounded-lg font-semibold transition-all"
            :class="activeFilter === filter
            ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
            : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'"
        >
          {{ filter }}
        </button>
      </div>

      <!-- Case Studies List -->
      <div v-if="pending" class="space-y-8">
        <div v-for="n in 3" :key="n" class="bg-white dark:bg-gray-900 rounded-3xl h-64 animate-pulse"></div>
      </div>

      <div v-else-if="caseStudies?.data && caseStudies.data.length > 0" class="space-y-16">
        <div
            v-for="(study, index) in caseStudies.data"
            :key="study.slug"
            class="group bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-blue-500 dark:hover:border-blue-500 shadow-xl hover:shadow-2xl transition-all duration-500"
        >
          <div class="grid lg:grid-cols-5 gap-8 p-8 lg:p-12">
            <!-- Image -->
            <div class="lg:col-span-2">
              <div class="relative h-64 lg:h-full bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 rounded-2xl flex items-center justify-center overflow-hidden">
                <img
                    v-if="study.image"
                    :src="study.image"
                    :alt="study.title"
                    class="w-full h-full object-cover"
                />
                <Icon v-else name="lucide:briefcase" class="w-24 h-24 text-white/20" />
              </div>
            </div>

            <!-- Content -->
            <div class="lg:col-span-3">
              <div class="inline-block px-4 py-1.5 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-full text-sm font-bold mb-4">
                {{ study.industry || 'Case Study' }}
              </div>

              <h3 class="text-3xl font-black mb-4 text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                {{ study.title }}
              </h3>

              <p class="text-lg text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                {{ study.challenge }}
              </p>

              <!-- Results -->
              <div v-if="getResults(study).length > 0" class="grid grid-cols-3 gap-4 mb-6">
                <div v-for="(result, idx) in getResults(study).slice(0, 3)" :key="idx" class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                  <div class="text-2xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-1">
                    {{ result.value || result }}
                  </div>
                  <div class="text-xs text-gray-600 dark:text-gray-400">{{ result.label || 'Result' }}</div>
                </div>
              </div>

              <!-- Tech Stack -->
              <div v-if="study.technologies && study.technologies.length > 0" class="flex flex-wrap gap-2 mb-6">
                <span
                    v-for="tech in study.technologies.slice(0, 5)"
                    :key="tech"
                    class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold"
                >
                  {{ tech }}
                </span>
                <span v-if="study.technologies.length > 5" class="px-3 py-1 text-gray-500 text-sm">
                  +{{ study.technologies.length - 5 }}
                </span>
              </div>

              <!-- CTA -->
              <NuxtLink
                  :to="`/case-studies/${study.slug}`"
                  class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all"
              >
                <span>Read Full Case Study</span>
                <Icon name="lucide:arrow-right" class="w-5 h-5" />
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20">
        <Icon name="lucide:book-open" class="w-20 h-20 text-gray-300 dark:text-gray-700 mx-auto mb-4" />
        <h3 class="text-xl font-bold mb-2">No Case Studies Found</h3>
        <p class="text-gray-600 dark:text-gray-400">Check back soon for new success stories</p>
      </div>

      <!-- Pagination -->
      <div v-if="caseStudies && caseStudies.meta && caseStudies.meta.last_page > 1" class="mt-12 flex justify-center">
        <nav class="flex items-center space-x-2">
          <button
              @click="page > 1 && page--"
              :disabled="page === 1"
              class="px-4 py-2 rounded-lg bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 disabled:opacity-50"
          >
            <Icon name="lucide:chevron-left" class="w-5 h-5" />
          </button>

          <button
              v-for="p in paginationRange"
              :key="p"
              @click="page = p"
              class="px-4 py-2 rounded-lg font-semibold"
              :class="page === p
              ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white'
              : 'bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700'"
          >
            {{ p }}
          </button>

          <button
              @click="page < caseStudies.meta.last_page && page++"
              :disabled="page === caseStudies.meta.last_page"
              class="px-4 py-2 rounded-lg bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 disabled:opacity-50"
          >
            <Icon name="lucide:chevron-right" class="w-5 h-5" />
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { PaginatedResponse, CaseStudy } from '~/types/api'

useSeoMeta({
  title: 'Case Studies | Mintreu - Client Success Stories',
  description: 'Explore our detailed case studies showcasing real projects with measurable results and proven ROI.'
})

const api = useApi()
const page = ref(1)
const activeFilter = ref('All')

const { data: rawCategories } = await useAsyncData('categories-case-studies', () => api.getCategories({ type: 'case-studies' }));\nconst filters = ref(['All']);
const api = useApi();
onMounted(async () => {
  try {
    const rawCategories = await api.getCategories({ type: 'case-studies' });
    filters.value = ['All', ...rawCategories.map(c => c.name)];
  } catch (error) {
    console.error('Failed to load categories', error);
  }
});

const { data: caseStudies, pending } = await useAsyncData<PaginatedResponse<CaseStudy>>(
  'case-studies',
  () => api.getCaseStudies({
    page: page.value,
    category: activeFilter.value !== 'All' ? activeFilter.value : undefined,
    per_page: 6
  }),
  { watch: [page, activeFilter] }
)

const paginationRange = computed(() => {
  if (!caseStudies.value?.meta) return []
  const total = caseStudies.value.meta.last_page
  const current = page.value
  const range = []
  for (let i = Math.max(1, current - 2); i <= Math.min(total, current + 2); i++) {
    range.push(i)
  }
  return range
})

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
</script>
