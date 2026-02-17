<script setup lang="ts">
import { onMounted, onUnmounted, ref, nextTick } from 'vue'
import type { Project } from '~/types/api'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const sectionRef = ref<HTMLElement | null>(null)
const projects = ref<Project[]>([])
const pending = ref(false)
const error = ref<Error | null>(null)
let ctx: gsap.Context | null = null
const { getProjects } = useApi()

const showcaseProjects: Project[] = [
  {
    slug: 'ecommerce-platform',
    title: 'E-Commerce Platform',
    description: 'Full-stack marketplace with real-time inventory, Stripe payments, and AI-powered product recommendations. Handles 10K+ concurrent users.',
    image: '',
    category: 'Web Application',
    technologies: ['Laravel', 'Nuxt.js', 'Redis', 'Stripe'],
    status: 'published',
    featured: true,
    created_at: '2025-08-15T00:00:00Z',
    updated_at: '2025-08-15T00:00:00Z',
    content: '',
    id: 0,
    live_url: '#',
  },
  {
    slug: 'healthcare-management',
    title: 'Healthcare Management System',
    description: 'HIPAA-compliant patient portal with appointment scheduling, telemedicine integration, and real-time health monitoring dashboards.',
    image: '',
    category: 'Healthcare',
    technologies: ['React', 'Node.js', 'PostgreSQL', 'Docker'],
    status: 'published',
    featured: true,
    created_at: '2025-06-20T00:00:00Z',
    updated_at: '2025-06-20T00:00:00Z',
    content: '',
    id: 0,
  },
  {
    slug: 'fintech-mobile-app',
    title: 'FinTech Mobile App',
    description: 'Native Android banking app with biometric authentication, real-time transaction tracking, and investment portfolio management.',
    image: '',
    category: 'Mobile',
    technologies: ['Kotlin', 'Firebase', 'TensorFlow', 'Razorpay'],
    status: 'published',
    featured: true,
    created_at: '2025-04-10T00:00:00Z',
    updated_at: '2025-04-10T00:00:00Z',
    content: '',
    id: 0,
  },
  {
    slug: 'real-estate-portal',
    title: 'Real Estate Portal',
    description: 'Property listing platform with interactive 3D virtual tours, AI-powered price predictions, and automated document processing.',
    image: '',
    category: 'Web Application',
    technologies: ['Laravel', 'Vue.js', 'Three.js', 'MySQL'],
    status: 'published',
    featured: true,
    created_at: '2025-02-28T00:00:00Z',
    updated_at: '2025-02-28T00:00:00Z',
    content: '',
    id: 0,
  },
  {
    slug: 'ai-analytics-dashboard',
    title: 'AI Analytics Dashboard',
    description: 'Enterprise analytics platform processing 50M+ events daily with predictive insights, custom reporting, and real-time anomaly detection.',
    image: '',
    category: 'AI/ML',
    technologies: ['Python', 'Next.js', 'TensorFlow', 'MongoDB'],
    status: 'published',
    featured: true,
    created_at: '2025-01-15T00:00:00Z',
    updated_at: '2025-01-15T00:00:00Z',
    content: '',
    id: 0,
  },
  {
    slug: 'desktop-inventory-system',
    title: 'Cross-Platform Desktop App',
    description: 'Inventory management system for warehouses with barcode scanning, real-time sync, and offline-first architecture for Windows, macOS, and Linux.',
    image: '',
    category: 'Desktop',
    technologies: ['Electron', 'React', 'SQLite', 'C++'],
    status: 'published',
    featured: true,
    created_at: '2024-11-05T00:00:00Z',
    updated_at: '2024-11-05T00:00:00Z',
    content: '',
    id: 0,
  },
]

const loadProjects = async () => {
  pending.value = true
  error.value = null
  try {
    const response = await getProjects({ featured: true, per_page: 6 }) as any
    const items = response?.data ?? []
    projects.value = items.length ? items : showcaseProjects
  } catch (err) {
    error.value = null
    projects.value = showcaseProjects
  } finally {
    pending.value = false
  }
}

const initAnimations = () => {
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    // Header entrance
    gsap.from('.projects-header', {
      y: 50, opacity: 0, duration: 0.9, ease: 'power3.out',
      scrollTrigger: { trigger: '.projects-header', start: 'top 85%' },
    })

    // Line reveal
    gsap.from('.projects-header .line-technical', {
      scaleX: 0, transformOrigin: 'left center', duration: 1.2, ease: 'power2.out',
      scrollTrigger: { trigger: '.projects-header .line-technical', start: 'top 90%' },
    })

    // Cards - 3D perspective entrance with stagger
    const cards = gsap.utils.toArray('.project-card') as HTMLElement[]
    cards.forEach((card, i) => {
      const fromX = i % 3 === 0 ? -40 : i % 3 === 2 ? 40 : 0
      const fromY = 60

      gsap.from(card, {
        x: fromX,
        y: fromY,
        opacity: 0,
        rotateY: i % 2 === 0 ? -8 : 8,
        scale: 0.92,
        duration: 0.9,
        delay: i * 0.1,
        ease: 'back.out(1.4)',
        scrollTrigger: { trigger: card, start: 'top 90%' },
      })

      // Tech tags cascade after card enters
      const tags = card.querySelectorAll('.tech-tag')
      if (tags.length) {
        gsap.from(tags, {
          y: 15, opacity: 0, scale: 0.8,
          duration: 0.4, stagger: 0.05, ease: 'power2.out',
          scrollTrigger: { trigger: card, start: 'top 80%' },
        })
      }
    })
  }, sectionRef.value)
}

