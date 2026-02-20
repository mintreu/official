<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 py-8 relative">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <nav class="flex items-center space-x-2 text-sm mb-8">
        <NuxtLink to="/" class="text-titanium-500 hover:text-mintreu-red-600 font-subheading transition-colors">Home</NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-titanium-400" />
        <NuxtLink to="/products" class="text-titanium-500 hover:text-mintreu-red-600 font-subheading transition-colors">Products</NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-titanium-400" />
        <span class="text-titanium-900 dark:text-white font-heading font-bold text-xs uppercase tracking-wider truncate max-w-[200px]">
          {{ product?.title || 'Loading...' }}
        </span>
      </nav>

      <!-- Loading -->
      <div v-if="pending" class="space-y-6">
        <div class="h-96 bg-white dark:bg-titanium-900 rounded-3xl border border-dashed border-titanium-300 dark:border-titanium-700 animate-pulse"></div>
        <div class="h-8 bg-titanium-200 dark:bg-titanium-800 rounded w-3/4 animate-pulse"></div>
      </div>

      <!-- Product Detail -->
      <div v-else-if="product" ref="sectionRef" class="space-y-12">
        <div class="grid lg:grid-cols-2 gap-12">
          <!-- Left: Product Image -->
          <div class="product-image">
            <div class="sticky top-24 space-y-6">
              <div class="relative h-96 rounded-3xl overflow-hidden border border-dashed border-titanium-300 dark:border-titanium-700 shadow-2xl">
                <div class="absolute inset-0 bg-blueprint opacity-10 pointer-events-none"></div>
                <img v-if="product.image" :src="product.image" :alt="product.title" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full bg-gradient-to-br from-titanium-800 via-titanium-900 to-titanium-950 flex items-center justify-center relative">
                  <div class="absolute inset-0 bg-blueprint opacity-20"></div>
                  <Icon name="lucide:package" class="relative w-32 h-32 text-titanium-600" />
                </div>

                <div v-if="product.price === 0" class="absolute top-6 left-6">
                  <span class="px-4 py-2 bg-green-600/90 backdrop-blur-sm text-white rounded-full text-sm font-heading font-bold">FREE</span>
                </div>
                <div class="absolute top-6 right-6">
                  <span class="px-4 py-2 bg-titanium-900/60 backdrop-blur-sm border border-titanium-600 rounded-full text-white text-sm font-heading font-bold">
                    {{ getTypeLabel(product.type) }}
                  </span>
                </div>
              </div>

              <!-- Quick Stats -->
              <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-4 bg-white dark:bg-titanium-900 rounded-xl border border-dashed border-titanium-300 dark:border-titanium-700">
                  <Icon name="lucide:download" class="w-6 h-6 mx-auto mb-2 text-mintreu-red-600" />
                  <div class="text-sm font-heading font-bold text-titanium-900 dark:text-white">{{ engagementStats.downloads }}</div>
                  <div class="text-xs text-titanium-500 font-subheading">Downloads</div>
                </div>
                <div class="text-center p-4 bg-white dark:bg-titanium-900 rounded-xl border border-dashed border-titanium-300 dark:border-titanium-700">
                  <Icon name="lucide:star" class="w-6 h-6 mx-auto mb-2 text-amber-500" />
                  <div class="text-sm font-heading font-bold text-titanium-900 dark:text-white">{{ engagementStats.rating.toFixed(1) }}/5</div>
                  <div class="text-xs text-titanium-500 font-subheading">Rating</div>
                </div>
                <div class="text-center p-4 bg-white dark:bg-titanium-900 rounded-xl border border-dashed border-titanium-300 dark:border-titanium-700">
                  <Icon name="lucide:tag" class="w-6 h-6 mx-auto mb-2 text-blueprint-600" />
                  <div class="text-sm font-heading font-bold text-titanium-900 dark:text-white">v{{ engagementStats.version }}</div>
                  <div class="text-xs text-titanium-500 font-subheading">Version</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Product Details -->
          <div class="product-details">
            <span class="px-4 py-1.5 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider inline-block mb-4">
              {{ product.category || 'Product' }}
            </span>

            <span class="label-schematic mb-3 block">PRD-{{ product.slug.slice(0, 8).toUpperCase() }}</span>

            <h1 class="text-3xl md:text-4xl lg:text-5xl font-heading font-black mb-4 text-titanium-900 dark:text-white">
              {{ product.title }}
            </h1>

            <p class="text-lg text-titanium-600 dark:text-titanium-400 mb-8 leading-relaxed font-subheading">
              {{ product.description }}
            </p>

            <!-- Content -->
            <div v-if="product.content" class="mb-8 prose-content" v-html="product.content"></div>

            <!-- Pricing / Plan Card -->
            <div class="bg-white dark:bg-titanium-900 rounded-2xl p-8 mb-8 border-2 border-dashed border-mintreu-red-600/30 relative overflow-hidden">
              <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
              <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600/40 rounded-br-xl"></div>

              <div v-if="!isApiProduct" class="relative">
                <div class="flex items-baseline space-x-2 mb-2">
                  <span class="text-5xl font-heading font-black text-mintreu-red-600">
                    {{ formatPrice(product.price) }}
                  </span>
                </div>
                <p class="text-sm text-titanium-500 dark:text-titanium-400 mb-6 font-subheading">
                  {{ product.price === 0 ? '100% Free - No signup required' : 'One-time purchase - Lifetime updates' }}
                </p>

                <button class="w-full px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white font-heading font-bold rounded-xl shadow-lg glow-red hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-300">
                  {{ product.price === 0 ? 'Download Free' : 'Purchase Now' }}
                </button>
              </div>

              <div v-else class="relative space-y-4">
                <div class="flex items-center justify-between">
                  <p class="text-xs uppercase tracking-wider font-heading font-bold text-titanium-500 dark:text-titanium-400">Subscription Plans</p>
                  <span v-if="recommendedPlan" class="px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                    Recommended: {{ recommendedPlan.name }}
                  </span>
                </div>

                <article
                  v-for="plan in subscriptionPlans"
                  :key="plan.id"
                  class="rounded-xl border border-dashed border-titanium-300 dark:border-titanium-700 p-4 space-y-3"
                >
                  <div class="flex items-start justify-between gap-3">
                    <div>
                      <p class="font-heading font-bold text-titanium-900 dark:text-white">{{ plan.name }}</p>
                      <p class="text-sm text-titanium-500 dark:text-titanium-400">{{ plan.description }}</p>
                    </div>
                    <span v-if="plan.is_popular" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-mintreu-red-100 text-mintreu-red-700 dark:bg-mintreu-red-900/40 dark:text-mintreu-red-300">
                      Recommended
                    </span>
                  </div>

                  <div class="flex items-end justify-between gap-4">
                    <p class="text-2xl font-heading font-black text-mintreu-red-600">
                      {{ plan.price_formatted }}<span class="text-sm font-semibold text-titanium-500 dark:text-titanium-400 ml-1">{{ plan.billing_label }}</span>
                    </p>
                    <button
                      type="button"
                      class="px-4 py-2.5 rounded-xl bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white text-sm font-heading font-bold transition-colors disabled:opacity-60"
                      :disabled="subscribingPlanId === plan.id"
                      @click="subscribeToPlan(plan)"
                    >
                      {{ subscribingPlanId === plan.id ? 'Subscribing...' : 'Subscribe' }}
                    </button>
                  </div>

                  <ul v-if="plan.features?.length" class="grid sm:grid-cols-2 gap-2">
                    <li
                      v-for="feature in plan.features"
                      :key="feature"
                      class="text-xs text-titanium-600 dark:text-titanium-300 flex items-center gap-2"
                    >
                      <Icon name="lucide:check-circle-2" class="w-3.5 h-3.5 text-emerald-500" />
                      <span>{{ feature }}</span>
                    </li>
                  </ul>
                </article>

                <p v-if="subscribeSuccess" class="text-xs text-emerald-600 dark:text-emerald-400">{{ subscribeSuccess }}</p>
                <p v-if="subscribeError" class="text-xs text-rose-600 dark:text-rose-400">{{ subscribeError }}</p>
              </div>
            </div>

            <!-- Action Links -->
            <div class="flex flex-wrap gap-4 mb-8">
              <a v-if="product.demo_url" :href="product.demo_url" target="_blank"
                class="inline-flex items-center space-x-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-heading font-bold transition-all">
                <Icon name="lucide:external-link" class="w-5 h-5" />
                <span>View Demo</span>
              </a>
              <a v-if="product.github_url" :href="product.github_url" target="_blank"
                class="inline-flex items-center space-x-2 px-6 py-3 bg-titanium-800 hover:bg-titanium-700 text-white rounded-xl font-heading font-bold transition-all">
                <Icon name="lucide:github" class="w-5 h-5" />
                <span>View on GitHub</span>
              </a>
              <a v-if="product.documentation_url" :href="product.documentation_url" target="_blank"
                class="inline-flex items-center space-x-2 px-6 py-3 bg-blueprint-600 hover:bg-blueprint-700 text-white rounded-xl font-heading font-bold transition-all">
                <Icon name="lucide:book-open" class="w-5 h-5" />
                <span>Documentation</span>
              </a>
            </div>
          </div>
        </div>

        <section v-if="isApiProduct && matchedFrontends.length > 0" class="space-y-6">
          <div class="flex items-center justify-between">
            <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white">Recommended Frontends</h2>
            <span class="text-xs uppercase tracking-wider font-heading font-bold text-titanium-500 dark:text-titanium-400">For this API</span>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <NuxtLink
              v-for="frontend in matchedFrontends"
              :key="frontend.id"
              :to="`/products/${frontend.slug}`"
              class="related-card group block p-6 rounded-2xl bg-white dark:bg-titanium-900 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
            >
              <h3 class="text-lg font-heading font-bold text-titanium-900 dark:text-white mb-2 group-hover:text-mintreu-red-600 transition-colors">
                {{ frontend.title }}
              </h3>
              <p class="text-sm text-titanium-600 dark:text-titanium-400 line-clamp-2 font-subheading mb-3">{{ frontend.short_description }}</p>
              <div class="flex items-center justify-between">
                <span class="font-heading font-black text-lg text-titanium-900 dark:text-white">
                  {{ formatPrice(frontend.price) }}
                </span>
                <span class="text-xs text-mintreu-red-600 font-heading font-bold">View frontend</span>
              </div>
            </NuxtLink>
          </div>
        </section>

        <!-- Related Products -->
        <section v-if="relatedProducts.length > 0" class="space-y-6">
          <div class="flex items-center justify-between">
            <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white">Related Products</h2>
            <NuxtLink to="/products" class="text-mintreu-red-600 font-heading font-bold text-sm hover:underline">See all</NuxtLink>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <NuxtLink
              v-for="related in relatedProducts"
              :key="related.slug"
              :to="`/products/${related.slug}`"
              class="related-card group block p-6 rounded-2xl bg-white dark:bg-titanium-900 border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
            >
              <h3 class="text-lg font-heading font-bold text-titanium-900 dark:text-white mb-2 group-hover:text-mintreu-red-600 transition-colors">
                {{ related.title }}
              </h3>
              <p class="text-sm text-titanium-600 dark:text-titanium-400 line-clamp-2 font-subheading mb-3">{{ related.description }}</p>
              <div class="flex items-center justify-between">
                <span class="font-heading font-black text-lg" :class="related.price === 0 ? 'text-green-600' : 'text-titanium-900 dark:text-white'">
                  {{ formatPrice(related.price) }}
                </span>
                <span class="text-xs text-mintreu-red-600 font-heading font-bold">View Details</span>
              </div>
            </NuxtLink>
          </div>
        </section>
      </div>

      <!-- 404 State -->
      <div v-else class="max-w-3xl mx-auto text-center py-20">
        <Icon name="lucide:alert-circle" class="w-20 h-20 text-mintreu-red-500 mx-auto mb-4" />
        <h1 class="text-3xl font-heading font-bold text-titanium-900 dark:text-white mb-2">Product Not Found</h1>
        <p class="text-titanium-600 dark:text-titanium-400 mb-6 font-subheading">The product you're looking for doesn't exist or has been removed.</p>
        <NuxtLink to="/products" class="px-6 py-3 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-xl font-heading font-bold shadow-lg glow-red transition-all">
          Back to Products
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch, onUnmounted, nextTick } from 'vue'
import type { MatchedFrontend, Product, ProductPlan } from '~/types/api'
import { resolveApiError } from '~/utils/api-error'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const route = useRoute()
const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const slug = computed(() => route.params.slug as string | undefined)
const product = ref<Product | null>(null)
const relatedProducts = ref<Product[]>([])
const matchedFrontends = ref<MatchedFrontend[]>([])
const pending = ref(false)
const fetchError = ref<Error | null>(null)
const currency = inject('currency', ref('USD'))
const router = useRouter()

