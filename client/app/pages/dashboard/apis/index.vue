<script setup lang="ts">
import { computed } from 'vue'
import { resolveApiError } from '~/utils/api-error'
import type { LicenseDashboardPayload } from '~/types/api'

definePageMeta({
  layout: 'dashboard',
  middleware: ['$auth'],
  title: 'API Services'
})

const route = useRoute()
const router = useRouter()
const currentPage = computed<number>(() => {
  const page = Number(route.query.page ?? 1)
  return Number.isFinite(page) && page > 0 ? Math.floor(page) : 1
})

const { data, pending, error, refresh } = await useAsyncData(
  () => `dashboard-api-list-${currentPage.value}`,
  () => useSanctumFetch<LicenseDashboardPayload>('/api/licenses', {
    method: 'GET',
    query: { type: 'api', page: currentPage.value, per_page: 8 }
  }),
  { watch: [currentPage] }
)

const licenses = computed(() => data.value?.data ?? [])
const meta = computed(() => data.value?.meta ?? { current_page: 1, last_page: 1, total: 0 })
const loadError = computed(() => (error.value ? resolveApiError(error.value, 'Unable to load API services.') : null))

const goToPage = async (page: number) => {
  if (page < 1 || page > meta.value.last_page) return
  await router.push({ path: '/dashboard/apis', query: { page: String(page) } })
}

const activeCount = computed(() => licenses.value.filter((item) => item.status === 'active').length)
const highUsageCount = computed(() =>
  licenses.value.filter((item) => {
    const used = item.api_key?.requests_this_month ?? 0
    const limit = item.plan?.requests_per_month ?? 0
    if (!limit) return false
    return used / limit >= 0.7
  }).length
)
</script>

<template>
  <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-heading font-black text-titanium-900 dark:text-white">API services</h1>
      <p class="text-sm text-titanium-500 dark:text-titanium-400 mt-1">Manage subscriptions, monitor usage, and open each service workspace.</p>
    </div>
    <div class="flex items-center gap-2">
      <NuxtLink
        to="/dashboard/spaces"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-mintreu-red-600 text-white text-sm font-semibold hover:bg-mintreu-red-700 transition-colors"
      >
        <Icon name="lucide:plus" class="w-4 h-4" />
        New Site
      </NuxtLink>
      <button
        type="button"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-titanium-200 dark:border-titanium-700 text-sm font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
        @click="refresh()"
      >
        <Icon name="lucide:refresh-cw" class="w-4 h-4" />
        Refresh
      </button>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <DashboardDashboardCard title="Total Services">
      <p class="text-3xl font-heading font-black text-titanium-900 dark:text-white">{{ meta.total }}</p>
    </DashboardDashboardCard>
    <DashboardDashboardCard title="Active">
      <p class="text-3xl font-heading font-black text-emerald-600 dark:text-emerald-400">{{ activeCount }}</p>
    </DashboardDashboardCard>
    <DashboardDashboardCard title="Needs Attention">
      <p class="text-3xl font-heading font-black text-amber-600 dark:text-amber-400">{{ highUsageCount }}</p>
    </DashboardDashboardCard>
  </div>

  <DashboardDashboardCard title="Service list" subtitle="Open a service to manage credentials, plans, and linked sites.">

    <div v-if="loadError" class="rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 text-sm">
      {{ loadError }}
    </div>

    <DashboardSkeletonLoader v-if="pending" variant="card" :count="3" />

    <DashboardEmptyState
      v-else-if="licenses.length === 0"
      icon="lucide:cloud-off"
      title="No API services"
      description="You don't have any active API services yet."
      action-label="Browse API Products"
      action-to="/products"
    />

    <div v-else class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-xs uppercase tracking-wider text-titanium-500 dark:text-titanium-400 border-b border-titanium-200 dark:border-titanium-700">
            <th class="py-3 pr-3">Service</th>
            <th class="py-3 pr-3">Plan</th>
            <th class="py-3 pr-3">Monthly</th>
            <th class="py-3 pr-3">Today</th>
            <th class="py-3 pr-3">Status</th>
            <th class="py-3 text-right">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="license in licenses"
            :key="license.uuid"
            class="border-b border-titanium-100 dark:border-titanium-800/70"
          >
            <td class="py-3 pr-3">
              <p class="font-semibold text-titanium-900 dark:text-white">{{ license.product.title }}</p>
              <p class="text-xs text-titanium-500 dark:text-titanium-400">{{ license.api_key?.key_prefix ?? 'No key' }}</p>
            </td>
            <td class="py-3 pr-3 text-titanium-700 dark:text-titanium-300">
              {{ license.plan?.name ?? 'N/A' }}
            </td>
            <td class="py-3 pr-3 text-titanium-700 dark:text-titanium-300">
              {{ (license.api_key?.requests_this_month ?? 0).toLocaleString() }} / {{ (license.plan?.requests_per_month ?? 0).toLocaleString() }}
            </td>
            <td class="py-3 pr-3 text-titanium-700 dark:text-titanium-300">
              {{ (license.api_key?.requests_today ?? 0).toLocaleString() }} / {{ (license.plan?.requests_per_day ?? 0).toLocaleString() }}
            </td>
            <td class="py-3 pr-3">
              <DashboardStatusBadge :status="license.status" />
            </td>
            <td class="py-3 text-right">
              <NuxtLink
                :to="`/dashboard/licenses/${license.uuid}`"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-titanium-900 dark:bg-titanium-700 text-white text-xs font-semibold hover:bg-titanium-800 dark:hover:bg-titanium-600 transition-colors"
              >
                Open
                <Icon name="lucide:arrow-right" class="w-3.5 h-3.5" />
              </NuxtLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="licenses.length > 0" class="pt-3">
      <DashboardPaginationControls
        :current-page="meta.current_page"
        :last-page="meta.last_page"
        :total="meta.total"
        @page="goToPage"
      />
    </div>
  </DashboardDashboardCard>

  <DashboardDashboardCard title="Guides & docs" subtitle="API setup guides, knowledgebase, and implementation references.">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <a
        v-for="license in licenses.slice(0, 6)"
        :key="`${license.uuid}-docs`"
        :href="license.product.documentation_url || '#'"
        target="_blank"
        rel="noopener noreferrer"
        class="rounded-xl border border-titanium-200 dark:border-titanium-700 p-3 hover:border-mintreu-red-300 dark:hover:border-mintreu-red-800 transition-colors"
      >
        <p class="font-semibold text-sm text-titanium-900 dark:text-white truncate">{{ license.product.title }}</p>
        <p class="text-xs text-titanium-500 dark:text-titanium-400 truncate">
          {{ license.product.documentation_url || 'Documentation coming soon' }}
        </p>
      </a>
    </div>
    <div class="flex flex-wrap gap-2 pt-1">
      <NuxtLink
        to="/insights"
        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-titanium-200 dark:border-titanium-700 text-xs font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
      >
        <Icon name="lucide:book-open" class="w-3.5 h-3.5" />
        Knowledgebase
      </NuxtLink>
      <NuxtLink
        to="/products/apis"
        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-titanium-200 dark:border-titanium-700 text-xs font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
      >
        <Icon name="lucide:file-text" class="w-3.5 h-3.5" />
        API Guidelines
      </NuxtLink>
    </div>
  </DashboardDashboardCard>
</template>
