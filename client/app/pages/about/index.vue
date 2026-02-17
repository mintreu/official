<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 relative">
    <div class="absolute inset-0 bg-blueprint-fine pointer-events-none"></div>

    <!-- Hero Section -->
    <section ref="sectionRef" class="about-hero py-20 lg:py-32 relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-4xl mx-auto">
          <div class="inline-block mb-6">
            <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
              About Us
            </span>
          </div>
          <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-heading font-black mb-8 text-titanium-900 dark:text-white leading-tight">
            Building Digital
            <span class="text-mintreu-red-600">Experiences</span>
            That Matter
          </h1>
          <p class="text-xl md:text-2xl text-titanium-600 dark:text-titanium-400 leading-relaxed max-w-3xl mx-auto font-subheading">
            We're a team of passionate developers and designers helping businesses transform their ideas into powerful digital solutions.
          </p>
          <div class="line-technical mt-8 mx-auto max-w-md"></div>
        </div>
      </div>
    </section>

    <!-- Mission & Vision -->
    <section class="about-mission py-20 bg-white dark:bg-titanium-900 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16">
          <div class="mission-card bg-titanium-50 dark:bg-titanium-800 rounded-3xl p-8 lg:p-12 border border-dashed border-titanium-300 dark:border-titanium-700 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
            <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600/40 rounded-br-xl"></div>
            <div class="relative">
              <span class="label-schematic mb-4 block">MISSION-001</span>
              <h2 class="text-3xl md:text-4xl font-heading font-black mb-6 text-titanium-900 dark:text-white">
                Our <span class="text-mintreu-red-600">Mission</span>
              </h2>
              <p class="text-lg text-titanium-600 dark:text-titanium-400 leading-relaxed mb-6 font-subheading">
                To empower businesses with high-quality, scalable software solutions that drive growth and innovation. We believe in building technology that makes a real difference in people's lives.
              </p>
              <p class="text-lg text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">
                Every project we take on is an opportunity to solve real problems and create meaningful experiences for users worldwide.
              </p>
            </div>
          </div>
          <div class="vision-card bg-titanium-50 dark:bg-titanium-800 rounded-3xl p-8 lg:p-12 border border-dashed border-titanium-300 dark:border-titanium-700 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
            <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-mintreu-red-600/40 rounded-br-xl"></div>
            <div class="relative">
              <span class="label-schematic mb-4 block">VISION-001</span>
              <h2 class="text-3xl md:text-4xl font-heading font-black mb-6 text-titanium-900 dark:text-white">
                Our <span class="text-mintreu-red-600">Vision</span>
              </h2>
              <p class="text-lg text-titanium-600 dark:text-titanium-400 leading-relaxed mb-6 font-subheading">
                To be a globally recognized software development agency known for delivering excellence, innovation, and exceptional value to our clients.
              </p>
              <p class="text-lg text-titanium-600 dark:text-titanium-400 leading-relaxed font-subheading">
                We envision a future where every business, regardless of size, has access to world-class software solutions that help them compete and thrive in the digital economy.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats -->
    <section class="about-stats relative overflow-hidden">
      <div class="absolute inset-0 bg-titanium-900"></div>
      <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>
      <div class="relative py-20 border-y border-dashed border-titanium-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div v-for="stat in stats" :key="stat.label" class="stat-item text-center">
              <div class="text-4xl md:text-6xl font-heading font-black text-mintreu-red-500 mb-2">{{ stat.value }}</div>
              <div class="text-titanium-400 text-lg font-subheading">{{ stat.label }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Values -->
    <section class="about-values py-20 bg-titanium-50 dark:bg-titanium-950 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
              Our Values
            </span>
          </div>
          <h2 class="text-4xl md:text-5xl font-heading font-black text-titanium-900 dark:text-white">
            What Drives <span class="text-mintreu-red-600">Us</span>
          </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div v-for="value in values" :key="value.title" class="value-card bg-white dark:bg-titanium-900 rounded-3xl p-8 shadow-xl border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-mintreu-red-600/40 rounded-tl-xl"></div>
            <div class="w-16 h-16 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 rounded-2xl flex items-center justify-center mb-6">
              <Icon :name="value.icon" class="w-8 h-8 text-mintreu-red-600" />
            </div>
            <h3 class="text-2xl font-heading font-bold mb-4 text-titanium-900 dark:text-white">{{ value.title }}</h3>
            <p class="text-titanium-600 dark:text-titanium-400 font-subheading leading-relaxed">{{ value.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Technology Stack -->
    <section class="about-tech py-20 bg-white dark:bg-titanium-900 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
              Technologies
            </span>
          </div>
          <h2 class="text-4xl md:text-5xl font-heading font-black text-titanium-900 dark:text-white">
            Our Tech <span class="text-mintreu-red-600">Stack</span>
          </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
          <div v-for="tech in technologies" :key="tech.name" class="tech-card bg-titanium-50 dark:bg-titanium-800 rounded-2xl p-6 flex flex-col items-center justify-center border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 hover:-translate-y-1 transition-all duration-300">
            <Icon :name="tech.icon" class="w-12 h-12 mb-3 text-titanium-700 dark:text-titanium-300" />
            <span class="font-heading font-bold text-titanium-900 dark:text-white text-sm">{{ tech.name }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Team -->
    <section class="about-team py-20 bg-titanium-50 dark:bg-titanium-950 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-mintreu-red-100 dark:bg-mintreu-red-900/30 text-mintreu-red-700 dark:text-mintreu-red-400 rounded-full text-sm font-heading font-bold uppercase tracking-wider">
              Team
            </span>
          </div>
          <h2 class="text-4xl md:text-5xl font-heading font-black text-titanium-900 dark:text-white">
            Meet the <span class="text-mintreu-red-600">Team</span>
          </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div v-for="member in team" :key="member.name" class="team-card bg-white dark:bg-titanium-900 rounded-3xl overflow-hidden shadow-xl border border-dashed border-titanium-300 dark:border-titanium-700 hover:border-mintreu-red-600/50 hover:-translate-y-1 transition-all duration-300">
            <div class="h-64 bg-titanium-800 flex items-center justify-center relative">
              <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>
              <Icon name="lucide:user" class="relative w-24 h-24 text-titanium-600" />
            </div>
            <div class="p-6">
              <h3 class="text-xl font-heading font-bold text-titanium-900 dark:text-white">{{ member.name }}</h3>
              <p class="text-mintreu-red-600 font-heading font-semibold mb-3 text-sm">{{ member.role }}</p>
              <p class="text-titanium-600 dark:text-titanium-400 text-sm font-subheading leading-relaxed">{{ member.bio }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="about-cta relative overflow-hidden">
      <div class="absolute inset-0 bg-titanium-900"></div>
      <div class="absolute inset-0 bg-blueprint opacity-20 pointer-events-none"></div>
      <div class="relative py-20 border-t border-dashed border-titanium-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div class="absolute top-4 left-4 w-8 h-8 border-t-2 border-l-2 border-mintreu-red-600 rounded-tl-xl"></div>
          <div class="absolute bottom-4 right-4 w-8 h-8 border-b-2 border-r-2 border-mintreu-red-600 rounded-br-xl"></div>

          <h2 class="text-4xl md:text-5xl font-heading font-black mb-6 text-white">
            Ready to Start Your <span class="text-mintreu-red-500">Project?</span>
          </h2>
          <p class="text-xl text-titanium-400 mb-10 font-subheading">
            Let's discuss how we can help bring your ideas to life.
          </p>
          <NuxtLink
            to="/contact"
            class="inline-flex items-center space-x-2 px-8 py-4 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white font-heading font-bold rounded-xl shadow-lg glow-red hover:shadow-xl transform hover:scale-105 transition-all"
          >
            <span>Get In Touch</span>
            <Icon name="lucide:arrow-right" class="w-5 h-5" />
          </NuxtLink>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

useSeoMeta({
  title: 'About | Mintreu - Digital Experiences That Matter',
  description: 'Learn about Mintreu - a team of passionate developers and designers building digital experiences that matter.'
})

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const stats = [
  { value: '50+', label: 'Projects Completed' },
  { value: '30+', label: 'Happy Clients' },
  { value: '5+', label: 'Years Experience' },
  { value: '15+', label: 'Technologies' }
]

const values = [
  {
    icon: 'lucide:zap',
    title: 'Quality First',
    description: 'We never compromise on quality. Every line of code, every design element is crafted with precision and care.'
  },
  {
    icon: 'lucide:users',
    title: 'Client Partnership',
    description: 'We work alongside our clients as true partners, sharing goals and celebrating successes together.'
  },
  {
    icon: 'lucide:lightbulb',
    title: 'Continuous Innovation',
    description: 'Technology evolves rapidly. We stay ahead of the curve to deliver cutting-edge solutions.'
  }
]

const technologies = [
  { name: 'Laravel', icon: 'lucide:server' },
  { name: 'Nuxt', icon: 'lucide:layers' },
  { name: 'Vue.js', icon: 'lucide:box' },
  { name: 'React', icon: 'lucide:atom' },
  { name: 'Node.js', icon: 'lucide:cpu' },
  { name: 'TypeScript', icon: 'lucide:code-2' },
  { name: 'Tailwind', icon: 'lucide:palette' },
  { name: 'PostgreSQL', icon: 'lucide:database' },
  { name: 'Redis', icon: 'lucide:bolt' },
  { name: 'Docker', icon: 'lucide:box' },
  { name: 'AWS', icon: 'lucide:cloud' },
  { name: 'Git', icon: 'lucide:git-branch' }
]

const team = [
  {
    name: 'Krishanu',
    role: 'Founder & Lead Developer',
    bio: 'Full-stack developer with 5+ years experience building scalable applications.'
  },
  {
    name: 'Team Member',
    role: 'Designer',
    bio: 'Creative designer passionate about building beautiful user experiences.'
  },
  {
    name: 'Team Member',
    role: 'Backend Developer',
    bio: 'API specialist with expertise in Laravel and system architecture.'
  }
]

const initAnimations = () => {
  ctx?.revert()
  if (!sectionRef.value) return
  ctx = gsap.context(() => {
    gsap.from('.about-hero', { y: 40, opacity: 0, duration: 0.8, ease: 'power3.out' })

    const sections = ['.about-mission', '.about-stats', '.about-values', '.about-tech', '.about-team', '.about-cta']
    sections.forEach((sel) => {
      gsap.from(sel, {
        y: 40, opacity: 0, duration: 0.8, ease: 'power3.out',
        scrollTrigger: { trigger: sel, start: 'top 85%' }
      })
    })

    const cards = ['.mission-card', '.vision-card']
    cards.forEach((sel, i) => {
      gsap.from(sel, {
        x: i === 0 ? -40 : 40, opacity: 0, duration: 0.7, ease: 'power3.out',
        scrollTrigger: { trigger: sel, start: 'top 85%' }
      })
    })

    const statItems = gsap.utils.toArray('.stat-item') as HTMLElement[]
    statItems.forEach((item, i) => {
      gsap.from(item, {
        y: 30, opacity: 0, duration: 0.6, delay: i * 0.1,
        ease: 'back.out(1.3)',
        scrollTrigger: { trigger: item, start: 'top 90%' }
      })
    })

    const valueCards = gsap.utils.toArray('.value-card') as HTMLElement[]
    valueCards.forEach((card, i) => {
      gsap.from(card, {
        y: 40, opacity: 0, scale: 0.95, duration: 0.7, delay: i * 0.1,
        ease: 'back.out(1.3)',
        scrollTrigger: { trigger: card, start: 'top 90%' }
      })
    })

    const techCards = gsap.utils.toArray('.tech-card') as HTMLElement[]
    techCards.forEach((card, i) => {
      gsap.from(card, {
        y: 20, opacity: 0, duration: 0.5, delay: i * 0.04,
        ease: 'power2.out',
        scrollTrigger: { trigger: card, start: 'top 92%' }
      })
    })

    const teamCards = gsap.utils.toArray('.team-card') as HTMLElement[]
    teamCards.forEach((card, i) => {
      gsap.from(card, {
        y: 40, opacity: 0, duration: 0.7, delay: i * 0.15,
        ease: 'back.out(1.3)',
        scrollTrigger: { trigger: card, start: 'top 90%' }
      })
    })
  }, sectionRef.value)
}

onMounted(() => { initAnimations() })
onUnmounted(() => { ctx?.revert() })
</script>
