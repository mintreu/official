<template>
  <div class="min-h-screen bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-50 transition-colors duration-300">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-gray-950/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
          <!-- Logo -->
          <NuxtLink to="/" class="flex items-center space-x-3 group">
            <div class="relative w-10 h-10 lg:w-12 lg:h-12">
              <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-xl rotate-6 group-hover:rotate-12 transition-transform duration-300"></div>
              <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-xl flex items-center justify-center">
                <span class="text-white font-black text-xl">M</span>
              </div>
            </div>
            <div class="hidden sm:block">
              <span class="text-xl lg:text-2xl font-black bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                Mintreu
              </span>
              <span class="block text-xs text-gray-500 dark:text-gray-400 font-medium -mt-1">
                Digital Solutions
              </span>
            </div>
          </NuxtLink>

          <!-- Desktop Menu -->
          <div class="hidden lg:flex items-center space-x-1">
            <NuxtLink
                v-for="item in menuItems"
                :key="item.path"
                :to="item.path"
                class="px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition-all duration-200"
                :class="{ 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400': isActiveRoute(item.path) }"
            >
              {{ item.name }}
            </NuxtLink>
          </div>

          <!-- Right Actions -->
          <div class="flex items-center space-x-2 sm:space-x-3">
            <!-- Currency Switcher -->
            <button
                @click="toggleCurrency"
                class="hidden md:flex items-center space-x-1 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-sm font-medium"
                :title="`Switch to ${currency === 'USD' ? 'INR' : 'USD'}`"
            >
              <Icon name="lucide:dollar-sign" class="w-4 h-4" v-if="currency === 'USD'" />
              <Icon name="lucide:indian-rupee" class="w-4 h-4" v-else />
              <span>{{ currency }}</span>
            </button>

            <!-- Theme Toggle -->
            <button
                @click="toggleTheme"
                class="p-2 sm:p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200 group"
                aria-label="Toggle theme"
            >
              <Icon
                  v-if="colorMode.value === 'dark'"
                  name="lucide:sun"
                  class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-yellow-500 transition-colors"
              />
              <Icon
                  v-else
                  name="lucide:moon"
                  class="w-5 h-5 text-gray-600 group-hover:text-blue-600 transition-colors"
              />
            </button>

            <!-- CTA Button -->
            <NuxtLink
                to="/contact"
                class="hidden sm:inline-flex items-center space-x-2 px-4 lg:px-6 py-2 lg:py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-200"
            >
              <span>Contact Us</span>
              <Icon name="lucide:arrow-right" class="w-4 h-4" />
            </NuxtLink>

            <!-- Mobile Menu Button -->
            <button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                aria-label="Toggle menu"
            >
              <Icon
                  :name="mobileMenuOpen ? 'lucide:x' : 'lucide:menu'"
                  class="w-6 h-6"
              />
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu -->
      <Transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0 -translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition duration-150 ease-in"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-1"
      >
        <div
            v-if="mobileMenuOpen"
            class="lg:hidden border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950"
        >
          <div class="px-4 py-4 space-y-1">
            <NuxtLink
                v-for="item in menuItems"
                :key="item.name"
                :to="item.path"
                @click="mobileMenuOpen = false"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition-colors"
            >
              <Icon :name="item.icon" class="w-5 h-5" />
              <span>{{ item.name }}</span>
            </NuxtLink>

            <!-- Currency Toggle Mobile -->
            <button
                @click="toggleCurrency"
                class="flex items-center space-x-3 w-full px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition-colors"
            >
              <Icon :name="currency === 'USD' ? 'lucide:dollar-sign' : 'lucide:indian-rupee'" class="w-5 h-5" />
              <span>Currency: {{ currency }}</span>
            </button>

            <NuxtLink
                to="#hire"
                @click="mobileMenuOpen = false"
                class="flex items-center justify-center space-x-2 w-full mt-4 px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg shadow-lg"
            >
              <span>Hire Us</span>
              <Icon name="lucide:arrow-right" class="w-4 h-4" />
            </NuxtLink>
          </div>
        </div>
      </Transition>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 lg:pt-20">
      <slot />
    </main>

    <!-- Footer (matched style with navbar, supports dark mode) -->
    <footer class="bg-white/80 dark:bg-gray-950/80 backdrop-blur-xl border-t border-gray-200 dark:border-gray-800 text-gray-900 dark:text-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 mb-12">
          <!-- Brand -->
          <div class="lg:col-span-2">
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                <span class="text-white font-black text-xl">M</span>
              </div>
              <div>
                <span class="text-2xl font-black">Mintreu</span>
                <span class="block text-xs text-gray-500 dark:text-gray-400 -mt-1">Digital Solutions</span>
              </div>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md">
              Transforming ideas into exceptional digital experiences. Full-stack solutions for web, mobile, and desktop applications.
            </p>
            <div class="flex items-center space-x-3">
              <a
                  v-for="social in socials"
                  :key="social.name"
                  :href="social.url"
                  class="w-10 h-10 rounded-lg bg-gray-100/70 dark:bg-gray-900/70 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all duration-200 transform hover:scale-110 group"
                  :aria-label="social.name"
              >
                <Icon :name="social.icon" class="w-5 h-5 text-gray-500 dark:text-gray-300 group-hover:text-white transition-colors" />
              </a>
            </div>
          </div>

          <!-- Quick Links -->
          <div>
            <h3 class="font-bold text-lg mb-4">Quick Links</h3>
            <ul class="space-y-3">
              <li v-for="link in quickLinks" :key="link.name">
                <NuxtLink
                    :to="link.path"
                    class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center space-x-2"
                >
                  <Icon name="lucide:chevron-right" class="w-4 h-4" />
                  <span>{{ link.name }}</span>
                </NuxtLink>
              </li>
            </ul>
          </div>

          <!-- Contact -->
          <div>
            <h3 class="font-bold text-lg mb-4">Get in Touch</h3>
            <ul class="space-y-3 text-gray-600 dark:text-gray-400">
              <li class="flex items-start space-x-2">
                <Icon name="lucide:mail" class="w-5 h-5 mt-0.5 flex-shrink-0 text-blue-500" />
                <span class="break-all">hello@mintreu.com</span>
              </li>
              <li class="flex items-start space-x-2">
                <Icon name="lucide:phone" class="w-5 h-5 mt-0.5 flex-shrink-0 text-blue-500" />
                <div>
                  <div>+1 (555) 123-4567</div>
                  <div class="text-sm">+91 98765 43210</div>
                </div>
              </li>
              <li class="flex items-start space-x-2">
                <Icon name="lucide:map-pin" class="w-5 h-5 mt-0.5 flex-shrink-0 text-blue-500" />
                <span>Global & India Operations</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 border-t border-gray-200 dark:border-gray-800 flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
          <p class="text-gray-500 dark:text-gray-400 text-sm text-center md:text-left">
            © {{ currentYear }} Mintreu. All rights reserved.
          </p>
          <div class="flex items-center space-x-6 text-sm">
            <NuxtLink to="/privacy" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
              Privacy Policy
            </NuxtLink>
            <NuxtLink to="/terms" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
              Terms of Service
            </NuxtLink>
          </div>
        </div>
      </div>
    </footer>

    <!-- Scroll to Top Button -->
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 scale-90"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-90"
    >
      <button
          v-if="showScrollTop"
          @click="scrollToTop"
          class="fixed bottom-8 right-8 z-40 w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-full shadow-2xl flex items-center justify-center transform hover:scale-110 active:scale-95 transition-all duration-200"
          aria-label="Scroll to top"
      >
        <Icon name="lucide:arrow-up" class="w-6 h-6" />
      </button>
    </Transition>
  </div>
