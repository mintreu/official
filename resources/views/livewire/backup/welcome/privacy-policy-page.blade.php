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
                <h1 class="text-4xl font-bold mb-6">Privacy Policy</h1>


              <div class="text-justify">
                  <p class="text-lg mb-6">
                      At Mintreu, we prioritize your privacy and are dedicated to protecting your personal data. This Privacy Policy outlines how we collect, use, disclose, and safeguard your information when you visit our website or utilize our services, including our API offerings and web development services.
                  </p>

                  <h2 class="text-2xl font-semibold mb-4">1. Information We Collect</h2>
                  <p class="text-lg mb-6">
                      We collect various types of information to enhance your experience and improve our services:
                  </p>
                  <ul class="pl-5 mb-6">
                      <li class="text-lg mb-2 "><strong>Personal Data:</strong> This includes your name, email address, phone number, and other contact information.</li>
                      <li class="text-lg mb-2 "><strong>Technical Data:</strong> We gather information such as your IP address, browser type, operating system, and device details.</li>
                      <li class="text-lg mb-2 "><strong>Usage Data:</strong> Details about your interaction with our website and services, including the pages you visit and features you use.</li>
                      <li class="text-lg mb-2 "><strong>Cookies and Tracking Technologies:</strong> We use cookies and similar technologies to collect data that helps us improve our website's functionality and user experience.</li>
                      <li class="text-lg "><strong>Client Customer Data:</strong> Information provided by our clients about their customers, collected through our API services. This may include personal data that our clients choose to submit.</li>
                  </ul>

                  <h2 class="text-2xl font-semibold mb-4">2. How We Use Your Information</h2>
                  <p class="text-lg mb-6">
                      We use the collected information for the following purposes:
                  </p>
                  <ul class=" pl-10 mb-6">
                      <li class="text-lg mb-2">To provide, operate, and maintain our services.</li>
                      <li class="text-lg mb-2">To improve, personalize, and expand our offerings.</li>
                      <li class="text-lg mb-2">To understand and analyze how you use our website and services.</li>
                      <li class="text-lg mb-2">To communicate with you, including customer service and support, updates, and promotional messages.</li>
                      <li class="text-lg mb-2">To process transactions and send related information.</li>
                      <li class="text-lg">To prevent fraudulent activities and ensure the security of our services.</li>
                  </ul>

                  <h2 class="text-2xl font-semibold mb-4">3. Data Security</h2>
                  <p class="text-lg mb-6">
                      We implement robust security measures to protect your data from unauthorized access, alteration, disclosure, or destruction. However, no data transmission over the internet or method of electronic storage is completely secure. While we strive to protect your personal data, we cannot guarantee its absolute security.
                  </p>

                  <h2 class="text-2xl font-semibold mb-4">4. Sharing and Disclosure of Information</h2>
                  <p class="text-lg mb-6">
                      We do not sell or share your personal information, including client customer data, with third parties for their marketing purposes. We may share your information with trusted partners who assist us in operating our website, conducting our business, or servicing you, provided they agree to keep this information confidential.
                  </p>

                  <h2 class="text-2xl font-semibold mb-4">5. Your Rights and Choices</h2>
                  <p class="text-lg mb-6">
                      You have the right to access, update, or delete your personal information. You can also object to the processing of your data or request data portability. To exercise these rights, please contact us at the email provided below.
                  </p>

                  <h2 class="text-2xl font-semibold mb-4">6. Changes to This Privacy Policy</h2>
                  <p class="text-lg mb-6">
                      We may update this Privacy Policy from time to time to reflect changes in our practices or for legal and regulatory reasons. Any changes will be posted on this page, and we encourage you to review it periodically.
                  </p>

                  <h2 class="text-2xl font-semibold mb-4">7. Contact Us</h2>
                  <p class="text-lg mb-6">
                      If you have any questions or concerns about this Privacy Policy, please contact us at:
                  </p>
                  <p class="text-lg mb-6">
                      <strong>Email:</strong> <a href="mailto:info@mintreu.com" class="underline">info@mintreu.com</a><br>
                      <strong>Address:</strong> 1234 Web Dev Avenue, Suite 100, Tech City, Country<br>
                      <strong>Phone:</strong> +123 456 7890
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
