<script setup lang="ts">
defineProps<{
  currentPage: number
  lastPage: number
  total?: number
}>()

const emit = defineEmits<{
  page: [page: number]
}>()
</script>

<template>
  <div v-if="lastPage > 1" class="flex items-center justify-between border-t border-titanium-200 dark:border-titanium-700 pt-4 mt-4">
    <button
      type="button"
      class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-titanium-200 dark:border-titanium-700 text-sm font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors disabled:opacity-40 disabled:pointer-events-none"
      :disabled="currentPage <= 1"
      @click="emit('page', currentPage - 1)"
    >
      <Icon name="lucide:chevron-left" class="w-4 h-4" />
      Previous
    </button>
    <div class="text-sm text-titanium-500 dark:text-titanium-400">
      Page {{ currentPage }} / {{ lastPage }}
      <span v-if="total !== undefined" class="hidden sm:inline"> &middot; {{ total }} total</span>
    </div>
    <button
      type="button"
      class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-titanium-200 dark:border-titanium-700 text-sm font-semibold text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-800 transition-colors disabled:opacity-40 disabled:pointer-events-none"
      :disabled="currentPage >= lastPage"
      @click="emit('page', currentPage + 1)"
    >
      Next
      <Icon name="lucide:chevron-right" class="w-4 h-4" />
    </button>
  </div>
</template>
