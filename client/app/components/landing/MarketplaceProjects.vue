<script setup lang="ts">
import { onMounted, onUnmounted, ref, inject, nextTick } from 'vue'
import type { Product } from '~/types/api'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const sectionRef = ref<HTMLElement | null>(null)
const currency = inject('currency', ref('USD'))
const productsResponse = ref<Product[]>([])
const pending = ref(false)
const fetchError = ref<Error | null>(null)
let ctx: gsap.Context | null = null
const { getProducts } = useApi()

const formatPrice = (price: number) => {
  if (price === 0) return 'Free'
  return currency.value === 'USD' ? `$${price}` : `\u20B9${(price * 82).toFixed(0)}`
}

const loadProducts = async () => {
  pending.value = true
  fetchError.value = null
  try {
    const response = await getProducts({ featured: true, per_page: 6 }) as any
    const items = response?.data ?? []
    productsResponse.value = items
  } catch (error) {
    fetchError.value = error as Error
    productsResponse.value = []
  } finally {
    pending.value = false
  }
}

const initAnimations = () => {
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    // Header
    gsap.from('.marketplace-header', {
      y: 50, opacity: 0, duration: 0.9, ease: 'power3.out',
      scrollTrigger: { trigger: '.marketplace-header', start: 'top 85%' },
    })

    gsap.from('.marketplace-header .line-technical', {
      scaleX: 0, transformOrigin: 'left center', duration: 1.2, ease: 'power2.out',
      scrollTrigger: { trigger: '.marketplace-header .line-technical', start: 'top 90%' },
    })

    // Product cards - "dealt cards" effect
    const cards = gsap.utils.toArray('.product-card') as HTMLElement[]
    cards.forEach((card, i) => {
      gsap.from(card, {
        y: 80,
        opacity: 0,
        scale: 0.9,
        rotateZ: i % 2 === 0 ? -3 : 3,
        duration: 0.8,
        delay: i * 0.08,
        ease: 'back.out(1.3)',
        scrollTrigger: { trigger: card, start: 'top 90%' },
      })

      // Stats slide in
      const stats = card.querySelector('.product-stats')
      if (stats) {
        gsap.from(stats, {
          x: 30, opacity: 0, duration: 0.5, delay: 0.3,
          ease: 'power2.out',
          scrollTrigger: { trigger: card, start: 'top 85%' },
        })
      }
    })

    // Knowledge CTA
    gsap.from('.knowledge-cta', {
      y: 60, opacity: 0, scale: 0.95, duration: 1, ease: 'power3.out',
      scrollTrigger: { trigger: '.knowledge-cta', start: 'top 85%' },
    })
  }, sectionRef.value)
}

onMounted(async () => {
  await loadProducts()
  nextTick(() => { initAnimations() })
})

onUnmounted(() => { ctx?.revert() })
</script>

