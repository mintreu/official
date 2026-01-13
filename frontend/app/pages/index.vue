<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 transition-colors duration-500">
    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
      <!-- Dynamic Gradient Background -->
      <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-950 dark:via-blue-950/30 dark:to-purple-950/30 transition-all duration-700"></div>

      <!-- Animated Background Elements -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 -left-1/4 w-96 h-96 bg-blue-500/30 dark:bg-blue-500/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-1/4 -right-1/4 w-96 h-96 bg-purple-500/30 dark:bg-purple-500/20 rounded-full blur-3xl animate-float-delayed"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-pink-500/20 dark:bg-pink-500/10 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.02] dark:opacity-[0.03]"></div>
      </div>

      <!-- Content -->
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <!-- Status Badge -->
        <div class="inline-flex items-center space-x-2 px-5 py-2.5 mb-8 bg-white/60 dark:bg-white/5 backdrop-blur-xl rounded-full border border-white/20 dark:border-white/10 shadow-lg shadow-blue-500/10 dark:shadow-blue-500/5 animate-fade-in-down group hover:scale-105 transition-all duration-300">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
          </span>
          <span class="text-sm font-medium bg-gradient-to-r from-gray-700 to-gray-900 dark:from-gray-200 dark:to-white bg-clip-text text-transparent">
            Open for new projects
          </span>
        </div>

        <!-- Main Heading -->
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black leading-tight mb-6 animate-fade-in-up">
          <span class="block text-gray-900 dark:text-white">Build. Design. Ship.</span>
          <span class="block mt-2 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 dark:from-blue-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent animate-gradient bg-[length:200%_auto] drop-shadow-[0_0_30px_rgba(59,130,246,0.3)]">
            Maintain. Scale.
          </span>
        </h1>

        <!-- Subheading -->
        <p class="text-lg sm:text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-10 animate-fade-in-up animation-delay-200 leading-relaxed font-medium">
          Complete digital solutions from concept to deployment. Web, Mobile & Desktop applications with ongoing support and maintenance.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16 animate-fade-in-up animation-delay-400">
          <NuxtLink
              to="#hire"
              class="group relative inline-flex items-center space-x-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-lg font-semibold rounded-xl shadow-2xl shadow-blue-500/50 dark:shadow-blue-500/30 transform hover:scale-105 hover:-translate-y-1 active:scale-95 transition-all duration-300 w-full sm:w-auto overflow-hidden"
          >
            <span class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-400 opacity-0 group-hover:opacity-100 transition-opacity blur-xl"></span>
            <span class="relative flex items-center space-x-2">
              <span>Start Your Project</span>
              <Icon name="lucide:rocket" class="w-5 h-5 group-hover:scale-110 transition-transform" />
            </span>
          </NuxtLink>

          <NuxtLink
              to="#services"
              class="group inline-flex items-center space-x-2 px-8 py-4 bg-white/80 dark:bg-white/5 backdrop-blur-xl border-2 border-gray-300 dark:border-white/10 hover:border-purple-500 dark:hover:border-purple-400 text-gray-900 dark:text-white text-lg font-semibold rounded-xl shadow-xl hover:shadow-2xl transform hover:scale-105 hover:-translate-y-1 active:scale-95 transition-all duration-300 w-full sm:w-auto"
          >
            <span>Explore Services</span>
            <Icon name="lucide:sparkles" class="w-5 h-5 group-hover:rotate-12 transition-transform" />
          </NuxtLink>
        </div>

        <!-- Premium Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6 max-w-4xl mx-auto animate-fade-in animation-delay-600">
          <div
              v-for="(stat, index) in stats"
              :key="stat.label"
              class="group relative bg-white/60 dark:bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/20 dark:border-white/10 shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300"
              :style="{ animationDelay: `${index * 100}ms` }"
          >
            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 blur-xl transition-opacity"></div>
            <div class="relative">
              <div class="text-3xl sm:text-4xl lg:text-5xl font-black bg-gradient-to-br from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mb-2">
                {{ stat.value }}
              </div>
              <div class="text-sm sm:text-base text-gray-600 dark:text-gray-400 font-medium">
                {{ stat.label }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Scroll Indicator -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce-slow">
        <div class="relative">
          <div class="absolute inset-0 bg-blue-500/30 blur-lg rounded-full"></div>
          <Icon name="lucide:chevron-down" class="relative w-8 h-8 text-gray-600 dark:text-gray-400" />
        </div>
      </div>
    </section>

    <!-- Comparison Table Section -->
    <section id="comparison" class="py-20 lg:py-32 relative overflow-hidden bg-white dark:bg-gray-950">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-sm font-semibold">
              Why Choose Mintreu
            </span>
          </div>
          <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black mb-6 text-gray-900 dark:text-white">
            The
            <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 dark:from-blue-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
              Mintreu Advantage
            </span>
          </h2>
          <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 leading-relaxed">
            See why businesses choose Mintreu over traditional agencies and freelance platforms
          </p>
        </div>

        <!-- Comparison Table -->
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden rounded-3xl border border-gray-200 dark:border-gray-800 shadow-2xl">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600">
                <tr>
                  <th scope="col" class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">
                    Feature
                  </th>
                  <th scope="col" class="px-6 py-4 text-center text-sm font-bold text-white uppercase tracking-wider">
                    Mintreu
                  </th>
                  <th scope="col" class="px-6 py-4 text-center text-sm font-bold text-white uppercase tracking-wider">
                    Traditional Agencies
                  </th>
                  <th scope="col" class="px-6 py-4 text-center text-sm font-bold text-white uppercase tracking-wider">
                    Freelance Platforms
                  </th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                <tr v-for="(row, index) in comparisonData" :key="index" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                    {{ row.feature }}
                  </td>
                  <td class="px-6 py-4 text-center">
                      <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-bold" :class="getComparisonClass(row.mintreu)">
                        {{ row.mintreu }}
                      </span>
                  </td>
                  <td class="px-6 py-4 text-center">
                      <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800">
                        {{ row.agency }}
                      </span>
                  </td>
                  <td class="px-6 py-4 text-center">
                      <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800">
                        {{ row.platform }}
                      </span>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Pricing Plans Section -->
    <section id="hire" class="py-20 lg:py-32 relative overflow-hidden bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-950">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-sm font-semibold">
              Flexible Hiring
            </span>
          </div>
          <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black mb-6 text-gray-900 dark:text-white">
            Choose Your
            <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 dark:from-blue-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
              Engagement Model
            </span>
          </h2>
          <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 leading-relaxed">
            Select the option that best fits your project timeline and budget
          </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
          <!-- Solo Developer Plan -->
          <div class="group relative bg-white dark:bg-gray-900 rounded-3xl p-8 lg:p-10 border-2 border-gray-200 dark:border-gray-800 hover:border-blue-500 dark:hover:border-blue-500 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-500">
            <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-blue-500/5 via-purple-500/5 to-pink-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>

            <div class="relative">
              <div class="inline-block px-4 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-sm font-bold mb-6">
                Individual Expert
              </div>

              <h3 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-4">
                Solo Developer
              </h3>

              <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                Perfect for startups and small businesses. Get dedicated expertise with flexible timelines and cost-effective solutions.
              </p>

              <ul class="space-y-4 mb-8">
                <li v-for="feature in soloFeatures" :key="feature" class="flex items-start space-x-3">
                  <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mt-0.5">
                    <Icon name="lucide:check" class="w-4 h-4 text-green-600 dark:text-green-400" />
                  </div>
                  <span class="text-gray-700 dark:text-gray-300">{{ feature }}</span>
                </li>
              </ul>

              <!-- Dual Currency Pricing -->
              <div class="mb-8 space-y-2">
                <div class="flex items-baseline space-x-2">
                  <span class="text-5xl font-black bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                    {{ currency === 'USD' ? '$50-80' : '₹4,000-6,500' }}
                  </span>
                  <span class="text-xl text-gray-600 dark:text-gray-400 font-medium">/hour</span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-500">
                  {{ currency === 'USD' ? '(₹4,000-6,500/hour INR)' : '($50-80/hour USD)' }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-500">Standard timeline • Flexible delivery</p>
              </div>

              <NuxtLink
                  to="#contact"
                  class="block w-full text-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-lg font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-300"
              >
                Get Started
              </NuxtLink>
            </div>
          </div>

          <!-- Team Plan -->
          <div class="group relative bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-3xl p-[2px] shadow-2xl hover:shadow-blue-500/50 transform hover:-translate-y-2 transition-all duration-500">
            <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>

            <div class="relative bg-white dark:bg-gray-900 rounded-3xl p-8 lg:p-10 h-full">
              <div class="absolute -top-4 right-8">
                <span class="px-4 py-1.5 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-full text-sm font-bold shadow-lg">
                  ⚡ 2-3x Faster
                </span>
              </div>

              <div class="inline-block px-4 py-1.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full text-sm font-bold mb-6">
                Full Team
              </div>

              <h3 class="text-3xl md:text-4xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">
                Expert Team
              </h3>

              <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                Enterprise-grade solution with specialized experts. Accelerated delivery for complex projects with strict deadlines.
              </p>

              <ul class="space-y-4 mb-8">
                <li v-for="feature in teamFeatures" :key="feature" class="flex items-start space-x-3">
                  <div class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mt-0.5">
                    <Icon name="lucide:check" class="w-4 h-4 text-white" />
                  </div>
                  <span class="text-gray-700 dark:text-gray-300 font-medium">{{ feature }}</span>
                </li>
              </ul>

              <!-- Dual Currency Pricing -->
              <div class="mb-8 space-y-2">
                <div class="flex items-baseline space-x-2">
                  <span class="text-5xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    {{ currency === 'USD' ? '$100-150' : '₹8,000-12,000' }}
                  </span>
                  <span class="text-xl text-gray-600 dark:text-gray-400 font-medium">/hour</span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-500">
                  {{ currency === 'USD' ? '(₹8,000-12,000/hour INR)' : '($100-150/hour USD)' }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-500">Accelerated timeline • Priority support</p>
              </div>

              <NuxtLink
                  to="#contact"
                  class="block w-full text-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-lg font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-300"
              >
                Contact for Quote
              </NuxtLink>
            </div>
          </div>
        </div>

        <!-- Payment Methods -->
        <div class="mt-16 text-center">
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 font-semibold">Accepted Payment Methods</p>
          <div class="flex flex-wrap items-center justify-center gap-6">
            <div class="flex items-center space-x-2 px-4 py-2 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800">
              <Icon name="lucide:credit-card" class="w-5 h-5 text-blue-600" />
              <span class="text-sm font-medium">Stripe</span>
            </div>
            <div class="flex items-center space-x-2 px-4 py-2 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800">
              <Icon name="lucide:wallet" class="w-5 h-5 text-blue-600" />
              <span class="text-sm font-medium">PayPal</span>
            </div>
            <div class="flex items-center space-x-2 px-4 py-2 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800">
              <Icon name="lucide:indian-rupee" class="w-5 h-5 text-purple-600" />
              <span class="text-sm font-medium">Razorpay</span>
            </div>
            <div class="flex items-center space-x-2 px-4 py-2 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800">
              <Icon name="lucide:banknote" class="w-5 h-5 text-green-600" />
              <span class="text-sm font-medium">Cashfree</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 lg:py-32 relative overflow-hidden">
      <div class="absolute inset-0 bg-white dark:bg-gray-950 transition-colors duration-500"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-blue-50/30 to-transparent dark:via-blue-950/10"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16">
          <div class="inline-block mb-4">
            <span class="px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-sm font-semibold">
              Our Services
            </span>
          </div>
          <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black mb-6 text-gray-900 dark:text-white">
            Complete Digital
            <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 dark:from-blue-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
              Solutions
            </span>
          </h2>
          <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 leading-relaxed">
            From web to mobile and desktop—comprehensive solutions for every platform
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
          <div
              v-for="(service, index) in services"
              :key="service.title"
              class="group relative bg-white/80 dark:bg-gray-900/50 backdrop-blur-xl rounded-3xl p-8 border border-gray-200 dark:border-gray-800 hover:border-blue-500/50 dark:hover:border-blue-500/50 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer overflow-hidden"
              :style="{ animationDelay: `${index * 100}ms` }"
          >
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-purple-500/10 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

            <div class="relative w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg shadow-blue-500/50">
              <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-purple-500 rounded-2xl blur-lg opacity-50 group-hover:opacity-75 transition-opacity"></div>
              <Icon :name="service.icon" class="relative w-8 h-8 text-white" />
            </div>

            <h3 class="relative text-2xl font-bold mb-4 text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
              {{ service.title }}
            </h3>
            <p class="relative text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
              {{ service.description }}
            </p>

            <ul class="relative space-y-3">
              <li
                  v-for="feature in service.features"
                  :key="feature"
                  class="flex items-start space-x-3 text-sm text-gray-700 dark:text-gray-300"
              >
                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mt-0.5">
                  <Icon name="lucide:check" class="w-3 h-3 text-green-600 dark:text-green-400" />
                </div>
                <span>{{ feature }}</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Tech Stack Marquee -->
        <div class="mt-24 relative">
          <div class="absolute inset-0 bg-gradient-to-r from-white via-transparent to-white dark:from-gray-950 dark:via-transparent dark:to-gray-950 z-10 pointer-events-none"></div>
          <div class="overflow-hidden">
            <div class="flex whitespace-nowrap animate-marquee">
              <div v-for="n in 2" :key="n" class="flex items-center">
                <span
                    v-for="(tech, idx) in technologies"
                    :key="`${tech}-${n}-${idx}`"
                    class="px-8 text-3xl md:text-4xl lg:text-5xl font-black bg-gradient-to-r from-gray-200 to-gray-300 dark:from-gray-800 dark:to-gray-700 bg-clip-text text-transparent"
                >
                  {{ tech }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Projects Grid Section -->
    <FeatureProjects />

    <!-- Case Studies Section -->
    <CaseStudies />

    <!-- Marketplace Section -->
    <MarketplaceProjects />
    <!-- Contact/CTA Section -->
    <section id="contact" class="py-20 lg:py-32 relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600"></div>
      <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

      <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-float"></div>
      <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-float-delayed"></div>

      <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 leading-tight">
          Ready to Start Your Project?
        </h2>

        <p class="text-lg md:text-xl text-white/90 mb-12 max-w-2xl mx-auto leading-relaxed">
          Let's discuss your requirements and build something amazing together. Available for projects of any scale.
        </p>

        <!-- Contact Options -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-12 max-w-2xl mx-auto">
          <a
              href="mailto:hello@mintreu.com"
              class="group flex items-center justify-center space-x-3 px-8 py-4 bg-white hover:bg-gray-50 text-gray-900 text-lg font-bold rounded-xl shadow-2xl transform hover:scale-105 hover:-translate-y-1 active:scale-95 transition-all duration-300"
          >
            <Icon name="lucide:mail" class="w-5 h-5" />
            <span>Email Us</span>
          </a>

          <a
              href="#"
              @click.prevent="scheduleCall"
              class="group flex items-center justify-center space-x-3 px-8 py-4 bg-white/10 backdrop-blur-xl border-2 border-white/30 hover:bg-white/20 text-white text-lg font-bold rounded-xl transform hover:scale-105 hover:-translate-y-1 active:scale-95 transition-all duration-300"
          >
            <Icon name="lucide:calendar" class="w-5 h-5" />
            <span>Schedule Call</span>
          </a>
        </div>

        <!-- Social Links -->
        <div class="flex items-center justify-center space-x-4">
          <a
              v-for="social in socials"
              :key="social.name"
              :href="social.url"
              class="group w-14 h-14 rounded-xl bg-white/10 backdrop-blur-xl border border-white/20 hover:bg-white/20 hover:border-white/40 flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:-translate-y-1"
              :aria-label="social.name"
          >
            <Icon :name="social.icon" class="w-6 h-6 text-white group-hover:scale-110 transition-transform" />
          </a>
        </div>

        <!-- Response Time Badge -->
        <div class="mt-8 inline-flex items-center space-x-2 px-4 py-2 bg-white/10 backdrop-blur-xl rounded-full border border-white/20">
          <Icon name="lucide:zap" class="w-4 h-4 text-yellow-300" />
          <span class="text-sm text-white font-medium">Typically respond within 2 hours</span>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import FeatureProjects from "../../components/landing/FeatureProjects.vue";
import MarketplaceProjects from "../components/landing/MarketplaceProjects.vue";
import CaseStudies from "../components/landing/CaseStudies.vue";

useSeoMeta({
  title: 'Mintreu | Complete Digital Solutions - Web, Mobile & Desktop Development',
  description: 'Full-stack digital agency specializing in Laravel, Nuxt, React, Android, and cross-platform development. Global and India operations with flexible hiring options.',
  ogTitle: 'Mintreu | Complete Digital Solutions',
  ogDescription: 'Expert development services across web, mobile, and desktop platforms. Laravel, Nuxt, React, Kotlin, Python, AI/ML solutions.',
  ogImage: '/og-image.jpg',
  twitterCard: 'summary_large_image'
})

// Get currency from layout
const currency = inject('currency', ref('USD'))

// Data
const stats = [
  { value: '100+', label: 'Projects Delivered' },
  { value: '50+', label: 'Happy Clients' },
  { value: '5+', label: 'Years Experience' },
  { value: '20+', label: 'Technologies' }
]

// Comparison Table Data
const comparisonData = [
  { feature: 'Availability', mintreu: '24/7', agency: 'Business Hours', platform: 'Variable' },
  { feature: 'Response Time', mintreu: '< 2 hours', agency: '1-2 days', platform: 'Variable' },
  { feature: 'Communication', mintreu: 'Direct', agency: 'Via Manager', platform: 'Platform Only' },
  { feature: 'Platforms', mintreu: 'Web+Mobile+Desktop', agency: 'Usually Web Only', platform: 'Varies' },
  { feature: 'Flexibility', mintreu: 'Very High', agency: 'Limited', platform: 'Medium' },
  { feature: 'Cost', mintreu: 'Competitive', agency: 'High', platform: 'Variable' },
  { feature: 'Quality', mintreu: 'Excellent', agency: 'Good', platform: 'Varies' },
  { feature: 'Speed', mintreu: 'Fast', agency: 'Slow', platform: 'Varies' }
]

const getComparisonClass = (value: string) => {
  const positiveValues = ['24/7', '< 2 hours', 'Direct', 'Web+Mobile+Desktop', 'Very High', 'Competitive', 'Excellent', 'Fast']
  return positiveValues.includes(value)
      ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
      : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'
}

// Hiring Plans Features
const soloFeatures = [
  'Direct communication with expert developer',
  'Personalized attention to project needs',
  'Flexible working hours and timeline',
  'Cost-effective development solutions',
  'Full code ownership and documentation',
  'Ongoing support and maintenance'
]

const teamFeatures = [
  'Team of specialized experts (Laravel, Nuxt, Kotlin)',
  'Parallel development for faster delivery',
  'Enterprise-grade code quality',
  'Dedicated project manager',
  'Priority 24/7 support',
  'Cross-platform expertise (Web+Mobile+Desktop)'
]

// Services Data
const services = [
  {
    title: 'Web Development',
    icon: 'lucide:globe',
    description: 'Full-stack web applications with Laravel, Nuxt.js, Next.js, and FilamentPHP. Responsive, scalable, and SEO-optimized.',
    features: [
      'Laravel & FilamentPHP backends',
      'Nuxt.js & Next.js frontends',
      'Livewire reactive interfaces',
      'RESTful & GraphQL APIs'
    ]
  },
  {
    title: 'Mobile Development',
    icon: 'lucide:smartphone',
    description: 'Native Android apps with Kotlin and cross-platform solutions. Play Store deployment and ongoing maintenance.',
    features: [
      'Native Android with Kotlin',
      'Cross-platform development',
      'Material Design UI/UX',
      'Play Store optimization'
    ]
  },
  {
    title: 'Desktop Applications',
    icon: 'lucide:monitor',
    description: 'Cross-platform desktop apps for Windows, macOS, and Linux. Electron and native solutions available.',
    features: [
      'Electron applications',
      'Native desktop solutions',
      'Cross-platform compatibility',
      'Auto-update systems'
    ]
  },
  {
    title: 'Database Solutions',
    icon: 'lucide:database',
    description: 'Expert database design and optimization. Support for MySQL, PostgreSQL, MongoDB, Redis, and more.',
    features: [
      'MySQL & PostgreSQL',
      'MongoDB & Redis',
      'Database optimization',
      'Migration services'
    ]
  },
  {
    title: 'AI & ML Integration',
    icon: 'lucide:brain',
    description: 'Artificial Intelligence and Machine Learning solutions with Python. Predictive analytics and automation.',
    features: [
      'Python ML models',
      'TensorFlow & PyTorch',
      'Natural language processing',
      'Computer vision'
    ]
  },
  {
    title: 'Payment Integration',
    icon: 'lucide:credit-card',
    description: 'Complete payment gateway integration for global and Indian markets with secure transactions.',
    features: [
      'Stripe & PayPal (Global)',
      'Razorpay & Cashfree (India)',
      'Subscription billing',
      'Secure PCI compliance'
    ]
  }
]

// Technologies
const technologies = [
  'Laravel', 'Livewire', 'FilamentPHP', 'Nuxt', 'Next.js', 'Vue', 'React',
  'TypeScript', 'Node.js', 'Python', 'Kotlin', 'Android', 'C#', 'C++',
  'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'TensorFlow', 'Docker'
]




// Socials
const socials = [
  { name: 'GitHub', url: 'https://github.com', icon: 'lucide:github' },
  { name: 'Twitter', url: 'https://twitter.com', icon: 'lucide:twitter' },
  { name: 'LinkedIn', url: 'https://linkedin.com', icon: 'lucide:linkedin' },
  { name: 'Dev.to', url: 'https://dev.to', icon: 'lucide:pen-tool' }
]

// Methods
const scheduleCall = () => {
  // Implement calendar scheduling integration (Calendly, Cal.com, etc.)
  window.open('https://calendly.com/mintreu', '_blank')
}
</script>

<style scoped>
/* Animations */
@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(3deg); }
}

@keyframes float-delayed {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-30px) rotate(-3deg); }
}

