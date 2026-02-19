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
        <span class="text-gray-900 dark:text-white font-medium">Templates</span>
      </nav>
    </div>

    <ProductListPage type="downloadable" title="Premium Templates" subtitle="Beautiful, production-ready templates for your next project built with modern technologies" />
  </div>
</template>

<script setup lang="ts">
useSeoMeta({
  title: 'Templates | Mintreu - Premium Code Templates',
  description: 'Browse our collection of premium code templates and starter kits.'
})
</script>
