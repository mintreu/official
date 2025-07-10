<div class="container mx-auto py-10 space-y-16">

    {{-- Product Detail Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        {{-- Product Image --}}
        <div>
            <img src="{{ $product->getFirstMediaUrl('displayImage') }}"
                 alt="{{ $product->name }}"
                 class="w-full rounded shadow">
        </div>

        {{-- Product Info --}}
        <div>
            {{-- Title --}}
            <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>

            {{-- Category --}}
            @if($product->category)
                <div class="mb-2 text-sm text-gray-600">
                    Category:
                    <a href="{{ url('/category/' . $product->category->url) }}" class="text-blue-600 hover:underline">
                        {{ $product->category->name }}
                    </a>
                </div>
            @endif

            {{-- Project --}}
            @if($product->project)
                <div class="mb-4 flex items-center space-x-3">
                    <img src="{{ $product->project->getFirstMediaUrl('displayImage') }}"
                         class="h-8 w-8 rounded" alt="{{ $product->project->name }}">
                    <a href="{{ url('/project/' . $product->project->url) }}" class="text-blue-600 font-medium hover:underline">
                        {{ $product->project->name }}
                    </a>
                </div>
            @endif

            {{-- Short Description --}}
            <p class="text-gray-700 mb-4">{{ $product->short_desc }}</p>

            {{-- Pricing --}}
            <div class="text-xl font-semibold text-green-600 mb-4">
                ${{ number_format($product->full_price, 2) }}
                @if ($product->chargeable)
                    <span class="text-sm text-gray-500">(Incl. Tax)</span>
                @endif
            </div>

            {{-- Status --}}
            <div class="mb-2">
                <strong>Status:</strong>
                <span class="{{ $product->status ? 'text-green-500' : 'text-red-500' }}">
                    {{ $product->status ? 'Available' : 'Unavailable' }}
                </span>
            </div>

            <div class="mb-2">
                <strong>Views:</strong> {{ $product->views }}
            </div>

            {{-- Metadata --}}
            @if (!empty($product->metadata) && is_array($product->metadata))
                <div class="mb-4">
                    <strong>Metadata:</strong>
                    <ul class="list-disc ml-6">
                        @foreach ($product->metadata as $key => $value)
                            <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- API URL --}}
            @if ($product->api_url)
                <div class="mt-6">
                    <a href="{{ $product->api_url }}" target="_blank" class="text-blue-600 hover:underline">
                        View API Documentation
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Full Description --}}
    <div class="prose max-w-full">
        {!! nl2br(e($product->desc)) !!}
    </div>

    {{-- Available Plans --}}
    @if ($product->plans->count())
        <div>
            <h2 class="text-2xl font-bold mb-6">Available Plans</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($product->plans as $plan)
                    <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                        <h3 class="text-lg font-semibold mb-2">{{ $plan->name }}</h3>
                        <p class="text-gray-700 mb-2">{{ $plan->desc }}</p>

                        <div class="text-green-600 font-bold mb-2">
                            ${{ number_format($plan->price, 2) }}
                        </div>

                        <div class="text-sm text-gray-600 mb-2">
                            @if($plan->is_recommended)
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Recommended</span>
                            @endif
                            @if($plan->is_enterprise)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">Enterprise</span>
                            @endif
                        </div>

                        @if (!empty($plan->features) && is_array($plan->features))
                            <ul class="list-disc text-sm ml-5 mt-3 text-gray-700">
                                @foreach ($plan->features as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

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