</template>

<script setup lang="ts">
const colorMode = useColorMode()
const mobileMenuOpen = ref(false)
const showScrollTop = ref(false)
const currentYear = new Date().getFullYear()
const route = useRoute()

// Currency management
const currency = useState<'USD' | 'INR'>('currency', () => 'USD')

// Check if a route path is active (handles nested routes)
const isActiveRoute = (path: string) => {
  if (path === '/') {
    return route.path === '/'
  }
  return route.path.startsWith(path)
}

// Helper function to navigate to section on home page
const navigateToSection = (section: string) => {
  if (route.path === '/') {
    const element = document.getElementById(section)
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' })
    }
  } else {
    navigateTo(`/#${section}`)
  }
}

const menuItems = [
  { name: 'Work', path: '/work', icon: 'lucide:folder' },
  { name: 'Case Studies', path: '/case-studies', icon: 'lucide:book-open' },
  { name: 'Products', path: '/products', icon: 'lucide:shopping-cart' },
  { name: 'Insights', path: '/insights', icon: 'lucide:lightbulb' },
  { name: 'Contact', path: '/contact', icon: 'lucide:mail' }
]

const quickLinks = [
  { name: 'About Us', path: '/about' },
  { name: 'Our Work', path: '/work' },
  { name: 'Services', path: '/services' },
  { name: 'Contact', path: '/contact' }
]

const socials = [
  { name: 'GitHub', url: 'https://github.com', icon: 'lucide:github' },
  { name: 'Twitter', url: 'https://twitter.com', icon: 'lucide:twitter' },
  { name: 'LinkedIn', url: 'https://linkedin.com', icon: 'lucide:linkedin' },
  { name: 'Dribbble', url: 'https://dribbble.com', icon: 'lucide:dribbble' }
]

const toggleTheme = () => {
  colorMode.preference = colorMode.value === 'dark' ? 'light' : 'dark'
}

const toggleCurrency = () => {
  currency.value = currency.value === 'USD' ? 'INR' : 'USD'
}

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Handle scroll events
onMounted(() => {
  const handleScroll = () => {
    showScrollTop.value = window.scrollY > 400
  }

  window.addEventListener('scroll', handleScroll)

  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
  })
})

// Close mobile menu on route change
watch(() => useRoute().fullPath, () => {
  mobileMenuOpen.value = false
})

// Provide currency to child components
provide('currency', currency)
</script>
