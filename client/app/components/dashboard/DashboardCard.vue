<script setup lang="ts">
defineProps<{
  title?: string
  subtitle?: string
  noPadding?: boolean
}>()
</script>

<template>
  <div class="card-dashboard">
    <div v-if="title" class="px-6 pt-6" :class="{ 'pb-4': !noPadding, 'pb-6': noPadding }">
      <h2 class="text-lg font-heading font-bold text-titanium-900 dark:text-white">{{ title }}</h2>
      <p v-if="subtitle" class="text-sm text-titanium-500 dark:text-titanium-400 mt-0.5">{{ subtitle }}</p>
    </div>
    <div :class="noPadding ? '' : 'px-6 pb-6'" class="space-y-4">
      <slot />
    </div>
  </div>
</template>
