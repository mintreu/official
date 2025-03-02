@php
$intaractive = auth()->check();
$theme = $intaractive ? config('webapp.default.theme.auth') : config('webapp.default.theme.global');

@endphp

<x-dynamic-component :component="$theme">


    <div class="flex flex-col justify-center items-center w-full  ">

{{--        <div class="flex flex-col justify-center items-center  py-72 ">--}}
{{--            <h1 class="text-6xl md:text-7xl lg:text-9xl font-semibold font-comfort">Mintreu</h1>--}}
{{--            <span class="text-md md:text-lg lg:text-xl font-raleway">--}}
{{--              Fueling your online success with powerful web development--}}
{{--            </span>--}}

{{--            <div class="mt-5 mb-2 py-1 flex-grow sm:flex-col gap-4">--}}
{{--                <a wire:navigate href="{{ url('/app') }}" class="bg-blue-700 mx-1 px-2 sm:px-4 py-2 rounded-3xl ">Get Started</a>--}}
{{--                <a wire:navigate href="{{ url('/web') }}" class="bg-green-700 mx-1 px-2 sm:px-4 py-2 rounded-3xl ">Know More</a>--}}
{{--            </div>--}}
{{--        </div>--}}


{{--        <div class="flex flex-col justify-center items-center  py-72">--}}
{{--            <h1 class="text-6xl md:text-7xl lg:text-9xl font-semibold font-comfort">Mintreu</h1>--}}
{{--            <span class="text-md md:text-lg lg:text-xl font-raleway">--}}
{{--              Fueling your online success with powerful web development--}}
{{--            </span>--}}

{{--            <div class="mt-5 mb-2 py-1 flex-grow sm:flex-col gap-4">--}}
{{--                <a wire:navigate href="{{ url('/app') }}" class="bg-blue-700 mx-1 px-2 sm:px-4 py-2 rounded-3xl ">Get Started</a>--}}
{{--                <a wire:navigate href="{{ url('/web') }}" class="bg-green-700 mx-1 px-2 sm:px-4 py-2 rounded-3xl ">Know More</a>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <livewire:animation.paralax-background />--}}

        <h1 class=" text-center text-5xl font-semibold"> Welcome {{config('app.name')}}</h1>

        <livewire:animation.three.paralax-sphere />
        <livewire:animation.three.particle-animation />


        <livewire:animation.three.demo-animation />

    </div>





</x-dynamic-component>
