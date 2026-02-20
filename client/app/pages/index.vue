<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 transition-colors duration-500">
    <LandingHeroScene />
    <LandingComparisonSection />
    <LandingPricingSection />
    <LandingServicesSection />
    <LandingFeatureProjects />
    <LandingCaseStudies />
    <LandingMarketplaceProjects />
    <LandingQuoteWizard />
  </div>
</template>

<script setup lang="ts">
useSeoMeta({
  title: 'Mintreu | Precision Digital Engineering - Web, Mobile & Desktop Development',
  description: 'Full-stack digital engineering firm. Laravel, Nuxt, React, Kotlin, AI/ML. Blueprint-grade solutions from concept to deployment.',
  ogTitle: 'Mintreu | Precision Digital Engineering',
  ogDescription: 'Expert development services across web, mobile, and desktop platforms. Enterprise-grade code quality.',
  ogImage: '/og-image.jpg',
  twitterCard: 'summary_large_image',
})
</script>
