<script setup lang="ts">
import { computed } from 'vue'
import { resolveApiError } from '~/utils/api-error'
import type { LicenseDashboardPayload, LicenseSummary } from '~/types/api'

definePageMeta({
  layout: 'dashboard',
  middleware: ['$auth'],
  title: 'Manage Licenses'
})

const route = useRoute()
const router = useRouter()

const filterType = computed<'all' | 'download' | 'api'>(() => {
  const type = String(route.query.type ?? 'all')
  if (type === 'download' || type === 'api') return type
  return 'all'
})
const currentPage = computed<number>(() => {
  const page = Number(route.query.page ?? 1)
  return Number.isFinite(page) && page > 0 ? Math.floor(page) : 1
})

const { data, pending, error, refresh } = await useAsyncData(
  () => `dashboard-license-list-${filterType.value}-${currentPage.value}`,
  () => useSanctumFetch<LicenseDashboardPayload>('/api/licenses', {
    method: 'GET',
    query: {
      type: filterType.value,
      page: currentPage.value,
      per_page: 8
    }
  }),
  { watch: [filterType, currentPage] }
)

const licenses = computed(() => data.value?.data ?? [])
const meta = computed(() => data.value?.meta ?? { current_page: 1, last_page: 1, total: 0 })
const loadError = computed(() => (error.value ? resolveApiError(error.value, 'Unable to load licenses.') : null))

const isApiLicense = (license: LicenseSummary) => ['api_service', 'api_referral'].includes(license.product.type)
const switchFilter = async (type: 'all' | 'download' | 'api') => {
  await router.push({ path: '/dashboard/licenses', query: { type, page: '1' } })
}
const goToPage = async (page: number) => {
  if (page < 1 || page > meta.value.last_page) return
  await router.push({ path: '/dashboard/licenses', query: { ...route.query, page: String(page) } })
}

const filters = [
  { key: 'all' as const, label: 'All', icon: 'lucide:layers' },
  { key: 'download' as const, label: 'Paid downloads', icon: 'lucide:download' },
  { key: 'api' as const, label: 'API subscriptions', icon: 'lucide:cloud-cog' },
]
</script>

<template>
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
      <h1 class="text-2xl font-heading font-black text-titanium-900 dark:text-white">Licenses & purchases</h1>
      <p class="text-sm text-titanium-500 dark:text-titanium-400 mt-1">All your purchased products and API subscriptions.</p>
    </div>
    <button
      type="button"
      class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-titanium-200 dark:border-titanium-700 text-sm font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors self-start"
      @click="refresh()"
    >
      <Icon name="lucide:refresh-cw" class="w-4 h-4" />
      Refresh
    </button>
  </div>

  <DashboardDashboardCard>
    <!-- Filter Tabs -->
    <div class="flex flex-wrap gap-2">
      <button
        v-for="f in filters"
        :key="f.key"
        type="button"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
        :class="filterType === f.key
          ? 'bg-mintreu-red-600 text-white shadow-sm'
          : 'border border-titanium-200 dark:border-titanium-700 text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800'"
        @click="switchFilter(f.key)"
      >
        <Icon :name="f.icon" class="w-4 h-4" />
        {{ f.label }}
      </button>
    </div>

    <!-- Record count -->
    <p class="text-sm text-titanium-500 dark:text-titanium-400">{{ meta.total }} records found</p>

    <!-- Error -->
    <div v-if="loadError" class="rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 text-sm">
      {{ loadError }}
    </div>

    <!-- Loading -->
    <DashboardSkeletonLoader v-if="pending" variant="row" :count="4" />

    <!-- Empty -->
    <DashboardEmptyState
      v-else-if="licenses.length === 0"
      icon="lucide:file-search"
      title="No records found"
      description="Try adjusting your filters or browse our products."
      action-label="Browse Products"
      action-to="/products"
    />

    <!-- List -->
    <div v-else class="space-y-3">
      <article
        v-for="license in licenses"
        :key="license.uuid"
        class="rounded-xl border border-titanium-200 dark:border-titanium-700 p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 hover:border-titanium-300 dark:hover:border-titanium-600 transition-colors"
      >
        <div class="flex items-start gap-3 min-w-0">
          <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" :class="isApiLicense(license) ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-blue-100 dark:bg-blue-900/30'">
            <Icon
              :name="isApiLicense(license) ? 'lucide:cloud-cog' : 'lucide:download'"
              :class="isApiLicense(license) ? 'text-emerald-600 dark:text-emerald-400' : 'text-blue-600 dark:text-blue-400'"
              class="w-5 h-5"
            />
          </div>
          <div class="min-w-0">
            <p class="text-xs uppercase font-semibold tracking-wider text-titanium-500 dark:text-titanium-400">{{ isApiLicense(license) ? 'API subscription' : 'Paid download' }}</p>
            <p class="font-semibold text-titanium-900 dark:text-white truncate">{{ license.product.title }}</p>
            <div class="flex items-center gap-2 mt-1">
              <span class="text-xs text-titanium-500 dark:text-titanium-400">{{ license.type_label }}</span>
              <DashboardStatusBadge :status="license.status" />
            </div>
          </div>
        </div>
        <NuxtLink
          :to="`/dashboard/licenses/${license.uuid}`"
          class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-mintreu-red-600 text-white text-sm font-semibold hover:bg-mintreu-red-700 transition-colors flex-shrink-0 self-start md:self-center"
        >
          View record
          <Icon name="lucide:arrow-right" class="w-3.5 h-3.5" />
        </NuxtLink>
      </article>
    </div>

    <!-- Pagination -->
    <DashboardPaginationControls
      :current-page="meta.current_page"
      :last-page="meta.last_page"
      :total="meta.total"
      @page="goToPage"
    />
  </DashboardDashboardCard>
</template>