const subscribingPlanId = ref<number | null>(null)
const subscribeSuccess = ref<string | null>(null)
const subscribeError = ref<string | null>(null)

const { getProduct } = useApi()

const engagementStats = computed(() => {
  const value = product.value
  return {
    downloads: value?.engagement?.downloads ?? value?.downloads ?? 0,
    rating: value?.engagement?.rating ?? value?.rating ?? 0,
    version: value?.engagement?.version ?? value?.version ?? '1.0.0'
  }
})

const loadProduct = async () => {
  if (!slug.value) return
  pending.value = true
  fetchError.value = null
  try {
    const response = await getProduct(slug.value) as any
    product.value = response?.data ?? null
    relatedProducts.value = response?.related ?? []
    matchedFrontends.value = response?.matched_frontends ?? []
  } catch (error) {
    fetchError.value = error as Error
    console.error('Unable to load product', error)
  } finally {
    pending.value = false
    nextTick(() => initAnimations())
  }
}

const initAnimations = () => {
  ctx?.revert()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.product-image', {
      x: -40, opacity: 0, duration: 0.8, ease: 'power3.out',
    })
    gsap.from('.product-details', {
      x: 40, opacity: 0, duration: 0.8, delay: 0.15, ease: 'power3.out',
    })

    const relatedCards = gsap.utils.toArray('.related-card') as HTMLElement[]
    relatedCards.forEach((card, i) => {
      gsap.from(card, {
        y: 30, opacity: 0, duration: 0.6, delay: i * 0.1,
        ease: 'back.out(1.3)',
        scrollTrigger: { trigger: card, start: 'top 90%' },
      })
    })
  }, sectionRef.value)
}

