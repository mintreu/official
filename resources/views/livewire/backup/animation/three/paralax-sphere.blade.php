<div class="h-screen w-full flex justify-center items-center overflow-hidden">
    <div id="paralax-sphere-container" class="w-full h-full"></div>
</div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize and configure the Animate class
            const animation = new window.Anima();

            // Set up scene components
            animation.init('paralax-sphere-container');
            animation.setSphereGeometry();
            animation.applyTexture('{{ asset("images/panoramic.jpg") }}');
            animation.setControls();
            animation.resize_event();

            // Handle visibility on page changes
            function checkVisibility() {
                animation.checkVisibility('paralax-background-container');
            }

            // Visibility change listener
            window.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible') {
                    checkVisibility();
                }
            });

            // Start animation
            animation.animate();
        });
    </script>
@endpush
