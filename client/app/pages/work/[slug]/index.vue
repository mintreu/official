<template>
  <div class="min-h-screen bg-white dark:bg-gray-950 py-8">
    <!-- Breadcrumb -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
      <nav class="flex items-center space-x-2 text-sm">
        <NuxtLink to="/" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Home
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <NuxtLink to="/work" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Work
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <span class="text-gray-900 dark:text-white font-medium truncate">{{ project?.title || 'Loading...' }}</span>
      </nav>
    </div>

    <div v-if="pending" class="max-w-5xl mx-auto px-4 animate-pulse">
      <div class="h-96 bg-gray-200 dark:bg-gray-800 rounded-3xl mb-8"></div>
      <div class="h-12 bg-gray-200 dark:bg-gray-800 rounded mb-4"></div>
    </div>

    <div v-else-if="project" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Back Button -->
      <NuxtLink to="/work" class="inline-flex items-center space-x-2 text-blue-600 dark:text-blue-400 hover:underline mb-8">
        <Icon name="lucide:arrow-left" class="w-4 h-4" />
        <span>Back to Work</span>
      </NuxtLink>

      <!-- Hero Image -->
      <div class="relative h-96 bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 rounded-3xl mb-12 flex items-center justify-center overflow-hidden">
        <img
            v-if="project.image"
            :src="project.image"
            :alt="project.title"
            class="w-full h-full object-cover"
        />
        <Icon v-else name="lucide:code" class="w-32 h-32 text-white/20" />

        <div class="absolute top-6 right-6">
          <span class="px-4 py-2 bg-white/20 backdrop-blur-xl border border-white/30 rounded-full text-white font-bold">
            {{ project.category || 'Project' }}
          </span>
        </div>
      </div>

      <h1 class="text-4xl sm:text-5xl font-black mb-6 text-gray-900 dark:text-white">
        {{ project.title }}
      </h1>
      <p class="text-xl text-gray-600 dark:text-gray-400 mb-12 leading-relaxed">
        {{ project.description }}
      </p>

      <!-- Content -->
      <div v-if="project.content" class="mb-12 prose dark:prose-invert max-w-none" v-html="project.content"></div>

      <!-- Tech Stack -->
      <div v-if="project.technologies && project.technologies.length > 0" class="mb-12">
        <h2 class="text-3xl font-bold mb-6">Technology Stack</h2>
        <div class="flex flex-wrap gap-3">
          <span v-for="tech in project.technologies" :key="tech" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold">
            {{ tech }}
          </span>
        </div>
      </div>

      <!-- Project Links -->
      <div class="flex flex-wrap gap-4 mb-12">
        <a v-if="project.live_url" :href="project.live_url" target="_blank" class="inline-flex items-center space-x-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold">
          <Icon name="lucide:external-link" class="w-5 h-5" />
          <span>View Live</span>
        </a>
        <a v-if="project.github_url" :href="project.github_url" target="_blank" class="inline-flex items-center space-x-2 px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-semibold">
          <Icon name="lucide:github" class="w-5 h-5" />
          <span>View on GitHub</span>
        </a>
      </div>

      <!-- CTA -->
      <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-3xl p-12 text-center">
        <h3 class="text-3xl font-black text-white mb-4">Want a Similar Project?</h3>
        <p class="text-white/90 mb-8 max-w-2xl mx-auto">Let's discuss how we can build something amazing for your business.</p>
        <NuxtLink to="/contact" class="inline-flex items-center space-x-2 px-8 py-4 bg-white text-blue-600 hover:bg-gray-100 rounded-xl font-bold shadow-2xl transform hover:scale-105 transition-all">
          <span>Start Your Project</span>
          <Icon name="lucide:arrow-right" class="w-5 h-5" />
        </NuxtLink>
      </div>

      <!-- Related Projects -->
      <div v-if="relatedProjects && relatedProjects.length > 0" class="mt-16">
        <h2 class="text-3xl font-bold mb-8 flex items-center">
          <Icon name="lucide:code" class="w-8 h-8 text-purple-500 mr-3" />
          Related Projects
        </h2>
        <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide" style="scroll-snap-type: x mandatory">
          <NuxtLink
              v-for="related in relatedProjects"
              :key="related.slug"
              :to="`/work/${related.slug}`"
              class="flex-shrink-0 w-72 group bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-purple-500 dark:hover:border-purple-500 shadow-lg hover:shadow-xl transition-all duration-500"
              style="scroll-snap-align: start"
          >
            <div class="relative h-40 bg-gradient-to-br from-purple-500 via-blue-600 to-pink-600 flex-shrink-0">
              <img
                  v-if="related.image"
                  :src="related.image"
                  :alt="related.title"
                  class="w-full h-full object-cover"
              />
              <Icon v-else name="lucide:code" class="w-16 h-16 text-white/30 absolute inset-0 m-auto" />
              <div class="absolute top-3 right-3">
                <span class="px-2 py-1 bg-white/20 backdrop-blur-sm border border-white/30 rounded text-white text-xs font-bold">
                  {{ related.category || 'Project' }}
                </span>
              </div>
            </div>
            <div class="p-5 flex-shrink-0">
              <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors line-clamp-2">
                {{ related.title }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">
                {{ related.description }}
              </p>
              <div class="flex items-center justify-between mt-3">
                <div class="flex flex-wrap gap-1">
                  <span
                      v-for="tech in (related.technologies || []).slice(0, 3)"
                      :key="tech"
                      class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded text-xs font-semibold"
                  >
                    {{ tech }}
                  </span>
                  <span v-if="(related.technologies || []).length > 3" class="px-2 py-1 text-gray-500 text-xs">
                    +{{ (related.technologies || []).length - 3 }}
                  </span>
                </div>
                <span class="text-xs text-purple-600 dark:text-purple-400 font-semibold">
                  View Project
                </span>
              </div>
            </div>
          </NuxtLink>
        </div>
      </div>
    </div>

    <!-- 404 State -->
    <div v-else class="max-w-5xl mx-auto px-4 text-center py-20">
      <Icon name="lucide:alert-circle" class="w-20 h-20 text-red-500 mx-auto mb-4" />
      <h1 class="text-3xl font-bold mb-2">Project Not Found</h1>
      <p class="text-gray-600 dark:text-gray-400 mb-4">The project you're looking for doesn't exist or has been removed.</p>
      <NuxtLink to="/work" class="text-blue-600 hover:underline">Back to Work</NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Project, ApiResponse } from '~/types/api'

const route = useRoute()
const { getProject } = useApi()

const { data: response, pending, error } = await useAsyncData(
  `project-${route.params.slug}`,
  () => getProject(route.params.slug as string)
)

const project = computed(() => response.value?.data)
const relatedProjects = computed(() => response.value?.related || [])

watchEffect(() => {
  if (project.value) {
    useSeoMeta({
      title: `${project.value.title} | Mintreu Work`,
      description: project.value.description
    })
  }
})
</script>
