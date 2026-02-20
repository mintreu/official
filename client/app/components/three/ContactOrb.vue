<script setup lang="ts">
import { useLoop } from '@tresjs/core'
import { shallowRef } from 'vue'
import * as THREE from 'three'

const props = withDefaults(defineProps<{
  color?: string
  wireColor?: string
  speed?: number
}>(), {
  color: '#DC2626',
  wireColor: '#3b82f6',
  speed: 0.3
})

const groupRef = shallowRef<THREE.Group | null>(null)
const ringRef1 = shallowRef<THREE.Mesh | null>(null)
const ringRef2 = shallowRef<THREE.Mesh | null>(null)
const ringRef3 = shallowRef<THREE.Mesh | null>(null)

const { onBeforeRender } = useLoop()

onBeforeRender(({ elapsed }) => {
  const t = elapsed * props.speed

  if (groupRef.value) {
    groupRef.value.rotation.y = t * 0.5
  }

  if (ringRef1.value) {
    ringRef1.value.rotation.x = t * 1.2
    ringRef1.value.rotation.z = t * 0.3
  }
  if (ringRef2.value) {
    ringRef2.value.rotation.y = t * 0.8
    ringRef2.value.rotation.x = t * 0.5
  }
  if (ringRef3.value) {
    ringRef3.value.rotation.z = t * 1.0
    ringRef3.value.rotation.y = t * 0.4
  }
})
</script>

<template>
  <TresGroup ref="groupRef">
    <!-- Central icosahedron -->
    <TresMesh>
      <TresIcosahedronGeometry :args="[1.2, 1]" />
      <TresMeshStandardMaterial :color="color" wireframe :opacity="0.4" transparent />
    </TresMesh>

    <!-- Inner sphere glow -->
    <TresMesh>
      <TresSphereGeometry :args="[0.6, 32, 32]" />
      <TresMeshStandardMaterial :color="color" :emissive="color" :emissive-intensity="0.5" :opacity="0.15" transparent />
    </TresMesh>

    <!-- Orbital ring 1 -->
    <TresMesh ref="ringRef1">
      <TresTorusGeometry :args="[2.0, 0.015, 16, 100]" />
      <TresMeshStandardMaterial :color="wireColor" :opacity="0.5" transparent />
    </TresMesh>

    <!-- Orbital ring 2 -->
    <TresMesh ref="ringRef2" :rotation="[Math.PI / 3, 0, 0]">
      <TresTorusGeometry :args="[2.4, 0.015, 16, 100]" />
      <TresMeshStandardMaterial :color="color" :opacity="0.35" transparent />
    </TresMesh>

    <!-- Orbital ring 3 -->
    <TresMesh ref="ringRef3" :rotation="[0, Math.PI / 4, Math.PI / 6]">
      <TresTorusGeometry :args="[2.8, 0.01, 16, 100]" />
      <TresMeshStandardMaterial color="#A8A9AD" :opacity="0.2" transparent />
    </TresMesh>

    <!-- Floating dots on orbits -->
    <TresMesh :position="[2.0, 0, 0]">
      <TresSphereGeometry :args="[0.06, 16, 16]" />
      <TresMeshStandardMaterial :color="color" :emissive="color" :emissive-intensity="2" />
    </TresMesh>
    <TresMesh :position="[-2.4, 0, 0]">
      <TresSphereGeometry :args="[0.05, 16, 16]" />
      <TresMeshStandardMaterial :color="wireColor" :emissive="wireColor" :emissive-intensity="2" />
    </TresMesh>
  </TresGroup>
</template>
