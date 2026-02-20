<script setup lang="ts">
const route = useRoute()
const router = useRouter()
const { sidebarOpen, close } = useDashboardSidebar()
const { user, logout } = useSanctum()

const navItems = [
  { label: 'Overview', to: '/dashboard', icon: 'lucide:layout-dashboard', exact: true },
  { label: 'Licenses', to: '/dashboard/licenses', icon: 'lucide:key-round', exact: false },
  { label: 'API Services', to: '/dashboard/apis', icon: 'lucide:cloud-cog', exact: false },
  { label: 'Sites', to: '/dashboard/spaces', icon: 'lucide:globe', exact: false },
  { label: 'Referrals', to: '/dashboard/referrals', icon: 'lucide:share-2', exact: false },
  { label: 'Profile & Security', to: '/dashboard/profile', icon: 'lucide:user-cog', exact: false },
]

const secondaryItems = [
  { label: 'Browse Products', to: '/products', icon: 'lucide:shopping-cart' },
  { label: 'Support', to: '/contact', icon: 'lucide:life-buoy' },
]

const isActive = (item: { to: string; exact: boolean }) => {
  if (item.exact) return route.path === item.to
  return route.path.startsWith(item.to)
}

const userInitials = computed(() => {
  const name = user?.value?.name
  if (!name) return '?'
  const parts = name.trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase()
  return parts[0][0].toUpperCase()
})

const handleLogout = async () => {
  close()
  await logout()
  router.push('/')
}
</script>

<template>
  <!-- Sidebar -->
  <aside
    class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-titanium-900 border-r border-titanium-200 dark:border-titanium-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
  >
    <!-- Brand -->
    <div class="flex items-center gap-3 px-5 h-16 border-b border-titanium-200 dark:border-titanium-800 flex-shrink-0">
      <NuxtLink to="/" class="flex items-center gap-2.5 group" @click="close()">
        <div class="w-9 h-9 bg-mintreu-red-600 rounded-lg flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
          <span class="text-white font-heading font-black text-sm">M</span>
        </div>
        <div>
          <span class="text-base font-heading font-black text-titanium-900 dark:text-white">Mintreu</span>
          <span class="block text-[10px] text-titanium-500 font-subheading font-semibold tracking-wider uppercase -mt-0.5">Client Panel</span>
        </div>
      </NuxtLink>
    </div>

    <!-- User Info -->
    <div class="px-4 py-4 border-b border-titanium-100 dark:border-titanium-800">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-mintreu-red-500 to-mintreu-red-700 flex items-center justify-center flex-shrink-0 shadow-sm">
          <span class="text-white text-sm font-heading font-bold">{{ userInitials }}</span>
        </div>
        <div class="min-w-0">
          <p class="text-sm font-semibold text-titanium-900 dark:text-white truncate">{{ user?.value?.name ?? 'Client' }}</p>
          <p class="text-xs text-titanium-500 dark:text-titanium-400 truncate">{{ user?.value?.email ?? '' }}</p>
        </div>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
      <p class="px-3 mb-2 text-[10px] uppercase tracking-[0.25em] font-semibold text-titanium-400 dark:text-titanium-600">Main</p>
      <NuxtLink
        v-for="item in navItems"
        :key="item.to"
        :to="item.to"
        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
        :class="isActive(item)
          ? 'bg-mintreu-red-600/10 text-mintreu-red-600 dark:text-mintreu-red-400 border-l-[3px] border-mintreu-red-600 -ml-px'
          : 'text-titanium-600 dark:text-titanium-400 hover:bg-titanium-100 dark:hover:bg-titanium-800/60 hover:text-titanium-900 dark:hover:text-white'"
        @click="close()"
      >
        <Icon :name="item.icon" class="w-[18px] h-[18px] flex-shrink-0" />
        {{ item.label }}
      </NuxtLink>

      <div class="pt-4 mt-4 border-t border-titanium-100 dark:border-titanium-800">
        <p class="px-3 mb-2 text-[10px] uppercase tracking-[0.25em] font-semibold text-titanium-400 dark:text-titanium-600">Quick links</p>
        <NuxtLink
          v-for="item in secondaryItems"
          :key="item.to"
          :to="item.to"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-titanium-500 dark:text-titanium-500 hover:bg-titanium-100 dark:hover:bg-titanium-800/60 hover:text-titanium-700 dark:hover:text-titanium-300 transition-all duration-200"
          @click="close()"
        >
          <Icon :name="item.icon" class="w-[18px] h-[18px] flex-shrink-0" />
          {{ item.label }}
        </NuxtLink>
      </div>
    </nav>

    <!-- Sign Out -->
    <div class="px-3 py-4 border-t border-titanium-100 dark:border-titanium-800 flex-shrink-0">
      <button
        type="button"
        class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all duration-200"
        @click="handleLogout()"
      >
        <Icon name="lucide:log-out" class="w-[18px] h-[18px] flex-shrink-0" />
        Sign out
      </button>
    </div>
  </aside>
</template>
