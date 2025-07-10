@section('title', $title ?? config('app.name'))

<div x-data="{ isOpen: false, activeSection: 'home' }" class="h-full w-full relative font-raleway text-white">

    {{-- Navigation (Responsive: Sidebar on Mobile, Top Navbar on Desktop) --}}
    <div class="w-full absolute z-20 h-10 bg-transparent px-6 py-3 flex justify-center gap-2">
        <div class="grow flex gap-4">
            <a href="{{ url('/') }}">
                <h1 class="text-2xl font-comfort ">Mintreu</h1>
            </a>
            <button @click="isOpen = true" class="md:hidden">
                @svg('heroicon-m-bars-3', 'w-6 h-6 mt-1.5')
            </button>
        </div>

        <div class="hidden md:block">
            <div class="flex flex-row gap-4">
                <a href="#home" @click.prevent="activeSection = 'home'" class="flex gap-2 items-center">
                    @svg('heroicon-m-home', 'w-6 h-6') Home
                </a>
                <a href="#about" @click.prevent="activeSection = 'about'" class="flex gap-2 items-center">
                    @svg('heroicon-s-information-circle', 'w-6 h-6') About
                </a>
                <a href="#services" @click.prevent="activeSection = 'services'" class="flex gap-2 items-center">
                    @svg('heroicon-s-briefcase', 'w-6 h-6') Services
                </a>
                <a href="#projects" @click.prevent="activeSection = 'projects'" class="flex gap-2 items-center">
                    @svg('heroicon-s-light-bulb', 'w-6 h-6') Projects
                </a>
                <a href="#products" @click.prevent="activeSection = 'products'" class="flex gap-2 items-center">
                    @svg('heroicon-s-cube', 'w-6 h-6') Products
                </a>
                <a href="#pricing" @click.prevent="activeSection = 'pricing'" class="flex gap-2 items-center">
                    @svg('heroicon-s-tag', 'w-6 h-6') Pricing
                </a>
                <a href="#contact" @click.prevent="activeSection = 'contact'" class="flex gap-2 items-center">
                    @svg('heroicon-s-phone', 'w-6 h-6') Contact
                </a>
                <a href="{{url('/app')}}" class="flex gap-2 items-center">
                    @svg('heroicon-s-lock-closed', 'w-6 h-6') Login
                </a>
            </div>

        </div>
    </div>

    {{-- Mobile Sidebar Navigation --}}
    <div x-show="isOpen" class="md:hidden absolute z-20 top-0 left-0 bottom-0 w-1/2 lg:w-1/3 bg-black py-10 px-6"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform -translate-x-full">
        <div class="flex justify-center gap-2">
            <h1 class="grow text-xl font-semibold ">Mintreu</h1>
            <button @click="isOpen = false">@svg('heroicon-c-x-mark', 'w-6 h-6 text-white')</button>
        </div>

        <div class="flex flex-col gap-3 justify-center mt-6">
            <a href="#home" @click.prevent="activeSection = 'home'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-m-home', 'w-6 h-6') Home</a>
            <a href="#about" @click.prevent="activeSection = 'about'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') About</a>
            <a href="#services" @click.prevent="activeSection = 'services'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Services</a>
            <a href="#projects" @click.prevent="activeSection = 'projects'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Projects</a>
            <a href="#products" @click.prevent="activeSection = 'products'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Products</a>
            <a href="#pricing" @click.prevent="activeSection = 'pricing'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Pricing</a>
            <a href="#contact" @click.prevent="activeSection = 'contact'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Contact</a>
            <a href="{{url('/app')}}" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Login</a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="h-screen w-full relative flex justify-center items-center overflow-hidden">
        {{-- Home Section --}}
        <div id="home" x-show="activeSection === 'home'" class="absolute w-fit mx-16 text-center p-1 flex flex-col " x-cloak>
            <h1 class="text-6xl md:text-7xl lg:text-9xl font-semibold font-comfort">Mintreu</h1>
            <span class="text-md md:text-lg lg:text-xl font-raleway">
              Fueling your online success with powerful web development
            </span>

            <div class="mt-5 mb-2 py-1 flex-grow sm:flex-col gap-4">
                <a wire:navigate href="{{ url('/app') }}" class="bg-blue-700 mx-1 px-2 sm:px-4 py-2 rounded-3xl ">Get Started</a>
                <a wire:navigate href="{{ url('/web') }}" class="bg-green-700 mx-1 px-2 sm:px-4 py-2 rounded-3xl ">Know More</a>
            </div>
        </div>

        <div class="homepage-section" x-show="activeSection === 'about'" x-cloak>
            <livewire:welcome.sections.about-section  />
        </div>

        <div class="homepage-section" x-show="activeSection === 'services'" x-cloak>
            <livewire:welcome.sections.services-section  />
        </div>

        <div class="homepage-section" x-show="activeSection === 'projects'" x-cloak>
            <livewire:welcome.sections.projects-section />
        </div>

        <div class="homepage-section" x-show="activeSection === 'products'" x-cloak>
            <livewire:welcome.sections.products-section />
        </div>

        <div class="homepage-section" x-show="activeSection === 'pricing'" x-cloak>
            <livewire:welcome.sections.pricing-section  />
        </div>

        <div class="homepage-section" x-show="activeSection === 'contact'" x-cloak>
            <livewire:welcome.sections.contact-section  />
        </div>





        {{-- Three js Background--}}
        <footer class="absolute bottom-0 right-1">
            <p class="text-sm">&copy; 2024 Mintreu. All rights reserved. |
                <a href="{{url('privacy')}}" class="text-blue-400 hover:underline">Privacy Policy</a> |
                <a href="{{url('terms')}}" class="text-blue-400 hover:underline">Terms of Service</a> |
                <a href="{{url('faqs')}}" class="text-blue-400 hover:underline">Faqs</a>
            </p>
        </footer>
        <livewire:welcome.welcome-screen />
    </div>

</div>
