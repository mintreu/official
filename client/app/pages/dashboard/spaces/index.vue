<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import { resolveApiError } from '~/utils/api-error'
import type { ApiSpaceSummary, LicenseDashboardPayload } from '~/types/api'

definePageMeta({
  layout: 'dashboard',
  middleware: ['$auth'],
  title: 'Sites'
})

const { data: spacesData, pending, error, refresh } = await useAsyncData(
  'dashboard-spaces-all',
  () => useSanctumFetch<{ data: ApiSpaceSummary[] }>('/api/api-spaces', { method: 'GET' })
)

const { data: apiLicenseData } = await useAsyncData(
  'dashboard-space-license-options',
  () => useSanctumFetch<LicenseDashboardPayload>('/api/licenses', {
    method: 'GET',
    query: { type: 'api', page: 1, per_page: 50 }
  })
)

const spaces = computed(() => spacesData.value?.data ?? [])
const apiLicenses = computed(() => apiLicenseData.value?.data ?? [])
const loadError = computed(() => (error.value ? resolveApiError(error.value, 'Unable to load spaces.') : null))
const search = ref('')
const editingSiteUuid = ref<string | null>(null)
const actionMessage = ref<string | null>(null)
const actionError = ref<string | null>(null)
const actionLoading = ref(false)

const filteredSpaces = computed(() => {
  const term = search.value.trim().toLowerCase()
  if (!term) return spaces.value
  return spaces.value.filter((space) => {
    return [
      space.name,
      space.website,
      space.product?.title ?? '',
      space.environment,
      space.status,
    ].join(' ').toLowerCase().includes(term)
  })
})

const apiKeyOptions = computed(() => {
  const opts = [{ label: 'Select API key', value: '' }]
  for (const license of apiLicenses.value) {
    opts.push({
      label: `${license.product.title} — ${license.api_key?.key_prefix ?? 'No key'}`,
      value: String(license.api_key?.id ?? '')
    })
  }
  return opts
})

const form = reactive({
  api_key_id: '',
  name: '',
  website: '',
  environment: 'prod'
})
const editForm = reactive({
  name: '',
  website: '',
  environment: 'prod'
})
const creating = ref(false)
const createMessage = ref<string | null>(null)
const createError = ref<string | null>(null)

const envOptions = [
  { label: 'Production', value: 'prod' },
  { label: 'Staging', value: 'staging' },
  { label: 'Development', value: 'dev' },
]

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
  if (!form.api_key_id || !form.name || !form.website) {
    createError.value = 'API key, name, and website are required.'
    return
  }
  const normalizedWebsite = normalizeWebsiteInput(form.website)
  form.website = normalizedWebsite
  creating.value = true
  createError.value = null
  createMessage.value = null
  try {
    await useSanctumFetch('/api/api-spaces', {
      method: 'POST',
      body: {
        api_key_id: Number(form.api_key_id),
        name: form.name,
        website: normalizedWebsite,
        environment: form.environment
      }
    })
    createMessage.value = 'Space created successfully.'
    form.name = ''
    form.website = ''
    await refresh()
  } catch (err: unknown) {
    createError.value = resolveApiError(err, 'Unable to create space.')
  } finally {
    creating.value = false
  }
}

const startEdit = (space: ApiSpaceSummary) => {
  editingSiteUuid.value = space.uuid
  editForm.name = space.name
  editForm.website = space.website
  editForm.environment = space.environment
  actionMessage.value = null
  actionError.value = null
}

const cancelEdit = () => {
  editingSiteUuid.value = null
}

const saveEdit = async () => {
  if (!editingSiteUuid.value) return
  actionLoading.value = true
  actionMessage.value = null
  actionError.value = null
  try {
    await useSanctumFetch(`/api/api-spaces/${editingSiteUuid.value}`, {
      method: 'PUT',
      body: {
        name: editForm.name,
        website: normalizeWebsiteInput(editForm.website),
        environment: editForm.environment
      }
    })
    actionMessage.value = 'Site updated successfully.'
    editingSiteUuid.value = null
    await refresh()
  } catch (err: unknown) {
    actionError.value = resolveApiError(err, 'Unable to update site.')
  } finally {
    actionLoading.value = false
  }
}

const disableSite = async (space: ApiSpaceSummary) => {
  if (space.status === 'disabled') return
  const confirmed = window.confirm(`Disable site "${space.name}"? This will soft-delete and mark it disabled.`)
  if (!confirmed) return

  actionLoading.value = true
  actionMessage.value = null
  actionError.value = null
  try {
    await useSanctumFetch(`/api/api-spaces/${space.uuid}`, { method: 'DELETE' })
    actionMessage.value = 'Site disabled successfully.'
    await refresh()
  } catch (err: unknown) {
    actionError.value = resolveApiError(err, 'Unable to disable site.')
  } finally {
    actionLoading.value = false
  }
}
</script>