watch(slug, () => { loadProduct() }, { immediate: true })

watch(product, (value) => {
  if (value) {
    useSeoMeta({
      title: `${value.title} | Mintreu Products`,
      description: value.description
    })
  }
})

onUnmounted(() => { ctx?.revert() })

const formatPrice = (price: number) => {
  if (price === 0) return 'Free'
  return currency.value === 'USD' ? `$${price}` : `\u20B9${(price * 82).toFixed(0)}`
}

const isApiProduct = computed(() => {
  const type = product.value?.type
  return type === 'api_service' || type === 'api_referral'
})

const subscriptionPlans = computed<ProductPlan[]>(() => {
  const plans = product.value?.plans ?? []
  return Array.isArray(plans) ? plans : []
})

const recommendedPlan = computed(() => {
  if (subscriptionPlans.value.length === 0) {
    return null
  }
  return subscriptionPlans.value.find((plan) => plan.is_popular) ?? subscriptionPlans.value[0]
})

const subscribeToPlan = async (plan: ProductPlan) => {
  if (!product.value) {
    return
  }

  subscribeSuccess.value = null
  subscribeError.value = null
  subscribingPlanId.value = plan.id

  try {
    const payload = await useSanctumFetch<{ data: { id: number; uuid: string } }>(
      '/api/licenses/subscribe',
      {
        method: 'POST',
        body: {
          product_slug: product.value.slug,
          plan_id: plan.id
        }
      }
    )

    subscribeSuccess.value = 'Subscription activated. Redirecting to dashboard...'
    const licenseUuid = payload?.data?.uuid
    if (licenseUuid) {
      await router.push(`/dashboard/licenses/${licenseUuid}`)
      return
    }
    await router.push('/dashboard/licenses')
  } catch (err: unknown) {
    const status = (err as any)?.statusCode || (err as any)?.status
    if (status === 401) {
      await router.push('/auth/signin')
      return
    }
    subscribeError.value = resolveApiError(err, 'Unable to start subscription for this plan.')
  } finally {
    subscribingPlanId.value = null
  }
}

const getTypeLabel = (type: string) => {
  const labels: Record<string, string> = {
    api: 'API',
    api_service: 'API Service',
    api_referral: 'API Referral',
    template: 'Template',
    downloadable: 'Downloadable',
    plugin: 'Plugin',
    freebie: 'Free',
    media: 'Media'
  }
  return labels[type] || type || 'Product'
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.prose-content :deep(h2) { @apply text-2xl font-bold mt-8 mb-4 text-titanium-900 dark:text-white font-heading; }
.prose-content :deep(h3) { @apply text-xl font-bold mt-6 mb-3 text-titanium-900 dark:text-white font-heading; }
.prose-content :deep(p) { @apply mb-4 leading-relaxed text-titanium-600 dark:text-titanium-400 font-subheading; }
.prose-content :deep(code) { @apply bg-titanium-100 dark:bg-titanium-800 px-2 py-1 rounded text-sm; }
.prose-content :deep(pre) { @apply bg-titanium-900 dark:bg-titanium-800 p-6 rounded-xl overflow-x-auto mb-6; }
.prose-content :deep(ul), .prose-content :deep(ol) { @apply mb-4 ml-6; }
.prose-content :deep(li) { @apply mb-2 text-titanium-600 dark:text-titanium-400; }
</style>
