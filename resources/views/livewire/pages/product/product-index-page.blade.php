<main x-cloak class="min-h-screen w-full  transition-colors overflow-hidden">

    {{-- Hero Section --}}
    <section class="relative bg-purple-500 py-24 text-center overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div id="hero-bg"
                 class="absolute left-1/2 transform -translate-x-1/2 top-0 w-[200%] h-[300%]
                        bg-gradient-to-br from-purple-600 via-indigo-500 to-purple-600 opacity-20 blur-3xl scale-110"
                 x-data
                 x-init="window.addEventListener('scroll', () => {
                     document.querySelector('#hero-bg').style.transform = `translateY(${window.scrollY * -0.2}px)`
                 })">
            </div>
        </div>
        <div class="absolute inset-0 bg-black/30 dark:bg-black/40 backdrop-blur-sm z-0"></div>
        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
            <h1 id="hero-text"
                class="text-5xl sm:text-6xl font-black leading-tight tracking-tight text-white bg-clip-text bg-gradient-to-b from-white to-white/40 dark:from-gray-200 dark:to-gray-500">
                <span>Digital Plugins • Themes • CMS</span>
            </h1>
            <p class="mt-6 text-xl sm:text-2xl max-w-2xl mx-auto font-medium text-white/90 dark:text-gray-200 drop-shadow-md">
                Discover your next digital asset in our futuristic marketplace
            </p>
        </div>
    </section>

    {{-- Category Navigation --}}
    <div class="px-4 sm:px-6 lg:px-8 mt-10 mb-4">
        <div class="flex flex-wrap items-center justify-center gap-3">
            <button wire:click="$set('category', '')"
                    class="px-4 py-2 rounded-full border text-sm transition
                           bg-gray-300 dark:bg-gray-800 dark:border-gray-700
                           hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-500
                           {{ $category === '' ? 'bg-indigo-600 text-white' : '' }}">
                All
            </button>
            @foreach($categories as $id => $name)
                <button wire:click="$set('category', '{{ $id }}')"
                        class="px-4 py-2 rounded-full border text-sm transition
                               bg-gray-300 dark:bg-gray-800 dark:border-gray-700
                               hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-500
                               {{ $category == $id ? 'bg-indigo-600 text-white' : '' }}">
                    {{ $name }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Sort Dropdown --}}
    <div class="px-4 sm:px-6 lg:px-8 mb-6 flex justify-end">
        <select wire:model="sort"
                class="p-2 border rounded bg-white dark:bg-gray-800 dark:border-gray-700 text-sm">
            <option value="latest">Newest</option>
            <option value="az">Name A→Z</option>
            <option value="za">Name Z→A</option>
{{--            <option value="plh">Price Low→High</option>--}}
{{--            <option value="phl">Price High→Low</option>--}}
        </select>
    </div>

    {{-- Products Grid --}}
    <div
        x-data
        x-init="window.addEventListener('scroll', () => {
            if (!@js($allLoaded) && window.innerHeight + window.scrollY >= document.body.offsetHeight - 300) {
                $wire.loadMore()
            }
        })"
        class="px-4 sm:px-6 lg:px-8 pb-12"
    >

        <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 px-4 sm:px-6 lg:px-8 py-12">
            @forelse ($products as $product)
                <div class="w-full max-w-xs mx-auto product-card relative">
                    <!-- Flip Card -->
                    <div class="flip-card w-full h-80 relative">
                        <div class="flip-inner w-full h-full rounded-xl">

                            <!-- Front -->
                            <div class="flip-front bg-gradient-to-br from-gray-900 via-gray-800 to-black shadow-xl
                                hover:shadow-[0_0_15px_5px_rgba(0,255,255,0.3),_0_0_35px_10px_rgba(255,0,255,0.25)]
                                transition-shadow duration-500 relative">

                                <!-- Ribbon inside flip-front (only visible on front) -->
                                <div class="absolute top-0 left-0 z-10">
                            <span class="bg-gradient-to-r from-pink-500 to-red-500 text-white text-xs font-bold px-3 py-1 rounded-br-lg shadow-md">
                                {{ $product->ribbon ?? 'New' }}
                            </span>
                                </div>

                                <img src="{{ $product->getFirstMediaUrl('displayImage') }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-40 object-cover rounded-t-xl">

                                <div class="p-4 flex flex-col justify-between h-36 text-white">
                                    <h3 class="text-base font-semibold truncate">{{ $product->name }}</h3>
                                    <a href="#"
                                       class="mt-3 text-sm bg-indigo-600 px-3 py-1 rounded hover:bg-indigo-700 text-center transition">
                                        View
                                    </a>
                                </div>
                            </div>

                            <!-- Back -->
                            <div class="flip-back bg-gray-100 shadow-md p-4 text-sm text-gray-800 rounded-xl">
                                <h4 class="font-semibold mb-2">Details</h4>
                                <p class="overflow-y-auto max-h-36">{{ $product->short_desc }}</p>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-400">No products found.</p>
            @endforelse
        </div>


        <div wire:loading class="mt-10 text-center text-gray-600 dark:text-gray-300">
            Loading more products…
        </div>
    </div>
</main>

@push('script')
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100');
                    entry.target.classList.remove('opacity-60');
                } else {
                    entry.target.classList.remove('opacity-100');
                    entry.target.classList.add('opacity-60');
                }
            });
        }, {
            root: null,
            rootMargin: '0px',
            threshold: 0.5 // Adjust this to tune sensitivity
        });

        function observeCards() {
            document.querySelectorAll('.product-card').forEach(card => {
                observer.observe(card);
            });
        }

        document.addEventListener('DOMContentLoaded', observeCards);

        document.addEventListener("livewire:load", () => {
            Livewire.hook('message.processed', () => {
                observeCards();
            });
        });
    </script>
@endpush


@push('style')
    <style>
        .flip-card {
            perspective: 1000px;
        }

        .flip-inner {
            transition: transform 0.6s;
            transform-style: preserve-3d;
            width: 100%;
            height: 100%;
            position: relative;
        }

        .flip-card:hover .flip-inner {
            transform: rotateY(180deg);
        }

        .flip-front,
        .flip-back {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            border-radius: 0.75rem;
            backface-visibility: hidden;
            overflow: hidden;
        }

        .flip-front {
            z-index: 2;
        }

        .flip-back {
            transform: rotateY(180deg);
            z-index: 1;
        }

        /* Scrollbar styles for back description */
        .flip-back p::-webkit-scrollbar {
            width: 6px;
        }

        .flip-back p::-webkit-scrollbar-thumb {
            background-color: rgba(100, 100, 100, 0.5);
            border-radius: 3px;
        }
    </style>
@endpush

