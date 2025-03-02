<div class="h-screen w-full flex justify-center items-center overflow-hidden">
    <div id="demo-canvas-container" class="w-full h-full"></div>
</div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize and configure the Animate class
            const animation = new window.Anima();
            // Initialize the animation on the specified div
            animaInstance.init('demo-canvas-container');
            // Start animation
            animation.animate();
        });
    </script>
@endpush
