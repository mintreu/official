<script setup lang="ts">
import { computed, reactive, ref, watch } from 'vue'
import { resolveApiError } from '~/utils/api-error'
import type { ApiSpaceSummary, LicenseDetailPayload, ProductReview } from '~/types/api'

definePageMeta({
  layout: 'dashboard',
  middleware: ['$auth'],
  title: 'License Record'
})

const route = useRoute()
const licenseUuid = computed(() => String(route.params.uuid ?? ''))

const {
  data: detailPayload,
  pending,
  error,
  refresh
} = await useAsyncData(
  () => `dashboard-license-${licenseUuid.value}`,
  () => useSanctumFetch<LicenseDetailPayload>(`/api/licenses/${licenseUuid.value}`, { method: 'GET' })
)

const license = computed(() => detailPayload.value?.data ?? null)
const upgradeOptions = computed(() => detailPayload.value?.upgrade_options ?? [])
const matchedFrontends = computed(() => detailPayload.value?.matched_frontends ?? [])
const isApiLicense = computed(() => !!license.value && ['api_service', 'api_referral'].includes(license.value.product.type))

const {
  data: spacesPayload,
  refresh: refreshSpaces
} = await useAsyncData(
  () => `dashboard-spaces-${licenseUuid.value}`,
  () => {
    const keyId = license.value?.api_key?.id
    if (!keyId) {
      return Promise.resolve({ data: [] as ApiSpaceSummary[] })
    }
    return useSanctumFetch<{ data: ApiSpaceSummary[] }>('/api/api-spaces', {
      method: 'GET',
      query: { api_key_id: keyId }
    })
  },
  { watch: [license] }
)

const spaces = computed(() => spacesPayload.value?.data ?? [])
const apiError = computed(() => (error.value ? resolveApiError(error.value, 'Unable to load license record.') : null))

const selectedRating = ref(0)
const reviewText = ref('')
const reviewMessage = ref<string | null>(null)
const reviewError = ref<string | null>(null)
const reviewSubmitting = ref(false)

const { data: reviewPayload, refresh: refreshReview } = await useAsyncData(
  () => `dashboard-review-${licenseUuid.value}`,
  async () => {
    if (!license.value?.product?.slug) {
      return { data: null as ProductReview | null }
    }
    return await useSanctumFetch<{ data: ProductReview | null }>('/api/reviews/my', {
      method: 'GET',
      query: { product_slug: license.value.product.slug }
    })
  },
  { watch: [license] }
)

const currentReview = computed(() => reviewPayload.value?.data ?? null)

watch(currentReview, (value) => {
  selectedRating.value = value?.rating ?? 0
  reviewText.value = value?.review ?? ''
}, { immediate: true })

const creatingSpace = ref(false)
const createError = ref<string | null>(null)
const createSuccess = ref<string | null>(null)
const spaceForm = reactive({
  name: '',
  website: '',
  environment: 'prod'
})

