<script setup lang="ts">
import { ref, onMounted } from 'vue'
import gsap from 'gsap'

defineProps<{
  items: {
    icon: string
    title: string
    description: string
    time: string
    color?: string
  }[]
}>()

const listRef = ref<HTMLElement | null>(null)

onMounted(() => {
  if (!listRef.value) return
  const entries = listRef.value.querySelectorAll('.timeline-entry')
  gsap.set(entries, { opacity: 0, x: -20 })
  gsap.to(entries, {
    opacity: 1,
    x: 0,
    duration: 0.5,
    stagger: 0.1,
    ease: 'power2.out',
    delay: 0.2
  })
})
</script>

<template>
  <div ref="listRef" class="space-y-0">
    <div
      v-for="(item, i) in items"
      :key="i"
      class="timeline-entry relative flex gap-3 pb-4 last:pb-0"
    >
      <!-- Line -->
      <div class="flex flex-col items-center">
        <div
          class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 ring-2 ring-offset-2 ring-offset-white dark:ring-offset-titanium-900"
          :class="item.color || 'bg-titanium-100 dark:bg-titanium-800 ring-titanium-200 dark:ring-titanium-700'"
        >
          <Icon :name="item.icon" class="w-4 h-4 text-titanium-600 dark:text-titanium-400" />
        </div>
        <div v-if="i < items.length - 1" class="w-px flex-1 bg-titanium-200 dark:bg-titanium-700 mt-1"></div>
      </div>

      <!-- Content -->
      <div class="flex-1 min-w-0 pt-0.5">
        <p class="text-sm font-semibold text-titanium-900 dark:text-white truncate">{{ item.title }}</p>
        <p class="text-xs text-titanium-500 dark:text-titanium-400 mt-0.5">{{ item.description }}</p>
        <p class="text-[10px] text-titanium-400 dark:text-titanium-500 mt-1 font-subheading">{{ item.time }}</p>
      </div>
    </div>
  </div>
</template>
