<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import gsap from 'gsap'

const props = withDefaults(defineProps<{
  label: string
  value: string | number
  icon: string
  color?: 'red' | 'emerald' | 'blue' | 'amber' | 'titanium'
  subtitle?: string
  sparklineData?: number[]
  trend?: { value: number; direction: 'up' | 'down' }
}>(), {
  color: 'titanium'
})

const displayValue = ref<string | number>(typeof props.value === 'number' ? 0 : props.value)
const cardRef = ref<HTMLElement | null>(null)

const colorClasses = computed(() => {
  const map: Record<string, { bg: string; text: string; ring: string }> = {
    red: { bg: 'bg-mintreu-red-100 dark:bg-mintreu-red-900/30', text: 'text-mintreu-red-600 dark:text-mintreu-red-400', ring: 'ring-mintreu-red-200 dark:ring-mintreu-red-800' },
    emerald: { bg: 'bg-emerald-100 dark:bg-emerald-900/30', text: 'text-emerald-600 dark:text-emerald-400', ring: 'ring-emerald-200 dark:ring-emerald-800' },
    blue: { bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-600 dark:text-blue-400', ring: 'ring-blue-200 dark:ring-blue-800' },
    amber: { bg: 'bg-amber-100 dark:bg-amber-900/30', text: 'text-amber-600 dark:text-amber-400', ring: 'ring-amber-200 dark:ring-amber-800' },
    titanium: { bg: 'bg-titanium-100 dark:bg-titanium-800', text: 'text-titanium-600 dark:text-titanium-400', ring: 'ring-titanium-200 dark:ring-titanium-700' },
  }
  return map[props.color] ?? map.titanium
})

const sparklineColor = computed(() => {
  const map: Record<string, string> = {
    red: '#DC2626', emerald: '#059669', blue: '#2563EB', amber: '#D97706', titanium: '#6B7280'
  }
  return map[props.color] ?? '#6B7280'
})

const trendClasses = computed(() => {
  if (!props.trend) return {}
  return props.trend.direction === 'up'
    ? { text: 'text-emerald-600 dark:text-emerald-400', bg: 'bg-emerald-50 dark:bg-emerald-900/20', icon: 'lucide:trending-up' }
    : { text: 'text-rose-600 dark:text-rose-400', bg: 'bg-rose-50 dark:bg-rose-900/20', icon: 'lucide:trending-down' }
})

const animateCounter = () => {
  if (typeof props.value !== 'number') {
    displayValue.value = props.value
    return
  }
  const target = { val: 0 }
  gsap.to(target, {
    val: props.value,
    duration: 1.2,
    ease: 'power2.out',
    onUpdate: () => {
      displayValue.value = Math.round(target.val)
    }
  })
}

onMounted(() => { animateCounter() })
watch(() => props.value, () => { animateCounter() })
</script>

<template>
  <div ref="cardRef" class="card-dashboard p-5 flex flex-col gap-3">
    <div class="flex items-start justify-between">
      <div class="flex items-start gap-4 min-w-0 flex-1">
        <div class="flex-shrink-0 w-11 h-11 rounded-xl ring-1 flex items-center justify-center" :class="[colorClasses.bg, colorClasses.ring]">
          <Icon :name="icon" class="w-5 h-5" :class="colorClasses.text" />
        </div>
        <div class="min-w-0">
          <p class="text-xs font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">{{ label }}</p>
          <p class="text-2xl font-heading font-black text-titanium-900 dark:text-white mt-0.5 truncate">{{ displayValue }}</p>
          <p v-if="subtitle" class="text-xs text-titanium-500 dark:text-titanium-400 mt-0.5 truncate">{{ subtitle }}</p>
        </div>
      </div>
      <!-- Trend Badge -->
      <div v-if="trend" class="flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-heading font-bold" :class="[trendClasses.bg, trendClasses.text]">
        <Icon :name="trendClasses.icon" class="w-3.5 h-3.5" />
        {{ trend.value }}%
      </div>
    </div>
    <!-- Sparkline -->
    <div v-if="sparklineData && sparklineData.length > 1" class="mt-auto">
      <DashboardMiniSparkline
        :data="sparklineData"
        :color="sparklineColor"
        :height="36"
        :width="200"
        filled
      />
    </div>
  </div>
</template>