const normalizeWebsiteInput = (value: string): string => {
  let normalized = value.trim()
  if (!normalized) {
    return normalized
  }

  normalized = normalized.replace(/^ht+tps:\/\//i, 'https://')
  normalized = normalized.replace(/^ttps:\/\//i, 'https://')
  normalized = normalized.replace(/^htp:\/\//i, 'http://')

  if (!/^https?:\/\//i.test(normalized)) {
    normalized = `https://${normalized}`
  }

  return normalized
}

const createSpace = async () => {
  if (!license.value?.api_key?.id) {
    return
  }

  createError.value = null
  createSuccess.value = null
  creatingSpace.value = true
  const normalizedWebsite = normalizeWebsiteInput(spaceForm.website)
  spaceForm.website = normalizedWebsite
  try {
    await useSanctumFetch('/api/api-spaces', {
      method: 'POST',
      body: {
        api_key_id: license.value.api_key.id,
        name: spaceForm.name,
        website: normalizedWebsite,
        environment: spaceForm.environment
      }
    })
    createSuccess.value = 'Site created successfully.'
    spaceForm.name = ''
    spaceForm.website = ''
    spaceForm.environment = 'prod'
    await refreshSpaces()
  } catch (err: unknown) {
    createError.value = resolveApiError(err, 'Unable to create site.')
  } finally {
    creatingSpace.value = false
  }
}

const credentialsForm = reactive({
  name: '',
  environment: 'prod',
  allowedDomains: '',
  ipWhitelist: ''
})
const credentialsSaving = ref(false)
const credentialsMessage = ref<string | null>(null)
const credentialsError = ref<string | null>(null)

watch(license, (value) => {
  if (!value?.api_key) {
    return
  }
  credentialsForm.name = value.api_key.name ?? ''
  credentialsForm.environment = value.api_key.environment ?? 'prod'
  credentialsForm.allowedDomains = (value.api_key.allowed_domains ?? []).join(', ')
  credentialsForm.ipWhitelist = (value.api_key.ip_whitelist ?? []).join(', ')
}, { immediate: true })

const saveCredentials = async () => {
  if (!license.value) {
    return
  }

  credentialsSaving.value = true
  credentialsMessage.value = null
  credentialsError.value = null
  try {
    await useSanctumFetch(`/api/licenses/${license.value.uuid}/api-key`, {
      method: 'PUT',
      body: {
        name: credentialsForm.name || null,
        environment: credentialsForm.environment,
        allowed_domains: credentialsForm.allowedDomains
          .split(',')
          .map((item) => item.trim())
          .filter(Boolean),
        ip_whitelist: credentialsForm.ipWhitelist
          .split(',')
          .map((item) => item.trim())
          .filter(Boolean)
      }
    })
    credentialsMessage.value = 'Credentials updated.'
    await refresh()
  } catch (err: unknown) {
    credentialsError.value = resolveApiError(err, 'Unable to update credentials.')
  } finally {
    credentialsSaving.value = false
  }
}

const upgradingPlanId = ref<number | null>(null)
const upgradeMessage = ref<string | null>(null)
const upgradeError = ref<string | null>(null)
const upgradeLicense = async (planId: number) => {
  if (!license.value) {
    return
  }

  upgradingPlanId.value = planId
  upgradeMessage.value = null
  upgradeError.value = null
  try {
    await useSanctumFetch(`/api/licenses/${license.value.uuid}/upgrade`, {
      method: 'POST',
      body: { plan_id: planId }
    })
    upgradeMessage.value = 'Plan upgraded successfully.'
    await refresh()
    await refreshSpaces()
  } catch (err: unknown) {
    upgradeError.value = resolveApiError(err, 'Unable to upgrade license.')
  } finally {
    upgradingPlanId.value = null
  }
}

const downloading = ref(false)
const downloadError = ref<string | null>(null)
const downloadAsset = async () => {
  if (!license.value?.product?.slug) {
    return
  }
  downloading.value = true
  downloadError.value = null
  try {
    const payload = await useSanctumFetch<{ download_url?: string }>(`/api/products/${license.value.product.slug}/download`, {
      method: 'POST'
    })
    if (payload?.download_url && process.client) {
      window.open(payload.download_url, '_blank')
    }
  } catch (err: unknown) {
    downloadError.value = resolveApiError(err, 'Unable to generate download link.')
  } finally {
    downloading.value = false
  }
}

const envOptions = [
  { label: 'Production', value: 'prod' },
  { label: 'Staging', value: 'staging' },
  { label: 'Development', value: 'dev' },
]
const credEnvOptions = [
  { label: 'Production', value: 'prod' },
  { label: 'Development', value: 'dev' },
]

const submitReview = async () => {
  if (!license.value?.product?.slug) return
  if (selectedRating.value < 1 || selectedRating.value > 5) {
    reviewError.value = 'Please select a rating from 1 to 5.'
    return
  }

  reviewSubmitting.value = true
  reviewMessage.value = null
  reviewError.value = null
  try {
    await useSanctumFetch('/api/reviews', {
      method: 'POST',
      body: {
        product_slug: license.value.product.slug,
        rating: selectedRating.value,
        review: reviewText.value || null,
        license_uuid: license.value.uuid
      }
    })
    reviewMessage.value = 'Thanks. Your review has been saved.'
    await refreshReview()
    await refresh()
  } catch (err: unknown) {
    reviewError.value = resolveApiError(err, 'Unable to save review.')
  } finally {
    reviewSubmitting.value = false
  }
}
</script>

<template>
  <!-- Back Link -->
  <NuxtLink
    to="/dashboard/licenses"
    class="inline-flex items-center gap-1.5 text-sm font-semibold text-titanium-500 dark:text-titanium-400 hover:text-mintreu-red-600 dark:hover:text-mintreu-red-400 transition-colors"
  >
    <Icon name="lucide:arrow-left" class="w-4 h-4" />
    Back to licenses
  </NuxtLink>

  <!-- Error -->
  <div v-if="apiError" class="rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 text-sm">
    {{ apiError }}
  </div>

  <!-- Loading -->
  <DashboardSkeletonLoader v-else-if="pending || !license" variant="card" :count="2" />

  <!-- Content -->
  <template v-else>
    <!-- License Header -->
    <DashboardDashboardCard>
      <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] font-semibold text-titanium-500 dark:text-titanium-400 mb-1">
            {{ isApiLicense ? 'API subscription record' : 'Paid download record' }}
          </p>
          <h1 class="text-2xl font-heading font-black text-titanium-900 dark:text-white">{{ license.product.title }}</h1>
          <p v-if="license.product.short_description" class="text-sm text-titanium-500 dark:text-titanium-400 mt-1">{{ license.product.short_description }}</p>
        </div>
        <DashboardStatusBadge :status="license.status" size="md" />
      </div>

      <!-- Meta Grid -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
        <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 px-4 py-3">
          <p class="text-[10px] uppercase tracking-wider font-semibold text-titanium-400 dark:text-titanium-500">License key</p>
          <p class="text-sm font-semibold text-titanium-900 dark:text-white mt-0.5 font-mono truncate">{{ license.license_key }}</p>
        </div>
        <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 px-4 py-3">
          <p class="text-[10px] uppercase tracking-wider font-semibold text-titanium-400 dark:text-titanium-500">Status</p>
          <p class="text-sm font-semibold text-titanium-900 dark:text-white mt-0.5 capitalize">{{ license.status }}</p>
        </div>
        <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 px-4 py-3">
          <p class="text-[10px] uppercase tracking-wider font-semibold text-titanium-400 dark:text-titanium-500">Expires</p>
          <p class="text-sm font-semibold text-titanium-900 dark:text-white mt-0.5">{{ license.expires_at ? new Date(license.expires_at).toLocaleDateString() : 'Never' }}</p>
        </div>
        <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 px-4 py-3">
          <p class="text-[10px] uppercase tracking-wider font-semibold text-titanium-400 dark:text-titanium-500">Plan</p>
          <p class="text-sm font-semibold text-titanium-900 dark:text-white mt-0.5">{{ license.plan?.name ?? 'N/A' }}</p>
        </div>
      </div>

      <button
        type="button"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-titanium-200 dark:border-titanium-700 text-sm font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors mt-2"
        @click="refresh()"
      >
        <Icon name="lucide:refresh-cw" class="w-4 h-4" />
        Refresh record
      </button>
    </DashboardDashboardCard>

    <!-- Download Credentials (non-API) -->
    <DashboardDashboardCard v-if="!isApiLicense" title="Download credentials">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <DashboardProgressBar
          :value="license.usage_count"
          :max="license.max_usage"
          label="Downloads used"
          color="blue"
        />
        <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 px-4 py-3">
          <p class="text-[10px] uppercase tracking-wider font-semibold text-titanium-400 dark:text-titanium-500">Remaining</p>
          <p class="text-sm font-semibold text-titanium-900 dark:text-white mt-0.5">{{ license.remaining_usages ?? 'Unlimited' }}</p>
        </div>
        <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 px-4 py-3">
          <p class="text-[10px] uppercase tracking-wider font-semibold text-titanium-400 dark:text-titanium-500">Version</p>
          <p class="text-sm font-semibold text-titanium-900 dark:text-white mt-0.5">{{ license.product.version ?? 'latest' }}</p>
        </div>
      </div>

      <div class="flex flex-wrap items-center gap-3 mt-2">
        <button
          type="button"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-mintreu-red-600 text-white text-sm font-semibold hover:bg-mintreu-red-700 transition-colors disabled:opacity-60"
          :disabled="downloading"
          @click="downloadAsset()"
        >
          <Icon name="lucide:download" class="w-4 h-4" />
          {{ downloading ? 'Preparing...' : 'Download latest build' }}
        </button>
        <NuxtLink
          :to="`/products/${license.product.slug}`"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-titanium-200 dark:border-titanium-700 text-sm font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
        >
          <Icon name="lucide:external-link" class="w-4 h-4" />
          Product page
        </NuxtLink>
      </div>
      <p v-if="downloadError" class="text-xs text-rose-600 dark:text-rose-400 mt-2">{{ downloadError }}</p>
    </DashboardDashboardCard>

    <!-- API Credentials & Sites (API licenses) -->
    <DashboardDashboardCard v-if="isApiLicense" title="API credentials & usage">
      <!-- Usage bars -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 px-4 py-3">
          <p class="text-[10px] uppercase tracking-wider font-semibold text-titanium-400 dark:text-titanium-500 mb-2">API key prefix</p>
          <p class="text-sm font-semibold text-titanium-900 dark:text-white font-mono">{{ license.api_key?.key_prefix ?? 'N/A' }}</p>
        </div>
        <DashboardProgressBar
          :value="license.api_key?.requests_this_month ?? 0"
          :max="license.plan?.requests_per_month ?? null"
          label="Monthly usage"
          color="emerald"
        />
        <DashboardProgressBar
          :value="license.api_key?.requests_today ?? 0"
          :max="license.plan?.requests_per_day ?? null"
          label="Today's usage"
          color="blue"
        />
      </div>

      <!-- Credential Config -->
      <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 p-5 space-y-4 mt-2">
        <p class="text-sm font-semibold text-titanium-900 dark:text-white">Credential configuration</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <DashboardFormInput
            v-model="credentialsForm.name"
            label="Key label"
            placeholder="e.g. My Production Key"
          />
          <DashboardFormSelect
            v-model="credentialsForm.environment"
            label="Environment"
            :options="credEnvOptions"
          />
          <DashboardFormInput
            v-model="credentialsForm.allowedDomains"
            label="Allowed domains"
            placeholder="domain1.com, domain2.com"
          />
          <DashboardFormInput
            v-model="credentialsForm.ipWhitelist"
            label="IP whitelist"
            placeholder="192.168.1.1, 10.0.0.1"
          />
        </div>
        <div class="flex items-center gap-3">
          <button
            type="button"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-titanium-900 dark:bg-titanium-700 text-white text-sm font-semibold hover:bg-titanium-800 dark:hover:bg-titanium-600 transition-colors disabled:opacity-60"
            :disabled="credentialsSaving"
            @click="saveCredentials()"
          >
            <Icon name="lucide:save" class="w-4 h-4" />
            {{ credentialsSaving ? 'Saving...' : 'Save credentials' }}
          </button>
          <span v-if="credentialsMessage" class="text-xs text-emerald-600 dark:text-emerald-400">{{ credentialsMessage }}</span>
          <span v-if="credentialsError" class="text-xs text-rose-600 dark:text-rose-400">{{ credentialsError }}</span>
        </div>
      </div>

      <!-- Create Site -->
      <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 p-5 space-y-4 mt-2">
        <p class="text-sm font-semibold text-titanium-900 dark:text-white">Setup new site</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <DashboardFormInput
            v-model="spaceForm.name"
            label="Site name"
            placeholder="My App"
          />
          <DashboardFormInput
            v-model="spaceForm.website"
            type="url"
            label="Website"
            placeholder="https://client-site.com"
          />
          <DashboardFormSelect
            v-model="spaceForm.environment"
            label="Environment"
            :options="envOptions"
          />
        </div>
        <div class="flex items-center gap-3">
          <button
            type="button"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-mintreu-red-600 text-white text-sm font-semibold hover:bg-mintreu-red-700 transition-colors disabled:opacity-60"
            :disabled="creatingSpace"
            @click="createSpace()"
          >
            <Icon name="lucide:plus" class="w-4 h-4" />
            {{ creatingSpace ? 'Creating...' : 'Create site' }}
          </button>
          <span v-if="createSuccess" class="text-xs text-emerald-600 dark:text-emerald-400">{{ createSuccess }}</span>
          <span v-if="createError" class="text-xs text-rose-600 dark:text-rose-400">{{ createError }}</span>
        </div>
      </div>

      <!-- Sites List -->
      <div class="space-y-3 mt-2">
        <p class="text-sm font-semibold text-titanium-900 dark:text-white">Sites</p>
        <DashboardEmptyState
          v-if="spaces.length === 0"
          icon="lucide:globe"
          title="No sites configured"
          description="Create a site above to start tracking API usage for your websites."
        />
        <article
          v-for="space in spaces"
          :key="space.uuid"
          class="rounded-xl border border-titanium-200 dark:border-titanium-700 p-4 space-y-3 hover:border-titanium-300 dark:hover:border-titanium-600 transition-colors"
        >
          <div class="flex items-center justify-between">
            <p class="font-semibold text-titanium-900 dark:text-white">{{ space.name }}</p>
            <DashboardStatusBadge :status="space.environment" />
          </div>
          <p class="text-sm text-titanium-500 dark:text-titanium-400">{{ space.website }}</p>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
            <div class="rounded-lg bg-titanium-50 dark:bg-titanium-800/50 px-3 py-2">
              <span class="text-titanium-500 dark:text-titanium-400">Monthly: </span>
              <span class="font-semibold text-titanium-900 dark:text-white">{{ space.requests_this_month }}</span>
            </div>
            <div class="rounded-lg bg-titanium-50 dark:bg-titanium-800/50 px-3 py-2">
              <span class="text-titanium-500 dark:text-titanium-400">Today: </span>
              <span class="font-semibold text-titanium-900 dark:text-white">{{ space.requests_today }}</span>
            </div>
            <div class="rounded-lg bg-titanium-50 dark:bg-titanium-800/50 px-3 py-2">
              <span class="text-titanium-500 dark:text-titanium-400">Top endpoint: </span>
              <span class="font-semibold text-titanium-900 dark:text-white">{{ space.insights?.top_endpoint ?? 'N/A' }}</span>
            </div>
          </div>
        </article>
      </div>
    </DashboardDashboardCard>

    <!-- Upgrade Options -->
    <DashboardDashboardCard v-if="upgradeOptions.length > 0" title="Upgrade options">
      <div v-if="upgradeMessage" class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 text-sm">
        {{ upgradeMessage }}
      </div>
      <div v-if="upgradeError" class="rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 text-sm">
        {{ upgradeError }}
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <article
          v-for="plan in upgradeOptions"
          :key="plan.id"
          class="rounded-xl border border-titanium-200 dark:border-titanium-700 p-5 space-y-3 hover:border-mintreu-red-300 dark:hover:border-mintreu-red-800 transition-colors"
        >
          <p class="font-semibold text-titanium-900 dark:text-white">{{ plan.name }}</p>
          <p class="text-sm text-titanium-500 dark:text-titanium-400">{{ plan.description }}</p>
          <p class="text-xl font-heading font-black text-mintreu-red-600">{{ plan.price_formatted }}</p>
          <button
            type="button"
            class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 rounded-xl bg-mintreu-red-600 text-white text-sm font-semibold hover:bg-mintreu-red-700 transition-colors disabled:opacity-60"
            :disabled="upgradingPlanId === plan.id"
            @click="upgradeLicense(plan.id)"
          >
            <Icon name="lucide:zap" class="w-4 h-4" />
            {{ upgradingPlanId === plan.id ? 'Upgrading...' : 'Upgrade license' }}
          </button>
        </article>
      </div>
    </DashboardDashboardCard>

    <!-- Matched Frontends -->
    <DashboardDashboardCard v-if="isApiLicense && matchedFrontends.length > 0" title="Matched frontends" subtitle="Recommended frontend products that integrate with this API.">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <article
          v-for="frontend in matchedFrontends"
          :key="frontend.id"
          class="rounded-xl border border-titanium-200 dark:border-titanium-700 p-5 space-y-3 hover:border-titanium-300 dark:hover:border-titanium-600 transition-colors"
        >
          <p class="font-semibold text-titanium-900 dark:text-white">{{ frontend.title }}</p>
          <p class="text-sm text-titanium-500 dark:text-titanium-400">{{ frontend.short_description }}</p>
          <p class="text-base font-heading font-bold text-titanium-900 dark:text-white">${{ frontend.price.toFixed(2) }}</p>
          <NuxtLink
            :to="`/products/${frontend.slug}`"
            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-titanium-200 dark:border-titanium-700 text-xs font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
          >
            View frontend
            <Icon name="lucide:external-link" class="w-3.5 h-3.5" />
          </NuxtLink>
        </article>
      </div>
    </DashboardDashboardCard>

    <DashboardDashboardCard title="Rating & review" subtitle="Rate your purchased product/service and share your feedback.">
      <div class="space-y-4">
        <div class="flex items-center gap-2">
          <button
            v-for="star in 5"
            :key="star"
            type="button"
            class="p-1"
            @click="selectedRating = star"
          >
            <Icon
              name="lucide:star"
              class="w-6 h-6 transition-colors"
              :class="star <= selectedRating ? 'text-amber-500 fill-amber-500' : 'text-titanium-300 dark:text-titanium-600'"
            />
          </button>
          <span class="text-xs text-titanium-500 dark:text-titanium-400 ml-2">
            {{ selectedRating > 0 ? `${selectedRating}/5` : 'Select rating' }}
          </span>
        </div>

        <textarea
          v-model="reviewText"
          rows="4"
          class="w-full rounded-xl border border-titanium-200 dark:border-titanium-700 bg-white dark:bg-titanium-900 px-3 py-2 text-sm text-titanium-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-mintreu-red-500/30"
          placeholder="Write your review (optional)..."
        />

        <div class="flex items-center gap-3">
          <button
            type="button"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-mintreu-red-600 text-white text-sm font-semibold hover:bg-mintreu-red-700 transition-colors disabled:opacity-60"
            :disabled="reviewSubmitting"
            @click="submitReview()"
          >
            <Icon name="lucide:send" class="w-4 h-4" />
            {{ reviewSubmitting ? 'Saving...' : 'Submit review' }}
          </button>
          <span v-if="currentReview?.updated_at" class="text-xs text-titanium-500 dark:text-titanium-400">
            Last updated: {{ new Date(currentReview.updated_at).toLocaleString() }}
          </span>
        </div>

        <p v-if="reviewMessage" class="text-xs text-emerald-600 dark:text-emerald-400">{{ reviewMessage }}</p>
        <p v-if="reviewError" class="text-xs text-rose-600 dark:text-rose-400">{{ reviewError }}</p>
      </div>
    </DashboardDashboardCard>
  </template>
</template>
