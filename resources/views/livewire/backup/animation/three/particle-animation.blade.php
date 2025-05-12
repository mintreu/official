<div class="px-10 py-20 bg-green-200 w-full">
    Demo
    <div id="particle-canvas" class="w-full h-96"></div> <!-- Set a fixed height -->
</div>

@push('script')
    <script type="module">
        // Ensure Animate is assigned to window.Anima
        const animaInstance = new window.Anima();

        // Initialize the animation on the specified div
        animaInstance.init('particle-canvas');

        // Set additional parameters
        animaInstance.setDistance(800);
        animaInstance.setSphereGeometry(); // Correct method name to set sphere geometry
        animaInstance.setParticle(2, 0xff44ff); // Initialize particles

        // Create the particles and add to the scene
        const renderParent = animaInstance.rr(animaInstance.particles);
        animaInstance.addToScene(renderParent);

        // Set camera position
        animaInstance.zCamera(400);

        // Handle mouse movements
        document.addEventListener('mousemove', (event) => {
            animaInstance.onMouseMove(event);
        });

        // Start the animation loop
        animaInstance.animate();

        // Handle window resize
        window.addEventListener('resize', () => {
            animaInstance.resize_event();
        });
    </script>
@endpush
