<x-layout.app-layout>
    <div class="w-full h-full">
        <div class="relative w-full h-screen">
            {{-- Three.js Parallax 360 background --}}
            <livewire:components.animation.paralax-background />

            <div class="absolute inset-0 flex flex-col z-10">

                {{-- Topbar --}}
                <nav class="h-12 w-full bg-black/50 text-white flex items-center justify-center md:justify-between px-4">
                    <div class="text-xl font-semibold">{{ config('app.name') }}</div>
                    {{-- Desktop Menu --}}
                    <ul class="hidden md:flex space-x-6">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Services</a></li>
                        <li class="font-bold"><a href="#">Products</a></li>
                        <li><a href="#">Packages</a></li>
                        <li><a href="#">Lab</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Login</a></li>
                    </ul>
                </nav>

                <div class="flex flex-1 overflow-hidden">
                    {{-- Sidebar (Mobile only) --}}
                    <aside class="w-64 bg-black/60 text-white p-6 space-y-4 backdrop-blur-sm block md:hidden">
                        <ul class="space-y-4 text-lg">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">About</a></li>
                            <li><a href="#">Services</a></li>
                            <li class="font-bold"><a href="#">Products</a></li>
                            <li><a href="#">Packages</a></li>
                            <li><a href="#">Lab</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Login</a></li>
                        </ul>
                    </aside>

                    {{-- Main content area --}}
                    <main class="flex-1 p-6 overflow-y-auto text-gray-800 dark:text-gray-200">
                        {{ $slot }}
                    </main>
                </div>

            </div>
        </div>
    </div>
</x-layout.app-layout>
