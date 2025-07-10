<x-layout.app-layout>

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col bg-black">

        <!-- Top Navbar -->
        <header class="hover:text-white bg-black text-gray-400 fixed top-0 left-0 right-0 z-30 h-16 flex items-center">
            <div class="max-w-7xl mx-auto px-4 flex justify-between items-center w-full">
                <div class="text-xl font-bold text-indigo-600">YourBrand</div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8 text-gray-700 font-medium">
                    <a href="{{url('/')}}" class="hover:text-indigo-600 transition">Home</a>
                    <a href="#about" class="hover:text-indigo-600 transition">Projects</a>
                    {{--                <a href="#services" class="hover:text-indigo-600 transition">Services</a>--}}
                    <a href="{{url('/products')}}" class="hover:text-indigo-600 transition">Products</a>
                    <a href="#blog" class="hover:text-indigo-600 transition">Blog</a>
                    <a href="#contact" class="hover:text-indigo-600 transition">Labs</a>
                    <a href="#contact" class="hover:text-indigo-600 transition">Contact</a>
                </nav>

                <!-- Mobile menu button -->
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden focus:outline-none" aria-label="Toggle sidebar menu">
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </header>


        <div class="flex pt-16 min-h-screen relative z-0 text-gray-800 dark:text-white">

            <!-- Overlay for mobile -->
            <div
                x-show="sidebarOpen"
                class="fixed inset-0 bg-black bg-opacity-40 z-20 md:hidden"
                @click="sidebarOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>

            <!-- Sidebar -->
            <aside
                class="md:hidden fixed top-16 md:sticky md:top-0 z-30 w-64 h-[calc(100vh-4rem)] bg-white shadow-lg md:shadow-none overflow-y-auto transform transition-transform duration-300 ease-in-out md:translate-x-0"
                :class="{ '-translate-x-full': !sidebarOpen }"
                x-show="sidebarOpen || window.innerWidth >= 768"
                @click.away="sidebarOpen = false"
            >
                <nav class="p-6 space-y-4">
                    <a href="#services" class="block text-gray-800 font-medium hover:text-indigo-600">Services</a>
                    <a href="{{url('/products')}}" class="block text-gray-800 font-medium hover:text-indigo-600">Products</a>
                    <a href="#latest-articles" class="block text-gray-800 font-medium hover:text-indigo-600">Articles</a>
                    <a href="#about-us" class="block text-gray-800 font-medium hover:text-indigo-600">About Us</a>
                </nav>
            </aside>


            {{$slot}}

        </div>





        <!-- Footer -->
        <footer class="bg-gray-900 w-full text-gray-300 py-16">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12">

                <!-- Newsletter CTA -->
                <div>
                    <h3 class="text-white text-xl font-semibold mb-4">Subscribe to our Newsletter</h3>
                    <p class="mb-6 text-gray-400">Get the latest updates, articles, and offers right in your inbox.</p>
                    <form class="flex max-w-md">
                        <input
                            type="email"
                            placeholder="Your email address"
                            class="flex-grow px-4 py-2 rounded-l-md focus:outline-none text-gray-900"
                            aria-label="Email address"
                        />
                        <button
                            type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 px-3 rounded-r-md font-semibold transition"
                        >
                            Subscribe
                        </button>
                    </form>
                </div>

                <!-- Quick Links -->
                <div class="ml-3">
                    <h3 class="text-white text-xl font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="#home" class="hover:text-indigo-500 transition">Home</a></li>
                        <li><a href="#about" class="hover:text-indigo-500 transition">About Us</a></li>
                        <li><a href="#services" class="hover:text-indigo-500 transition">Services</a></li>
                        <li><a href="#services" class="hover:text-indigo-500 transition">Projects</a></li>
                        <li><a href="{{url('/products')}}" class="hover:text-indigo-500 transition">Products</a></li>
                        <li><a href="#blog" class="hover:text-indigo-500 transition">Blog</a></li>
                        <li><a href="#blog" class="hover:text-indigo-500 transition">Career</a></li>
                        <li><a href="#contact" class="hover:text-indigo-500 transition">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-white text-xl font-semibold mb-4">Contact Us</h3>
                    <p>1234 Your Street, City, Country</p>
                    <p class="mt-2">Phone: <a href="tel:+1234567890" class="hover:text-indigo-500 transition">+1 234 567 890</a></p>
                    <p class="mt-2">Email: <a href="mailto:info@yourbrand.com" class="hover:text-indigo-500 transition">info@yourbrand.com</a></p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" aria-label="Facebook" class="hover:text-indigo-500 transition">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path d="M22 12a10 10 0 1 0-11.5 9.8v-6.9H8v-3h2.5V9.7c0-2.5 1.5-3.9 3.7-3.9 1.1 0 2.3.2 2.3.2v2.5H15c-1.2 0-1.6.8-1.6 1.6v2h2.7l-.4 3h-2.3v6.9A10 10 0 0 0 22 12" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Twitter" class="hover:text-indigo-500 transition">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path d="M23 3a10.9 10.9 0 0 1-3.14.86 5.48 5.48 0 0 0 2.4-3.04 11 11 0 0 1-3.48 1.33 5.46 5.46 0 0 0-9.3 4.98A15.49 15.49 0 0 1 1.64 2.16 5.46 5.46 0 0 0 3.1 9.72a5.43 5.43 0 0 1-2.48-.68v.07a5.46 5.46 0 0 0 4.37 5.35 5.5 5.5 0 0 1-2.47.09 5.46 5.46 0 0 0 5.1 3.8 11 11 0 0 1-6.8 2.35A10.77 10.77 0 0 1 1 20.39 15.44 15.44 0 0 0 8.38 22c10.07 0 15.58-8.34 15.58-15.58 0-.24 0-.48-.02-.71A11.14 11.14 0 0 0 23 3z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="LinkedIn" class="hover:text-indigo-500 transition">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path d="M19 3A2 2 0 0 1 21 5v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14zm-9 14v-6H7v6h3zm-1.5-7.12a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5zm9.5 7.12v-3a3 3 0 0 0-3-3 3 3 0 0 0-3 3v3h3v-3a1 1 0 1 1 2 0v3h3z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Call to Action -->
                <div>
                    <h3 class="text-white text-xl font-semibold mb-4">Get Started</h3>
                    <p class="mb-6">Ready to work with us? Get in touch or explore our products and services.</p>
                    <a
                        href="#contact"
                        class="block bg-indigo-600 hover:bg-indigo-700 text-white text-center py-3 rounded mb-4 font-semibold transition"
                    >
                        Contact Us
                    </a>
                    <a
                        href="#services"
                        class="block border border-indigo-600 hover:border-indigo-700 text-indigo-600 hover:text-indigo-700 text-center py-3 rounded font-semibold transition"
                    >
                        Our Services
                    </a>
                </div>

            </div>

            <div class="mt-12 border-t border-gray-700 pt-8 text-center text-gray-500 text-sm">
                &copy; 2025 YourBrand. All rights reserved.
            </div>
        </footer>



    </div>



</x-layout.app-layout>
