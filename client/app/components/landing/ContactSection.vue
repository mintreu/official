<template>
  <section id="contact" ref="sectionRef" class="py-20 lg:py-32 relative overflow-hidden">
    <!-- Dark industrial background -->
    <div class="absolute inset-0 bg-titanium-900 dark:bg-titanium-950"></div>
    <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>

    <!-- Floating decorative elements -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-mintreu-red-600/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-mintreu-red-600/10 rounded-full blur-3xl animate-float-delayed"></div>

    <!-- Technical dimension lines -->
    <div class="absolute top-16 left-8 hidden lg:block">
      <div class="w-px h-24 bg-gradient-to-b from-mintreu-red-600/0 via-mintreu-red-600/30 to-mintreu-red-600/0"></div>
    </div>
    <div class="absolute top-16 right-8 hidden lg:block">
      <div class="w-px h-24 bg-gradient-to-b from-mintreu-red-600/0 via-mintreu-red-600/30 to-mintreu-red-600/0"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <!-- Spec label -->
      <div class="contact-badge label-schematic mb-4">SEC-CONTACT // REV-A</div>

      <h2 class="contact-heading text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-black text-white mb-6 leading-tight">
        Ready to Start Your Project?
      </h2>

      <p class="contact-subheading text-lg md:text-xl text-titanium-400 mb-12 max-w-2xl mx-auto leading-relaxed font-subheading">
        Let's discuss your requirements and build something amazing together. Available for projects of any scale.
      </p>

      <!-- Contact Options -->
      <div class="contact-cta grid grid-cols-1 sm:grid-cols-2 gap-4 mb-12 max-w-2xl mx-auto">
        <a
          href="mailto:hello@mintreu.com"
          class="group flex items-center justify-center space-x-3 px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white text-lg font-heading font-bold rounded-xl shadow-2xl glow-red transform hover:scale-105 hover:-translate-y-1 active:scale-95 transition-all duration-300"
        >
          <Icon name="lucide:mail" class="w-5 h-5" />
          <span>Email Us</span>
        </a>

        <a
          href="#"
          @click.prevent="scheduleCall"
          class="group flex items-center justify-center space-x-3 px-8 py-4 bg-titanium-800/50 backdrop-blur-xl border-2 border-titanium-600 hover:border-mintreu-red-600 text-white text-lg font-heading font-bold rounded-xl transform hover:scale-105 hover:-translate-y-1 active:scale-95 transition-all duration-300"
        >
          <Icon name="lucide:calendar" class="w-5 h-5" />
          <span>Schedule Call</span>
        </a>
      </div>

      <!-- Social Links -->
      <div class="contact-socials flex items-center justify-center space-x-4">
        <a
          v-for="social in socials"
          :key="social.name"
          :href="social.url"
          class="group w-14 h-14 rounded-xl bg-titanium-800/50 backdrop-blur-xl border border-titanium-700 hover:bg-mintreu-red-600 hover:border-mintreu-red-600 flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:-translate-y-1"
          :aria-label="social.name"
        >
          <Icon :name="social.icon" class="w-6 h-6 text-titanium-400 group-hover:text-white transition-colors" />
        </a>
      </div>

      <!-- Response Time Badge -->
      <div class="contact-badge-bottom mt-8 inline-flex items-center space-x-2 px-4 py-2 bg-titanium-800/50 backdrop-blur-xl rounded-full border border-titanium-700 pulse-ring">
        <Icon name="lucide:zap" class="w-4 h-4 text-mintreu-red-500" />
        <span class="text-sm text-titanium-300 font-subheading font-medium">Typically respond within 2 hours</span>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null
const { homeData, loadHomeData } = useHomeData()

const socials = computed(() => homeData.value?.socials ?? [])

const scheduleCall = () => {
  window.open('https://calendly.com/mintreu', '_blank')
}

onMounted(async () => {
  await loadHomeData()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    const tl = gsap.timeline({
      scrollTrigger: { trigger: sectionRef.value, start: 'top 75%' },
    })

    tl.from('.contact-badge', { y: -20, opacity: 0, duration: 0.5, ease: 'power3.out' })
      .from('.contact-heading', {
        y: 40, opacity: 0, rotateX: 10,
        duration: 0.8, ease: 'power3.out',
      }, '-=0.2')
      .from('.contact-subheading', { y: 20, opacity: 0, duration: 0.6, ease: 'power3.out' }, '-=0.3')
      .from('.contact-cta > a', {
        y: 30, opacity: 0, scale: 0.9,
        duration: 0.6, stagger: 0.15, ease: 'back.out(1.3)',
      }, '-=0.2')

    // Social icons - elastic pop-in one by one
    const socialIcons = gsap.utils.toArray('.contact-socials a') as HTMLElement[]
    if (socialIcons.length) {
      tl.from(socialIcons, {
        scale: 0, opacity: 0, rotation: -45,
        duration: 0.5, stagger: 0.08, ease: 'elastic.out(1, 0.5)',
      }, '-=0.3')
    }

    tl.from('.contact-badge-bottom', {
      y: 15, opacity: 0, scale: 0.9,
      duration: 0.5, ease: 'back.out(1.2)',
    }, '-=0.2')
  }, sectionRef.value)
})

onUnmounted(() => { ctx?.revert() })
</script>
