<script setup lang="ts">
const props = withDefaults(defineProps<{
  status: string
  size?: 'sm' | 'md'
}>(), {
  size: 'sm'
})

const normalized = computed(() => props.status?.toLowerCase() ?? 'unknown')

const classes = computed(() => {
  const map: Record<string, string> = {
    active: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
    expired: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
    suspended: 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
    cancelled: 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
    paused: 'bg-titanium-100 text-titanium-600 dark:bg-titanium-800 dark:text-titanium-400',
    prod: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
    production: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
    staging: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
    dev: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    development: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
  }
  return map[normalized.value] ?? 'bg-titanium-100 text-titanium-600 dark:bg-titanium-800 dark:text-titanium-400'
})

const dotColor = computed(() => {
  const map: Record<string, string> = {
    active: 'bg-emerald-500',
    expired: 'bg-amber-500',
    suspended: 'bg-rose-500',
    cancelled: 'bg-rose-500',
    paused: 'bg-titanium-400',
    prod: 'bg-emerald-500',
    production: 'bg-emerald-500',
    staging: 'bg-amber-500',
    dev: 'bg-blue-500',
    development: 'bg-blue-500',
  }
  return map[normalized.value] ?? 'bg-titanium-400'
})

const sizeClasses = computed(() =>
  props.size === 'md' ? 'px-3 py-1.5 text-xs' : 'px-2 py-1 text-[11px]'
)
</script>

<template>
  <span class="inline-flex items-center gap-1.5 rounded-full font-semibold capitalize" :class="[classes, sizeClasses]">
    <span class="w-1.5 h-1.5 rounded-full" :class="dotColor" />
    {{ normalized }}
  </span>
</template>
