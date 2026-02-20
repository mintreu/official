<script setup lang="ts">
defineProps<{
  modelValue: string
  label?: string
  placeholder?: string
  type?: string
  error?: string
  disabled?: boolean
}>()

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()
</script>

<template>
  <div class="space-y-1.5">
    <label v-if="label" class="block text-xs font-semibold text-titanium-600 dark:text-titanium-400 uppercase tracking-wider">{{ label }}</label>
    <input
      :type="type ?? 'text'"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      class="input-dashboard disabled:opacity-50 disabled:cursor-not-allowed"
      @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
    >
    <p v-if="error" class="text-xs text-rose-600 dark:text-rose-400">{{ error }}</p>
  </div>
</template>