onMounted(async () => {
  await loadProjects()
  nextTick(() => { initAnimations() })
})

onUnmounted(() => { ctx?.revert() })
</script>

<template>
  <section id="projects" ref="sectionRef" class="py-20 lg:py-32 relative overflow-hidden bg-titanium-50 dark:bg-titanium-900">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="projects-header max-w-3xl mx-auto text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
            Portfolio
          </span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-black mb-6 text-titanium-900 dark:text-white">
          Featured
          <span class="text-mintreu-red-600">Projects</span>
        </h2>
        <p class="text-lg md:text-xl text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">
          Real-world applications delivered across web, mobile, and desktop platforms
        </p>
        <div class="line-technical mt-8 mx-auto max-w-md"></div>
      </div>

      <!-- Loading State -->
      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="i in 6" :key="i" class="bg-white dark:bg-titanium-900 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 shadow-xl animate-pulse">
          <div class="h-56 bg-titanium-200 dark:bg-titanium-800"></div>
          <div class="p-6 space-y-3">
            <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded"></div>
            <div class="h-4 bg-titanium-200 dark:bg-titanium-800 rounded w-3/4"></div>
            <div class="flex gap-2">
              <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded w-16"></div>
              <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded w-16"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Projects Grid -->
      <div v-else-if="projects.length > 0" class="perspective-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <NuxtLink
          v-for="project in projects"
          :key="project.slug"
          :to="`/work/${project.slug}`"
          class="project-card group relative bg-white dark:bg-titanium-900 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 dark:hover:border-mintreu-red-600/50 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
        >
          <!-- Project Image / Placeholder -->
          <div class="relative h-56 bg-titanium-800 overflow-hidden">
            <img v-if="project.image" :src="project.image" :alt="project.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
            <div v-else class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-titanium-800 via-titanium-900 to-titanium-950">
              <div class="relative">
                <div class="absolute inset-0 bg-blueprint opacity-30"></div>
                <Icon name="lucide:folder-open" class="relative w-20 h-20 text-titanium-600 group-hover:text-mintreu-red-600/60 transition-colors duration-500" />
              </div>
            </div>
            <!-- Hover overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
              <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between">
                <span class="text-white text-sm font-heading font-semibold">View Project</span>
                <Icon name="lucide:arrow-right" class="w-5 h-5 text-white transform group-hover:translate-x-1 transition-transform" />
              </div>
            </div>
            <!-- Category Badge -->
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 bg-titanium-900/60 backdrop-blur-sm border border-titanium-600 rounded-full text-white text-xs font-heading font-bold">
                {{ project.category }}
              </span>
            </div>
            <div v-if="project.featured" class="absolute top-4 left-4">
              <span class="px-3 py-1 bg-mintreu-red-600/90 backdrop-blur-sm rounded-full text-white text-xs font-heading font-bold flex items-center gap-1">
                <Icon name="lucide:star" class="w-3 h-3" />
                Featured
              </span>
            </div>
          </div>

          <!-- Project Info -->
          <div class="p-6">
            <h3 class="text-xl font-heading font-bold mb-2 text-titanium-900 dark:text-white group-hover:text-mintreu-red-600 transition-colors">
              {{ project.title }}
            </h3>
            <p class="text-sm text-titanium-600 dark:text-titanium-400 mb-4 line-clamp-2 font-subheading">
              {{ project.description }}
            </p>
            <div v-if="project.technologies?.length" class="flex flex-wrap gap-2 mb-4">
              <span v-for="tech in project.technologies.slice(0, 4)" :key="tech"
                class="tech-tag px-2 py-1 bg-titanium-100 dark:bg-titanium-800 text-titanium-700 dark:text-titanium-300 rounded text-xs font-heading font-semibold">
                {{ tech }}
              </span>
              <span v-if="project.technologies.length > 4"
                class="tech-tag px-2 py-1 bg-titanium-100 dark:bg-titanium-800 text-titanium-600 rounded text-xs font-heading">
                +{{ project.technologies.length - 4 }}
              </span>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-titanium-200 dark:border-titanium-800">
              <div class="flex items-center space-x-1 text-xs text-titanium-500 font-subheading">
                <Icon name="lucide:calendar" class="w-4 h-4" />
                <span>{{ new Date(project.created_at).getFullYear() }}</span>
              </div>
              <span class="text-xs text-mintreu-red-600 font-heading font-semibold uppercase">{{ project.status }}</span>
            </div>
          </div>
        </NuxtLink>
      </div>

      <!-- Empty State (shouldn't reach due to showcase fallback) -->
      <div v-else class="text-center py-12">
        <Icon name="lucide:folder-open" class="w-16 h-16 mx-auto mb-4 text-titanium-400" />
        <p class="text-titanium-600 dark:text-titanium-400 font-subheading">No projects available at the moment.</p>
      </div>

      <!-- CTA Button -->
      <div class="w-full my-8 flex justify-center items-center">
        <NuxtLink
          to="/work"
          class="px-8 py-4 w-full md:w-auto rounded-2xl bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white text-center font-heading font-bold shadow-lg glow-red hover:shadow-xl transform hover:scale-105 transition-all duration-300"
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
