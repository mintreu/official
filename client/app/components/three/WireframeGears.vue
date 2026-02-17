<script setup lang="ts">
import { useLoop } from '@tresjs/core'
import { shallowRef } from 'vue'

const props = withDefaults(defineProps<{
  position?: [number, number, number]
  scale?: number
  speed?: number
  color?: string
}>(), {
  position: () => [0, 0, 0],
  scale: 1,
  speed: 0.005,
  color: '#DC2626',
})

const meshRef = shallowRef<any>(null)

const { onBeforeRender } = useLoop()

onBeforeRender(({ elapsed }) => {
  if (meshRef.value) {
    meshRef.value.rotation.z = elapsed * props.speed * 10
  }
})
</script>

<template>
  <TresMesh ref="meshRef" :position="props.position" :scale="props.scale">
    <TresTorusGeometry :args="[1, 0.3, 8, 60]" />
    <TresMeshStandardMaterial
      :color="props.color"
      wireframe
      :opacity="0.6"
      transparent
    />
  </TresMesh>
</template>
