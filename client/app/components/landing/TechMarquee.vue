<template>
  <div class="relative overflow-hidden py-8 bg-titanium-100/50 dark:bg-titanium-950/50 border-y border-titanium-200 dark:border-titanium-800 marquee-hover-pause">
    <!-- Fade edges -->
    <div class="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-titanium-100 dark:from-titanium-950 to-transparent z-10 pointer-events-none"></div>
    <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-titanium-100 dark:from-titanium-950 to-transparent z-10 pointer-events-none"></div>

    <!-- Conveyor track lines -->
    <div class="absolute top-0 left-0 right-0 h-px bg-mintreu-red-600/20"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px bg-mintreu-red-600/20"></div>

    <!-- Row 1 - Forward -->
    <div class="flex whitespace-nowrap animate-marquee mb-4">
      <div v-for="n in 2" :key="n" class="flex items-center">
        <span
          v-for="(tech, idx) in technologiesRow1"
          :key="`${tech}-${n}-${idx}`"
          class="marquee-item px-8 text-2xl md:text-3xl lg:text-4xl font-heading font-bold text-titanium-300 dark:text-titanium-700 hover:text-mintreu-red-600 dark:hover:text-mintreu-red-500 transition-colors duration-300 cursor-default"
        >
          {{ tech }}
        </span>
      </div>
    </div>

    <!-- Row 2 - Reverse -->
    <div class="flex whitespace-nowrap animate-marquee-reverse">
      <div v-for="n in 2" :key="n" class="flex items-center">
        <span
          v-for="(tech, idx) in technologiesRow2"
          :key="`${tech}-${n}-${idx}`"
          class="marquee-item px-8 text-xl md:text-2xl lg:text-3xl font-heading font-bold text-titanium-200 dark:text-titanium-800 hover:text-mintreu-red-600 dark:hover:text-mintreu-red-500 transition-colors duration-300 cursor-default"
        >
          {{ tech }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { homeData, loadHomeData } = useHomeData()

onMounted(async () => {
  await loadHomeData()
})

const technologies = computed(() => homeData.value?.technologies ?? [])
const technologiesRow1 = computed(() => technologies.value.filter((_, index) => index % 2 === 0))
const technologiesRow2 = computed(() => technologies.value.filter((_, index) => index % 2 !== 0))
</script>
