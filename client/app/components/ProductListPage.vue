<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div ref="sectionRef" class="product-header text-center mb-16">
      <div class="inline-block mb-4">
        <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
          {{ productType === 'api' ? 'APIs' : productType === 'template' ? 'Templates' : productType === 'freebie' ? 'Free' : 'Premium Solutions' }}
        </span>
      </div>
      <h1 class="text-4xl sm:text-5xl md:text-6xl font-heading font-black mb-6 text-titanium-900 dark:text-white">
        {{ title }}
      </h1>
      <p class="text-lg text-titanium-600 dark:text-titanium-400 max-w-2xl mx-auto font-subheading">
        {{ subtitle }}
      </p>
      <div class="line-technical mt-8 mx-auto max-w-md"></div>
    </div>

    <!-- Quick Nav Tabs -->
    <div class="flex flex-wrap gap-2 justify-center mb-8">
      <NuxtLink
        to="/products"
        class="px-4 py-2 rounded-xl font-heading font-bold text-sm transition-all duration-300"
        :class="!productType
          ? 'bg-mintreu-red-600 text-white shadow-lg glow-red'
          : 'bg-white dark:bg-titanium-900 text-titanium-700 dark:text-titanium-300 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50'"
      >
        <Icon name="lucide:grid-3x3" class="w-4 h-4 inline mr-1" />
        All
      </NuxtLink>
      <NuxtLink
        to="/products/apis"
        class="px-4 py-2 rounded-xl font-heading font-bold text-sm transition-all duration-300"
        :class="productType === 'api'
          ? 'bg-mintreu-red-600 text-white shadow-lg glow-red'
          : 'bg-white dark:bg-titanium-900 text-titanium-700 dark:text-titanium-300 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50'"
      >
        <Icon name="lucide:server" class="w-4 h-4 inline mr-1" />
        APIs
      </NuxtLink>
      <NuxtLink
        to="/products/templates"
        class="px-4 py-2 rounded-xl font-heading font-bold text-sm transition-all duration-300"
        :class="productType === 'template'
          ? 'bg-mintreu-red-600 text-white shadow-lg glow-red'
          : 'bg-white dark:bg-titanium-900 text-titanium-700 dark:text-titanium-300 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50'"
      >
        <Icon name="lucide:layout" class="w-4 h-4 inline mr-1" />
        Templates
      </NuxtLink>
      <NuxtLink
        to="/products/freebies"
        class="px-4 py-2 rounded-xl font-heading font-bold text-sm transition-all duration-300"
        :class="productType === 'freebie'
          ? 'bg-mintreu-red-600 text-white shadow-lg glow-red'
          : 'bg-white dark:bg-titanium-900 text-titanium-700 dark:text-titanium-300 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50'"
      >
        <Icon name="lucide:gift" class="w-4 h-4 inline mr-1" />
        Free
      </NuxtLink>
    </div>

    <!-- Filters & Search -->
    <div class="mb-12">
      <div class="flex flex-col lg:flex-row gap-4 items-center justify-between mb-6">
        <div class="relative w-full lg:w-96">
          <Icon name="lucide:search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-titanium-400" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search products..."
            class="w-full pl-12 pr-4 py-3 bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 focus:border-mintreu-red-500 font-subheading text-titanium-900 dark:text-white placeholder-titanium-400"
          />
        </div>

        <div class="flex gap-3 w-full lg:w-auto">
          <select
            v-model="activeCategory"
            class="px-4 py-3 bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 font-subheading text-titanium-900 dark:text-white flex-1 lg:flex-none"
          >
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>

          <select
            v-model="sortBy"
            class="px-4 py-3 bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 rounded-xl focus:ring-2 focus:ring-mintreu-red-500 font-subheading text-titanium-900 dark:text-white flex-1 lg:flex-none"
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
          class="px-6 py-2 rounded-xl font-heading font-bold text-sm transition-all duration-300"
          :class="(category === 'All' && !activeCategory) || activeCategory === category
            ? 'bg-mintreu-red-600 text-white shadow-lg glow-red'
            : 'bg-white dark:bg-titanium-900 text-titanium-700 dark:text-titanium-300 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50'"
        >
          {{ category }}
        </button>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div v-for="n in 6" :key="n" class="bg-white dark:bg-titanium-900 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 shadow-xl animate-pulse">
        <div class="h-48 bg-titanium-200 dark:bg-titanium-800"></div>
        <div class="p-6 space-y-3">
          <div class="h-6 bg-titanium-200 dark:bg-titanium-800 rounded w-3/4"></div>
          <div class="h-4 bg-titanium-200 dark:bg-titanium-800 rounded"></div>
          <div class="h-10 bg-titanium-200 dark:bg-titanium-800 rounded w-1/2"></div>
        </div>
      </div>
    </div>

    <!-- Products Grid -->
    <div v-else-if="products?.data && products.data.length > 0" class="perspective-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <NuxtLink
        v-for="product in products.data"
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

            <!-- Type Badge -->
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1.5 bg-titanium-900/60 backdrop-blur-sm border border-titanium-600 rounded-full text-white text-xs font-heading font-bold">
                {{ getTypeLabel(product.type) }}
              </span>
            </div>

            <!-- Free Badge -->
            <div v-if="product.price === 0" class="absolute top-4 left-4">
              <span class="px-3 py-1.5 bg-green-600/80 backdrop-blur-sm border border-green-500/30 rounded-full text-white text-xs font-heading font-bold">
                FREE
              </span>
            </div>
          </div>

          <!-- Content -->
          <div class="p-6">
            <h3 class="text-xl font-heading font-bold mb-2 text-titanium-900 dark:text-white group-hover:text-mintreu-red-600 transition-colors line-clamp-1">
              {{ product.title }}
            </h3>
            <p class="text-sm text-titanium-600 dark:text-titanium-400 mb-4 line-clamp-2 font-subheading leading-relaxed">
              {{ product.description }}
            </p>

            <!-- Price & Stats -->
            <div class="pt-4 border-t border-titanium-200 dark:border-titanium-800">
              <div class="flex items-center justify-between mb-3">
                <div v-if="product.price === 0" class="text-lg font-heading font-black text-green-600 dark:text-green-400">
                  FREE
                </div>
                <div v-else class="text-lg font-heading font-black text-titanium-900 dark:text-white">
                  {{ formatPrice(product.price) }}
                  <span v-if="product.type === 'api'" class="text-xs text-titanium-500 font-subheading">/mo</span>
                </div>

                <div class="flex items-center space-x-3 text-xs text-titanium-500 font-subheading">
                  <div v-if="product.downloads" class="flex items-center space-x-1">
                    <Icon name="lucide:download" class="w-4 h-4" />
                    <span>{{ product.downloads }}</span>
                  </div>
                  <div v-if="product.rating" class="flex items-center space-x-1">
                    <Icon name="lucide:star" class="w-4 h-4 text-amber-500" />
                    <span>{{ product.rating }}</span>
                  </div>
                </div>
              </div>

              <button
                class="w-full px-4 py-2.5 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-xl font-heading font-bold text-sm shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-300"
              >
                {{ product.price === 0 ? 'Download Free' : product.type === 'api' ? 'Start Free Trial' : 'View Details' }}
              </button>
            </div>
          </div>
        </div>
      </NuxtLink>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-20">
      <Icon name="lucide:package-open" class="w-20 h-20 text-titanium-400 mx-auto mb-4" />
      <h3 class="text-xl font-heading font-bold mb-2 text-titanium-900 dark:text-white">No Products Found</h3>
      <p class="text-titanium-600 dark:text-titanium-400 font-subheading">Try adjusting your filters</p>
    </div>

    <!-- Pagination -->
    <div v-if="products && products.meta && products.meta.last_page > 1" class="mt-12 flex justify-center">
      <nav class="flex items-center space-x-2">
        <button
          @click="page > 1 && page--"
          :disabled="page === 1"
          class="px-4 py-2 rounded-xl bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 disabled:opacity-50 text-titanium-700 dark:text-titanium-300 hover:border-mintreu-red-600/50 transition-colors"
        >
          <Icon name="lucide:chevron-left" class="w-5 h-5" />
        </button>

        <button
          v-for="p in paginationRange"
          :key="p"
          @click="page = p"
          class="px-4 py-2 rounded-xl font-heading font-bold text-sm transition-all"
          :class="page === p
            ? 'bg-mintreu-red-600 text-white shadow-lg glow-red'
            : 'bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 text-titanium-700 dark:text-titanium-300 hover:border-mintreu-red-600/50'"
        >
          {{ p }}
        </button>

        <button
          @click="page < products.meta.last_page && page++"
          :disabled="page === products.meta.last_page"
          class="px-4 py-2 rounded-xl bg-white dark:bg-titanium-900 border border-titanium-300 dark:border-titanium-700 disabled:opacity-50 text-titanium-700 dark:text-titanium-300 hover:border-mintreu-red-600/50 transition-colors"
        >
          <Icon name="lucide:chevron-right" class="w-5 h-5" />
        </button>
      </nav>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch, onMounted, onUnmounted } from 'vue'
