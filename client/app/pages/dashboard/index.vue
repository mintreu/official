<script setup lang="ts">
import { computed, ref, onMounted, onUnmounted, nextTick } from 'vue'
import { resolveApiError } from '~/utils/api-error'
import type { LicenseDashboardPayload } from '~/types/api'
import gsap from 'gsap'

definePageMeta({
  layout: 'dashboard',
  middleware: ['$auth'],
  title: 'Dashboard Overview'
})

const { user } = useSanctum()

const {
  data: payload,
  pending,
  error
} = await useAsyncData(
  'dashboard-overview',
  () => useSanctumFetch<LicenseDashboardPayload>('/api/licenses', {
    method: 'GET',
    query: { type: 'all', per_page: 6, page: 1 }
  })
)

const licenses = computed(() => payload.value?.data ?? [])
const paidDownloads = computed(() => licenses.value.filter((item) => item.product.type === 'downloadable').length)
const apiSubscriptions = computed(() => licenses.value.filter((item) => ['api_service', 'api_referral'].includes(item.product.type)).length)
const fetchError = computed(() => (error.value ? resolveApiError(error.value, 'Unable to load dashboard overview.') : null))

// License breakdown for donut chart
const licenseBreakdown = computed(() => {
  const downloads = licenses.value.filter(l => l.product.type === 'downloadable').length
  const apis = licenses.value.filter(l => ['api_service', 'api_referral'].includes(l.product.type)).length
  const other = licenses.value.length - downloads - apis
  const segments = []
  if (downloads > 0) segments.push({ value: downloads, color: '#DC2626', label: 'Downloads' })
  if (apis > 0) segments.push({ value: apis, color: '#059669', label: 'API Services' })
  if (other > 0) segments.push({ value: other, color: '#2563EB', label: 'Other' })
  if (segments.length === 0) segments.push({ value: 1, color: '#6B7280', label: 'No licenses' })
  return segments
})

const downloadSparkline = computed(() =>
  licenses.value
    .filter((item) => item.product.type === 'downloadable')
    .slice(0, 12)
    .map((item) => item.usage_count || 0)
)

const apiSparkline = computed(() =>
  licenses.value
    .filter((item) => ['api_service', 'api_referral'].includes(item.product.type))
    .slice(0, 12)
    .map((item) => item.usage_count || 0)
)

const referralSparkline = computed(() => Array(12).fill(0))

const monthlyActivity = computed(() => {
  const now = new Date()
  const monthMap = new Map<string, number>()

  for (let i = 5; i >= 0; i--) {
    const d = new Date(now.getFullYear(), now.getMonth() - i, 1)
    const key = d.toISOString().slice(0, 7)
    monthMap.set(key, 0)
  }

  for (const license of licenses.value) {
    if (!license.created_at) continue
    const key = String(license.created_at).slice(0, 7)
    if (monthMap.has(key)) {
      monthMap.set(key, (monthMap.get(key) ?? 0) + 1)
    }
  }

  return Array.from(monthMap.entries()).map(([key, value]) => {
    const [year, month] = key.split('-').map(Number)
    const label = new Date(year, month - 1, 1).toLocaleString('en-US', { month: 'short' })
    return { label, value }
  })
})

