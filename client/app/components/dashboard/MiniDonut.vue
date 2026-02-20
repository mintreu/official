<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import gsap from 'gsap'

const props = withDefaults(defineProps<{
  segments: { value: number; color: string; label: string }[]
  size?: number
  thickness?: number
}>(), {
  size: 140,
  thickness: 20
})

const pathsRef = ref<SVGGElement | null>(null)

const radius = computed(() => (props.size - props.thickness) / 2)
const center = computed(() => props.size / 2)
const total = computed(() => props.segments.reduce((sum, s) => sum + s.value, 0))

const arcs = computed(() => {
  if (!total.value) return []
  let cumAngle = -90 // start at top
  return props.segments.map(seg => {
    const angle = (seg.value / total.value) * 360
    const startAngle = cumAngle
    cumAngle += angle
    const endAngle = cumAngle

    const startRad = (startAngle * Math.PI) / 180
    const endRad = (endAngle * Math.PI) / 180

    const x1 = center.value + radius.value * Math.cos(startRad)
    const y1 = center.value + radius.value * Math.sin(startRad)
    const x2 = center.value + radius.value * Math.cos(endRad)
    const y2 = center.value + radius.value * Math.sin(endRad)

    const largeArc = angle > 180 ? 1 : 0

    return {
      d: `M ${x1} ${y1} A ${radius.value} ${radius.value} 0 ${largeArc} 1 ${x2} ${y2}`,
      color: seg.color,
      label: seg.label,
      value: seg.value
    }
  })
})

const animate = () => {
  if (!pathsRef.value) return
  const paths = pathsRef.value.querySelectorAll('.donut-arc')
  paths.forEach(path => {
    const el = path as SVGPathElement
    const length = el.getTotalLength()
    gsap.set(el, { strokeDasharray: length, strokeDashoffset: length })
    gsap.to(el, {
      strokeDashoffset: 0,
      duration: 1,
      ease: 'power2.out',
      delay: 0.1
    })
  })
}

onMounted(() => { animate() })
watch(() => props.segments, () => { animate() }, { deep: true })
</script>

<template>
  <div class="inline-flex flex-col items-center gap-3">
    <svg :width="size" :height="size" :viewBox="`0 0 ${size} ${size}`">
      <g ref="pathsRef">
        <path
          v-for="(arc, i) in arcs"
          :key="i"
          class="donut-arc"
          :d="arc.d"
          fill="none"
          :stroke="arc.color"
          :stroke-width="thickness"
          stroke-linecap="round"
        />
      </g>
      <!-- Center text -->
      <text
        :x="center"
        :y="center - 6"
        text-anchor="middle"
        dominant-baseline="middle"
        class="fill-titanium-900 dark:fill-white"
        style="font-size: 22px; font-family: 'Orbitron', monospace; font-weight: 900;"
      >
        {{ total }}
      </text>
      <text
        :x="center"
        :y="center + 14"
        text-anchor="middle"
        dominant-baseline="middle"
        class="fill-titanium-500 dark:fill-titanium-400"
        style="font-size: 10px; font-family: 'Rajdhani', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;"
      >
        Total
      </text>
    </svg>
    <!-- Legend -->
    <div class="flex flex-wrap gap-x-4 gap-y-1 justify-center">
      <div v-for="seg in segments" :key="seg.label" class="flex items-center gap-1.5">
        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" :style="{ backgroundColor: seg.color }"></span>
        <span class="text-xs text-titanium-600 dark:text-titanium-400 font-subheading">{{ seg.label }} ({{ seg.value }})</span>
      </div>
    </div>
  </div>
</template>