<template>
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
      <h1 class="text-2xl font-heading font-black text-titanium-900 dark:text-white">Sites</h1>
      <p class="text-sm text-titanium-500 dark:text-titanium-400 mt-1">Create and manage sites connected to your API services.</p>
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

  <DashboardDashboardCard title="Create new site">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <DashboardFormSelect
        v-model="form.api_key_id"
        label="API Key"
        :options="apiKeyOptions"
      />
      <DashboardFormInput
        v-model="form.name"
        label="Site name"
        placeholder="My App"
      />
      <DashboardFormInput
        v-model="form.website"
        type="url"
        label="Website"
        placeholder="https://client-site.com"
      />
      <DashboardFormSelect
        v-model="form.environment"
        label="Environment"
        :options="envOptions"
      />
    </div>
    <div class="flex items-center gap-3">
      <button
        type="button"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-mintreu-red-600 text-white text-sm font-semibold hover:bg-mintreu-red-700 transition-colors disabled:opacity-60"
        :disabled="creating"
        @click="createSpace()"
      >
        <Icon name="lucide:plus" class="w-4 h-4" />
        {{ creating ? 'Creating...' : 'Create site' }}
      </button>
      <span v-if="createMessage" class="text-xs text-emerald-600 dark:text-emerald-400">{{ createMessage }}</span>
      <span v-if="createError" class="text-xs text-rose-600 dark:text-rose-400">{{ createError }}</span>
    </div>
  </DashboardDashboardCard>

  <DashboardDashboardCard title="Site list" subtitle="Search, edit, and disable sites from one place.">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <DashboardFormInput
        v-model="search"
        label="Search"
        placeholder="Search by site, domain, API service, status..."
      />
    </div>

    <div v-if="loadError" class="rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 text-sm">
      {{ loadError }}
    </div>
    <div v-if="actionMessage" class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 text-sm">
      {{ actionMessage }}
    </div>
    <div v-if="actionError" class="rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 text-sm">
      {{ actionError }}
    </div>

    <DashboardSkeletonLoader v-else-if="pending" variant="card" :count="3" />

    <DashboardEmptyState
      v-else-if="filteredSpaces.length === 0"
      icon="lucide:globe"
      title="No sites found"
      description="Create your first site above or refine your search."
    />

    <div v-else class="space-y-3">
      <article
        v-for="space in filteredSpaces"
        :key="space.uuid"
        class="rounded-xl border border-titanium-200 dark:border-titanium-700 p-4 space-y-3 hover:border-titanium-300 dark:hover:border-titanium-600 transition-colors"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
              <Icon name="lucide:globe" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
            </div>
            <div class="min-w-0">
              <p class="font-semibold text-titanium-900 dark:text-white truncate">{{ space.name }}</p>
              <p class="text-xs text-titanium-500 dark:text-titanium-400 truncate">{{ space.website }}</p>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <DashboardStatusBadge :status="space.environment" />
            <DashboardStatusBadge :status="space.status" />
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
          <div class="rounded-lg bg-titanium-50 dark:bg-titanium-800/50 px-3 py-2">
            <span class="text-titanium-500 dark:text-titanium-400">API Service: </span>
            <span class="font-semibold text-titanium-900 dark:text-white">{{ space.product?.title ?? 'N/A' }}</span>
          </div>
          <div class="rounded-lg bg-titanium-50 dark:bg-titanium-800/50 px-3 py-2">
            <span class="text-titanium-500 dark:text-titanium-400">Monthly: </span>
            <span class="font-semibold text-titanium-900 dark:text-white">{{ space.requests_this_month }}</span>
          </div>
          <div class="rounded-lg bg-titanium-50 dark:bg-titanium-800/50 px-3 py-2">
            <span class="text-titanium-500 dark:text-titanium-400">Top endpoint: </span>
            <span class="font-semibold text-titanium-900 dark:text-white">{{ space.insights?.top_endpoint ?? 'N/A' }}</span>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-titanium-200 dark:border-titanium-700 text-xs font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
            @click="startEdit(space)"
          >
            <Icon name="lucide:pencil" class="w-3.5 h-3.5" />
            Edit
          </button>
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-rose-200 dark:border-rose-800 text-xs font-semibold text-rose-700 dark:text-rose-300 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors disabled:opacity-60"
            :disabled="actionLoading || space.status === 'disabled'"
            @click="disableSite(space)"
          >
            <Icon name="lucide:trash-2" class="w-3.5 h-3.5" />
            Disable
          </button>
        </div>

        <div
          v-if="editingSiteUuid === space.uuid"
          class="rounded-xl border border-titanium-200 dark:border-titanium-700 p-3 bg-titanium-50 dark:bg-titanium-900/40 space-y-3"
        >
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <DashboardFormInput v-model="editForm.name" label="Site name" />
            <DashboardFormInput v-model="editForm.website" label="Website" />
            <DashboardFormSelect v-model="editForm.environment" label="Environment" :options="envOptions" />
          </div>
          <div class="flex items-center gap-2">
            <button
              type="button"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-mintreu-red-600 text-white text-xs font-semibold hover:bg-mintreu-red-700 transition-colors disabled:opacity-60"
              :disabled="actionLoading"
              @click="saveEdit()"
            >
              <Icon name="lucide:save" class="w-3.5 h-3.5" />
              Save
            </button>
            <button
              type="button"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-titanium-200 dark:border-titanium-700 text-xs font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
              @click="cancelEdit()"
            >
              Cancel
            </button>
          </div>
        </div>
      </article>
    </div>
  </DashboardDashboardCard>
</template>
