<script setup lang="ts">
import { computed, ref } from 'vue'
import { resolveApiError } from '~/app/utils/api-error'
import type { ApiResponse, DownloadLinkResponse, FeatureFlags, LicenseDashboardPayload, LicenseSummary } from '~/types/api'

definePageMeta({
  middleware: ['$auth'],
  title: 'Account Dashboard'
})

const config = useRuntimeConfig()
const { user } = useSanctum()

const {
  data: licensePayload,
  pending: licensePending,
  error: licensesError,
  refresh: refreshLicenses
} = await useAsyncData('dashboard-licenses', () =>
  useSanctumFetch<ApiResponse<LicenseDashboardPayload>>('/api/licenses', { method: 'GET' })
    .then((response) => response.data)
)

const licenses = computed(() => licensePayload.value?.data ?? [])
const downloads = computed(() => licenses.value.filter((license) => license.product.type === 'downloadable'))
const apiSubscriptions = computed(() => licenses.value.filter((license) => license.product.type === 'api_service'))
const featureFlags = computed<FeatureFlags>(() => licensePayload.value?.feature_flags ?? {
  downloads_enabled: true,
  api_access_enabled: true,
  licensing_enabled: true
})

const licenseFetchError = computed(() => (licensesError ? resolveApiError(licensesError, 'Unable to load dashboard data.') : null))

const downloadingSlug = ref<string | null>(null)
const downloadError = ref<string | null>(null)
const licenseCopyMessage = ref<string | null>(null)

const downloadLicense = async (license: LicenseSummary) => {
  if (!license.product.slug) {
    return
  }

  downloadError.value = null
  downloadingSlug.value = license.product.slug
  try {
    const response = await useSanctumFetch<ApiResponse<DownloadLinkResponse>>(`/api/products/${license.product.slug}/download`, {
      method: 'POST'
    })
    const url = response?.data?.download_url
    if (url && process.client) {
      window.open(url, '_blank')
    }
  } catch (error: unknown) {
    downloadError.value = resolveApiError(error, 'Unable to generate the download link.')
  } finally {
    downloadingSlug.value = null
  }
}

const copyKeyPrefix = async (license: LicenseSummary) => {
  const value = license.api_key?.key_prefix
  if (!value) {
    return
  }
  try {
    if (process.client && navigator.clipboard) {
      await navigator.clipboard.writeText(value)
      licenseCopyMessage.value = 'Key prefix copied'
    }
  } catch {
    licenseCopyMessage.value = 'Unable to copy'
  } finally {
    setTimeout(() => {
      licenseCopyMessage.value = null
    }, 1600)
  }
}

const monthlyUsagePercent = (license: LicenseSummary): number => {
  const limit = license.plan?.requests_per_month ?? license.api_key?.plan?.requests_per_month
  const used = license.api_key?.requests_this_month ?? 0
  if (!limit || limit <= 0) {
    return 0
  }
  return Math.min(100, Math.round((used / limit) * 100))
}

const downloadUsagePercent = (license: LicenseSummary): number => {
  if (!license.max_usage) {
    return 0
  }
  return Math.min(100, Math.round((license.usage_count / license.max_usage) * 100))
}

const formatDate = (value?: string | null) => {
  if (!value) {
    return 'N/A'
  }
  return new Date(value).toLocaleDateString()
}

const statusLabels: Record<string, string> = {
  active: 'Active',
  expired: 'Expired',
  disabled: 'Disabled',
  usage_limit: 'Usage limit reached'
}

const statusClasses: Record<string, string> = {
  active: 'bg-emerald-50 border border-emerald-200 text-emerald-700',
  expired: 'bg-amber-50 border border-amber-200 text-amber-700',
  disabled: 'bg-slate-100 border border-slate-200 text-slate-600',
  usage_limit: 'bg-rose-50 border border-rose-200 text-rose-700'
}

const flagLabels: Record<keyof FeatureFlags, string> = {
  downloads_enabled: 'Downloads',
  api_access_enabled: 'API access',
  licensing_enabled: 'Licensing guardrails'
}
</script>

