@php
$intaractive = true;
$theme = $intaractive ? 'layout.default.default-paralax-theme' : 'layout.default.default-theme';

@endphp

<x-dynamic-component :component="$theme">


    <div class="w-full h-full  p-8 ">

        <div class="flex flex-col justify-center items-center w-full h-full text-white rounded-3xl">
            <div class="flex flex-col gap-3 justify-center items-center rounded-3xl p-10 ">
                <h1 class="text-6xl md:text-7xl lg:text-9xl font-semibold font-comfort ">Mintreu</h1>
                <h3 class=" text-3xl font-raleway text-center">
                    Fueling your online success with powerful web development
                </h3>

                <div class="mt-5 mb-2 py-1 flex-grow sm:flex-col gap-4">
                    <a wire:navigate href="{{ url('/app') }}" class="bg-blue-700 mx-1 px-10 sm:px-6 py-3 rounded-3xl text-xl shadow-black shadow-lg">Get Started</a>
                    <a wire:navigate href="{{ url('/web') }}" class="bg-green-700 mx-1 px-10 sm:px-6 py-3 rounded-3xl text-xl shadow-black shadow-lg">Know More</a>
                </div>
            </div>



{{--            <div class="w-full">--}}
{{--                hh--}}



{{--                            <livewire:animation.mad-world />--}}

{{--                <livewire:animation.shape-animation />--}}
{{--            </div>--}}


        </div>

    </div>




</x-dynamic-component>