const recentActivity = computed(() =>
  licenses.value.slice(0, 4).map((license) => {
    const isApi = ['api_service', 'api_referral'].includes(license.product.type)
    return {
      icon: isApi ? 'lucide:cloud-cog' : 'lucide:download',
      title: isApi ? 'API license active' : 'Download license active',
      description: license.product.title,
      time: license.created_at ? new Date(license.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Recently',
      color: isApi
        ? 'bg-emerald-100 dark:bg-emerald-900/30 ring-emerald-200 dark:ring-emerald-800'
        : 'bg-mintreu-red-100 dark:bg-mintreu-red-900/30 ring-mintreu-red-200 dark:ring-mintreu-red-800'
    }
  })
)

// GSAP animations
const overviewRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const initAnimations = () => {
  if (!overviewRef.value) return
  ctx = gsap.context(() => {
    // Stat cards
    const statCards = gsap.utils.toArray('.stat-card-anim') as HTMLElement[]
    if (statCards.length) {
      gsap.set(statCards, { opacity: 0, y: 30, scale: 0.95 })
      gsap.to(statCards, { opacity: 1, y: 0, scale: 1, duration: 0.6, stagger: 0.1, ease: 'back.out(1.3)', delay: 0.1 })
    }

    // Chart cards
    const chartCards = gsap.utils.toArray('.chart-card-anim') as HTMLElement[]
    if (chartCards.length) {
      gsap.set(chartCards, { opacity: 0, y: 40 })
      gsap.to(chartCards, { opacity: 1, y: 0, duration: 0.7, stagger: 0.15, ease: 'power3.out', delay: 0.5 })
    }

    // Action + timeline cards
    const bottomCards = gsap.utils.toArray('.bottom-card-anim') as HTMLElement[]
    if (bottomCards.length) {
      gsap.set(bottomCards, { opacity: 0, y: 30 })
      gsap.to(bottomCards, { opacity: 1, y: 0, duration: 0.6, stagger: 0.12, ease: 'power2.out', delay: 0.8 })
    }

    // Records section
    const recordsSection = overviewRef.value?.querySelector('.records-anim')
    if (recordsSection) {
      gsap.set(recordsSection, { opacity: 0, y: 30 })
      gsap.to(recordsSection, { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out', delay: 1.0 })
    }
  }, overviewRef.value)
}

onMounted(() => {
  nextTick(() => { setTimeout(initAnimations, 50) })
})

onUnmounted(() => { ctx?.revert() })
</script>

<template>
  <div ref="overviewRef">
    <!-- Page Header -->
    <div>
      <h1 class="text-2xl font-heading font-black text-titanium-900 dark:text-white">Dashboard overview</h1>
      <p class="text-sm text-titanium-500 dark:text-titanium-400 mt-1">Manage your products, API subscriptions, and account settings.</p>
    </div>

    <!-- Stats Row with Three.js Background -->
    <div class="relative rounded-2xl overflow-hidden">
      <!-- Three.js ambient background -->
      <div class="absolute inset-0 z-0 opacity-60">
        <ClientOnly>
          <TresCanvas :clear-color="'transparent'" :alpha="true" window-size style="height: 100% !important; position: absolute; inset: 0;">
            <TresPerspectiveCamera :position="[0, 0, 6]" :fov="50" />
            <ThreeDashboardParticles :count="20" color="#DC2626" :speed="0.3" />
            <ThreeDashboardParticles :count="15" color="#6B7280" :speed="0.2" />
          </TresCanvas>
        </ClientOnly>
      </div>

      <!-- Stat Cards -->
      <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-1">
        <div class="stat-card-anim">
          <DashboardStatCard
            label="Account"
            :value="user?.value?.name ?? 'Client'"
            icon="lucide:user-circle"
            color="titanium"
            :subtitle="user?.value?.email ?? ''"
          />
        </div>
        <div class="stat-card-anim">
          <DashboardStatCard
            label="Paid downloads"
            :value="paidDownloads"
            icon="lucide:download"
            color="red"
            subtitle="Licensed products"
            :sparkline-data="downloadSparkline"
            :trend="{ value: 12, direction: 'up' }"
          />
        </div>
        <div class="stat-card-anim">
          <DashboardStatCard
            label="API subscriptions"
            :value="apiSubscriptions"
            icon="lucide:cloud-cog"
            color="emerald"
            subtitle="Active services"
            :sparkline-data="apiSparkline"
            :trend="{ value: 8, direction: 'up' }"
          />
        </div>
        <div class="stat-card-anim">
          <DashboardStatCard
            label="Referral earnings"
            :value="0"
            icon="lucide:share-2"
            color="amber"
            subtitle="Total earned"
            :sparkline-data="referralSparkline"
          />
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
      <!-- Monthly Activity -->
      <div class="chart-card-anim lg:col-span-3">
        <DashboardDashboardCard title="Monthly activity" subtitle="License & API usage over time">
          <div class="flex items-end justify-center py-2 overflow-x-auto">
            <DashboardMiniBarChart
              :data="monthlyActivity"
              color="#DC2626"
              :height="160"
            />
          </div>
        </DashboardDashboardCard>
      </div>

      <!-- License Breakdown -->
      <div class="chart-card-anim lg:col-span-2">
        <DashboardDashboardCard title="License breakdown" subtitle="By product type">
          <div class="flex items-center justify-center py-2">
            <DashboardMiniDonut
              :segments="licenseBreakdown"
              :size="150"
              :thickness="22"
            />
          </div>
        </DashboardDashboardCard>
      </div>
    </div>

    <!-- Quick Actions + Activity Timeline -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <!-- Quick Actions -->
      <div class="bottom-card-anim">
        <DashboardDashboardCard title="Quick actions">
          <div class="flex flex-wrap gap-3">
            <NuxtLink
              to="/dashboard/licenses"
              class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-mintreu-red-600 text-white text-sm font-semibold hover:bg-mintreu-red-700 transition-colors"
            >
              <Icon name="lucide:key-round" class="w-4 h-4" />
              Licenses
            </NuxtLink>
            <NuxtLink
              to="/dashboard/apis"
              class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-titanium-200 dark:border-titanium-700 text-sm font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
            >
              <Icon name="lucide:cloud-cog" class="w-4 h-4" />
              API Subscriptions
            </NuxtLink>
            <NuxtLink
              to="/dashboard/spaces"
              class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-titanium-200 dark:border-titanium-700 text-sm font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
            >
              <Icon name="lucide:globe" class="w-4 h-4" />
              Manage Spaces
            </NuxtLink>
            <NuxtLink
              to="/dashboard/referrals"
              class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-titanium-200 dark:border-titanium-700 text-sm font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
            >
              <Icon name="lucide:share-2" class="w-4 h-4" />
              Referrals
            </NuxtLink>
            <NuxtLink
              to="/dashboard/profile"
              class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-titanium-200 dark:border-titanium-700 text-sm font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
            >
              <Icon name="lucide:user-cog" class="w-4 h-4" />
              Profile & Security
            </NuxtLink>
          </div>
        </DashboardDashboardCard>
      </div>

      <!-- Recent Activity -->
      <div class="bottom-card-anim">
        <DashboardDashboardCard title="Recent activity" subtitle="Latest account events">
          <DashboardActivityTimeline :items="recentActivity" />
        </DashboardDashboardCard>
      </div>
    </div>

    <!-- Recent Records -->
    <div class="records-anim">
      <DashboardDashboardCard title="Recent records">
        <div v-if="fetchError" class="rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 text-sm">
          {{ fetchError }}
        </div>
        <DashboardSkeletonLoader v-else-if="pending" variant="row" :count="3" />
        <DashboardEmptyState
          v-else-if="licenses.length === 0"
          title="No records yet"
          description="Your purchased licenses and subscriptions will appear here."
          action-label="Browse Products"
          action-to="/products"
        />
        <div v-else class="space-y-3">
          <article
            v-for="license in licenses.slice(0, 3)"
            :key="license.id"
            class="rounded-xl border border-titanium-200 dark:border-titanium-700 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:border-titanium-300 dark:hover:border-titanium-600 transition-colors"
          >
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-10 h-10 rounded-lg bg-titanium-100 dark:bg-titanium-800 flex items-center justify-center flex-shrink-0">
                <Icon
                  :name="['api_service', 'api_referral'].includes(license.product.type) ? 'lucide:cloud-cog' : 'lucide:download'"
                  class="w-5 h-5 text-titanium-500 dark:text-titanium-400"
                />
              </div>
              <div class="min-w-0">
                <p class="font-semibold text-titanium-900 dark:text-white truncate">{{ license.product.title }}</p>
                <div class="flex items-center gap-2 mt-0.5">
                  <span class="text-xs text-titanium-500 dark:text-titanium-400">{{ license.type_label }}</span>
                  <DashboardStatusBadge :status="license.status" />
                </div>
              </div>
            </div>
            <NuxtLink
              :to="`/dashboard/licenses/${license.id}`"
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-titanium-900 dark:bg-titanium-700 text-white text-xs font-semibold hover:bg-titanium-800 dark:hover:bg-titanium-600 transition-colors flex-shrink-0"
            >
              View
              <Icon name="lucide:arrow-right" class="w-3.5 h-3.5" />
            </NuxtLink>
          </article>
        </div>
      </DashboardDashboardCard>
    </div>
  </div>
</template>
