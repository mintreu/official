<template>
  <div class="min-h-screen bg-titanium-50 dark:bg-titanium-950 text-titanium-900 dark:text-titanium-100 transition-colors duration-300 scrollbar-industrial">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-titanium-50/90 dark:bg-titanium-950/90 backdrop-blur-xl border-b border-titanium-200 dark:border-titanium-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
          <!-- Logo -->
          <NuxtLink to="/" class="flex items-center space-x-3 group">
            <div class="relative w-10 h-10 lg:w-12 lg:h-12">
              <div class="absolute inset-0 bg-mintreu-red-600 rounded-xl rotate-6 group-hover:rotate-12 transition-transform duration-300 opacity-50"></div>
              <div class="absolute inset-0 bg-mintreu-red-600 rounded-xl flex items-center justify-center shadow-lg">
                <span class="text-white font-heading font-black text-lg lg:text-xl">M</span>
              </div>
            </div>
            <div class="hidden sm:block">
              <span class="text-xl lg:text-2xl font-heading font-black text-titanium-900 dark:text-white">
                Mintreu
              </span>
              <span class="block text-xs text-titanium-500 font-subheading font-semibold tracking-wider uppercase -mt-1">
                Digital Engineering
              </span>
            </div>
          </NuxtLink>

          <!-- Desktop Menu -->
          <div class="hidden lg:flex items-center space-x-1">
            <NuxtLink
              v-for="item in menuItems"
              :key="item.path"
              :to="item.path"
              class="px-4 py-2 rounded-lg text-titanium-600 dark:text-titanium-300 hover:text-mintreu-red-600 dark:hover:text-mintreu-red-500 hover:bg-titanium-100 dark:hover:bg-titanium-900 font-subheading font-semibold text-lg tracking-wide transition-all duration-200"
              :class="{ 'bg-mintreu-red-600/10 text-mintreu-red-600 dark:text-mintreu-red-500': isActiveRoute(item.path) }"
            >
              {{ item.name }}
            </NuxtLink>
          </div>

          <!-- Right Actions -->
          <div class="flex items-center space-x-2 sm:space-x-3">
            <!-- Currency Switcher -->
            <button
              @click="toggleCurrency"
              class="hidden md:flex items-center space-x-1 px-3 py-2 rounded-lg hover:bg-titanium-100 dark:hover:bg-titanium-900 transition-colors text-sm font-subheading font-semibold text-titanium-600 dark:text-titanium-400"
              :title="`Switch to ${currency === 'USD' ? 'INR' : 'USD'}`"
            >
              <Icon name="lucide:dollar-sign" class="w-4 h-4" v-if="currency === 'USD'" />
              <Icon name="lucide:indian-rupee" class="w-4 h-4" v-else />
              <span>{{ currency }}</span>
            </button>

            <!-- Theme Toggle -->
            <button
              @click="toggleTheme"
              class="p-2 sm:p-2.5 rounded-lg hover:bg-titanium-100 dark:hover:bg-titanium-900 transition-colors duration-200 group"
              aria-label="Toggle theme"
            >
              <Icon
                v-if="colorMode.value === 'dark'"
                name="lucide:sun"
                class="w-5 h-5 text-titanium-500 group-hover:text-amber-500 transition-colors"
              />
              <Icon
                v-else
                name="lucide:moon"
                class="w-5 h-5 text-titanium-600 group-hover:text-mintreu-red-600 transition-colors"
              />
            </button>

            <!-- CTA Button -->
            <NuxtLink
              to="/contact"
              class="hidden sm:inline-flex items-center space-x-2 px-4 lg:px-6 py-2 lg:py-2.5 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white font-heading font-bold text-sm rounded-lg shadow-lg glow-red transform hover:scale-105 active:scale-95 transition-all duration-200"
            >
              <span>Contact Us</span>
              <Icon name="lucide:arrow-right" class="w-4 h-4" />
            </NuxtLink>
            <NuxtLink
              to="/auth/signin"
              class="hidden sm:inline-flex items-center space-x-2 px-4 lg:px-6 py-2 lg:py-2.5 bg-white text-mintreu-red-600 hover:bg-mintreu-red-50 border border-mintreu-red-200 rounded-lg font-heading font-semibold shadow-sm transition-all duration-200"
            >
              <span>Sign In</span>
              <Icon name="lucide:user" class="w-4 h-4" />
            </NuxtLink>

            <!-- Mobile Menu Button -->
            <button
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="lg:hidden p-2 rounded-lg hover:bg-titanium-100 dark:hover:bg-titanium-900 transition-colors"
              aria-label="Toggle menu"
            >
              <Icon
                :name="mobileMenuOpen ? 'lucide:x' : 'lucide:menu'"
                class="w-6 h-6 text-titanium-700 dark:text-titanium-300"
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
          class="lg:hidden border-t border-titanium-200 dark:border-titanium-800 bg-titanium-50 dark:bg-titanium-950"
        >
          <div class="px-4 py-4 space-y-1">
            <NuxtLink
              v-for="item in menuItems"
              :key="item.name"
              :to="item.path"
              @click="mobileMenuOpen = false"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-900 font-subheading font-semibold tracking-wide transition-colors"
            >
              <Icon :name="item.icon" class="w-5 h-5" />
              <span>{{ item.name }}</span>
            </NuxtLink>

            <!-- Currency Toggle Mobile -->
            <button
              @click="toggleCurrency"
              class="flex items-center space-x-3 w-full px-4 py-3 rounded-lg text-titanium-700 dark:text-titanium-300 hover:bg-titanium-100 dark:hover:bg-titanium-900 font-subheading font-semibold transition-colors"
            >
              <Icon :name="currency === 'USD' ? 'lucide:dollar-sign' : 'lucide:indian-rupee'" class="w-5 h-5" />
              <span>Currency: {{ currency }}</span>
            </button>

            <NuxtLink
              to="/contact"
              @click="mobileMenuOpen = false"
              class="flex items-center justify-center space-x-2 w-full mt-4 px-4 py-3 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white font-heading font-bold rounded-lg shadow-lg glow-red"
            >
              <span>Hire Us</span>
              <Icon name="lucide:arrow-right" class="w-4 h-4" />
            </NuxtLink>
            <NuxtLink
              to="/auth/signin"
              @click="mobileMenuOpen = false"
              class="flex items-center justify-center space-x-2 w-full mt-3 px-4 py-3 bg-white text-mintreu-red-600 rounded-lg border border-mintreu-red-200 font-heading font-semibold transition-colors duration-200"
            >
              <span>Sign In</span>
              <Icon name="lucide:user" class="w-4 h-4" />
            </NuxtLink>
          </div>
        </div>
      </Transition>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 lg:pt-20">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="relative bg-titanium-100 dark:bg-titanium-950 border-t border-titanium-200 dark:border-titanium-800 text-titanium-900 dark:text-titanium-100 overflow-hidden">
      <!-- Blueprint grid overlay -->
      <div class="absolute inset-0 bg-blueprint opacity-30 pointer-events-none"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 mb-12">
          <!-- Brand -->
          <div class="lg:col-span-2">
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-10 h-10 bg-mintreu-red-600 rounded-xl flex items-center justify-center shadow-lg">
                <span class="text-white font-heading font-black text-xl">M</span>
              </div>
              <div>
                <span class="text-2xl font-heading font-black text-titanium-900 dark:text-white">Mintreu</span>
                <span class="block text-xs text-titanium-500 font-subheading font-semibold tracking-wider uppercase -mt-1">Digital Engineering</span>
              </div>
            </div>
            <p class="text-titanium-600 dark:text-titanium-400 mb-6 max-w-md font-subheading text-lg">
              Precision digital engineering for web, mobile, and desktop applications. Blueprint-grade solutions from concept to deployment.
            </p>
            <div class="flex items-center space-x-3">
              <a
                v-for="social in socials"
                :key="social.name"
                :href="social.url"
                class="w-10 h-10 rounded-lg bg-titanium-200/70 dark:bg-titanium-900/70 hover:bg-mintreu-red-600 flex items-center justify-center transition-all duration-200 transform hover:scale-110 group"
                :aria-label="social.name"
              >
                <Icon :name="social.icon" class="w-5 h-5 text-titanium-500 dark:text-titanium-400 group-hover:text-white transition-colors" />
              </a>
            </div>
          </div>

          <!-- Quick Links -->
          <div>
            <h3 class="font-heading font-bold text-sm uppercase tracking-wider text-titanium-900 dark:text-white mb-4">Quick Links</h3>
            <ul class="space-y-3">
              <li v-for="link in quickLinks" :key="link.name">
                <NuxtLink
                  :to="link.path"
                  class="text-titanium-600 dark:text-titanium-400 hover:text-mintreu-red-600 dark:hover:text-mintreu-red-500 transition-colors flex items-center space-x-2 font-subheading font-medium"
                >
                  <Icon name="lucide:chevron-right" class="w-4 h-4" />
                  <span>{{ link.name }}</span>
                </NuxtLink>
              </li>
            </ul>
          </div>

          <!-- Contact -->
          <div>
            <h3 class="font-heading font-bold text-sm uppercase tracking-wider text-titanium-900 dark:text-white mb-4">Get in Touch</h3>
            <ul class="space-y-3 text-titanium-600 dark:text-titanium-400 font-subheading">
              <li class="flex items-start space-x-2">
                <Icon name="lucide:mail" class="w-5 h-5 mt-0.5 flex-shrink-0 text-mintreu-red-600" />
                <span class="break-all">hello@mintreu.com</span>
              </li>
              <li class="flex items-start space-x-2">
                <Icon name="lucide:phone" class="w-5 h-5 mt-0.5 flex-shrink-0 text-mintreu-red-600" />
                <div>
                  <div>+1 (555) 123-4567</div>
                  <div class="text-sm">+91 98765 43210</div>
                </div>
              </li>
              <li class="flex items-start space-x-2">
                <Icon name="lucide:map-pin" class="w-5 h-5 mt-0.5 flex-shrink-0 text-mintreu-red-600" />
                <span>Global & India Operations</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 border-t border-titanium-200 dark:border-titanium-800 flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
          <p class="text-titanium-500 text-sm text-center md:text-left font-subheading">
            &copy; {{ currentYear }} Mintreu. All rights reserved.
          </p>
          <div class="flex items-center space-x-6 text-sm font-subheading">
            <NuxtLink to="/privacy" class="text-titanium-500 hover:text-mintreu-red-600 dark:hover:text-mintreu-red-500 transition-colors">
              Privacy Policy
            </NuxtLink>
            <NuxtLink to="/terms" class="text-titanium-500 hover:text-mintreu-red-600 dark:hover:text-mintreu-red-500 transition-colors">
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
        class="fixed bottom-8 right-8 z-40 w-12 h-12 bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white rounded-full shadow-2xl glow-red flex items-center justify-center transform hover:scale-110 active:scale-95 transition-all duration-200"
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