import type { PaginatedResponse, Product } from '~/types/api'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

interface Props {
  type?: string
  title: string
  subtitle: string
}

const props = defineProps<Props>()
const productType = computed(() => props.type || '')

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const currency = inject('currency', ref('USD'))
const page = ref(1)
const searchQuery = ref('')
const activeCategory = ref('')
const sortBy = ref<'latest' | 'popular' | 'price_low' | 'price_high'>('latest')
const categories = ref<string[]>([])
const products = ref<PaginatedResponse<Product> | null>(null)
const pending = ref(false)
const fetchError = ref<Error | null>(null)

const { getProducts, getCategories } = useApi()

const loadCategories = async () => {
  try {
    const response = await getCategories({ type: 'products' }) as any
    const items = Array.isArray(response)
      ? response
      : Array.isArray(response?.data)
        ? response.data
        : []
    if (items.length) {
      categories.value = items.map((c: any) => c.name)
    }
  } catch (error) {
    console.error('Unable to load categories', error)
  }
}

const fetchProducts = async () => {
  pending.value = true
  fetchError.value = null
  try {
    const response = await getProducts({
      page: page.value,
      search: searchQuery.value || undefined,
      category: activeCategory.value || undefined,
      type: productType.value || undefined,
      sort: sortBy.value,
      per_page: 12
    }) as any
    products.value = response ?? null
  } catch (error) {
    fetchError.value = error as Error
    console.error('Unable to load products', error)
  } finally {
    pending.value = false
    initAnimations()
  }
}