<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 py-10">
    <div class="max-w-7xl mx-auto px-4 space-y-6">
      <section class="bg-white dark:bg-titanium-900/80 border border-titanium-200 dark:border-titanium-800 rounded-3xl p-6 space-y-4 shadow-sm">
        <div class="flex flex-col gap-4">
          <div>
            <p class="text-[10px] uppercase tracking-[0.6em] text-titanium-500">Account dashboard</p>
            <h1 class="text-3xl lg:text-4xl font-heading font-black text-titanium-900 dark:text-white">Paid downloads &amp; API keys</h1>
            <p class="text-sm text-titanium-500 dark:text-titanium-400">Monitor your purchased downloads, licenses, and API subscriptions in one guarded view.</p>
          </div>
          <div class="flex flex-wrap gap-3">
            <NuxtLink to="/contact" class="px-4 py-2 bg-mintreu-red-600 text-white rounded-2xl text-sm font-semibold">Contact support</NuxtLink>
            <NuxtLink to="/auth/forgot-password" class="px-4 py-2 border border-titanium-200 rounded-2xl text-sm font-semibold text-titanium-700">Reset password</NuxtLink>
            <NuxtLink to="/products" class="px-4 py-2 bg-slate-100 rounded-2xl text-sm font-semibold text-titanium-700">Browse products</NuxtLink>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="p-4 bg-titanium-100 dark:bg-titanium-800/60 rounded-2xl border border-titanium-200 dark:border-titanium-800">
              <p class="text-xs uppercase text-titanium-500">Logged in as</p>
              <p class="font-semibold text-titanium-900 dark:text-white">{{ user?.value?.name ?? 'Client Demo' }}</p>
              <p class="text-titanium-500 dark:text-titanium-400">{{ user?.value?.email }}</p>
            </div>
            <div class="p-4 bg-titanium-100 dark:bg-titanium-800/60 rounded-2xl border border-titanium-200 dark:border-titanium-800">
              <p class="text-xs uppercase text-titanium-500">Paid downloads</p>
              <p class="text-2xl font-bold text-mintreu-red-600">{{ downloads.length }}</p>
            </div>
            <div class="p-4 bg-titanium-100 dark:bg-titanium-800/60 rounded-2xl border border-titanium-200 dark:border-titanium-800">
              <p class="text-xs uppercase text-titanium-500">API subscriptions</p>
              <p class="text-2xl font-bold text-emerald-600">{{ apiSubscriptions.length }}</p>
            </div>
          </div>
          <div v-if="licenseFetchError" class="rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 text-sm">
            {{ licenseFetchError }}
          </div>
        </div>
      </section>

      <section class="bg-white dark:bg-titanium-900/80 border border-titanium-200 dark:border-titanium-800 rounded-3xl p-6 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
          <div>
            <p class="text-[10px] uppercase tracking-[0.6em] text-titanium-500">Licensed downloads</p>
            <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white">One-time purchases</h2>
            <p class="text-sm text-titanium-500 dark:text-titanium-400">Download the software you already own and keep an eye on limits.</p>
          </div>
          <div class="flex gap-3">
            <button class="px-4 py-2 text-sm font-semibold border border-titanium-200 rounded-2xl" type="button" @click="refreshLicenses()">Refresh</button>
            <NuxtLink to="/products" class="px-4 py-2 text-sm font-semibold bg-mintreu-red-600 text-white rounded-2xl">View store</NuxtLink>
          </div>
        </div>

        <div v-if="licensePending" class="space-y-3 mt-4">
          <div v-for="s in 2" :key="s" class="h-40 rounded-2xl bg-slate-100 animate-pulse"></div>
        </div>

        <div v-else-if="downloads.length === 0" class="mt-4 rounded-2xl border border-dashed border-titanium-200 dark:border-titanium-800 bg-titanium-100 dark:bg-titanium-900/50 px-5 py-6 text-sm text-titanium-600">
          <p>You haven't purchased any downloadable assets yet. Start with a flagship template and the license will appear here.</p>
          <NuxtLink to="/products" class="inline-flex mt-3 text-sm font-semibold text-mintreu-red-600">Browse paid downloads ?</NuxtLink>
        </div>

        <div v-else class="space-y-4 mt-4">
          <article v-for="license in downloads" :key="license.id" class="space-y-4 rounded-3xl border border-titanium-200 dark:border-titanium-800 bg-titanium-50 dark:bg-titanium-900/60 p-4">
            <div class="flex flex-col sm:flex-row justify-between gap-3">
              <div>
                <p class="text-lg font-semibold text-titanium-900 dark:text-white">{{ license.product.title }}</p>
                <p class="text-sm text-titanium-500 dark:text-titanium-400">{{ license.product.short_description ?? 'No description available.' }}</p>
              </div>
              <span :class="['px-3 py-1 rounded-full text-xs font-semibold', statusClasses[license.status] ?? 'bg-slate-100 border border-slate-200 text-slate-600']">
                {{ statusLabels[license.status] ?? license.status }}
              </span>
            </div>
            <div class="flex flex-wrap gap-3 text-xs text-titanium-500 dark:text-titanium-400">
              <span>Version {{ license.product.version ?? 'latest' }}</span>
              <span>Downloads {{ license.product.engagement.downloads }}</span>
              <span>Rating {{ (license.product.engagement.rating ?? 0).toFixed(1) }}</span>
            </div>
            <div class="flex flex-wrap gap-3 mt-4">
              <button
                class="px-4 py-2 rounded-2xl bg-mintreu-red-600 text-white text-sm font-semibold"
                type="button"
                :disabled="downloadingSlug === license.product.slug"
                @click="downloadLicense(license)"
              >
                <span v-if="downloadingSlug === license.product.slug">Preparing…</span>
                <span v-else>Download latest build</span>
              </button>
              <NuxtLink
                v-if="license.product.documentation_url"
                :href="license.product.documentation_url"
                target="_blank"
                rel="noreferrer"
                class="px-4 py-2 rounded-2xl border border-titanium-200 text-sm font-semibold text-titanium-700"
              >
                Documentation
              </NuxtLink>
              <span class="text-xs text-titanium-500 dark:text-titanium-400">Expires {{ formatDate(license.expires_at) }}</span>
            </div>
            <div class="mt-4 space-y-2">
              <div
                v-for="source in license.download_sources"
                :key="source.id"
                class="flex items-start justify-between gap-3 p-3 bg-white dark:bg-titanium-900 border border-titanium-200 dark:border-titanium-800 rounded-2xl"
              >
                <div>
                  <p class="font-semibold text-titanium-900 dark:text-white">{{ source.name }}</p>
                  <p class="text-xs text-titanium-500 dark:text-titanium-400">{{ source.provider_label }} · {{ source.version ?? 'latest' }}</p>
                </div>
                <div class="text-right text-xs text-titanium-500 dark:text-titanium-400">
                  <p>{{ source.file_name ?? 'package' }}</p>
                  <p v-if="source.file_size_formatted">{{ source.file_size_formatted }}</p>
                </div>
              </div>
            </div>
            <div class="text-xs text-titanium-500 dark:text-titanium-400">
              Usage: {{ license.usage_count }}{{ license.max_usage ? ` / ${license.max_usage}` : '' }}
            </div>
            <div class="mt-1 h-1.5 rounded-full bg-slate-100 dark:bg-titanium-800">
              <div class="h-full rounded-full bg-mintreu-red-600" :style="{ width: `${downloadUsagePercent(license)}%` }"></div>
            </div>
          </article>
        </div>

        <div v-if="downloadError" class="rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 text-sm">
          {{ downloadError }}
        </div>
      </section>

      <section class="bg-white dark:bg-titanium-900/80 border border-titanium-200 dark:border-titanium-800 rounded-3xl p-6 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
          <div>
            <p class="text-[10px] uppercase tracking-[0.6em] text-titanium-500">API subscriptions</p>
            <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white">Active API plans</h2>
            <p class="text-sm text-titanium-500 dark:text-titanium-400">Track API keys, usage, and renewal dates for your SaaS access.</p>
          </div>
          <div class="flex gap-3">
            <button class="px-4 py-2 text-sm font-semibold border border-titanium-200 rounded-2xl" type="button" @click="refreshLicenses()">Refresh</button>
            <NuxtLink to="/contact" class="px-4 py-2 bg-slate-100 rounded-2xl text-sm font-semibold text-titanium-700">Need help?</NuxtLink>
          </div>
        </div>

        <div v-if="licensePending" class="space-y-3 mt-4">
          <div v-for="s in 2" :key="`api-${s}`" class="h-48 rounded-2xl bg-slate-100 animate-pulse"></div>
        </div>

        <div v-else-if="apiSubscriptions.length === 0" class="mt-4 rounded-2xl border border-dashed border-titanium-200 dark:border-titanium-800 bg-titanium-100 dark:bg-titanium-900/50 px-5 py-6 text-sm text-titanium-600">
          <p>You don't have any active API subscriptions yet. Purchase a plan to unlock rate-limited endpoints.</p>
          <NuxtLink to="/products" class="inline-flex mt-3 text-sm font-semibold text-mintreu-red-600">Explore integrations ?</NuxtLink>
        </div>

        <div v-else class="grid gap-4 md:grid-cols-2 mt-4">
          <article v-for="license in apiSubscriptions" :key="license.id" class="space-y-4 rounded-3xl border border-titanium-200 dark:border-titanium-800 bg-titanium-50 dark:bg-titanium-900/60 p-4">
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="text-lg font-semibold text-titanium-900 dark:text-white">{{ license.product.title }}</p>
                <p class="text-xs text-titanium-500 dark:text-titanium-400">{{ license.plan?.name ?? 'API Subscription' }}</p>
              </div>
              <span class="text-xs uppercase tracking-[0.3em] text-titanium-500">{{ license.plan?.price_formatted ?? '$' }}</span>
            </div>
            <div class="space-y-2">
              <div class="flex items-center justify-between text-xs text-titanium-500 dark:text-titanium-400">
                <span>API key</span>
                <span>{{ license.api_key?.environment ?? 'prod' }}</span>
              </div>
              <div class="flex items-center justify-between gap-3 border border-titanium-200 dark:border-titanium-800 rounded-2xl bg-white dark:bg-titanium-900/40 px-4 py-3">
                <p class="font-mono text-sm text-titanium-900 dark:text-white">{{ license.api_key?.key_prefix ?? '••••••••••••' }}</p>
                <button type="button" class="text-xs font-semibold text-mintreu-red-600" @click="copyKeyPrefix(license)">Copy prefix</button>
              </div>
              <p v-if="licenseCopyMessage" class="text-xs text-emerald-600">{{ licenseCopyMessage }}</p>
            </div>
            <div class="space-y-2">
              <div class="flex items-center justify-between text-xs text-titanium-500 dark:text-titanium-400">
                <span>Monthly usage</span>
                <span>{{ license.api_key?.requests_this_month ?? 0 }} / {{ license.plan?.requests_per_month ?? '8' }}</span>
              </div>
              <div class="h-2 rounded-full bg-slate-100 dark:bg-titanium-800">
                <div class="h-full rounded-full bg-emerald-500" :style="{ width: `${monthlyUsagePercent(license)}%` }"></div>
              </div>
              <div class="text-xs text-titanium-500 dark:text-titanium-400">
                Next renewal: {{ formatDate(license.next_renewal_at) }}
              </div>
            </div>
            <div class="flex flex-wrap gap-3">
              <button class="px-4 py-2 text-sm font-semibold border border-titanium-200 rounded-2xl" type="button">Regenerate key</button>
              <NuxtLink to="/contact" class="px-4 py-2 text-sm font-semibold bg-slate-100 rounded-2xl text-titanium-700">Request domain change</NuxtLink>
            </div>
          </article>
        </div>
      </section>

      <section class="bg-white dark:bg-titanium-900/80 border border-titanium-200 dark:border-titanium-800 rounded-3xl p-6 shadow-sm space-y-4">
        <div>
          <p class="text-[10px] uppercase tracking-[0.6em] text-titanium-500">System status</p>
          <h2 class="text-2xl font-heading font-bold text-titanium-900 dark:text-white">Kill switch overview</h2>
          <p class="text-sm text-titanium-500 dark:text-titanium-400">Feature flags guard payments, downloads, licensing, and API access.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div
            v-for="(value, key) in featureFlags"
            :key="key"
            class="p-4 rounded-3xl border border-titanium-200 dark:border-titanium-800 bg-titanium-50 dark:bg-titanium-900/60 space-y-1"
          >
            <p class="text-xs uppercase tracking-[0.5em] text-titanium-500">{{ flagLabels[key as keyof FeatureFlags] }}</p>
            <p class="text-xl font-semibold text-titanium-900 dark:text-white">{{ value ? 'Enabled' : 'Disabled' }}</p>
            <p class="text-xs text-titanium-500">Kill switch {{ value ? 'open' : 'closed' }}</p>
          </div>
        </div>
        <p class="text-xs text-titanium-500">Need a full audit trail? Reach us at <a class="text-mintreu-red-600" :href="`mailto:${config.public.supportEmail}`">{{ config.public.supportEmail }}</a>.</p>
      </section>
    </div>
  </div>
</template>

