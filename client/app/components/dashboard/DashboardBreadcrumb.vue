<script setup lang="ts">
const route = useRoute()

const breadcrumbs = computed(() => {
  const segments = route.path.split('/').filter(Boolean)
  const crumbs: Array<{ label: string; to: string; active: boolean }> = [
    { label: 'Dashboard', to: '/dashboard', active: false }
  ]

  const labelMap: Record<string, string> = {
    licenses: 'Licenses',
    apis: 'API Services',
    spaces: 'Sites',
    profile: 'Profile',
  }

  if (segments.length > 1) {
    const section = segments[1]
    if (labelMap[section]) {
      crumbs.push({ label: labelMap[section], to: `/dashboard/${section}`, active: false })
    }
  }

  if (segments.length > 2) {
    crumbs.push({ label: `#${segments[2]}`, to: route.path, active: true })
  }

  crumbs[crumbs.length - 1].active = true
  return crumbs
})
</script>

<template>
  <nav class="flex items-center gap-1.5 text-sm">
    <template v-for="(crumb, i) in breadcrumbs" :key="crumb.to">
      <Icon v-if="i > 0" name="lucide:chevron-right" class="w-3.5 h-3.5 text-titanium-400 dark:text-titanium-600 flex-shrink-0" />
      <NuxtLink
        v-if="!crumb.active"
        :to="crumb.to"
        class="text-titanium-500 dark:text-titanium-400 hover:text-mintreu-red-600 dark:hover:text-mintreu-red-400 transition-colors font-medium truncate"
      >
        {{ crumb.label }}
      </NuxtLink>
      <span
        v-else
        class="text-titanium-900 dark:text-white font-semibold truncate"
      >
        {{ crumb.label }}
      </span>
    </template>
  </nav>
</template>
