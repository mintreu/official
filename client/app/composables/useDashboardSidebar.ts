const sidebarOpen = ref(false)

export function useDashboardSidebar() {
  const route = useRoute()

  const open = () => { sidebarOpen.value = true }
  const close = () => { sidebarOpen.value = false }
  const toggle = () => { sidebarOpen.value = !sidebarOpen.value }

  watch(() => route.path, () => { sidebarOpen.value = false })

  return { sidebarOpen: readonly(sidebarOpen), open, close, toggle }
}
