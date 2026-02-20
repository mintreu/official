<script setup lang="ts">
const props = withDefaults(defineProps<{
  variant?: 'card' | 'row' | 'stat' | 'text'
  count?: number
}>(), {
  variant: 'card',
  count: 3
})

const heightClass = computed(() => {
  const map: Record<string, string> = {
    card: 'h-28',
    row: 'h-20',
    stat: 'h-24',
    text: 'h-4',
  }
  return map[props.variant] ?? 'h-28'
})
</script>

<template>
  <div class="space-y-3">
    <div v-for="i in count" :key="i" class="skeleton-dashboard" :class="heightClass" />
  </div>
</template>
