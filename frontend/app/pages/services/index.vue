<template>
  <div class="min-h-screen bg-white dark:bg-gray-950">
    <!-- Hero Section -->
    <section class="py-20 lg:py-32 relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 via-purple-600/5 to-pink-600/5"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-4xl mx-auto">
          <div class="inline-block mb-6">
            <span class="px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-sm font-semibold">
              Services
            </span>
          </div>
          <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-8 text-gray-900 dark:text-white leading-tight">
            Premium <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Development</span> Services
          </h1>
          <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-400 leading-relaxed max-w-3xl mx-auto">
            From concept to deployment, we deliver end-to-end software solutions tailored to your unique needs.
          </p>
        </div>
      </div>
    </section>

    <!-- Services Grid -->
    <section class="py-20 bg-gray-50 dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Web Development -->
          <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6">
              <Icon name="lucide:globe" class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Web Development</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
              Custom web applications built with modern frameworks like Laravel, Nuxt, and React. Scalable, secure, and performant.
            </p>
            <ul class="space-y-2">
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-blue-500" />
                <span>Custom Web Applications</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-blue-500" />
                <span>SPA & SSR Solutions</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-blue-500" />
                <span>API Development</span>
              </li>
            </ul>
          </div>

          <!-- Mobile Apps -->
          <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
              <Icon name="lucide:smartphone" class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Mobile Development</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
              Native and cross-platform mobile applications for iOS and Android. Beautiful UI with native performance.
            </p>
            <ul class="space-y-2">
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-purple-500" />
                <span>iOS & Android Apps</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-purple-500" />
                <span>Cross-Platform (React Native)</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-purple-500" />
                <span>App Store Optimization</span>
              </li>
            </ul>
          </div>

          <!-- SaaS Products -->
          <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
              <Icon name="lucide:cloud" class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">SaaS Development</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
              Build recurring revenue with subscription-based software products. From MVP to enterprise scale.
            </p>
            <ul class="space-y-2">
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-pink-500" />
                <span>SaaS MVP Development</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-pink-500" />
                <span>Subscription Management</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-pink-500" />
                <span>Multi-tenant Architecture</span>
              </li>
            </ul>
          </div>

          <!-- API Development -->
          <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6">
              <Icon name="lucide:server" class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">API Development</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
              Robust, scalable APIs designed for performance and security. RESTful and GraphQL implementations.
            </p>
            <ul class="space-y-2">
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-green-500" />
                <span>RESTful API Design</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-green-500" />
                <span>GraphQL APIs</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-green-500" />
                <span>API Documentation</span>
              </li>
            </ul>
          </div>

          <!-- E-Commerce -->
          <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6">
              <Icon name="lucide:shopping-cart" class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">E-Commerce</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
              Complete e-commerce solutions with payment integration, inventory management, and analytics.
            </p>
            <ul class="space-y-2">
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-orange-500" />
                <span>Custom Store Development</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-orange-500" />
                <span>Payment Integration</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-orange-500" />
                <span>Inventory Systems</span>
              </li>
            </ul>
          </div>

          <!-- Consulting -->
          <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
              <Icon name="lucide:lightbulb" class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Tech Consulting</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
              Strategic technology consulting to help you make informed decisions about your digital products.
            </p>
            <ul class="space-y-2">
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-red-500" />
                <span>Architecture Planning</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-red-500" />
                <span>Technology Selection</span>
              </li>
              <li class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                <Icon name="lucide:check" class="w-5 h-5 text-red-500" />
                <span>Code Review & Audit</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Process -->
    <section class="py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-sm font-semibold">
              Process
            </span>
          </div>
          <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white">
            How We <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Work</span>
          </h2>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
          <div v-for="(step, index) in process" :key="step.title" class="text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
              {{ index + 1 }}
            </div>
            <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">{{ step.title }}</h3>
            <p class="text-gray-600 dark:text-gray-400">{{ step.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Pricing -->
    <section class="py-20 bg-gray-50 dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-sm font-semibold">
              Pricing
            </span>
          </div>
          <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white">
            Simple, Transparent <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Pricing</span>
          </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
          <!-- Hourly -->
          <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl">
            <h3 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Hourly Rate</h3>
            <div class="text-4xl font-black text-blue-600 mb-6">$75<span class="text-lg text-gray-500 font-normal">/hr</span></div>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Perfect for ongoing projects and maintenance.</p>
            <ul class="space-y-3 mb-8">
              <li class="flex items-center space-x-2">
                <Icon name="lucide:check" class="w-5 h-5 text-green-500" />
                <span class="text-gray-700 dark:text-gray-300">Flexible engagement</span>
              </li>
              <li class="flex items-center space-x-2">
                <Icon name="lucide:check" class="w-5 h-5 text-green-500" />
                <span class="text-gray-700 dark:text-gray-300">Quick turnaround</span>
              </li>
              <li class="flex items-center space-x-2">
                <Icon name="lucide:check" class="w-5 h-5 text-green-500" />
                <span class="text-gray-700 dark:text-gray-300">No long-term commitment</span>
              </li>
            </ul>
          </div>

          <!-- Project -->
          <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-3xl p-8 shadow-2xl transform scale-105">
            <div class="text-white">
              <h3 class="text-2xl font-bold mb-2">Fixed Project</h3>
              <div class="text-4xl font-black mb-6">Custom</div>
              <p class="text-white/90 mb-6">Best for well-defined projects with clear requirements.</p>
              <ul class="space-y-3 mb-8">
                <li class="flex items-center space-x-2">
                  <Icon name="lucide:check" class="w-5 h-5 text-white" />
                  <span>Fixed timeline & budget</span>
                </li>
                <li class="flex items-center space-x-2">
                  <Icon name="lucide:check" class="w-5 h-5 text-white" />
                  <span>Detailed proposal</span>
                </li>
                <li class="flex items-center space-x-2">
                  <Icon name="lucide:check" class="w-5 h-5 text-white" />
                  <span>Regular updates</span>
                </li>
              </ul>
            </div>
          </div>

          <!-- Retainer -->
          <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl">
            <h3 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Monthly Retainer</h3>
            <div class="text-4xl font-black text-purple-600 mb-6">$2,500+<span class="text-lg text-gray-500 font-normal">/mo</span></div>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Dedicated team for ongoing development needs.</p>
            <ul class="space-y-3 mb-8">
              <li class="flex items-center space-x-2">
                <Icon name="lucide:check" class="w-5 h-5 text-green-500" />
                <span class="text-gray-700 dark:text-gray-300">Dedicated hours</span>
              </li>
              <li class="flex items-center space-x-2">
                <Icon name="lucide:check" class="w-5 h-5 text-green-500" />
                <span class="text-gray-700 dark:text-gray-300">Priority support</span>
              </li>
              <li class="flex items-center space-x-2">
                <Icon name="lucide:check" class="w-5 h-5 text-green-500" />
                <span class="text-gray-700 dark:text-gray-300">Flexible rollover</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="py-20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-black mb-6 text-gray-900 dark:text-white">
          Ready to Start Your <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Project?</span>
        </h2>
        <p class="text-xl text-gray-600 dark:text-gray-400 mb-10">
          Let's discuss your project and find the perfect solution for your needs.
        </p>
        <NuxtLink
            to="/contact"
            class="inline-flex items-center space-x-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all"
        >
          <span>Get Free Consultation</span>
          <Icon name="lucide:arrow-right" class="w-5 h-5" />
        </NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
useSeoMeta({
  title: 'Services | Mintreu - Premium Development Services',
  description: 'Explore our premium development services including web development, mobile apps, SaaS products, and API development.'
})

const process = [
  {
    title: 'Discovery',
    description: 'We learn about your business, goals, and requirements to create a detailed project plan.'
  },
  {
    title: 'Design',
    description: 'Our designers create wireframes and prototypes to visualize the solution before development.'
  },
  {
    title: 'Development',
    description: 'Our developers build your solution using best practices and modern technologies.'
  },
  {
    title: 'Launch',
    description: 'We deploy your project and provide ongoing support to ensure success.'
  }
]
</script>
