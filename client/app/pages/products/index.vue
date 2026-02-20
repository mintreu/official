<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 relative">
    <!-- 3D Hero -->
    <SharedPageHero
      badge="Premium Solutions"
      title="Our <span class='text-transparent bg-clip-text bg-gradient-to-r from-mintreu-red-400 via-mintreu-red-500 to-mintreu-red-600'>Products</span>"
      subtitle="Ready-made solutions, API subscriptions, and code resources to accelerate your development"
      node-color="#DC2626"
      :node-count="40"
    />

    <ProductListPage title="Our Products" subtitle="Ready-made solutions, API subscriptions, and code resources to accelerate your development" />
  </div>
</template>

<script setup lang="ts">
useSeoMeta({
  title: 'Products | Mintreu - Premium Code Solutions & APIs',
  description: 'Browse our marketplace for ready-made templates, API subscriptions, and code resources for your projects.'
})
</script>
