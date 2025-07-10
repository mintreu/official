@section('title', $title ?? config('app.name'))

<div x-data="{ isOpen: false, activeSection: 'home' }" class="h-full w-full relative">

    {{-- Navigation (Responsive: Sidebar on Mobile, Top Navbar on Desktop) --}}
    <div class="w-full absolute z-20 h-10 bg-transparent px-6 py-3 flex justify-center gap-2">
        <div class="grow flex gap-4">
            <a href="{{ url('/') }}">
                <h1 class="text-2xl font-semibold">Mintreu</h1>
            </a>
            {{--            <button @click="isOpen = true" class="md:hidden">--}}
            {{--                @svg('heroicon-m-bars-3', 'w-6 h-6 mt-1.5')--}}
            {{--            </button>--}}
        </div>

        {{--        <div class="hidden md:block">--}}
        {{--            <div class="flex flex-row gap-4">--}}
        {{--                <a href="{{url('/')}}" class="flex gap-2 items-center">@svg('heroicon-m-home', 'w-6 h-6') Home</a>--}}
        {{--                <a href="{{url('/').'#about'}}"  class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') About</a>--}}
        {{--                <a href="#services" @click.prevent="activeSection = 'services'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Services</a>--}}
        {{--                <a href="#projects" @click.prevent="activeSection = 'projects'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Projects</a>--}}
        {{--                <a href="#pricing" @click.prevent="activeSection = 'pricing'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Pricing</a>--}}
        {{--                <a href="#contact" @click.prevent="activeSection = 'contact'" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Contact</a>--}}
        {{--                <a href="{{url('/app')}}" class="flex gap-2 items-center">@svg('heroicon-c-folder', 'w-6 h-6') Login</a>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    </div>




    {{-- Main Content --}}
    <div class="h-screen w-full relative flex justify-center items-center overflow-hidden">




        <div class="homepage-section">

            <div class="homepage-section-content bg-black/50 p-5">
                <h1 class="text-4xl font-bold mb-6">Terms & Conditions</h1>


                <div class="text-justify">
                    <p class="text-lg text-gray-700 mb-4">
                        Welcome to Mintreu. By using our services, you agree to the following terms and conditions.
                    </p>
                    <h2 class="text-2xl font-semibold mb-2 text-blue-800">1. Acceptance of Terms</h2>
                    <p class="text-lg text-gray-700 mb-4">
                        By accessing or using our services, you agree to comply with and be bound by these terms and conditions.
                    </p>
                    <h2 class="text-2xl font-semibold mb-2 text-blue-800">2. User Responsibilities</h2>
                    <p class="text-lg text-gray-700 mb-4">
                        You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account.
                    </p>
                    <h2 class="text-2xl font-semibold mb-2 text-blue-800">3. Intellectual Property</h2>
                    <p class="text-lg text-gray-700 mb-4">
                        All content and materials provided through our services are the property of Mintreu or its licensors and are protected by intellectual property laws.
                    </p>
                    <h2 class="text-2xl font-semibold mb-2 text-blue-800">4. Limitation of Liability</h2>
                    <p class="text-lg text-gray-700 mb-4">
                        We are not liable for any indirect, incidental, or consequential damages arising out of or in connection with your use of our services.
                    </p>
                    <h2 class="text-2xl font-semibold mb-2 text-blue-800">5. Changes to Terms</h2>
                    <p class="text-lg text-gray-700 mb-4">
                        We may update these terms and conditions from time to time. The updated terms will be posted on this page, and your continued use of our services signifies your acceptance of the changes.
                    </p>
                    <h2 class="text-2xl font-semibold mb-2 text-blue-800">6. Contact Us</h2>
                    <p class="text-lg text-gray-700">
                        If you have any questions about these Terms & Conditions, please contact us at <a href="mailto:info@mintreu.com" class="text-blue-700 underline">info@mintreu.com</a>.
                    </p>
                </div>


                <div>
                    <a class="px-6 py-2 bg-green-500 text-white rounded-2xl shadow-lg shadow-black" href="{{url('/')}}">Back to Home</a>
                </div>

            </div>

        </div>







        {{-- Three js Background--}}
        <footer class="absolute bottom-0 right-1">
            <p class="text-sm">&copy; 2024 Mintreu. All rights reserved. |
                <a href="{{url('privacy')}}" class="text-blue-400 underline font-semibold">Privacy Policy</a> |
                <a href="{{url('terms')}}" class="text-blue-400 hover:underline">Terms of Service</a> |
                <a href="{{url('faqs')}}" class="text-blue-400 hover:underline">Faqs</a>
            </p>
        </footer>
        <livewire:welcome.welcome-screen />
    </div>

</div>
