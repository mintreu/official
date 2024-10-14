<!-- resources/views/livewire/madbox-homepage.blade.php -->
<div class="h-screen w-full flex justify-center items-center overflow-hidden bg-gray-900">
    <div id="madbox-three-container" class="w-full h-full"></div>

    @push('scripts')
        <!-- Three.js Library -->
        <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/build/three.min.js"></script>
        <!-- OrbitControls -->
        <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
        <!-- GLTFLoader (if you plan to load models) -->
        <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Check if Three.js is loaded
                if (!window.THREE) {
                    console.error('Three.js not loaded.');
                    return;
                }

                // Initialize the container
                const container = document.getElementById('madbox-three-container');
                if (!container) {
                    console.error('Container #madbox-three-container not found.');
                    return;
                }

                // Scene Setup
                const scene = new THREE.Scene();

                // Camera Setup
                const camera = new THREE.PerspectiveCamera(
                    75,
                    container.clientWidth / container.clientHeight,
                    0.1,
                    1000
                );
                camera.position.set(0, 0, 10); // Adjust as needed

                // Renderer Setup
                const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
                renderer.setSize(container.clientWidth, container.clientHeight);
                renderer.setPixelRatio(window.devicePixelRatio);
                container.appendChild(renderer.domElement);

                // Controls Setup
                const controls = new THREE.OrbitControls(camera, renderer.domElement);
                controls.enableZoom = false;
                controls.enablePan = false;
                controls.rotateSpeed = 0.3;

                // Add Lights
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
                scene.add(ambientLight);

                const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
                directionalLight.position.set(10, 10, 10);
                scene.add(directionalLight);

                // Load Texture
                const loader = new THREE.TextureLoader();
                const texture = loader.load('{{ asset("images/panoramic.jpg") }}', (loadedTexture) => {
                    loadedTexture.minFilter = THREE.LinearFilter;
                    loadedTexture.magFilter = THREE.LinearFilter;
                    loadedTexture.colorSpace = THREE.SRGBColorSpace; // Ensure compatibility
                }, undefined, (error) => {
                    console.error('Error loading texture:', error);
                });

                // Create Sphere Geometry with Texture
                const geometry = new THREE.SphereGeometry(500, 60, 40);
                geometry.scale(-1, 1, 1); // Invert the geometry to view from inside
                const material = new THREE.MeshBasicMaterial({ map: texture });
                const sphere = new THREE.Mesh(geometry, material);
                scene.add(sphere);

                // Handle Window Resize
                window.addEventListener('resize', onWindowResize, false);

                function onWindowResize() {
                    camera.aspect = container.clientWidth / container.clientHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(container.clientWidth, container.clientHeight);
                }

                // Animation Loop
                let lastTime = 0;
                const rotationSpeed = 0.00005;

                function animate(time) {
                    requestAnimationFrame(animate);

                    const delta = time - lastTime;
                    lastTime = time;

                    // Rotate the sphere slowly
                    sphere.rotation.y += rotationSpeed * delta;

                    controls.update();
                    renderer.render(scene, camera);
                }

                animate(0);
            });
        </script>
    @endpush
</div>
