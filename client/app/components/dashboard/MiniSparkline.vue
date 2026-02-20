<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import gsap from 'gsap'

const props = withDefaults(defineProps<{
  data: number[]
  color?: string
  height?: number
  width?: number
  filled?: boolean
}>(), {
  color: '#DC2626',
  height: 40,
  width: 120,
  filled: true
})

const pathRef = ref<SVGPathElement | null>(null)
const fillRef = ref<SVGPathElement | null>(null)
const svgId = `sparkline-${Math.random().toString(36).slice(2, 9)}`

const padding = 2

const points = computed(() => {
  if (!props.data.length) return ''
  const max = Math.max(...props.data)
  const min = Math.min(...props.data)
  const range = max - min || 1
  const stepX = (props.width - padding * 2) / (props.data.length - 1)

  return props.data.map((v, i) => {
    const x = padding + i * stepX
    const y = padding + (1 - (v - min) / range) * (props.height - padding * 2)
    return `${x},${y}`
  }).join(' ')
})

const linePath = computed(() => {
  if (!points.value) return ''
  return `M ${points.value.split(' ').join(' L ')}`
})

const fillPath = computed(() => {
  if (!points.value) return ''
  const pts = points.value.split(' ')
  const firstX = pts[0].split(',')[0]
  const lastX = pts[pts.length - 1].split(',')[0]
  return `M ${pts.join(' L ')} L ${lastX},${props.height} L ${firstX},${props.height} Z`
})

const animate = () => {
  if (!pathRef.value) return
  const length = pathRef.value.getTotalLength()
  gsap.set(pathRef.value, { strokeDasharray: length, strokeDashoffset: length })
  if (fillRef.value) gsap.set(fillRef.value, { opacity: 0 })

  gsap.to(pathRef.value, {
    strokeDashoffset: 0,
    duration: 1,
    ease: 'power2.out',
    onComplete: () => {
      if (fillRef.value) {
        gsap.to(fillRef.value, { opacity: 1, duration: 0.4, ease: 'power1.out' })
      }
    }
  })
}

onMounted(() => { animate() })
watch(() => props.data, () => { animate() }, { deep: true })
</script>

<template>
  <svg :width="width" :height="height" :viewBox="`0 0 ${width} ${height}`" class="overflow-visible">
    <defs>
      <linearGradient :id="svgId" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0%" :stop-color="color" stop-opacity="0.3" />
        <stop offset="100%" :stop-color="color" stop-opacity="0.02" />
      </linearGradient>
    </defs>
    <path
      v-if="filled && fillPath"
      ref="fillRef"
      :d="fillPath"
      :fill="`url(#${svgId})`"
      opacity="0"
    />
    <path
      ref="pathRef"
      :d="linePath"
      fill="none"
      :stroke="color"
      stroke-width="1.5"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
  </svg>
</template>
