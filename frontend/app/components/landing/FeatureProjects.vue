<script setup lang="ts">
import type { Project } from '~/types/api'

const { getProjects } = useApi()

// Fetch featured projects from API
const { data: projectsData, pending, error } = await useAsyncData(
  'featured-projects',
  () => getProjects({ featured: true, per_page: 6 })
)

const projects = computed(() => projectsData.value?.data || [])
</script>

<template>
  <!-- Projects Grid Section -->
  <section id="projects" class="py-20 lg:py-32 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <div class="max-w-3xl mx-auto text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-sm font-semibold">
            Portfolio
          </span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black mb-6 text-gray-900 dark:text-white">
          Featured
          <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 dark:from-blue-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
            Projects
          </span>
        </h2>
        <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 leading-relaxed">
          Real-world applications delivered across web, mobile, and desktop platforms
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="i in 6" :key="i" class="bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-xl animate-pulse">
          <div class="h-56 bg-gray-300 dark:bg-gray-700"></div>
          <div class="p-6 space-y-3">
            <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded"></div>
            <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-3/4"></div>
            <div class="flex gap-2">
              <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded w-16"></div>
              <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded w-16"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <div class="inline-flex items-center space-x-2 px-6 py-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg">
          <Icon name="lucide:alert-circle" class="w-5 h-5" />
          <span>Failed to load projects. Please try again later.</span>
        </div>
      </div>

      <!-- Projects Grid -->
      <div v-else-if="projects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <NuxtLink
          v-for="(project, index) in projects"
          :key="project.slug"
          :to="`/work/${project.slug}`"
          class="group relative bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-blue-500 dark:hover:border-blue-500 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
          :style="{ animationDelay: `${index * 100}ms` }"
        >
          <!-- Project Image -->
          <div class="relative h-56 bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 overflow-hidden">
            <img
              v-if="project.image"
              :src="project.image"
              :alt="project.title"
              class="w-full h-full object-cover"
            />
            <div v-else class="absolute inset-0 flex items-center justify-center">
              <Icon name="lucide:folder-open" class="w-24 h-24 text-white/20" />
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
              <div class="absolute bottom-4 left-4 right-4">
                <div class="flex items-center justify-between">
                  <span class="text-white text-sm font-semibold">View Project</span>
                  <Icon name="lucide:arrow-right" class="w-5 h-5 text-white transform group-hover:translate-x-1 transition-transform" />
                </div>
              </div>
            </div>
            <!-- Category Badge -->
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full text-white text-xs font-bold">
                {{ project.category }}
              </span>
            </div>
            <!-- Featured Badge -->
            <div v-if="project.featured" class="absolute top-4 left-4">
              <span class="px-3 py-1 bg-yellow-500/90 backdrop-blur-sm rounded-full text-white text-xs font-bold flex items-center gap-1">
                <Icon name="lucide:star" class="w-3 h-3" />
                Featured
              </span>
            </div>
          </div>

          <!-- Project Info -->
          <div class="p-6">
            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
              {{ project.title }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
              {{ project.description }}
            </p>

            <!-- Tech Tags -->
            <div v-if="project.technologies && project.technologies.length > 0" class="flex flex-wrap gap-2 mb-4">
              <span
                v-for="tech in project.technologies.slice(0, 4)"
                :key="tech"
                class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded text-xs font-semibold"
              >
                {{ tech }}
              </span>
              <span
                v-if="project.technologies.length > 4"
                class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded text-xs font-semibold"
              >
                +{{ project.technologies.length - 4 }}
              </span>
            </div>

            <!-- Stats -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-800">
              <div class="flex items-center space-x-1 text-xs text-gray-500 dark:text-gray-500">
                <Icon name="lucide:calendar" class="w-4 h-4" />
                <span>{{ new Date(project.created_at).getFullYear() }}</span>
              </div>
              <div class="flex items-center space-x-1 text-xs text-blue-600 dark:text-blue-400 font-semibold">
                <span>{{ project.status }}</span>
              </div>
            </div>
          </div>
        </NuxtLink>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <Icon name="lucide:folder-open" class="w-16 h-16 mx-auto mb-4 text-gray-400" />
        <p class="text-gray-600 dark:text-gray-400">No projects available at the moment.</p>
      </div>

      <!-- CTA Button -->
      <div class="w-full my-6 flex justify-center items-center">
        <NuxtLink
          to="/work"
          class="px-8 py-4 w-full md:w-auto rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-center font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
        >
          View All Projects
        </NuxtLink>
      </div>

    </div>
  </section>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
