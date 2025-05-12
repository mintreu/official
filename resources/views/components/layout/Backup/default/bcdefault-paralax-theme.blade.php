@section('title', $title ?? config('app.name'))

<div class=" bg-black text-white">
    <div x-data="{ isOpen: false, activeSection: 'home' }" class="h-full w-full relative ">

{{--         Navigation (Responsive: Sidebar on Mobile, Top Navbar on Desktop)--}}
        <div class="w-full absolute z-20 h-12 px-6 flex justify-center gap-2 items-center overflow-hidden ">
            <div class="grow flex gap-4 mt-1">
                <a href="{{ url('/') }}">
                    <h1 class="text-2xl font-semibold font-comfort">Mintreu</h1>
                </a>
                <button @click="isOpen = true" class="md:hidden">
                    @svg('heroicon-m-bars-3', 'w-6 h-6 mt-1.5')
                </button>
            </div>

            <div class="hidden md:flex flex-row gap-4">
                <a href="{{url('/')}}" class="flex gap-2 items-center text-lg">@svg('heroicon-m-home', 'w-6 h-6') Home</a>
                <a href="{{url('/').'#about'}}" class="flex gap-2 items-center text-lg">@svg('heroicon-c-folder', 'w-6 h-6') About</a>
                <a href="#services" @click.prevent="activeSection = 'services'" class="flex gap-2 items-center text-lg">@svg('heroicon-c-folder', 'w-6 h-6') Services</a>
                <a href="#projects" @click.prevent="activeSection = 'projects'" class="flex gap-2 items-center text-lg">@svg('heroicon-c-folder', 'w-6 h-6') Projects</a>
                <a href="#pricing" @click.prevent="activeSection = 'pricing'" class="flex gap-2 items-center text-lg">@svg('heroicon-c-folder', 'w-6 h-6') Pricing</a>
                <a href="#contact" @click.prevent="activeSection = 'contact'" class="flex gap-2 items-center text-lg">@svg('heroicon-c-folder', 'w-6 h-6') Contact</a>
                <a href="{{url('/app')}}" class="flex gap-2 items-center text-lg">@svg('heroicon-c-folder', 'w-6 h-6') Login</a>
            </div>
        </div>

        <div x-show="isOpen" class="md:hidden absolute z-20 top-0 left-0 bottom-0 w-full py-10 px-6 flex flex-col"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform -translate-x-full">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-semibold font-comfort">Mintreu</h1>
                <button @click="isOpen = false" class="text-white hover:text-purple-500">
                    @svg('heroicon-c-x-mark', 'w-10 h-10')
                </button>
            </div>

            <div class="flex flex-col gap-3 justify-center mt-6 mb-auto text-xl gap-2">
                <a href="#home" @click.prevent="activeSection = 'home'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-m-home', 'w-6 h-6') Home</a>
                <a href="#about" @click.prevent="activeSection = 'about'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') About</a>
                <a href="#services" @click.prevent="activeSection = 'services'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Services</a>
                <a href="#projects" @click.prevent="activeSection = 'projects'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Projects</a>
                <a href="#pricing" @click.prevent="activeSection = 'pricing'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Pricing</a>
                <a href="#contact" @click.prevent="activeSection = 'contact'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Contact</a>
            </div>
            <a href="{{url('/app')}}" class="flex gap-2 items-center text-lg">@svg('heroicon-c-folder', 'w-6 h-6') Login</a>
        </div>

{{--         Main Content--}}
        <div class="w-full relative flex flex-col overflow-hidden mx-auto ">
            <div class="absolute bottom-0 top-0 left-0 right-0 w-full max-h-screen overflow-y-scroll overflow-x-hidden border-4 p-2 border-black m-10">
                {{ $slot }}
            </div>
                    <livewire:animation.paralax-background />
{{--             Footer--}}
            <footer class="absolute bottom-0 right-1 p-2">
                <p class="text-lg flex flex-col">&copy; 2024 Mintreu. All rights reserved.
                    <span class="flex gap-2">
                    <a href="{{url('privacy')}}" class="text-blue-400 hover:underline">Privacy Policy</a>
                    <a href="{{url('terms')}}" class="text-blue-400 hover:underline">Terms of Service</a>
                    <a href="{{url('faqs')}}" class="text-blue-400 hover:underline">FAQs</a>
                </span>
                </p>
            </footer>
        </div>

    </div> <!-- Close x-data div -->

</div>



