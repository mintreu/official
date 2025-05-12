@section('title', $title ?? config('app.name'))

<div x-data="{ isOpen: false, activeSection: 'home' }" class="h-full w-full relative">

    {{-- Navigation (Responsive: Sidebar on Mobile, Top Navbar on Desktop) --}}
    <div class="w-full absolute z-20 h-10 bg-transparent px-6 py-3 flex justify-center gap-2">
        <div class="grow flex gap-4">
            <a href="{{ url('/') }}">
                <h1 class="text-2xl font-semibold">Mintreu</h1>
            </a>
            {{--            <button @click="isOpen = true" class="md:hidden">--}}
            {{--                @svg('heroicon-m-bars-3', 'w-6 h-6 mt-1.5')--}}
            {{--            </button>--}}
        </div>

        {{--        <div class="hidden md:block">--}}
        {{--            <div class="flex flex-row gap-4">--}}
        {{--                <a href="{{url('/')}}" class="flex gap-2 items-center">@svg('heroicon-m-home', 'w-6 h-6') Home</a>--}}
        {{--                <a href="{{url('/').'#about'}}"  class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') About</a>--}}
        {{--                <a href="#services" @click.prevent="activeSection = 'services'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Services</a>--}}
        {{--                <a href="#projects" @click.prevent="activeSection = 'projects'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Projects</a>--}}
        {{--                <a href="#pricing" @click.prevent="activeSection = 'pricing'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Pricing</a>--}}
        {{--                <a href="#contact" @click.prevent="activeSection = 'contact'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Contact</a>--}}
        {{--                <a href="{{url('/app')}}" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Login</a>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    </div>




    {{-- Main Content --}}
    <div class="h-screen w-full relative flex justify-center items-center overflow-hidden">




        <div class="homepage-section">

            <div class="homepage-section-content bg-black/50 p-5">
                <h1 class="text-4xl font-bold mb-6">Frequently Asked Questions</h1>


                <!-- FAQ Item 1 -->
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-blue-800">1. What services does Mintreu offer?</h2>
                    <p class="text-lg text-gray-700 mt-2">
                        Mintreu provides web development, mobile app development, UI/UX design, and digital marketing services tailored to your business needs.
                    </p>
                </div>

                <!-- FAQ Item 2 -->
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-blue-800">2. How can I get in touch with Mintreu?</h2>
                    <p class="text-lg text-gray-700 mt-2">
                        You can contact us via email at <a href="mailto:info@mintreu.com" class="text-blue-700 underline">info@mintreu.com</a> or call us at +123 456 7890.
                    </p>
                </div>

                <!-- FAQ Item 3 -->
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-blue-800">3. What is your pricing model?</h2>
                    <p class="text-lg text-gray-700 mt-2">
                        Our pricing depends on the complexity and scope of the project. We offer customized quotes based on your specific requirements.
                    </p>
                </div>

                <!-- FAQ Item 4 -->
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-blue-800">4. Do you offer support after the project is completed?</h2>
                    <p class="text-lg text-gray-700 mt-2">
                        Yes, we offer post-project support and maintenance to ensure everything runs smoothly.
                    </p>
                </div>

                <!-- FAQ Item 5 -->
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-blue-800">5. Can I request a project update or change?</h2>
                    <p class="text-lg text-gray-700 mt-2">
                        Absolutely! We work closely with our clients and are happy to accommodate updates or changes within the scope of the project.
                    </p>
                </div>

                <!-- FAQ Item 6 -->
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-blue-800">6. How do I get a quote for a project?</h2>
                    <p class="text-lg text-gray-700 mt-2">
                        To get a quote, please fill out the contact form on our website or reach out to us directly via email.
                    </p>
                </div>


                <div>
                    <a class="px-6 py-2 bg-green-500 text-white rounded-2xl shadow-lg shadow-black" href="{{url('/')}}">Back to Home</a>
                </div>

            </div>

        </div>







        {{-- Three js Background--}}
        <footer class="absolute bottom-0 right-1">
            <p class="text-sm">&copy; 2024 Mintreu. All rights reserved. |
                <a href="{{url('privacy')}}" class="text-blue-400 underline font-semibold">Privacy Policy</a> |
                <a href="{{url('terms')}}" class="text-blue-400 hover:underline">Terms of Service</a> |
                <a href="{{url('faqs')}}" class="text-blue-400 hover:underline">Faqs</a>
            </p>
        </footer>
        <livewire:welcome.welcome-screen />
    </div>

</div>
