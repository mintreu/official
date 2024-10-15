@section('title', $title ?? config('app.name'))

<div class="relative h-screen w-full overflow-hidden" x-data="{ isOpen: false }">

    {{-- Full-Screen Background --}}
    <livewire:animation.paralax-background />

    {{-- Main Content Area --}}
    <div class="absolute top-0 left-0 w-full h-full z-10 flex">

        {{-- Collapsible Sidebar --}}
        <div
            :class="isOpen
                ? 'w-full sm:w-2/3 md:w-1/3 lg:w-1/4 xl:w-1/6'
                : 'w-16'"
            class="bg-gray-900 bg-opacity-90 text-white flex flex-col space-y-4 transition-all duration-300 ease-in-out p-4 relative shadow-lg">

            {{-- Brand Logo and Toggle Button --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2" :class="isOpen ? 'opacity-100' : 'opacity-0'" x-transition>
                    <img src="{{ asset('images/logo.svg') }}" alt="Brand Logo" class="w-8 h-8">
                    <span class="font-bold text-lg text-white">Mintreu</span>
                </div>

                <button @click="isOpen = !isOpen" class="text-white p-2 focus:outline-none">
                    <template x-if="!isOpen">
                        @svg('heroicon-m-arrow-right', 'w-6 h-6')
                    </template>
                    <template x-if="isOpen">
                        @svg('heroicon-m-arrow-left', 'w-6 h-6')
                    </template>
                </button>
            </div>

            {{-- Sidebar Navigation --}}
            <div class="flex flex-col space-y-4 mt-4">
                @foreach ([
                    ['#home', 'Home', 'heroicon-m-home'],
                    ['#about', 'About', 'heroicon-s-information-circle'],
                    ['#services', 'Services', 'heroicon-s-briefcase'],
                    ['#projects', 'Projects', 'heroicon-s-light-bulb'],
                    ['#products', 'Products', 'heroicon-s-cube'],
                    ['#pricing', 'Pricing', 'heroicon-s-tag'],
                    ['#contact', 'Contact', 'heroicon-s-phone'],
                    [url('/app'), 'Login', 'heroicon-s-lock-closed']
                ] as [$href, $label, $icon])
                    <a href="{{ $href }}" @click.prevent="activeSection = '{{ strtolower($label) }}'" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700" :class="isOpen ? 'justify-start' : 'justify-center'">
                        @svg($icon, 'w-6 h-6 text-white') {{-- Ensure icon color is white --}}
                        <span x-show="isOpen" x-transition class="text-white">{{ $label }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        {{-- ./Sidebar Navigation --}}

        {{-- Main Content --}}
        <div class="grow bg-gray-800 bg-opacity-70 p-8 rounded-lg">
            {{ $slot }}
        </div>
        {{-- ./Main Content --}}

    </div>
</div>
