<script setup lang="ts">
definePageMeta({
  layout: 'dashboard',
  middleware: ['$auth'],
  title: 'Profile & Security'
})

const config = useRuntimeConfig()
const { user } = useSanctum()

const userInitials = computed(() => {
  const name = user?.value?.name
  if (!name) return '?'
  const parts = name.trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase()
  return parts[0][0].toUpperCase()
})

const isVerified = computed(() => !!user?.value?.email_verified_at)
</script>

<template>
  <!-- Page Header -->
  <div>
    <h1 class="text-2xl font-heading font-black text-titanium-900 dark:text-white">Profile & security</h1>
    <p class="text-sm text-titanium-500 dark:text-titanium-400 mt-1">Your account details and security settings.</p>
  </div>

  <!-- User Avatar Card -->
  <DashboardDashboardCard>
    <div class="flex items-center gap-5">
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-mintreu-red-500 to-mintreu-red-700 flex items-center justify-center flex-shrink-0 shadow-lg">
        <span class="text-white text-xl font-heading font-black">{{ userInitials }}</span>
      </div>
      <div class="min-w-0">
        <p class="text-xl font-heading font-bold text-titanium-900 dark:text-white">{{ user?.value?.name ?? 'N/A' }}</p>
        <p class="text-sm text-titanium-500 dark:text-titanium-400 truncate">{{ user?.value?.email ?? 'N/A' }}</p>
        <div class="mt-1.5">
          <DashboardStatusBadge :status="isVerified ? 'active' : 'paused'" size="sm" />
          <span class="text-xs text-titanium-500 dark:text-titanium-400 ml-1">{{ isVerified ? 'Email verified' : 'Email not verified' }}</span>
        </div>
      </div>
    </div>
  </DashboardDashboardCard>

  <!-- Account Details -->
  <DashboardDashboardCard title="Account details">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 px-4 py-3">
        <p class="text-[10px] uppercase tracking-wider font-semibold text-titanium-400 dark:text-titanium-500">Name</p>
        <p class="text-sm font-semibold text-titanium-900 dark:text-white mt-0.5">{{ user?.value?.name ?? 'N/A' }}</p>
      </div>
      <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 px-4 py-3">
        <p class="text-[10px] uppercase tracking-wider font-semibold text-titanium-400 dark:text-titanium-500">Email</p>
        <p class="text-sm font-semibold text-titanium-900 dark:text-white mt-0.5">{{ user?.value?.email ?? 'N/A' }}</p>
      </div>
      <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 px-4 py-3">
        <p class="text-[10px] uppercase tracking-wider font-semibold text-titanium-400 dark:text-titanium-500">Email verified</p>
        <p class="text-sm font-semibold mt-0.5" :class="isVerified ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400'">
          {{ isVerified ? 'Verified' : 'Not verified' }}
        </p>
      </div>
      <div class="rounded-xl border border-titanium-200 dark:border-titanium-700 px-4 py-3">
        <p class="text-[10px] uppercase tracking-wider font-semibold text-titanium-400 dark:text-titanium-500">Support</p>
        <p class="text-sm font-semibold text-titanium-900 dark:text-white mt-0.5">{{ config.public.supportEmail ?? 'hello@mintreu.com' }}</p>
      </div>
    </div>
  </DashboardDashboardCard>

  <!-- Actions -->
  <DashboardDashboardCard title="Security actions">
    <div class="flex flex-wrap gap-3">
      <NuxtLink
        to="/auth/forgot-password"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition-colors"
      >
        <Icon name="lucide:lock" class="w-4 h-4" />
        Change password
      </NuxtLink>
      <NuxtLink
        to="/contact"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-titanium-200 dark:border-titanium-700 text-sm font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
      >
        <Icon name="lucide:mail" class="w-4 h-4" />
        Update profile request
      </NuxtLink>
    </div>
  </DashboardDashboardCard>
</template>
