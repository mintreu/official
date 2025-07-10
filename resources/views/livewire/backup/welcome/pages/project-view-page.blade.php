@section('title', $title ?? config('app.name'))

<div x-data="{ isOpen: false, activeSection: 'content' }" class="h-full w-full relative font-raleway text-white">

    {{-- Navigation (Responsive: Sidebar on Mobile, Top Navbar on Desktop) --}}
    <div class="w-full absolute z-20 h-10 bg-transparent px-6 py-3 flex justify-center gap-2">
        <div class="grow flex gap-4">
            <a href="{{ url('/') }}">
                <h1 class="text-2xl font-comfort">Mintreu</h1>
            </a>
            <button @click="isOpen = true" class="md:hidden">
                @svg('heroicon-m-bars-3', 'w-6 h-6 mt-1.5')
            </button>
        </div>

        <div class="hidden md:block">
            <div class="flex flex-row gap-4">
                <a href="#home" @click.prevent="activeSection = 'home'" class="flex gap-2 items-center">@svg('heroicon-m-home', 'w-6 h-6') Home</a>
                <a href="#about" @click.prevent="activeSection = 'about'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') About</a>
                <a href="#services" @click.prevent="activeSection = 'services'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Services</a>
                <a href="#projects" @click.prevent="activeSection = 'projects'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Projects</a>
                <a href="#pricing" @click.prevent="activeSection = 'pricing'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Pricing</a>
                <a href="#contact" @click.prevent="activeSection = 'contact'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Contact</a>
                <a href="{{url('/app')}}" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Login</a>
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
            <h1 class="grow text-xl font-semibold">Mintreu</h1>
            <button @click="isOpen = false">@svg('heroicon-c-x-mark', 'w-6 h-6 text-white')</button>
        </div>

        <div class="flex flex-col gap-3 justify-center mt-6">
            <a href="#home" @click.prevent="activeSection = 'home'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-m-home', 'w-6 h-6') Home</a>
            <a href="#about" @click.prevent="activeSection = 'about'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') About</a>
            <a href="#services" @click.prevent="activeSection = 'services'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Services</a>
            <a href="#projects" @click.prevent="activeSection = 'projects'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Projects</a>
            <a href="#pricing" @click.prevent="activeSection = 'pricing'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Pricing</a>
            <a href="#contact" @click.prevent="activeSection = 'contact'; isOpen = false" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Contact</a>
            <a href="{{url('/app')}}" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Login</a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="h-screen w-full relative flex justify-center items-center overflow-hidden">
        {{-- Home Section --}}
        <div id="home" x-show="activeSection === 'home'" class="absolute w-fit mx-16 text-center p-1 flex flex-col" x-cloak>
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

        <div class="homepage-section" x-show="activeSection === 'pricing'" x-cloak>
            <livewire:welcome.sections.pricing-section  />
        </div>

        <div class="homepage-section" x-show="activeSection === 'contact'" x-cloak>
            <livewire:welcome.sections.contact-section  />
        </div>

        <div class="homepage-section" x-show="activeSection === 'content'" x-cloak>
            {{-- Main content section when 'content' tab is active. --}}

            <!-- Your content and modifications go here -->

            @isset($record)

                <div class="homepage-section-content">

                    <h1 class="text-5xl font-semibold py-6">{{$record->name}}</h1>

                    <p class="text-lg text-justify w-full">{!! $record->desc !!}</p>

                    <div class="w-full text-left justify-normal mt-6">
                        <div class="flex gap-3">
                            <h2 class="text-2xl font-semibold grow">Available Products :-</h2>
                            <div>
                                <select wire:model="filterType" wire:change="updateFilterType" class="bg-transparent px-6 py-2 rounded-lg border-none">
                                    <option class="bg-black/50" value="">Select Product Type</option>

                                    {{-- Dynamically populate options using ProductTypeCast enum --}}
                                    @foreach(\App\Models\Enums\ProductTypeCast::cases() as $type)
                                        <option class="bg-black/50" value="{{ $type->value }}">
                                            {{ $type->getLabel() }} {{-- Fetch the label dynamically --}}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <hr />

                        @if($record->products?->count())
                            <div class="p-3">
                                @foreach($record->products->groupBy('type')->sortKeys() as $type => $products)
                                    <h2 class="text-2xl font-bold my-4">{{ \App\Models\Enums\ProductTypeCast::from($type)->getLabel() }}</h2>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                                        @foreach($products as $product)
                                            <div class="col-span-1 w-full rounded-lg overflow-hidden transform transition-transform duration-200 hover:scale-105">
                                                <div class="relative w-full rounded-t-2xl">
                                                    <img src="{{ $product->getFirstMediaUrl('displayImage') }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-t-lg">
                                                    <div class="absolute inset-0 bg-black opacity-0 hover:opacity-25 transition-opacity duration-300"></div>
                                                </div>
                                                <div class="p-4 w-full bg-black/50 text-white flex flex-col gap-2">
                                                    <div class="flex gap-6">
                                                        <h2 class="text-xl grow font-semibold">{{ $product->name }}</h2>
                                                        <span class="px-3 py-1 bg-fuchsia-600 rounded-2xl text-sm">{{ $product->type->getLabel() }}</span>
                                                    </div>
                                                    <p class="mt-2">{{ \Illuminate\Support\Str::limit($product->short_desc, 120) }}</p>
                                                    <a href="{{ route('welcome.product.view', ['product' => $product->url]) }}"
                                                       class="text-center border-2 bg-transparent hover:bg-fuchsia-600 font-semibold py-2 px-4 w-full rounded-2xl transition-colors duration-200">
                                                        View
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="w-full p-5 flex justify-center items-center">
                                <h3 class="text-xl font-semibold text-fuchsia-600">No Product Listed</h3>
                            </div>
                        @endif
                    </div>

                </div>


            @endisset

            {{-- End of main content section. --}}
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
