<div class="container mx-auto py-10 space-y-16">


    <div class="w-full flex flex-col items-center bg-white text-gray-800">

        <!-- Hero Section -->
        <section class="w-full py-20 px-6 text-center bg-gradient-to-br from-blue-50 via-white to-blue-100 border-b border-gray-200">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-gray-900 leading-tight mb-6">
                    {{ $product->name }}
                </h1>
                <p class="max-w-2xl mx-auto text-lg md:text-xl text-gray-600 leading-relaxed">
                    {{ $product->short_desc }}
                </p>
                <div class="mt-8 flex justify-center gap-4">
                    <a href="#get-started"
                       class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold text-sm rounded-md shadow hover:bg-blue-700 transition">
                        Get Started
                    </a>
                    <a href="#demo_accounts"
                       class="inline-block px-6 py-3 bg-gray-200 text-gray-800 font-semibold text-sm rounded-md hover:bg-gray-300 transition">
                        View Demo
                    </a>
                </div>
            </div>
        </section>

        <!-- Gallery / Demo Section -->
        <section id="gallery" class="w-full py-20 px-6 bg-gray-50" x-data="{ showModal: false, modalImage: '' }">
            <div class="max-w-7xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Product Screenshots</h2>
                <p class="text-lg text-gray-600 mb-12">
                    Explore the core functionality and UI of our API dashboard and tools.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @for($i = 0; $i < 12; $i++)
                        <div class="relative group rounded-lg overflow-hidden shadow hover:shadow-lg transition duration-300 cursor-pointer">
                            <img
                                src="{{ $product->getFirstMediaUrl('imageGallery') }}"
                                alt="{{ $product->name }}"
                                class="w-full h-30 md:h-48 object-cover transform transition-transform duration-300 group-hover:scale-105"
                                @click="showModal = true; modalImage = '{{ $product->getFirstMediaUrl('imageGallery') }}'">

                            <!-- CTA Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <button
                                    class="px-4 py-2 bg-white text-gray-900 text-sm font-medium rounded shadow hover:bg-gray-100"
                                    @click.stop="showModal = true; modalImage = '{{ $product->getFirstMediaUrl('imageGallery') }}'">
                                    View
                                </button>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Modal -->
            <div
                x-show="showModal"
                x-transition
                @keydown.escape.window="showModal = false"
                @click.self="showModal = false"
                class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50"
                style="display: none;">
                <div class="relative bg-white rounded-lg shadow-xl w-11/12 md:w-4/5 lg:w-3/5 max-h-[90vh] overflow-hidden p-4">
                    <button
                        class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-3xl font-bold focus:outline-none"
                        @click="showModal = false"
                        aria-label="Close">
                        &times;
                    </button>
                    <img :src="modalImage" alt="Preview" class="w-full h-auto rounded-md object-contain max-h-[80vh] mx-auto">
                </div>
            </div>
        </section>

        <!-- Details Section -->
        <section id="description" class="w-full py-20 px-6 bg-white border-t border-b border-gray-200">
            <div class="max-w-5xl mx-auto text-left">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Product Details</h2>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                    {!! $product->desc !!}
                </div>
            </div>
        </section>

        <!-- Demo Accounts Section -->
        @php
            $demoAccounts = $product->data->demo_accounts ?? [];
        @endphp

        <section id="demo_accounts" class="w-full py-20 px-6 bg-gray-50">
            <div class="max-w-6xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Try It Yourself</h2>
                <p class="text-lg text-gray-600 mb-12">
                    Use these credentials to explore the product as different roles. Each role offers a different level of access.
                </p>

                <div class="flex flex-wrap justify-center gap-6">
                    @foreach($demoAccounts as $role => $info)
                        @php
                            // Determine optimal width per card (100%, 50%, or 33.333%)
                            $cardCount = count($demoAccounts);
                            $mod = $loop->remaining + 1;

                            $baseClass = 'w-full sm:w-1/2 lg:w-1/3';

                            // If it's the last row and only one item remains — make it full width
                            if ($mod === 1 && $cardCount % 3 === 1) {
                                $baseClass = 'w-full';
                            }

                            // If it's the last row and 2 items remain — split into 2 evenly
                            if ($mod <= 2 && $cardCount % 3 === 2 && $loop->remaining <= 1) {
                                $baseClass = 'w-full sm:w-1/2';
                            }
                        @endphp

                        <div class="{{ $baseClass }} max-w-full bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition duration-300 p-6">
                            <!-- Icon + Title -->
                            <div class="flex items-center gap-3 mb-4">
                                @switch($role)
                                    @case('admin')
                                        <x-heroicon-o-shield-exclamation class="w-7 h-7 text-red-500"/>
                                        @break
                                    @case('developer')
                                        <x-heroicon-o-code-bracket class="w-7 h-7 text-green-500"/>
                                        @break
                                    @case('user')
                                        <x-heroicon-o-user class="w-7 h-7 text-blue-500"/>
                                        @break
                                    @default
                                        <x-heroicon-o-identification class="w-7 h-7 text-gray-500"/>
                                @endswitch
                                <h3 class="text-xl font-semibold text-gray-800 capitalize">{{ $role }} Account</h3>
                            </div>

                            <!-- Credentials -->
                            <p class="text-sm text-gray-600 mb-3">Use these credentials:</p>
                            <ul class="text-sm text-gray-700 space-y-1 mb-5">
                                @foreach($info as $key => $value)
                                    <li>
                                        <span class="font-medium capitalize">{{ $key }}:</span>
                                        <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $value }}</code>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- CTA -->
                            <a href="{{ $product->demo_url ?? '#' }}" target="_blank"
                               class="block text-center w-full bg-blue-600 text-white font-medium py-2 rounded-md hover:bg-blue-700 transition">
                                Launch Demo
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>


        @if($product->plans)
            <section id="product_plan" class="w-full py-20 px-6 bg-white">
                @php
                    $plans = $product->plans; // You can add ->where('visible_on_front', true) if needed
                @endphp

                @if($plans->count())
                    <div class="max-w-6xl mx-auto text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Choose Your Plan</h2>
                        <p class="text-lg text-gray-600">Select a subscription plan that fits your usage and business size.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                        @foreach($plans as $plan)
                            <div class="flex flex-col justify-between relative bg-white border rounded-xl shadow-sm hover:shadow-lg transition duration-300 p-6 {{ $plan->is_recommended ? 'border-blue-600' : 'border-gray-200' }}">

                                {{-- Badge --}}
                                @if($plan->is_recommended)
                                    <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-bl-lg">
                                        Recommended
                                    </div>
                                @elseif($plan->is_enterprise)
                                    <div class="absolute top-0 right-0 bg-gray-700 text-white text-xs font-semibold px-3 py-1 rounded-bl-lg">
                                        Enterprise
                                    </div>
                                @endif

                                {{-- Content --}}
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $plan->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-4 min-h-[48px]">{{ $plan->desc }}</p>

                                    <div class="mb-4">
                                        <span class="text-3xl font-bold text-gray-900">${{ number_format($plan->price, 2) }}</span>
                                        <span class="text-sm text-gray-500 ml-1">/mo</span>
                                    </div>

                                    {{-- Features --}}
                                    @if($plan->features)
                                        <ul class="text-sm text-gray-700 space-y-2 mb-5">
                                            @foreach($plan->features as $feature)
                                                <li class="flex items-start gap-2">
                                                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span>{{ $feature }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="mb-5"></div>
                                    @endif
                                </div>

                                {{-- CTA Button --}}
                                <form method="POST" action="#">
                                    @csrf
                                    <button type="submit"
                                            wire:click="addToCart({{ $plan->id }})"
                                            class="mt-auto w-full bg-blue-600 text-white font-medium py-2 rounded-md hover:bg-blue-700 transition">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif


    </div>













    {{-- Related Products --}}
    <div>
        <h2 class="text-2xl font-bold mb-6">Related Products</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @php
                $relatedProducts = \App\Models\Product\Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->visible()
                    ->take(6)
                    ->get();
            @endphp

            @forelse ($relatedProducts as $related)
                <div class="border rounded p-4 hover:shadow">
                    <a href="{{ url('/product/' . $related->url) }}">
                        <img src="{{ $related->getFirstMediaUrl('displayImage') }}"
                             alt="{{ $related->name }}"
                             class="w-full h-48 object-cover rounded mb-3">
                        <h3 class="text-lg font-semibold mb-1">{{ $related->name }}</h3>
                        <p class="text-green-600 font-bold">${{ number_format($related->full_price, 2) }}</p>
                    </a>
                </div>
            @empty
                <p class="text-gray-600">No related products found.</p>
            @endforelse
        </div>
    </div>
</div>
