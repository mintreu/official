<script setup lang="ts">
import { onMounted, onUnmounted, ref, nextTick } from 'vue'
import gsap from 'gsap'

withDefaults(defineProps<{
  badge: string
  title: string
  subtitle: string
  nodeColor?: string
  nodeCount?: number
}>(), {
  nodeColor: '#DC2626',
  nodeCount: 50,
})

const heroRef = ref<HTMLElement | null>(null)
let tl: gsap.core.Timeline | null = null

onMounted(() => {
  nextTick(() => {
    if (!heroRef.value) return
    tl = gsap.timeline({ delay: 0.1 })
    const q = (sel: string) => heroRef.value!.querySelector(sel)
    tl.from(q('.hero-badge')!, { y: 20, opacity: 0, duration: 0.6, ease: 'power3.out' })
      .from(q('.hero-title')!, { y: 40, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.35')
      .from(q('.hero-desc')!, { y: 30, opacity: 0, duration: 0.7, ease: 'power3.out' }, '-=0.35')
      .from(q('.hero-line')!, { scaleX: 0, transformOrigin: 'center', duration: 1, ease: 'power2.out' }, '-=0.3')
  })
})

onUnmounted(() => { tl?.kill() })
</script>

<template>
  <section ref="heroRef" class="relative min-h-[50vh] flex items-center justify-center overflow-hidden bg-titanium-950">
    <!-- 3D Background -->
    <div class="absolute inset-0 z-0">
      <ClientOnly>
        <TresCanvas :clear-color="'#1a1d1e'" :alpha="false" window-size>
          <TresPerspectiveCamera :position="[0, 0, 10]" :fov="50" />
          <TresAmbientLight :intensity="0.2" />
          <TresDirectionalLight :position="[5, 3, 5]" :intensity="0.6" :color="nodeColor" />
          <TresDirectionalLight :position="[-4, 2, 3]" :intensity="0.3" color="#3b82f6" />
          <ThreeFloatingNodes :count="nodeCount" :spread="14" :color="nodeColor" />
          <ThreeBlueprintGrid :size="30" :divisions="60" color="#4b5563" :opacity="0.06" />
        </TresCanvas>
      </ClientOnly>
    </div>

    <!-- Gradient overlays -->
    <div class="absolute inset-0 bg-gradient-to-b from-titanium-950/40 via-transparent to-titanium-950/80 z-[1]"></div>
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-titanium-50 dark:from-titanium-950 z-[2]"></div>

    <!-- Content -->
    <div class="relative z-10 text-center px-4 sm:px-6 max-w-3xl mx-auto py-20">
      <div class="hero-badge inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/10 mb-6">
        <span class="text-sm font-heading font-bold uppercase tracking-wider text-titanium-200">{{ badge }}</span>
      </div>

      <!-- eslint-disable-next-line vue/no-v-html -->
      <h1 class="hero-title text-4xl sm:text-5xl md:text-6xl font-heading font-black text-white mb-6 leading-[1.1]" v-html="title"></h1>

      <p class="hero-desc text-lg sm:text-xl text-titanium-300 max-w-xl mx-auto font-subheading leading-relaxed">
        {{ subtitle }}
      </p>

      <div class="hero-line line-technical mt-10 mx-auto max-w-md"></div>
    </div>
  </section>
</template>
