<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import gsap from 'gsap'

const props = withDefaults(defineProps<{
  value: number
  max: number
  color?: string
  size?: number
  label?: string
}>(), {
  color: '#DC2626',
  size: 100,
  label: ''
})

const arcRef = ref<SVGCircleElement | null>(null)

const radius = computed(() => (props.size - 12) / 2)
const circumference = computed(() => 2 * Math.PI * radius.value)
const percentage = computed(() => props.max > 0 ? Math.min((props.value / props.max) * 100, 100) : 0)
const center = computed(() => props.size / 2)

const trackColor = computed(() => {
  return 'rgba(168, 169, 173, 0.15)'
})

const animate = () => {
  if (!arcRef.value) return
  const offset = circumference.value * (1 - percentage.value / 100)
  gsap.set(arcRef.value, { strokeDasharray: circumference.value, strokeDashoffset: circumference.value })
  gsap.to(arcRef.value, {
    strokeDashoffset: offset,
    duration: 1,
    ease: 'power2.out'
  })
}

onMounted(() => { animate() })
watch([() => props.value, () => props.max], () => { animate() })
</script>

<template>
  <div class="inline-flex flex-col items-center gap-1">
    <div class="relative" :style="{ width: size + 'px', height: size + 'px' }">
      <svg :width="size" :height="size" :viewBox="`0 0 ${size} ${size}`" class="-rotate-90">
        <!-- Track -->
        <circle
          :cx="center"
          :cy="center"
          :r="radius"
          fill="none"
          :stroke="trackColor"
          stroke-width="6"
        />
        <!-- Progress -->
        <circle
          ref="arcRef"
          :cx="center"
          :cy="center"
          :r="radius"
          fill="none"
          :stroke="color"
          stroke-width="6"
          stroke-linecap="round"
          :stroke-dasharray="circumference"
          :stroke-dashoffset="circumference"
        />
      </svg>
      <!-- Center overlay -->
      <div class="absolute inset-0 flex flex-col items-center justify-center">
        <span class="text-lg font-heading font-black text-titanium-900 dark:text-white">{{ Math.round(percentage) }}%</span>
        <span v-if="label" class="text-[9px] text-titanium-500 dark:text-titanium-400 font-subheading font-semibold uppercase tracking-wider">{{ label }}</span>
      </div>
    </div>
  </div>
</template>
