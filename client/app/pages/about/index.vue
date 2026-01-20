<template>
  <div class="min-h-screen bg-white dark:bg-gray-950">
    <!-- Hero Section -->
    <section class="py-20 lg:py-32 relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 via-purple-600/10 to-pink-600/10"></div>
      <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-4xl mx-auto">
          <div class="inline-block mb-6">
            <span class="px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-sm font-semibold">
              About Us
            </span>
          </div>
          <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-8 text-gray-900 dark:text-white leading-tight">
            Building Digital
            <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Experiences</span>
            That Matter
          </h1>
          <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-400 leading-relaxed max-w-3xl mx-auto">
            We're a team of passionate developers and designers helping businesses transform their ideas into powerful digital solutions.
          </p>
        </div>
      </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-20 bg-gray-50 dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16">
          <div>
            <h2 class="text-3xl md:text-4xl font-black mb-6 text-gray-900 dark:text-white">
              Our <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Mission</span>
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
              To empower businesses with high-quality, scalable software solutions that drive growth and innovation. We believe in building technology that makes a real difference in people's lives.
            </p>
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
              Every project we take on is an opportunity to solve real problems and create meaningful experiences for users worldwide.
            </p>
          </div>
          <div>
            <h2 class="text-3xl md:text-4xl font-black mb-6 text-gray-900 dark:text-white">
              Our <span class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Vision</span>
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
              To be a globally recognized software development agency known for delivering excellence, innovation, and exceptional value to our clients.
            </p>
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
              We envision a future where every business, regardless of size, has access to world-class software solutions that help them compete and thrive in the digital economy.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats -->
    <section class="py-20 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <div class="text-center">
            <div class="text-4xl md:text-6xl font-black text-white mb-2">50+</div>
            <div class="text-white/80 text-lg">Projects Completed</div>
          </div>
          <div class="text-center">
            <div class="text-4xl md:text-6xl font-black text-white mb-2">30+</div>
            <div class="text-white/80 text-lg">Happy Clients</div>
          </div>
          <div class="text-center">
            <div class="text-4xl md:text-6xl font-black text-white mb-2">5+</div>
            <div class="text-white/80 text-lg">Years Experience</div>
          </div>
          <div class="text-center">
            <div class="text-4xl md:text-6xl font-black text-white mb-2">15+</div>
            <div class="text-white/80 text-lg">Technologies</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Values -->
    <section class="py-20 bg-gray-50 dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-sm font-semibold">
              Our Values
            </span>
          </div>
          <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white">
            What Drives <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Us</span>
          </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-shadow">
            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mb-6">
              <Icon name="lucide:zap" class="w-8 h-8 text-blue-600 dark:text-blue-400" />
            </div>
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Quality First</h3>
            <p class="text-gray-600 dark:text-gray-400">
              We never compromise on quality. Every line of code, every design element is crafted with precision and care.
            </p>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-shadow">
            <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center mb-6">
              <Icon name="lucide:users" class="w-8 h-8 text-purple-600 dark:text-purple-400" />
            </div>
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Client Partnership</h3>
            <p class="text-gray-600 dark:text-gray-400">
              We work alongside our clients as true partners, sharing goals and celebrating successes together.
            </p>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-shadow">
            <div class="w-16 h-16 bg-pink-100 dark:bg-pink-900/30 rounded-2xl flex items-center justify-center mb-6">
              <Icon name="lucide:lightbulb" class="w-8 h-8 text-pink-600 dark:text-pink-400" />
            </div>
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Continuous Innovation</h3>
            <p class="text-gray-600 dark:text-gray-400">
              Technology evolves rapidly. We stay ahead of the curve to deliver cutting-edge solutions.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Technology Stack -->
    <section class="py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-sm font-semibold">
              Technologies
            </span>
          </div>
          <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white">
            Our Tech <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Stack</span>
          </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
          <div v-for="tech in technologies" :key="tech.name" class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 flex flex-col items-center justify-center hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
            <Icon :name="tech.icon" class="w-12 h-12 mb-3 text-gray-700 dark:text-gray-300" />
            <span class="font-semibold text-gray-900 dark:text-white">{{ tech.name }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Team -->
    <section class="py-20 bg-gray-50 dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-full text-sm font-semibold">
              Team
            </span>
          </div>
          <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white">
            Meet the <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Team</span>
          </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div v-for="member in team" :key="member.name" class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-xl">
            <div class="h-64 bg-gradient-to-br from-blue-500 via-purple-600 to-pink-600 flex items-center justify-center">
              <Icon name="lucide:user" class="w-24 h-24 text-white/30" />
            </div>
            <div class="p-6">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ member.name }}</h3>
              <p class="text-blue-600 dark:text-blue-400 font-medium mb-3">{{ member.role }}</p>
              <p class="text-gray-600 dark:text-gray-400 text-sm">{{ member.bio }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-black mb-6 text-gray-900 dark:text-white">
          Ready to Start Your <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Project?</span>
        </h2>
        <p class="text-xl text-gray-600 dark:text-gray-400 mb-10">
          Let's discuss how we can help bring your ideas to life.
        </p>
        <NuxtLink
            to="/contact"
            class="inline-flex items-center space-x-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all"
        >
          <span>Get In Touch</span>
          <Icon name="lucide:arrow-right" class="w-5 h-5" />
        </NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
useSeoMeta({
  title: 'About | Mintreu - Digital Experiences That Matter',
  description: 'Learn about Mintreu - a team of passionate developers and designers building digital experiences that matter.'
})

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
  { name: 'Git', icon: 'lucide:git-branch' },
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
  },
]
</script>

<style scoped>
.bg-grid-pattern {
  background-image:
    linear-gradient(to right, rgba(59, 130, 246, 0.1) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(59, 130, 246, 0.1) 1px, transparent 1px);
  background-size: 30px 30px;
}
</style>
