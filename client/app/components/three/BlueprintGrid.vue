<script setup lang="ts">
withDefaults(defineProps<{
  size?: number
  divisions?: number
  color?: string
  opacity?: number
}>(), {
  size: 20,
  divisions: 40,
  color: '#6b7280',
  opacity: 0.1,
})
</script>

<template>
  <TresGridHelper
    :args="[size, divisions, color, color]"
    :rotation-x="Math.PI / 2"
    :position-z="-2"
  />
</template>
