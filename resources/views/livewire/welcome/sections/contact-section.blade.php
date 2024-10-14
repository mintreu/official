<div class="homepage-section-content bg-black/50">
    <h1 class="text-4xl font-bold mb-4">Contact Us</h1>
    <p class="text-lg"><h2>We would love to hear from you!</h2>
        Whether you have a question, need assistance, or are ready to start a project, please get in touch with us:</p>

    @if(session()->has('message'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="mt-6 flex flex-col gap-4 text-white">
        <input type="text" placeholder="Your Name" wire:model="name" class="p-2 bg-transparent border border-gray-300 rounded-lg focus:outline-none focus:border-fuchsia-500 transition-colors duration-300" required>
        <input type="email" placeholder="Your Email" wire:model="email" class="p-2 bg-transparent border border-gray-300 rounded-lg focus:outline-none focus:border-fuchsia-500 transition-colors duration-300" required>
        <input type="text" placeholder="Your Contact Number" wire:model="contact" class="p-2 bg-transparent border border-gray-300 rounded-lg focus:outline-none focus:border-fuchsia-500 transition-colors duration-300">
        <input type="text" placeholder="Subject" wire:model="subject" class="p-2 bg-transparent border border-gray-300 rounded-lg focus:outline-none focus:border-fuchsia-500 transition-colors duration-300">
        <textarea placeholder="Your Message" wire:model="message" class="p-2 bg-transparent border border-gray-300 rounded-lg h-32 focus:outline-none focus:border-fuchsia-500 transition-colors duration-300" required></textarea>
        <button type="submit" class="bg-fuchsia-700 text-white py-2 rounded-lg hover:bg-fuchsia-800 transition-colors duration-300">Send Message</button>
    </form>

    <div class="mt-6 text-lg">
        <p><strong>Our Office:</strong></p>
        <p>1234 Web Dev Avenue, Suite 100, Tech City, Country</p>
        <p><strong>Email:</strong> info@mintreu.com</p>
        <p><strong>Phone:</strong> +123 456 7890</p>
        {{-- Social media links can be uncommented and updated as needed --}}
        {{--
        <div class="flex justify-center gap-4 mt-4">
            <a href="https://facebook.com/mintreu" target="_blank">@svg('social-icons.facebook','w-6 h-6')</a>
            <a href="https://twitter.com/mintreu" target="_blank">@svg('social-icons.twitter','w-6 h-6')</a>
            <a href="https://linkedin.com/company/mintreu" target="_blank">@svg('social-icons.linkedin','w-6 h-6')</a>
            <a href="https://instagram.com/mintreu" target="_blank">@svg('social-icons.instagram','w-6 h-6')</a>
        </div>
        --}}
    </div>
</div>
