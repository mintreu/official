<script setup lang="ts">
defineProps<{
  modelValue: string
  label?: string
  options: Array<{ label: string; value: string }>
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
    <select
      :value="modelValue"
      :disabled="disabled"
      class="input-dashboard disabled:opacity-50 disabled:cursor-not-allowed appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.23%207.21a.75.75%200%20011.06.02L10%2011.168l3.71-3.938a.75.75%200%20111.08%201.04l-4.25%204.5a.75.75%200%2001-1.08%200l-4.25-4.5a.75.75%200%2001.02-1.06z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_0.5rem_center] bg-no-repeat pr-10"
      @change="emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
    >
      <option v-for="opt in options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
    </select>
    <p v-if="error" class="text-xs text-rose-600 dark:text-rose-400">{{ error }}</p>
  </div>
</template>
