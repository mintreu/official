<script setup lang="ts">
import { useLoop } from '@tresjs/core'
import { shallowRef } from 'vue'

const props = withDefaults(defineProps<{
  position?: [number, number, number]
  floatAmplitude?: number
  floatSpeed?: number
  color?: string
}>(), {
  position: () => [0, 0, 0],
  floatAmplitude: 0.3,
  floatSpeed: 1,
  color: '#3b82f6',
})

const meshRef = shallowRef<any>(null)
const initialY = props.position[1]

const { onBeforeRender } = useLoop()

onBeforeRender(({ elapsed }) => {
  if (meshRef.value) {
    meshRef.value.position.y = initialY + Math.sin(elapsed * props.floatSpeed) * props.floatAmplitude
    meshRef.value.rotation.y = Math.sin(elapsed * 0.3) * 0.1
  }
})
</script>

<template>
  <TresMesh ref="meshRef" :position="props.position">
    <TresPlaneGeometry :args="[2, 2.8]" />
    <TresMeshStandardMaterial
      :color="props.color"
      wireframe
      :opacity="0.3"
      transparent
      :side="2"
    />
  </TresMesh>
</template>
