<script setup lang="ts">
import type { Product } from '~/types/api'

const { getProducts } = useApi()

// Get currency from layout
const currency = inject('currency', ref('USD'))

// Fetch featured products from API
const { data: productsData, pending, error } = await useAsyncData(
  'featured-products',
  () => getProducts({ featured: true, per_page: 6 })
)

const products = computed(() => productsData.value?.data || [])

// Format price based on currency
const formatPrice = (price: number) => {
  if (price === 0) return 'Free'
  return currency.value === 'USD' ? `$${price}` : `₹${(price * 82).toFixed(0)}`
}
</script>

<template>
  <!-- Marketplace Section -->
  <section id="marketplace" class="py-20 lg:py-32 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl mx-auto text-center mb-16">
        <div class="inline-block mb-4">
          <span class="px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-sm font-semibold">
            Products
          </span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black mb-6 text-gray-900 dark:text-white">
          Ready-Made
          <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 dark:from-blue-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
            Solutions
          </span>
        </h2>
        <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 leading-relaxed">
          Production-ready templates and tools to accelerate your development
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        <div v-for="i in 6" :key="i" class="bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-xl animate-pulse">
          <div class="h-48 bg-gray-300 dark:bg-gray-700"></div>
          <div class="p-6 space-y-3">
            <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded"></div>
            <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-3/4"></div>
            <div class="h-8 bg-gray-300 dark:bg-gray-700 rounded w-1/2"></div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <div class="inline-flex items-center space-x-2 px-6 py-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg">
          <Icon name="lucide:alert-circle" class="w-5 h-5" />
          <span>Failed to load products. Please try again later.</span>
        </div>
      </div>

      <!-- Products Grid -->
      <div v-else-if="products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        <NuxtLink
          v-for="(product, index) in products"
          :key="product.slug"
          :to="`/products/${product.slug}`"
          class="group relative bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-transparent shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
          :style="{ animationDelay: `${index * 100}ms` }"
        >
          <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity rounded-3xl"></div>
          <div class="absolute inset-[1px] bg-white dark:bg-gray-900 rounded-3xl"></div>

          <div class="relative">
            <div class="relative h-48 bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 flex items-center justify-center overflow-hidden">
              <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
              <img
                v-if="product.image"
                :src="product.image"
                :alt="product.title"
                class="relative w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
              />
              <Icon v-else name="lucide:package" class="relative w-20 h-20 text-white/40 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500" />

              <div class="absolute top-4 right-4">
                <span class="px-3 py-1.5 bg-white/20 backdrop-blur-xl border border-white/30 rounded-full text-white text-xs font-bold shadow-lg">
                  {{ product.category }}
                </span>
              </div>

              <!-- Type Badge -->
              <div class="absolute top-4 left-4">
                <span
                  class="px-3 py-1.5 backdrop-blur-xl border rounded-full text-white text-xs font-bold shadow-lg"
                  :class="{
                    'bg-green-500/80 border-green-400/30': product.price === 0,
                    'bg-purple-500/80 border-purple-400/30': product.type === 'api',
                    'bg-blue-500/80 border-blue-400/30': product.type === 'template' || product.type === 'plugin'
                  }"
                >
                  {{ product.price === 0 ? 'FREE' : (product.type || 'Product') }}
                </span>
              </div>
            </div>

            <div class="p-6">
              <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                {{ product.title }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 line-clamp-2 leading-relaxed">
                {{ product.description }}
              </p>

              <!-- Stats -->
              <div class="flex items-center gap-4 mb-4 text-xs text-gray-500">
                <div class="flex items-center gap-1">
                  <Icon name="lucide:download" class="w-4 h-4" />
                  <span>{{ product.downloads }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <Icon name="lucide:star" class="w-4 h-4 text-yellow-500" />
                  <span>{{ product.rating }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <Icon name="lucide:package" class="w-4 h-4" />
                  <span>v{{ product.version }}</span>
                </div>
              </div>

              <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-800">
                <div>
                  <div class="text-2xl font-black bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                    {{ formatPrice(product.price) }}
                  </div>
                  <div v-if="product.price > 0" class="text-xs text-gray-500 dark:text-gray-500 font-medium">
                    one-time
                  </div>
                </div>
                <button class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-300">
                  {{ product.price === 0 ? 'Download' : 'Get Now' }}
                </button>
              </div>
            </div>
          </div>
        </NuxtLink>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <Icon name="lucide:package" class="w-16 h-16 mx-auto mb-4 text-gray-400" />
        <p class="text-gray-600 dark:text-gray-400">No products available at the moment.</p>
      </div>

      <!-- CTA Button -->
      <div class="flex justify-center items-center w-full">
        <NuxtLink
          to="/products"
          class="px-8 py-4 my-6 w-full md:w-auto rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-center font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
        >
          Visit Marketplace
        </NuxtLink>
      </div>

      <!-- Knowledge Base CTA -->
      <div class="mt-20 relative overflow-hidden rounded-3xl">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

        <div class="relative bg-white/5 backdrop-blur-sm border border-white/20 rounded-3xl p-12 lg:p-16 text-center">
          <div class="relative w-20 h-20 mx-auto mb-8 bg-white/10 backdrop-blur-xl rounded-2xl flex items-center justify-center border border-white/20 shadow-2xl">
            <Icon name="lucide:book-open" class="w-10 h-10 text-white" />
          </div>

          <h3 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-6">
            Knowledge Base & Resources
          </h3>

          <p class="text-lg md:text-xl text-white/90 mb-10 max-w-2xl mx-auto leading-relaxed">
            Access curated tutorials, code snippets, best practices, and technical guides. Learn from real-world experience.
          </p>

          <NuxtLink
            to="/insights"
            class="group inline-flex items-center space-x-2 px-8 py-4 bg-white hover:bg-gray-50 text-gray-900 text-lg font-bold rounded-xl shadow-2xl transform hover:scale-105 hover:-translate-y-1 active:scale-95 transition-all duration-300"
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

.bg-grid-pattern {
  background-image:
    linear-gradient(to right, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
  background-size: 20px 20px;
}
</style>
