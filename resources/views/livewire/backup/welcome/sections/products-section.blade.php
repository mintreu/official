<div class="homepage-section-content">
    <h1 class="text-4xl font-bold mb-6">Our Products</h1>
    <p class="text-lg mb-6">
        Explore our range of innovative solutions designed to streamline your business processes and drive growth. Here
        are some of our featured products:
    </p>

    <div class="w-full py-6 flex gap-6">
        <!-- Category Select -->
        <div class="w-1/3">
            <select wire:model="selectedCategory" class="w-full px-3 py-1 border rounded bg-transparent">
                <option class="px-1 bg-black/50" value="">Select a category</option>
                @foreach($categories as $category)
                    <option class="px-1 bg-black/50" value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Spacer for layout -->
        <div class="grow"></div>

        <!-- Product Type Select using ProductTypeCast Enum -->
        <div class="w-1/3">
            <select wire:model="selectedProductType" class="w-full px-3 py-1 border rounded bg-transparent">
                <option class="px-1 bg-black/50" value="">Select a product type</option>
                @foreach(\App\Models\Enums\Product\ProductTypeCast::cases() as $type)
                    <option class="px-1 bg-black/50" value="{{ $type->value }}">{{ $type->getLabel() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @isset($records)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
            @foreach($records as $record)
                <!-- Product Card -->
                <div class="bg-black/50 p-6 rounded-lg flex flex-col shadow-black shadow-xl py-3 px-6">
                    <div class="w-full h-32 bg-black rounded-lg mb-4 flex items-center justify-center">
                        <img src="{{ $record->getFirstMediaUrl('displayImage') }}" alt="{{ $record->name }}"
                             class="object-cover rounded-lg h-40 border-4 border-fuchsia-700 shadow-black shadow-lg"/>
                    </div>
                    <h3 class="text-xl font-semibold mt-2 mb-2">{{ $record->name }}</h3>
                    <p>{{ $record->short_desc }}</p>
                    <a href="{{ route('welcome.project.view', ['project' => $record->url]) }}"
                       class="my-1 mx-auto px-6 py-1 border-2 rounded-3xl bg-black/50 flex gap-1 items-center hover:bg-blue-600 hover:border-fuchsia-600">
                        @svg('heroicon-s-eye','w-8 h-8 hover:animate-pulse') View
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $records->links() }}
        </div>
    @endisset
</div>
