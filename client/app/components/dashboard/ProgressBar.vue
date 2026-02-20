<script setup lang="ts">
const props = withDefaults(defineProps<{
  value: number
  max: number | null
  label?: string
  showPercentage?: boolean
  color?: 'red' | 'emerald' | 'blue' | 'amber'
}>(), {
  showPercentage: true,
  color: 'emerald'
})

const percentage = computed(() => {
  if (!props.max || props.max <= 0) return 0
  return Math.min(100, Math.round((props.value / props.max) * 100))
})

const isUnlimited = computed(() => props.max === null || props.max === 0)

const barColor = computed(() => {
  if (percentage.value >= 90) return 'bg-rose-500'
  if (percentage.value >= 70) return 'bg-amber-500'
  const map: Record<string, string> = {
    red: 'bg-mintreu-red-500',
    emerald: 'bg-emerald-500',
    blue: 'bg-blue-500',
    amber: 'bg-amber-500',
  }
  return map[props.color] ?? 'bg-emerald-500'
})
</script>

<template>
  <div class="space-y-1.5">
    <div v-if="label || showPercentage" class="flex items-center justify-between text-xs">
      <span v-if="label" class="text-titanium-500 dark:text-titanium-400">{{ label }}</span>
      <span class="font-semibold text-titanium-700 dark:text-titanium-300">
        {{ value }}<template v-if="!isUnlimited"> / {{ max }}</template>
        <template v-if="isUnlimited"> (Unlimited)</template>
        <template v-else-if="showPercentage"> ({{ percentage }}%)</template>
      </span>
    </div>
    <div class="h-2 rounded-full bg-titanium-200 dark:bg-titanium-700 overflow-hidden">
      <div
        v-if="!isUnlimited"
        class="h-full rounded-full transition-all duration-500"
        :class="barColor"
        :style="{ width: `${percentage}%` }"
      />
      <div
        v-else
        class="h-full w-full rounded-full bg-emerald-500/30"
      />
    </div>
  </div>
</template>
