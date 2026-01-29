<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-16">
      <div class="inline-block mb-4">
        <span class="px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-sm font-semibold">
          {{ productType === 'api' ? 'APIs' : productType === 'template' ? 'Templates' : productType === 'freebie' ? 'Free' : 'Premium Solutions' }}
        </span>
      </div>
      <h1 class="text-4xl sm:text-5xl md:text-6xl font-black mb-6 text-gray-900 dark:text-white">
        {{ title }}
      </h1>
      <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
        {{ subtitle }}
      </p>
    </div>

    <!-- Quick Nav Tabs -->
    <div class="flex flex-wrap gap-2 justify-center mb-8">
      <NuxtLink
          to="/products"
          class="px-4 py-2 rounded-lg font-semibold text-sm transition-all"
          :class="!productType
          ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
          : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'"
      >
        <Icon name="lucide:grid-3x3" class="w-4 h-4 inline mr-1" />
        All
      </NuxtLink>
      <NuxtLink
          to="/products/apis"
          class="px-4 py-2 rounded-lg font-semibold text-sm transition-all"
          :class="productType === 'api'
          ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
          : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'"
      >
        <Icon name="lucide:server" class="w-4 h-4 inline mr-1" />
        APIs
      </NuxtLink>
      <NuxtLink
          to="/products/templates"
          class="px-4 py-2 rounded-lg font-semibold text-sm transition-all"
          :class="productType === 'template'
          ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
          : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'"
      >
        <Icon name="lucide:layout" class="w-4 h-4 inline mr-1" />
        Templates
      </NuxtLink>
      <NuxtLink
          to="/products/freebies"
          class="px-4 py-2 rounded-lg font-semibold text-sm transition-all"
          :class="productType === 'freebie'
          ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
          : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'"
      >
        <Icon name="lucide:gift" class="w-4 h-4 inline mr-1" />
        Free
      </NuxtLink>
    </div>

    <!-- Filters & Search -->
    <div class="mb-12">
      <div class="flex flex-col lg:flex-row gap-4 items-center justify-between mb-6">
        <!-- Search -->
        <div class="relative w-full lg:w-96">
          <Icon name="lucide:search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
          <input
              v-model="searchQuery"
              type="text"
              placeholder="Search products..."
              class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
        </div>

        <!-- Category & Sort -->
        <div class="flex gap-3 w-full lg:w-auto">
          <select
              v-model="activeCategory"
              class="px-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 flex-1 lg:flex-none"
          >
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>

          <select
              v-model="sortBy"
              class="px-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 flex-1 lg:flex-none"
          >
            <option value="latest">Latest First</option>
            <option value="popular">Most Popular</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
          </select>
        </div>
      </div>

      <!-- Category Pills -->
      <div class="flex flex-wrap gap-3">
        <button
            v-for="category in ['All', ...categories]"
            :key="category"
            @click="activeCategory = category === 'All' ? '' : category"
            class="px-6 py-2 rounded-lg font-semibold transition-all"
            :class="(category === 'All' && !activeCategory) || activeCategory === category
            ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
            : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'"
        >
          {{ category }}
        </button>
      </div>
    </div>

    <!-- Products Grid -->
    <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div v-for="n in 6" :key="n" class="bg-white dark:bg-gray-900 rounded-3xl h-96 animate-pulse"></div>
    </div>

    <div v-else-if="products?.data && products.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <NuxtLink
          v-for="product in products.data"
          :key="product.slug"
          :to="`/products/${product.slug}`"
          class="group relative bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-transparent shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
      >
        <!-- Gradient Border -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity rounded-3xl"></div>
        <div class="absolute inset-[1px] bg-white dark:bg-gray-900 rounded-3xl"></div>

        <div class="relative block">
          <!-- Product Image -->
          <div class="relative h-48 bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 flex items-center justify-center">
            <img
                v-if="product.image"
                :src="product.image"
                :alt="product.title"
                class="w-full h-full object-cover"
            />
            <Icon v-else name="lucide:package" class="w-20 h-20 text-white/40" />

            <!-- Type Badge -->
            <div class="absolute top-4 right-4">
              <span
                  class="px-3 py-1.5 backdrop-blur-xl border rounded-full text-xs font-bold"
                  :class="getTypeBadgeClass(product.type)"
              >
                {{ getTypeLabel(product.type) }}
              </span>
            </div>

            <!-- Free Badge -->
            <div v-if="product.price === 0" class="absolute top-4 left-4">
              <span class="px-3 py-1.5 bg-green-500 text-white rounded-full text-xs font-bold">
                FREE
              </span>
            </div>
          </div>

          <!-- Content -->
          <div class="p-6">
            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1">
              {{ product.title }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
              {{ product.description }}
            </p>

            <!-- Price & Stats -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
              <div class="flex items-center justify-between mb-3">
                <!-- Price -->
                <div v-if="product.type === 'api'" class="text-lg font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                  {{ currency === 'USD' ? `$${product.price}` : `₹${(product.price * 82).toFixed(0)}` }}<span class="text-xs">/mo</span>
                </div>
                <div v-else-if="product.price === 0" class="text-lg font-black text-green-600 dark:text-green-400">
                  FREE
                </div>
                <div v-else class="text-lg font-black text-gray-900 dark:text-white">
                  {{ currency === 'USD' ? `$${product.price}` : `₹${(product.price * 82).toFixed(0)}` }}
                </div>

                <!-- Downloads/Rating -->
                <div class="flex items-center space-x-3 text-xs text-gray-500">
                  <div v-if="product.downloads" class="flex items-center space-x-1">
                    <Icon name="lucide:download" class="w-4 h-4" />
                    <span>{{ product.downloads }}</span>
                  </div>
                  <div v-if="product.rating" class="flex items-center space-x-1">
                    <Icon name="lucide:star" class="w-4 h-4 text-yellow-500" />
                    <span>{{ product.rating }}</span>
                  </div>
                </div>
              </div>

              <!-- View Details Button -->
              <button
                  class="w-full px-4 py-2 rounded-lg font-bold text-sm shadow-lg hover:shadow-xl transform hover:scale-105 transition-all"
                  :class="getButtonClass(product.type)"
              >
                {{ getButtonText(product.type) }}
              </button>
            </div>
          </div>
        </div>
      </NuxtLink>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-20">
      <Icon name="lucide:package-open" class="w-20 h-20 text-gray-300 dark:text-gray-700 mx-auto mb-4" />
      <h3 class="text-xl font-bold mb-2">No Products Found</h3>
      <p class="text-gray-600 dark:text-gray-400">Try adjusting your filters</p>
    </div>

    <!-- Pagination -->
    <div v-if="products && products.meta && products.meta.last_page > 1" class="mt-12 flex justify-center">
      <nav class="flex items-center space-x-2">
        <button
            @click="page > 1 && page--"
            :disabled="page === 1"
            class="px-4 py-2 rounded-lg bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 disabled:opacity-50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
        >
          <Icon name="lucide:chevron-left" class="w-5 h-5" />
        </button>

        <button
            v-for="p in paginationRange"
            :key="p"
            @click="page = p"
            class="px-4 py-2 rounded-lg font-semibold transition-colors"
            :class="page === p
            ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white'
            : 'bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800'"
        >
          {{ p }}
        </button>

        <button
            @click="page < products.meta.last_page && page++"
            :disabled="page === products.meta.last_page"
            class="px-4 py-2 rounded-lg bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 disabled:opacity-50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
        >
          <Icon name="lucide:chevron-right" class="w-5 h-5" />
        </button>
      </nav>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { PaginatedResponse, Product } from '~/types/api'

interface Props {
  type?: string
  title: string
  subtitle: string
}

const props = defineProps<Props>()
const productType = computed(() => props.type || '')

const api = useApi()
const currency = inject('currency', ref('USD'))
const page = ref(1)
const searchQuery = ref('')
const activeCategory = ref('')
const sortBy = ref('latest')

const { data: rawCategories } = await useAsyncData('categories-products', () => api.getCategories({ type: 'products' }));\nconst categories = computed(() => ['All', ...(rawCategories.value || []).map(c => c.name)])

const { data: products, pending } = await useAsyncData<PaginatedResponse<Product>>(
  computed(() => `products-${productType.value}-${page.value}-${searchQuery.value}-${activeCategory.value}-${sortBy.value}`),
  () => api.getProducts({
    page: page.value,
    search: searchQuery.value || undefined,
    category: activeCategory.value || undefined,
    type: productType.value || undefined,
    sort: sortBy.value,
    per_page: 12
  }),
  { watch: [page, searchQuery, activeCategory, sortBy] }
)

const paginationRange = computed(() => {
  if (!products.value?.meta) return []
  const total = products.value.meta.last_page
  const current = page.value
  const range = []
  for (let i = Math.max(1, current - 2); i <= Math.min(total, current + 2); i++) {
    range.push(i)
  }
  return range
})

const getTypeBadgeClass = (type: string) => {
  if (type === 'api') return 'bg-purple-500/20 border-purple-300/30 text-white'
  if (type === 'template' || type === 'plugin') return 'bg-blue-500/20 border-blue-300/30 text-white'
  if (type === 'freebie') return 'bg-green-500/20 border-green-300/30 text-white'
  if (type === 'media') return 'bg-pink-500/20 border-pink-300/30 text-white'
  return 'bg-white/20 border-white/30 text-white'
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

const getButtonClass = (type: string) => {
  if (type === 'api') return 'bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white'
  return 'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white'
}

const getButtonText = (type: string) => {
  if (type === 'api') return 'Start Free Trial'
  if (type === 'freebie') return 'Download Free'
  return 'View Details'
}
</script>
