<div class="h-screen w-full flex justify-center items-center overflow-hidden bg-white">
    Animation
    <div id="three-dev-deploy-container" class="w-full h-full"></div>
</div>

@script

<script>
    document.addEventListener('livewire:initialized', function () {
        // Three.js Initialization
        console.log('Three.js is initializing.');

        // Scene Setup
        const container = document.getElementById('three-dev-deploy-container');
        const scene = new THREE.Scene();

        // Camera Setup
        const camera = new THREE.PerspectiveCamera(
            75,
            container.clientWidth / container.clientHeight,
            0.1,
            1000
        );
        camera.position.z = 5; // Position the camera away

        // Renderer Setup
        const renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: false // Opaque background
        });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setPixelRatio(window.devicePixelRatio);
        container.appendChild(renderer.domElement);

        console.log('Renderer initialized.');

        // Set the clear color to black
        renderer.setClearColor(0x000000, 1); // Black color

        // Animation Loop
        function animate() {
            requestAnimationFrame(animate);
            renderer.render(scene, camera); // Render the scene from the perspective of the camera
        }

        animate(); // Start the animation loop
        console.log('Animation loop started.');

        // Responsive Handling
        window.addEventListener('resize', () => {
            camera.aspect = container.clientWidth / container.clientHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(container.clientWidth, container.clientHeight);
        });
    });
</script>

@endscript
