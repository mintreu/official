@php
$intaractive = auth()->check();
$theme = $intaractive ? 'layout.default.default-theme' : 'layout.default.default-paralax-theme';

@endphp

<x-dynamic-component :component="$theme">


    <div class="">

        <div class="flex flex-col justify-center items-center  py-72 ">
            <h1 class="text-6xl md:text-7xl lg:text-9xl font-semibold font-comfort">Mintreu</h1>
            <span class="text-md md:text-lg lg:text-xl font-raleway">
              Fueling your online success with powerful web development
            </span>

            <div class="mt-5 mb-2 py-1 flex-grow sm:flex-col gap-4">
                <a wire:navigate href="{{ url('/app') }}" class="bg-blue-700 mx-1 px-2 sm:px-4 py-2 rounded-3xl ">Get Started</a>
                <a wire:navigate href="{{ url('/web') }}" class="bg-green-700 mx-1 px-2 sm:px-4 py-2 rounded-3xl ">Know More</a>
            </div>
        </div>


        <div class="flex flex-col justify-center items-center  py-72">
            <h1 class="text-6xl md:text-7xl lg:text-9xl font-semibold font-comfort">Mintreu</h1>
            <span class="text-md md:text-lg lg:text-xl font-raleway">
              Fueling your online success with powerful web development
            </span>

            <div class="mt-5 mb-2 py-1 flex-grow sm:flex-col gap-4">
                <a wire:navigate href="{{ url('/app') }}" class="bg-blue-700 mx-1 px-2 sm:px-4 py-2 rounded-3xl ">Get Started</a>
                <a wire:navigate href="{{ url('/web') }}" class="bg-green-700 mx-1 px-2 sm:px-4 py-2 rounded-3xl ">Know More</a>
            </div>
        </div>


    </div>




</x-dynamic-component>