<template>
  <section id="marketplace" ref="sectionRef" class="py-20 lg:py-32 relative overflow-hidden bg-titanium-50 dark:bg-titanium-900">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="marketplace-header max-w-3xl mx-auto text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-blueprint-100 dark:bg-blueprint-900/30 text-blueprint-700 dark:text-blueprint-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
            Products
          </span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-black mb-6 text-titanium-900 dark:text-white">
          Ready-Made
          <span class="text-mintreu-red-600">Solutions</span>
        </h2>
        <p class="text-lg md:text-xl text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">
          Production-ready templates and tools to accelerate your development
        </p>
        <div class="line-technical mt-8 mx-auto max-w-md"></div>
      </div>

      <!-- Loading State -->
      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        <div v-for="i in 6" :key="i" class="bg-white dark:bg-titanium-900 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 shadow-xl animate-pulse">
          <div class="h-48 bg-titanium-200 dark:bg-titanium-800"></div>
          <div class="p-6 space-y-3">
            <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded"></div>
            <div class="h-4 bg-titanium-200 dark:bg-titanium-800 rounded w-3/4"></div>
            <div class="h-8 bg-titanium-200 dark:bg-titanium-800 rounded w-1/2"></div>
          </div>
        </div>
      </div>

      <!-- Products Grid -->
      <div v-else-if="productsResponse.length > 0" class="perspective-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        <NuxtLink
          v-for="product in productsResponse"
          :key="product.slug"
          :to="`/products/${product.slug}`"
          class="product-card group relative bg-white dark:bg-titanium-900 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 dark:hover:border-mintreu-red-600/50 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
        >
          <div class="relative">
            <!-- Product Image -->
            <div class="relative h-48 bg-titanium-800 flex items-center justify-center overflow-hidden">
              <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>
              <img v-if="product.image" :src="product.image" :alt="product.title"
                class="relative w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
              <Icon v-else name="lucide:package" class="relative w-20 h-20 text-titanium-600 group-hover:text-mintreu-red-600/60 group-hover:scale-110 transition-all duration-500" />

              <!-- Category Badge -->
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1.5 bg-titanium-900/60 backdrop-blur-xl border border-titanium-600 rounded-full text-white text-xs font-heading font-bold">
                  {{ product.category }}
                </span>
              </div>

              <!-- Type Badge -->
              <div class="absolute top-4 left-4">
                <span
                  class="px-3 py-1.5 backdrop-blur-xl border rounded-full text-white text-xs font-heading font-bold"
                  :class="{
                    'bg-green-600/80 border-green-500/30': product.price === 0,
                    'bg-mintreu-red-600/80 border-mintreu-red-500/30': ['api_service', 'api_referral'].includes(product.type),
                    'bg-blueprint-600/80 border-blueprint-500/30': ['downloadable', 'demo'].includes(product.type)
                  }"
                >
                  {{ product.price === 0 ? 'FREE' : (product.type || 'Product') }}
                </span>
              </div>
            </div>

            <div class="p-6">
              <h3 class="text-xl font-heading font-bold mb-2 text-titanium-900 dark:text-white group-hover:text-mintreu-red-600 transition-colors">
                {{ product.title }}
              </h3>
              <p class="text-sm text-titanium-600 dark:text-titanium-400 mb-6 line-clamp-2 leading-relaxed font-subheading">
                {{ product.description }}
              </p>

              <!-- Stats -->
              <div class="product-stats flex items-center gap-4 mb-4 text-xs text-titanium-500 font-subheading">
                <div class="flex items-center gap-1">
                  <Icon name="lucide:download" class="w-4 h-4" />
                  <span>{{ product.downloads }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <Icon name="lucide:star" class="w-4 h-4 text-amber-500" />
                  <span>{{ product.rating }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <Icon name="lucide:package" class="w-4 h-4" />
                  <span>v{{ product.version }}</span>
                </div>
              </div>

              <div class="flex items-center justify-between pt-4 border-t border-titanium-200 dark:border-titanium-800">
                <div>
                  <div class="text-2xl font-heading font-black text-titanium-900 dark:text-white">
                    {{ formatPrice(product.price) }}
                  </div>
                  <div v-if="['api_service', 'api_referral'].includes(product.type)" class="text-xs text-titanium-500 font-subheading">/month</div>
                  <div v-else-if="product.price > 0" class="text-xs text-titanium-500 font-subheading">one-time</div>
                </div>
                <button class="px-5 py-2.5 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-xl text-sm font-heading font-bold shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-300">
                  {{ product.price === 0 ? 'Download' : 'Get Now' }}
                </button>
              </div>
            </div>
          </div>
        </NuxtLink>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <Icon name="lucide:package" class="w-16 h-16 mx-auto mb-4 text-titanium-400" />
        <p class="text-titanium-600 dark:text-titanium-400 font-subheading">No products available at the moment.</p>
      </div>

      <!-- CTA Button -->
      <div class="flex justify-center items-center w-full">
        <NuxtLink
          to="/products"
          class="px-8 py-4 my-8 w-full md:w-auto rounded-2xl bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white text-center font-heading font-bold shadow-lg glow-red hover:shadow-xl transform hover:scale-105 transition-all duration-300"
        >
          Visit Marketplace
        </NuxtLink>
      </div>

      <!-- Knowledge Base CTA -->
      <div class="knowledge-cta mt-20 relative overflow-hidden rounded-3xl">
        <div class="absolute inset-0 bg-titanium-900"></div>
        <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>

        <div class="relative border border-dashed border-titanium-700 rounded-3xl p-12 lg:p-16 text-center">
          <!-- Corner marks -->
          <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-mintreu-red-600 rounded-tl-xl"></div>
          <div class="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 border-mintreu-red-600 rounded-tr-xl"></div>
          <div class="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 border-mintreu-red-600 rounded-bl-xl"></div>
          <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-mintreu-red-600 rounded-br-xl"></div>

          <div class="relative w-20 h-20 mx-auto mb-8 bg-titanium-800 rounded-2xl flex items-center justify-center border border-titanium-700 shadow-2xl">
            <Icon name="lucide:book-open" class="w-10 h-10 text-mintreu-red-600" />
          </div>

          <h3 class="text-3xl md:text-4xl lg:text-5xl font-heading font-black text-white mb-6">
            Knowledge Base & Resources
          </h3>

          <p class="text-lg md:text-xl text-titanium-400 mb-10 max-w-2xl mx-auto leading-relaxed font-subheading">
            Access curated tutorials, code snippets, best practices, and technical guides. Learn from real-world experience.
          </p>

          <NuxtLink
            to="/insights"
            class="group inline-flex items-center space-x-2 px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white text-lg font-heading font-bold rounded-xl shadow-2xl glow-red transform hover:scale-105 hover:-translate-y-1 active:scale-95 transition-all duration-300"
          >
            <span>Explore Resources</span>
            <Icon name="lucide:arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
          </NuxtLink>
        </div>
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
