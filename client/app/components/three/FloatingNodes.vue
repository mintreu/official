<script setup lang="ts">
import { useLoop } from '@tresjs/core'
import { shallowRef } from 'vue'
import * as THREE from 'three'

const props = withDefaults(defineProps<{
  count?: number
  spread?: number
  color?: string
}>(), {
  count: 60,
  spread: 12,
  color: '#DC2626'
})

const pointsRef = shallowRef<THREE.Points | null>(null)

const positions = new Float32Array(props.count * 3)
const velocities: number[] = []

for (let i = 0; i < props.count; i++) {
  const i3 = i * 3
  positions[i3] = (Math.random() - 0.5) * props.spread
  positions[i3 + 1] = (Math.random() - 0.5) * props.spread
  positions[i3 + 2] = (Math.random() - 0.5) * (props.spread * 0.5)
  velocities.push(
    (Math.random() - 0.5) * 0.003,
    (Math.random() - 0.5) * 0.003,
    (Math.random() - 0.5) * 0.002
  )
}

const geometry = new THREE.BufferGeometry()
geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3))

const { onBeforeRender } = useLoop()

onBeforeRender(() => {
  if (!pointsRef.value) return
  const posAttr = pointsRef.value.geometry.attributes.position as THREE.BufferAttribute
  const arr = posAttr.array as Float32Array
  const half = props.spread * 0.5

  for (let i = 0; i < props.count; i++) {
    const i3 = i * 3
    arr[i3] += velocities[i3]
    arr[i3 + 1] += velocities[i3 + 1]
    arr[i3 + 2] += velocities[i3 + 2]

    if (Math.abs(arr[i3]) > half) velocities[i3] *= -1
    if (Math.abs(arr[i3 + 1]) > half) velocities[i3 + 1] *= -1
    if (Math.abs(arr[i3 + 2]) > half * 0.5) velocities[i3 + 2] *= -1
  }
  posAttr.needsUpdate = true
})
</script>

<template>
  <TresPoints ref="pointsRef" :geometry="geometry">
    <TresPointsMaterial
      :color="color"
      :size="0.04"
      transparent
      :opacity="0.6"
      :size-attenuation="true"
    />
  </TresPoints>
</template>
