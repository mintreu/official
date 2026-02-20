<script setup lang="ts">
import { useLoop } from '@tresjs/core'
import { shallowRef } from 'vue'
import * as THREE from 'three'

const props = withDefaults(defineProps<{
  count?: number
  color?: string
  speed?: number
}>(), {
  count: 20,
  color: '#DC2626',
  speed: 0.3
})

const pointsRef = shallowRef<THREE.Points | null>(null)

const positions = new Float32Array(props.count * 3)
const velocities: number[] = []
const spread = 16
const halfSpread = spread / 2

for (let i = 0; i < props.count; i++) {
  const i3 = i * 3
  positions[i3] = (Math.random() - 0.5) * spread
  positions[i3 + 1] = (Math.random() - 0.5) * 4
  positions[i3 + 2] = (Math.random() - 0.5) * 3
  velocities.push(
    (Math.random() - 0.5) * 0.001 * props.speed,
    (Math.random() - 0.5) * 0.0008 * props.speed,
    (Math.random() - 0.5) * 0.0005 * props.speed
  )
}

const geometry = new THREE.BufferGeometry()
geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3))

const { onBeforeRender } = useLoop()

onBeforeRender(() => {
  if (!pointsRef.value) return
  const posAttr = pointsRef.value.geometry.attributes.position as THREE.BufferAttribute
  const arr = posAttr.array as Float32Array

  for (let i = 0; i < props.count; i++) {
    const i3 = i * 3
    arr[i3] += velocities[i3]
    arr[i3 + 1] += velocities[i3 + 1]
    arr[i3 + 2] += velocities[i3 + 2]

    if (Math.abs(arr[i3]) > halfSpread) velocities[i3] *= -1
    if (Math.abs(arr[i3 + 1]) > 2) velocities[i3 + 1] *= -1
    if (Math.abs(arr[i3 + 2]) > 1.5) velocities[i3 + 2] *= -1
  }
  posAttr.needsUpdate = true
})
</script>

<template>
  <TresPoints ref="pointsRef" :geometry="geometry">
    <TresPointsMaterial
      :color="color"
      :size="0.06"
      transparent
      :opacity="0.35"
      :size-attenuation="true"
    />
  </TresPoints>
</template>