const initAnimations = () => {
  ctx?.revert()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.product-header', {
      y: 40, opacity: 0, duration: 0.8, ease: 'power3.out',
      scrollTrigger: { trigger: '.product-header', start: 'top 85%' },
    })

    const cards = gsap.utils.toArray('.product-card') as HTMLElement[]
    cards.forEach((card, i) => {
      gsap.from(card, {
        y: 50, opacity: 0, scale: 0.95,
        duration: 0.7, delay: i * 0.06,
        ease: 'back.out(1.3)',
        scrollTrigger: { trigger: card, start: 'top 90%' },
      })
    })
  }, sectionRef.value)
}

onMounted(() => {
  loadCategories()
})

watch(
  [page, searchQuery, activeCategory, sortBy, productType],
  () => { fetchProducts() },
  { immediate: true }
)

onUnmounted(() => { ctx?.revert() })

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

const formatPrice = (price: number) => {
  if (price === 0) return 'Free'
  return currency.value === 'USD' ? `$${price}` : `\u20B9${(price * 82).toFixed(0)}`
}

const getTypeLabel = (type: string) => {
  const labels: Record<string, string> = {
    api: 'API', template: 'Template', plugin: 'Plugin', freebie: 'Free', media: 'Media'
  }
  return labels[type] || type || 'Product'
}
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
