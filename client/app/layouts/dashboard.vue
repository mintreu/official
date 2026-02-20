<script setup lang="ts">
const { sidebarOpen, close } = useDashboardSidebar()
const { refreshUser } = useSanctum()
const currency = useState<'USD' | 'INR'>('currency', () => 'USD')
provide('currency', currency)

onMounted(() => {
  refreshUser()
})
</script>

<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 transition-colors">
    <!-- Topbar -->
    <DashboardTopbar />

    <!-- Sidebar -->
    <DashboardSidebar />

    <!-- Mobile overlay -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="sidebarOpen"
        class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm lg:hidden"
        @click="close()"
      />
    </Transition>

    <!-- Main content -->
    <main class="lg:pl-64 pt-16 min-h-screen">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 space-y-6">
        <slot />
      </div>
    </main>
  </div>
</template>
