<template>
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

      <!-- Projects Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div
            v-for="(project, index) in projectsGrid"
            :key="project.id"
            class="group relative bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-blue-500 dark:hover:border-blue-500 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
            :style="{ animationDelay: `${index * 100}ms` }"
        >
          <!-- Project Image -->
          <div class="relative h-56 bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 overflow-hidden">
            <div class="absolute inset-0 flex items-center justify-center">
              <Icon :name="project.icon" class="w-24 h-24 text-white/20" />
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
              <div class="absolute bottom-4 left-4 right-4">
                <div class="flex items-center justify-between">
                  <span class="text-white text-sm font-semibold">View Project</span>
                  <Icon name="lucide:arrow-right" class="w-5 h-5 text-white transform group-hover:translate-x-1 transition-transform" />
                </div>
              </div>
            </div>
            <!-- Platform Badge -->
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full text-white text-xs font-bold">
                {{ project.platform }}
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
            <div class="flex flex-wrap gap-2 mb-4">
              <span
                  v-for="tech in project.tech"
                  :key="tech"
                  class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded text-xs font-semibold"
              >
                {{ tech }}
              </span>
            </div>

            <!-- Stats -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-800">
              <div class="flex items-center space-x-1 text-xs text-gray-500 dark:text-gray-500">
                <Icon name="lucide:clock" class="w-4 h-4" />
                <span>{{ project.duration }}</span>
              </div>
              <div class="flex items-center space-x-1 text-xs text-green-600 dark:text-green-400 font-semibold">
                <Icon name="lucide:trending-up" class="w-4 h-4" />
                <span>{{ project.result }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const projectsGrid = [
  {
    id: '01',
    title: 'E-Commerce Platform',
    description: 'Full-featured online store with Laravel backend, Nuxt frontend, and native Android app.',
    icon: 'lucide:shopping-cart',
    platform: 'Web + Mobile',
    tech: ['Laravel', 'Nuxt', 'Kotlin', 'Stripe'],
    duration: '4 months',
    result: '300% ROI'
  },
  {
    id: '02',
    title: 'Healthcare Portal',
    description: 'HIPAA-compliant patient management system with telemedicine capabilities.',
    icon: 'lucide:heart-pulse',
    platform: 'Web + Desktop',
    tech: ['Laravel', 'Livewire', 'Electron'],
    duration: '5 months',
    result: '10K+ Users'
  },
  {
    id: '03',
    title: 'FinTech Dashboard',
    description: 'Real-time financial analytics dashboard with AI-powered insights.',
    icon: 'lucide:bar-chart-3',
    platform: 'Web',
    tech: ['Next.js', 'Python', 'TensorFlow'],
    duration: '3 months',
    result: '$1M+ Processed'
  },
  {
    id: '04',
    title: 'Delivery App',
    description: 'On-demand delivery platform with real-time tracking and routing.',
    icon: 'lucide:truck',
    platform: 'Mobile + Web',
    tech: ['Kotlin', 'Laravel', 'Google Maps'],
    duration: '6 months',
    result: '50K+ Orders'
  },
  {
    id: '05',
    title: 'CRM System',
    description: 'Enterprise CRM with FilamentPHP admin panel and automation.',
    icon: 'lucide:users',
    platform: 'Web',
    tech: ['Laravel', 'FilamentPHP', 'Livewire'],
    duration: '4 months',
    result: '95% Efficiency'
  },
  {
    id: '06',
    title: 'Learning Platform',
    description: 'Interactive e-learning platform with video streaming and assessments.',
    icon: 'lucide:graduation-cap',
    platform: 'Web + Mobile',
    tech: ['Nuxt', 'Laravel', 'Android'],
    duration: '7 months',
    result: '20K+ Students'
  }
]
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>