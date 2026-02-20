<script setup lang="ts">
const colorMode = useColorMode()
const { toggle } = useDashboardSidebar()
const { user } = useSanctum()
const route = useRoute()

const pageTitle = computed(() => {
  if (route.path === '/dashboard') return 'Overview'
  if (route.path.startsWith('/dashboard/apis')) return 'API Services'
  if (route.path.startsWith('/dashboard/spaces')) return 'Sites'
  if (route.path.startsWith('/dashboard/licenses')) return 'Licenses'
  if (route.path.startsWith('/dashboard/profile')) return 'Profile'
  return 'Dashboard'
})

const toggleTheme = () => {
  colorMode.preference = colorMode.value === 'dark' ? 'light' : 'dark'
}

const userInitial = computed(() => {
  const name = user?.value?.name
  if (!name) return '?'
  return name.charAt(0).toUpperCase()
})
</script>

<template>
  <header class="fixed top-0 left-0 right-0 z-30 h-16 lg:pl-64 bg-white/90 dark:bg-titanium-900/90 backdrop-blur-xl border-b border-titanium-200 dark:border-titanium-800 transition-colors">
    <div class="flex items-center justify-between h-full px-4 sm:px-6">
      <!-- Left: Hamburger + Breadcrumb -->
      <div class="flex items-center gap-3 min-w-0">
        <button
          type="button"
          class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
          aria-label="Toggle sidebar"
          @click="toggle()"
        >
          <Icon name="lucide:menu" class="w-5 h-5 text-titanium-600 dark:text-titanium-400" />
        </button>
        <div class="hidden md:block min-w-0">
          <p class="text-[11px] uppercase tracking-[0.2em] text-titanium-400 dark:text-titanium-500 font-semibold">Mintreu Control</p>
          <h1 class="text-sm font-bold text-titanium-900 dark:text-white truncate">{{ pageTitle }}</h1>
        </div>
        <DashboardBreadcrumb class="hidden lg:flex" />
      </div>

      <!-- Right: Theme + User -->
      <div class="flex items-center gap-2">
        <NuxtLink
          to="/dashboard/apis"
          class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-titanium-200 dark:border-titanium-700 text-xs font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
        >
          <Icon name="lucide:cloud-cog" class="w-3.5 h-3.5" />
          API
        </NuxtLink>
        <NuxtLink
          to="/dashboard/spaces"
          class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-titanium-200 dark:border-titanium-700 text-xs font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
        >
          <Icon name="lucide:globe" class="w-3.5 h-3.5" />
          Sites
        </NuxtLink>
        <button
          type="button"
          class="p-2 rounded-lg hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors"
          aria-label="Toggle theme"
          @click="toggleTheme"
        >
          <Icon
            :name="colorMode.value === 'dark' ? 'lucide:sun' : 'lucide:moon'"
            class="w-5 h-5 text-titanium-500 dark:text-titanium-400"
          />
        </button>
        <div class="w-8 h-8 rounded-full bg-mintreu-red-600 flex items-center justify-center">
          <span class="text-white text-xs font-heading font-bold">{{ userInitial }}</span>
        </div>
      </div>
    </div>
  </header>
</template>
