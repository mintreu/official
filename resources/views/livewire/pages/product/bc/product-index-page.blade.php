<main x-cloak class="min-h-screen   transition-colors overflow-hidden">

    {{-- Hero Section --}}
    <section class="relative bg-indigo-600 dark:bg-indigo-800 py-24 text-center overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div id="hero-bg"
                 class="absolute left-1/2 transform -translate-x-1/2 top-0 w-[200%] h-[300%]
                        bg-gradient-to-br from-fuchsia-400 via-red-600 to-purple-600 opacity-20 blur-3xl scale-110"
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
        <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mx-10">
            @forelse ($products as $product)
                {{-- Product Card --}}
                <div x-cloak class="product-card opacity-60 transition-opacity duration-500 bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="aspect-w-4 aspect-h-3 bg-gray-100 dark:bg-gray-700">
                        <img src="{{ $product->getFirstMediaUrl('displayImage') }}"
                             alt="{{ $product->name }}"
                             loading="lazy"
                             class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    </div>
                    <div class="p-4 flex flex-col h-64">
                        <h2 class="font-semibold text-lg truncate">{{ $product->name }}</h2>
                        <p class="text-sm mt-2 line-clamp-2">
                            {{ $product->short_desc ?? Str::limit($product->desc, 80) }}
                        </p>
{{--                        <span class="mt-auto text-xl font-bold text-indigo-600 dark:text-indigo-400">--}}
{{--                            ${{ number_format($product->full_price, 2) }}--}}
{{--                        </span>--}}
                        <div class="mt-3 flex space-x-2">
                            <a href="{{ url('/product/'.$product->url) }}"
                               class="flex-1 bg-indigo-600 text-white px-3 py-2 rounded hover:bg-indigo-700 text-sm text-center transform hover:scale-105 transition">
                                View
                            </a>
                            @if($product->chargeable)
                                <button class="flex-1 bg-gray-200 dark:bg-gray-700 px-3 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">
                                    Add to Cart
                                </button>
                            @else
                                <span class="px-3 py-2 text-green-600 dark:text-green-400 rounded text-sm self-center">Free</span>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Product Card --}}
            @empty
                <div class="col-span-full text-center py-16 text-lg">
                    😕 No products found. Try a different filter or browse another category.
                </div>
            @endforelse

            @if($allLoaded && $products->isNotEmpty())
                <div class="col-span-full text-center py-10">
                    🎉 You’ve reached the end. Browse other categories or check back later!
                </div>
            @endif
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
