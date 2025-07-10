

        <!-- Main Content -->
        <main class="flex-1 w-full " x-data="{ tab: 'company' }">
            <!-- Your long content here -->


            <livewire:animation.paralax-background />

            <!-- Tabs -->
            <div class="max-w-7xl mx-auto w-full px-6 my-5">
                <h1 class="text-center mb-5 text-5xl">Know About</h1>
                <div class="flex justify-center space-x-8 border-b border-gray-300">
                    <button
                        id="tab-company"
                        @click="tab = 'company'"
                        :class="tab === 'company'
                    ? 'text-indigo-600 border-b-4 border-indigo-600 font-extrabold'
                    : 'text-gray-500 hover:text-indigo-600 border-b-4 border-transparent'"
                        class="pb-4 text-4xl transition-colors duration-300 focus:outline-none"
                        style="min-width: 220px;"
                    >
                        Company
                    </button>
                    <button
                        id="tab-me"
                        @click="tab = 'me'"
                        :class="tab === 'me'
                    ? 'text-indigo-600 border-b-4 border-indigo-600 font-extrabold'
                    : 'text-gray-500 hover:text-indigo-600 border-b-4 border-transparent'"
                        class="pb-4 text-4xl transition-colors duration-300 focus:outline-none"
                        style="min-width: 220px;"
                    >
                        Me
                    </button>
                </div>

                <!-- Tab Contents -->
                <div class="mt-12 max-w-7xl mx-auto">

                    <div
                        x-show="tab === 'company'"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4"
                        x-cloak
                    >
                        <!-- Your existing "Know About Company" content here -->

                        <!-- About Us Section -->
                        <section id="about-us" class=" py-20 overflow-hidden">
                            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

                                    <!-- Text Content -->
                                    <div class="about-text animate-slide-zoom-in">
                                        <h2 class="text-4xl font-extrabold text-gray-900 mb-6">Who We Are</h2>
                                        <p class="text-lg text-gray-700 leading-relaxed mb-6">
                                            We’re a lean, passionate development studio led by a solo full-stack developer with years of hands-on experience in web and cross-platform app development. Backed by a small, trusted network of designers and engineers, we turn ambitious ideas into clean, scalable digital products.
                                        </p>
                                        <p class="text-lg text-gray-700 leading-relaxed mb-6">
                                            Our size is our strength — we’re agile, personal, and completely focused on delivering high-impact work without layers of bureaucracy. We collaborate closely with every client, whether you're a startup, a founder, or a growing business, ensuring your vision comes to life with clarity, speed, and quality.
                                        </p>
                                        <p class="text-lg text-gray-700 leading-relaxed">
                                            If you're looking for a team that feels like a partner — invested, responsive, and genuinely passionate about your success — you’re in the right place.
                                        </p>
                                    </div>

                                    <!-- Image -->
                                    <div class="about-image animate-zoom-rotate-in delay-300">
                                        <img src="https://fastly.picsum.photos/id/2/5000/3333.jpg?hmac=_KDkqQVttXw_nM-RyJfLImIbafFrqLsuGO5YuHqD-qQ" alt="Our Team" class="w-full h-auto rounded-xl shadow-2xl object-cover">
                                    </div>
                                </div>
                            </div>
                        </section>





                        <!-- Services Section -->
                        <section id="services" class="w-full min-h-[50vh] max-w-7xl mx-auto px-6 py-20">
                            <h2 class="text-3xl font-bold text-center mb-12">Our Services</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

                                <div
                                    class="bg-white rounded-lg shadow p-6 text-center opacity-0 transform scale-90 transition duration-500 ease-out hover:scale-105 hover:shadow-lg"
                                    style="animation: fadeScaleIn 0.5s forwards;"
                                >
                                    <div class="text-indigo-600 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75a3 3 0 014.5 4.5" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold mb-2">Cross-Platform Mobile Apps</h3>
                                    <p class="text-gray-600">Cross-platform mobile apps that run smoothly on both iOS and Android using React Native.</p>
                                </div>

                                <div
                                    class="bg-white rounded-lg shadow p-6 text-center opacity-0 transform scale-90 transition duration-500 ease-out hover:scale-105 hover:shadow-lg"
                                    style="animation: fadeScaleIn 0.6s forwards;"
                                >
                                    <div class="text-indigo-600 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 010 6.844L12 14z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold mb-2">Web Applications</h3>
                                    <p class="text-gray-600">Modern, responsive web apps that deliver excellent user experiences on all devices.</p>
                                </div>

                                <div
                                    class="bg-white rounded-lg shadow p-6 text-center opacity-0 transform scale-90 transition duration-500 ease-out hover:scale-105 hover:shadow-lg"
                                    style="animation: fadeScaleIn 0.7s forwards;"
                                >
                                    <div class="text-indigo-600 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m6 0l-6 6m6-6l-6-6" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold mb-2">Backend & APIs</h3>
                                    <p class="text-gray-600">Scalable backend solutions and APIs that power your cross-platform products reliably.</p>
                                </div>

                                <!-- Additional Services -->
                                <div
                                    class="bg-white rounded-lg shadow p-6 text-center opacity-0 transform scale-90 transition duration-500 ease-out hover:scale-105 hover:shadow-lg"
                                    style="animation: fadeScaleIn 0.8s forwards;"
                                >
                                    <div class="text-indigo-600 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 17l4-4 4 4m0-8l-4 4-4-4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold mb-2">UI/UX Design</h3>
                                    <p class="text-gray-600">Creating intuitive and engaging designs that enhance user experience across platforms.</p>
                                </div>

                                <div
                                    class="bg-white rounded-lg shadow p-6 text-center opacity-0 transform scale-90 transition duration-500 ease-out hover:scale-105 hover:shadow-lg"
                                    style="animation: fadeScaleIn 0.9s forwards;"
                                >
                                    <div class="text-indigo-600 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 6l-6 6 6 6" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold mb-2">Cloud Integration</h3>
                                    <p class="text-gray-600">Seamless integration with cloud services for scalability, storage, and performance.</p>
                                </div>

                                <div
                                    class="bg-white rounded-lg shadow p-6 text-center opacity-0 transform scale-90 transition duration-500 ease-out hover:scale-105 hover:shadow-lg"
                                    style="animation: fadeScaleIn 1.0s forwards;"
                                >
                                    <div class="text-indigo-600 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h11M9 21V3m6 18l6-9-6-9" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold mb-2">Maintenance & Support</h3>
                                    <p class="text-gray-600">Ongoing support and maintenance to keep your applications secure and up to date.</p>
                                </div>
                            </div>
                        </section>

                        <!-- Your existing "Know About Company" content here -->
                    </div>

                    <div
                        x-show="tab === 'me'"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4"
                        x-cloak
                    >
                        <!-- Your existing "Know About Me" content here -->

                        <!-- Hero Section -->
                        <header
                            class="w-full min-h-screen flex flex-col md:flex-row items-center justify-center px-6 py-20  overflow-hidden"
                        >
                            <!-- Text Content -->
                            <div
                                class="flex-1 max-w-2xl text-center md:text-left"
                                style="animation: slideFadeInLeft 1s ease forwards;"
                            >
                                <h1 class="text-6xl font-extrabold mb-6 leading-tight">
                                    Hi, I'm <span class="text-indigo-600">Krishanu</span>
                                </h1>
                                <p class="text-2xl text-gray-700 mb-10 max-w-xl">
                                    Solo Web & Cross-Platform Developer — crafting sleek websites and apps that run anywhere.
                                </p>
                                <a
                                    href="#contact"
                                    class="inline-block bg-indigo-600 text-white font-semibold px-10 py-4 rounded-lg shadow-lg hover:bg-indigo-700 transition transform hover:scale-105"
                                >
                                    Work With Me
                                </a>
                            </div>

                            <!-- Profile Image -->
                            <div
                                class="flex-1 max-w-lg mt-12 md:mt-0 flex justify-center"
                                style="animation: slideFadeInRight 1.2s ease forwards;"
                            >
                                <img
                                    src="https://fastly.picsum.photos/id/1/5000/3333.jpg?hmac=Asv2DU3rA_5D1xSe22xZK47WEAN0wjWeFOhzd13ujW4"
                                    alt="Your Name"
                                    class="rounded-xl shadow-2xl w-full max-w-md object-cover transform transition-transform hover:scale-105"
                                    loading="lazy"
                                />
                            </div>
                        </header>


                        <!-- Skills Section -->
                        <section id="skills" class="w-full min-h-[50vh] max-w-5xl mx-auto px-6 py-16">
                            <h2 class="text-3xl font-bold text-center mb-10">Love To Work With</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-8 text-center">

                                <!-- Skill Item Template with animation and hover effect -->
                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 0.5s forwards;"
                                >
                                    <img
                                        src="https://upload.wikimedia.org/wikipedia/commons/6/61/HTML5_logo_and_wordmark.svg"
                                        alt="HTML5"
                                        class="mx-auto w-16 h-16 mb-2"
                                    />
                                    <p>HTML5</p>
                                </div>

                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 0.6s forwards;"
                                >
                                    <img
                                        src="https://cdn.worldvectorlogo.com/logos/css-3.svg"
                                        alt="CSS3"
                                        class="mx-auto w-16 h-16 mb-2"
                                    />
                                    <p>CSS3</p>
                                </div>

                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 0.7s forwards;"
                                >
                                    <img
                                        src="https://cdn.worldvectorlogo.com/logos/javascript-1.svg"
                                        alt="JavaScript"
                                        class="mx-auto w-16 h-16 mb-2"
                                    />
                                    <p>JavaScript</p>
                                </div>

                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 0.8s forwards;"
                                >
                                    <img
                                        src="https://cdn.worldvectorlogo.com/logos/vue-9.svg"
                                        alt="Vue.js"
                                        class="mx-auto w-16 h-16 mb-2"
                                    />
                                    <p>Vue.js</p>
                                </div>

                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 0.9s forwards;"
                                >
                                    <img
                                        src="https://cdn.worldvectorlogo.com/logos/nodejs-icon.svg"
                                        alt="Node.js"
                                        class="mx-auto w-16 h-16 mb-2"
                                    />
                                    <p>Node.js</p>
                                </div>

                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 1.0s forwards;"
                                >
                                    <img
                                        src="https://cdn.worldvectorlogo.com/logos/tailwindcss.svg"
                                        alt="Tailwind CSS"
                                        class="mx-auto w-16 h-16 mb-2"
                                    />
                                    <p>Tailwind CSS</p>
                                </div>

                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 1.1s forwards;"
                                >
                                    <svg
                                        class="mx-auto w-16 h-16 mb-2 text-indigo-600"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 20v-6m0 0V8m0 6h6m-6 0H6"
                                        />
                                    </svg>
                                    <p>API Integration</p>
                                </div>

                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 1.2s forwards;"
                                >
                                    <img
                                        src="https://cdn.worldvectorlogo.com/logos/laravel-2.svg"
                                        alt="Laravel"
                                        class="mx-auto w-16 h-16 mb-2"
                                    />
                                    <p>Laravel</p>
                                </div>

                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 1.3s forwards;"
                                >
                                    <img
                                        src="https://symfony.com/logos/symfony_black_03.svg"
                                        alt="Symfony"
                                        class="mx-auto w-16 h-16 mb-2"
                                    />
                                    <p>Symfony</p>
                                </div>

                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 1.4s forwards;"
                                >
                                    <svg
                                        class="mx-auto w-16 h-16 mb-2 text-yellow-500"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M12 2L2 7v8l10 5 10-5V7z" />
                                        <path d="M12 22V12" />
                                    </svg>
                                    <p>Python</p>
                                </div>

                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 1.5s forwards;"
                                >
                                    <img
                                        src="https://fastapi.tiangolo.com/img/logo-margin/logo-teal.png"
                                        alt="FastAPI"
                                        class="mx-auto w-16 h-16 mb-2"
                                    />
                                    <p>FastAPI</p>
                                </div>

                                <div
                                    class="opacity-0 transform scale-90 transition duration-700 ease-out hover:scale-110"
                                    style="animation: fadeScaleIn 1.6s forwards;"
                                >
                                    <img
                                        src="https://upload.wikimedia.org/wikipedia/commons/7/74/Kotlin_Icon.png"
                                        alt="Kotlin"
                                        class="mx-auto w-16 h-16 mb-2"
                                    />
                                    <p>Kotlin</p>
                                </div>

                            </div>

                            <!-- Read More CTA -->
                            <div class="mt-12 text-center">
                                <a
                                    href="#more-skills"
                                    class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition"
                                >
                                    Read More
                                </a>
                            </div>
                        </section>



                        <!-- Your existing "Know About Me" content here -->

                    </div>

                </div>
            </div>


            <!-- Most Popular Projects Section -->
            <section id="portfolio" class="w-full bg-indigo-50 py-24">
                <div class="max-w-6xl mx-auto px-6">

                    <!-- Heading + CTA aligned on same row -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-14 gap-6">
                        <h2 class="text-4xl font-extrabold text-gray-900 text-center md:text-left">
                            Most Popular Projects
                        </h2>
                        <a href="/portfolio"
                           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-semibold py-3 px-6 rounded-lg transition text-center md:text-right w-full md:w-auto">
                            View Full Portfolio
                        </a>
                    </div>

                    <!-- Grid of projects -->
                    <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Project Card 1 -->
                        <div
                            class="bg-white rounded-lg shadow-lg transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl opacity-0 animate-fade-in-up"
                            x-intersect="$el.classList.add('animate-fade-in-up', 'opacity-100')">
                            <img src="https://placehold.co/400x250?text=Project+1" alt="Project 1" class="rounded-t-lg mb-4 w-full" />
                            <div class="p-6">
                                <h3 class="font-semibold text-xl mb-2">Project One</h3>
                                <p class="text-gray-600 mb-3">Responsive web app built with React and Tailwind CSS.</p>
                                <a href="#" class="text-indigo-600 font-medium hover:underline">View Details →</a>
                            </div>
                        </div>

                        <!-- Project Card 2 -->
                        <div
                            class="bg-white rounded-lg shadow-lg transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl opacity-0 animate-fade-in-up delay-150"
                            x-intersect="$el.classList.add('animate-fade-in-up', 'opacity-100')">
                            <img src="https://placehold.co/400x250?text=Project+2" alt="Project 2" class="rounded-t-lg mb-4 w-full" />
                            <div class="p-6">
                                <h3 class="font-semibold text-xl mb-2">Project Two</h3>
                                <p class="text-gray-600 mb-3">Cross-platform mobile app developed with Flutter.</p>
                                <a href="#" class="text-indigo-600 font-medium hover:underline">View Details →</a>
                            </div>
                        </div>

                        <!-- Project Card 3 -->
                        <div
                            class="bg-white rounded-lg shadow-lg transform transition duration-500 hover:scale-[1.03] hover:shadow-2xl opacity-0 animate-fade-in-up delay-300"
                            x-intersect="$el.classList.add('animate-fade-in-up', 'opacity-100')">
                            <img src="https://placehold.co/400x250?text=Project+3" alt="Project 3" class="rounded-t-lg mb-4 w-full" />
                            <div class="p-6">
                                <h3 class="font-semibold text-xl mb-2">Project Three</h3>
                                <p class="text-gray-600 mb-3">Full-stack app with Node.js backend and React frontend.</p>
                                <a href="#" class="text-indigo-600 font-medium hover:underline">View Details →</a>
                            </div>
                        </div>
                    </div>

                </div>
            </section>



            <!-- Latest Articles Section -->
            <section id="latest-articles" class="w-full bg-white py-24">
                <div class="max-w-6xl mx-auto px-6">

                    <!-- Heading + CTA aligned in row -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-14 gap-6">
                        <h2 class="text-4xl font-extrabold text-gray-900 text-center md:text-left">
                            Latest Articles
                        </h2>
                        <a href="/blog"
                           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-semibold py-3 px-6 rounded-lg transition text-center md:text-right w-full md:w-auto">
                            View All Articles
                        </a>
                    </div>

                    <!-- Articles Grid -->
                    <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Article Card 1 -->
                        <div
                            class="bg-gray-50 rounded-lg shadow-md transform transition duration-500 hover:scale-[1.02] hover:shadow-xl opacity-0 animate-fade-in-up"
                            x-intersect="$el.classList.add('animate-fade-in-up', 'opacity-100')">
                            <img src="https://placehold.co/400x220?text=Article+1" alt="Article 1" class="rounded-t-lg mb-4 w-full" />
                            <div class="p-6">
                                <p class="text-sm text-indigo-500 uppercase mb-1">Development</p>
                                <h3 class="font-semibold text-xl mb-2">The Future of Cross-Platform Apps</h3>
                                <p class="text-gray-600 mb-3">Why businesses are investing in cross-platform tech and how to stay ahead.</p>
                                <a href="#" class="text-indigo-600 font-medium hover:underline">Read More →</a>
                            </div>
                        </div>

                        <!-- Article Card 2 -->
                        <div
                            class="bg-gray-50 rounded-lg shadow-md transform transition duration-500 hover:scale-[1.02] hover:shadow-xl opacity-0 animate-fade-in-up delay-150"
                            x-intersect="$el.classList.add('animate-fade-in-up', 'opacity-100')">
                            <img src="https://placehold.co/400x220?text=Article+2" alt="Article 2" class="rounded-t-lg mb-4 w-full" />
                            <div class="p-6">
                                <p class="text-sm text-indigo-500 uppercase mb-1">Product Design</p>
                                <h3 class="font-semibold text-xl mb-2">Designing for Real Humans</h3>
                                <p class="text-gray-600 mb-3">UI/UX principles that improve accessibility, usability, and delight.</p>
                                <a href="#" class="text-indigo-600 font-medium hover:underline">Read More →</a>
                            </div>
                        </div>

                        <!-- Article Card 3 -->
                        <div
                            class="bg-gray-50 rounded-lg shadow-md transform transition duration-500 hover:scale-[1.02] hover:shadow-xl opacity-0 animate-fade-in-up delay-300"
                            x-intersect="$el.classList.add('animate-fade-in-up', 'opacity-100')">
                            <img src="https://placehold.co/400x220?text=Article+3" alt="Article 3" class="rounded-t-lg mb-4 w-full" />
                            <div class="p-6">
                                <p class="text-sm text-indigo-500 uppercase mb-1">Engineering</p>
                                <h3 class="font-semibold text-xl mb-2">How We Build Scalable APIs</h3>
                                <p class="text-gray-600 mb-3">A behind-the-scenes look at our API architecture and best practices.</p>
                                <a href="#" class="text-indigo-600 font-medium hover:underline">Read More →</a>
                            </div>
                        </div>
                    </div>

                </div>
            </section>



            <!-- Your long content here -->
        </main>






@push('style')
    <style>
        @keyframes fadeScaleIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideFadeInLeft {
            0% {
                opacity: 0;
                transform: translateX(-50px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideFadeInRight {
            0% {
                opacity: 0;
                transform: translateX(50px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Optional: style parallax container if needed */
        .parallax-container {
            position: relative;
            overflow: hidden;
            height: 30vh;
            width: 100%;
        }
        @keyframes slideZoomIn {
            0% {
                opacity: 0;
                transform: translateX(-40px) scale(0.9);
            }
            100% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes zoomRotateIn {
            0% {
                opacity: 0;
                transform: scale(0.8) rotate(-3deg);
            }
            100% {
                opacity: 1;
                transform: scale(1) rotate(0);
            }
        }

        .animate-slide-zoom-in {
            animation: slideZoomIn 1s ease-out both;
        }

        .animate-zoom-rotate-in {
            animation: zoomRotateIn 1s ease-out both;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

    </style>
@endpush