const currency = useState<'USD' | 'INR'>('currency', () => 'USD')

const isActiveRoute = (path: string) => {
  if (path === '/') return route.path === '/'
  return route.path.startsWith(path)
}

const menuItems = [
  { name: 'Work', path: '/work', icon: 'lucide:folder' },
  { name: 'Case Studies', path: '/case-studies', icon: 'lucide:book-open' },
  { name: 'Products', path: '/products', icon: 'lucide:shopping-cart' },
  { name: 'Insights', path: '/insights', icon: 'lucide:lightbulb' },
  { name: 'Contact', path: '/contact', icon: 'lucide:mail' },
]

const quickLinks = [
  { name: 'About Us', path: '/about' },
  { name: 'Our Work', path: '/work' },
  { name: 'Services', path: '/services' },
  { name: 'Contact', path: '/contact' },
]

const socials = [
  { name: 'GitHub', url: 'https://github.com', icon: 'lucide:github' },
  { name: 'Twitter', url: 'https://twitter.com', icon: 'lucide:twitter' },
  { name: 'LinkedIn', url: 'https://linkedin.com', icon: 'lucide:linkedin' },
  { name: 'Dribbble', url: 'https://dribbble.com', icon: 'lucide:dribbble' },
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

onMounted(() => {
  const handleScroll = () => { showScrollTop.value = window.scrollY > 400 }
  window.addEventListener('scroll', handleScroll)
  onUnmounted(() => { window.removeEventListener('scroll', handleScroll) })
})

watch(() => useRoute().fullPath, () => { mobileMenuOpen.value = false })

provide('currency', currency)
</script>
