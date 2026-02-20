<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import gsap from 'gsap'

const props = withDefaults(defineProps<{
  data: { label: string; value: number }[]
  color?: string
  height?: number
}>(), {
  color: '#DC2626',
  height: 140
})

const barsRef = ref<SVGGElement | null>(null)

const barWidth = 28
const gap = 8
const labelHeight = 20
const chartHeight = computed(() => props.height - labelHeight)
const svgWidth = computed(() => props.data.length * (barWidth + gap) - gap)

const maxValue = computed(() => Math.max(...props.data.map(d => d.value), 1))

const bars = computed(() => {
  return props.data.map((d, i) => {
    const h = (d.value / maxValue.value) * (chartHeight.value - 4)
    return {
      x: i * (barWidth + gap),
      y: chartHeight.value - h,
      width: barWidth,
      height: h,
      label: d.label,
      value: d.value
    }
  })
})

const animate = () => {
  if (!barsRef.value) return
  const rects = barsRef.value.querySelectorAll('.bar-rect')
  gsap.set(rects, { scaleY: 0, transformOrigin: 'bottom center' })
  gsap.to(rects, {
    scaleY: 1,
    duration: 0.6,
    stagger: 0.08,
    ease: 'back.out(1.4)'
  })
}

onMounted(() => { animate() })
watch(() => props.data, () => { animate() }, { deep: true })
</script>

<template>
  <svg :width="svgWidth" :height="height" :viewBox="`0 0 ${svgWidth} ${height}`" class="overflow-visible">
    <g ref="barsRef">
      <g v-for="(bar, i) in bars" :key="i">
        <rect
          class="bar-rect"
          :x="bar.x"
          :y="bar.y"
          :width="bar.width"
          :height="bar.height"
          :fill="color"
          rx="4"
          opacity="0.85"
        />
        <text
          :x="bar.x + bar.width / 2"
          :y="chartHeight + 14"
          text-anchor="middle"
          class="fill-titanium-500 dark:fill-titanium-400"
          style="font-size: 10px; font-family: 'Rajdhani', sans-serif;"
        >
          {{ bar.label }}
        </text>
      </g>
    </g>
  </svg>
</template>
