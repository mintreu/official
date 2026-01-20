<template>
  <div class="min-h-screen bg-white dark:bg-gray-950 py-8">
    <!-- Breadcrumb -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
      <nav class="flex items-center space-x-2 text-sm">
        <NuxtLink to="/" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Home
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <NuxtLink to="/products" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Products
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <span class="text-gray-900 dark:text-white font-medium truncate">{{ product?.title || 'Loading...' }}</span>
      </nav>
    </div>

    <div v-if="pending" class="max-w-6xl mx-auto px-4 animate-pulse">
      <div class="h-96 bg-gray-200 dark:bg-gray-800 rounded-3xl mb-8"></div>
      <div class="h-12 bg-gray-200 dark:bg-gray-800 rounded mb-4"></div>
    </div>

    <div v-else-if="product" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Back Button -->
      <NuxtLink to="/products" class="inline-flex items-center space-x-2 text-blue-600 dark:text-blue-400 hover:underline mb-8">
        <Icon name="lucide:arrow-left" class="w-4 h-4" />
        <span>Back to Products</span>
      </NuxtLink>

      <div class="grid lg:grid-cols-2 gap-12">
        <!-- Left: Product Image -->
        <div>
          <div class="sticky top-24">
            <div class="relative h-96 bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 rounded-3xl flex items-center justify-center mb-6 overflow-hidden">
              <img
                  v-if="product.image"
                  :src="product.image"
                  :alt="product.title"
                  class="w-full h-full object-cover"
              />
              <Icon v-else name="lucide:package" class="w-32 h-32 text-white/30" />

              <!-- Badges -->
              <div v-if="product.price === 0" class="absolute top-6 left-6">
                <span class="px-4 py-2 bg-green-500 text-white rounded-full text-sm font-bold">FREE</span>
              </div>
              <div class="absolute top-6 right-6">
                <span class="px-4 py-2 bg-white/20 backdrop-blur-xl border border-white/30 rounded-full text-white text-sm font-bold">
                  {{ getTypeLabel(product.type) }}
                </span>
              </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-4">
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                <Icon name="lucide:download" class="w-6 h-6 mx-auto mb-2 text-blue-600" />
                <div class="text-sm font-semibold">{{ product.downloads || 0 }}</div>
                <div class="text-xs text-gray-500">Downloads</div>
              </div>
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                <Icon name="lucide:star" class="w-6 h-6 mx-auto mb-2 text-yellow-500" />
                <div class="text-sm font-semibold">{{ product.rating || 4.8 }}/5</div>
                <div class="text-xs text-gray-500">Rating</div>
              </div>
              <div class="text-center p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                <Icon name="lucide:tag" class="w-6 h-6 mx-auto mb-2 text-purple-600" />
                <div class="text-sm font-semibold">v{{ product.version || '1.0' }}</div>
                <div class="text-xs text-gray-500">Version</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Product Details -->
        <div>
          <!-- Category Badge -->
          <div class="inline-block px-4 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-sm font-bold mb-4">
            {{ product.category || 'Product' }}
          </div>

          <h1 class="text-4xl sm:text-5xl font-black mb-4 text-gray-900 dark:text-white">
            {{ product.title }}
          </h1>

          <p class="text-xl text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
            {{ product.description }}
          </p>

          <!-- Content -->
          <div v-if="product.content" class="mb-8 prose dark:prose-invert max-w-none" v-html="product.content"></div>

          <!-- Pricing Card -->
          <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-900 dark:to-gray-800 rounded-2xl p-8 mb-8 border-2 border-blue-100 dark:border-gray-800">
            <div class="flex items-baseline space-x-2 mb-2">
              <span class="text-5xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                {{ formatPrice(product.price) }}
              </span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
              {{ product.price === 0 ? '100% Free - No signup required' : 'One-time purchase - Lifetime updates' }}
            </p>

            <button class="w-full px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
              {{ product.price === 0 ? 'Download Free' : 'Purchase Now' }}
            </button>
          </div>

          <!-- Action Links -->
          <div class="flex flex-wrap gap-4 mb-8">
            <a v-if="product.demo_url" :href="product.demo_url" target="_blank" class="inline-flex items-center space-x-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold">
              <Icon name="lucide:external-link" class="w-5 h-5" />
              <span>View Demo</span>
            </a>
            <a v-if="product.github_url" :href="product.github_url" target="_blank" class="inline-flex items-center space-x-2 px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-semibold">
              <Icon name="lucide:github" class="w-5 h-5" />
              <span>View on GitHub</span>
            </a>
            <a v-if="product.documentation_url" :href="product.documentation_url" target="_blank" class="inline-flex items-center space-x-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold">
              <Icon name="lucide:book-open" class="w-5 h-5" />
              <span>Documentation</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Related Products - Slider Style -->
      <div v-if="relatedProducts && relatedProducts.length > 0" class="mt-16">
        <h2 class="text-3xl font-bold mb-8 flex items-center">
          <Icon name="lucide:shopping-bag" class="w-8 h-8 text-blue-500 mr-3" />
          Related Products
        </h2>
        <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide" style="scroll-snap-type: x mandatory">
          <NuxtLink
              v-for="related in relatedProducts"
              :key="related.slug"
              :to="`/products/${related.slug}`"
              class="flex-shrink-0 w-72 group bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-blue-500 dark:hover:border-blue-500 shadow-lg hover:shadow-xl transition-all duration-500"
              style="scroll-snap-align: start"
          >
            <div class="relative h-40 bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 flex-shrink-0">
              <img
                  v-if="related.image"
                  :src="related.image"
                  :alt="related.title"
                  class="w-full h-full object-cover"
              />
              <Icon v-else name="lucide:package" class="w-16 h-16 text-white/30 absolute inset-0 m-auto" />
              <div v-if="related.price === 0" class="absolute top-3 left-3">
                <span class="px-2 py-1 bg-green-500 text-white rounded text-xs font-bold">FREE</span>
              </div>
            </div>
            <div class="p-5 flex-shrink-0">
              <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1">
                {{ related.title }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                {{ related.description }}
              </p>
              <div class="flex items-center justify-between mt-3">
                <span class="font-black text-lg" :class="related.price === 0 ? 'text-green-600' : 'text-gray-900 dark:text-white'">
                  {{ formatPrice(related.price) }}
                </span>
                <span class="text-xs text-blue-600 dark:text-blue-400 font-semibold">
                  View Details
                </span>
              </div>
            </div>
          </NuxtLink>
        </div>
      </div>
    </div>

    <!-- 404 State -->
    <div v-if="!product && !pending" class="max-w-6xl mx-auto px-4 text-center py-20">
      <Icon name="lucide:alert-circle" class="w-20 h-20 text-red-500 mx-auto mb-4" />
      <h1 class="text-3xl font-bold mb-2">Product Not Found</h1>
      <p class="text-gray-600 dark:text-gray-400 mb-4">The product you're looking for doesn't exist or has been removed.</p>
      <NuxtLink to="/products" class="text-blue-600 hover:underline">Back to Products</NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Product, ApiResponse } from '~/types/api'

const route = useRoute()
const { getProduct } = useApi()

const { data: response, pending } = await useAsyncData(
  `product-${route.params.slug}`,
  () => getProduct(route.params.slug as string)
)

const product = computed(() => response.value?.data)
const relatedProducts = computed(() => response.value?.related || [])

// Get currency from layout
const currency = inject('currency', ref('USD'))

// Format price based on currency
const formatPrice = (price: number) => {
  if (price === 0) return 'Free'
  if (currency.value === 'USD') return `$${price}`
  return `₹${(price * 82).toFixed(0)}`
}

const getTypeLabel = (type: string) => {
  const labels: Record<string, string> = {
    api: 'API',
    template: 'Template',
    plugin: 'Plugin',
    freebie: 'Free',
    media: 'Media'
  }
  return labels[type] || type || 'Product'
}

watchEffect(() => {
  if (product.value) {
    useSeoMeta({
      title: `${product.value.title} | Mintreu Products`,
      description: product.value.description
    })
  }
})
</script>
