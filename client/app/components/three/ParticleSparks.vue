<script setup lang="ts">
import { useLoop } from '@tresjs/core'
import { shallowRef } from 'vue'
import * as THREE from 'three'

const props = withDefaults(defineProps<{
  count?: number
  spread?: number
  color?: string
}>(), {
  count: 200,
  spread: 15,
  color: '#DC2626',
})

const pointsRef = shallowRef<any>(null)
const positions = new Float32Array(props.count * 3)
const velocities: number[] = []

for (let i = 0; i < props.count; i++) {
  positions[i * 3] = (Math.random() - 0.5) * props.spread
  positions[i * 3 + 1] = (Math.random() - 0.5) * props.spread
  positions[i * 3 + 2] = (Math.random() - 0.5) * props.spread
  velocities.push(
    (Math.random() - 0.5) * 0.02,
    Math.random() * 0.01 + 0.005,
    (Math.random() - 0.5) * 0.02,
  )
}

const geometry = new THREE.BufferGeometry()
geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3))

const { onBeforeRender } = useLoop()

onBeforeRender(() => {
  if (!pointsRef.value) return
  const posAttr = pointsRef.value.geometry.attributes.position

  for (let i = 0; i < props.count; i++) {
    posAttr.array[i * 3] += velocities[i * 3]
    posAttr.array[i * 3 + 1] += velocities[i * 3 + 1]
    posAttr.array[i * 3 + 2] += velocities[i * 3 + 2]

    if (posAttr.array[i * 3 + 1] > props.spread / 2) {
      posAttr.array[i * 3] = (Math.random() - 0.5) * props.spread
      posAttr.array[i * 3 + 1] = -props.spread / 2
      posAttr.array[i * 3 + 2] = (Math.random() - 0.5) * props.spread
    }
  }
  posAttr.needsUpdate = true
})
</script>

<template>
  <TresPoints ref="pointsRef" :geometry="geometry">
    <TresPointsMaterial
      :color="props.color"
      :size="0.05"
      transparent
      :opacity="0.7"
      size-attenuation
    />
  </TresPoints>
</template>