@keyframes pulse-slow {
  0%, 100% { opacity: 0.3; transform: scale(1); }
  50% { opacity: 0.5; transform: scale(1.05); }
}

@keyframes gradient {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

@keyframes marquee {
  0% { transform: translateX(0%); }
  100% { transform: translateX(-50%); }
}

@keyframes fade-in-down {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes bounce-slow {
  0%, 100% { transform: translateY(-25%) translateX(-50%); }
  50% { transform: translateY(0) translateX(-50%); }
}

.animate-float { animation: float 8s ease-in-out infinite; }
.animate-float-delayed { animation: float-delayed 10s ease-in-out infinite; }
.animate-pulse-slow { animation: pulse-slow 4s ease-in-out infinite; }
.animate-gradient { background-size: 200% 200%; animation: gradient 3s ease infinite; }
.animate-marquee { animation: marquee 40s linear infinite; }
.animate-fade-in-down { animation: fade-in-down 0.8s ease-out; }
.animate-fade-in-up { animation: fade-in-up 0.8s ease-out; }
.animate-fade-in { animation: fade-in-up 0.8s ease-out; }
.animate-bounce-slow { animation: bounce-slow 3s ease-in-out infinite; }

.animation-delay-200 { animation-delay: 0.2s; animation-fill-mode: both; }
.animation-delay-400 { animation-delay: 0.4s; animation-fill-mode: both; }
.animation-delay-600 { animation-delay: 0.6s; animation-fill-mode: both; }

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.bg-grid-pattern {
  background-image:
      linear-gradient(to right, rgba(0, 0, 0, 0.1) 1px, transparent 1px),
      linear-gradient(to bottom, rgba(0, 0, 0, 0.1) 1px, transparent 1px);
  background-size: 40px 40px;
}

html { scroll-behavior: smooth; }

::-webkit-scrollbar { width: 12px; }
::-webkit-scrollbar-track { @apply bg-gray-100 dark:bg-gray-900; }
::-webkit-scrollbar-thumb { @apply bg-gray-300 dark:bg-gray-700 rounded-full; }
::-webkit-scrollbar-thumb:hover { @apply bg-gray-400 dark:bg-gray-600; }
</style>
