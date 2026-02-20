<script setup lang="ts">
withDefaults(defineProps<{
  icon?: string
  title: string
  description?: string
  actionLabel?: string
  actionTo?: string
}>(), {
  icon: 'lucide:inbox'
})
</script>

<template>
  <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
    <div class="w-14 h-14 rounded-2xl bg-titanium-100 dark:bg-titanium-800 flex items-center justify-center mb-4">
      <Icon :name="icon" class="w-7 h-7 text-titanium-400 dark:text-titanium-500" />
    </div>
    <h3 class="text-base font-semibold text-titanium-700 dark:text-titanium-300 mb-1">{{ title }}</h3>
    <p v-if="description" class="text-sm text-titanium-500 dark:text-titanium-400 max-w-sm">{{ description }}</p>
    <NuxtLink
      v-if="actionLabel && actionTo"
      :to="actionTo"
      class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-mintreu-red-600 text-white text-sm font-semibold hover:bg-mintreu-red-700 transition-colors"
    >
      {{ actionLabel }}
      <Icon name="lucide:arrow-right" class="w-4 h-4" />
    </NuxtLink>
  </div>
</template>
