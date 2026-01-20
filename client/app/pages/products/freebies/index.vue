<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8">
    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
      <nav class="flex items-center space-x-2 text-sm">
        <NuxtLink to="/" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Home
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <NuxtLink to="/products" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          Products
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-gray-400" />
        <span class="text-gray-900 dark:text-white font-medium">Free Resources</span>
      </nav>
    </div>

    <ProductListPage type="freebie" title="Free Resources" subtitle="Free code snippets, components, and utilities to help you build faster" />
  </div>
</template>

<script setup lang="ts">
useSeoMeta({
  title: 'Free Resources | Mintreu - Free Code & Components',
  description: 'Download free code snippets, components, and resources for your projects.'
})
</script>
