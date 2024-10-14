<div class="h-screen w-full relative">
    <div id="madbox-scene" class="w-full h-full"></div>

    <!-- Optional Overlay Content -->
    <div class="absolute top-0 left-0 w-full h-full flex flex-col justify-center items-center pointer-events-none">
        <h1 class="text-white text-4xl font-bold">Welcome to Madbox</h1>
        <p class="text-white mt-4">Interactive 3D Experience</p>
    </div>

    @push('scripts')
        <!-- Include Three.js Library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
        <!-- Include OrbitControls -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/examples/js/controls/OrbitControls.js"></script>
        <!-- Include GLTFLoader for loading 3D models -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/examples/js/loaders/GLTFLoader.js"></script>

        <script>
            document.addEventListener('livewire:load', function () {
                // Three.js Initialization
                console.log('Initializing Madbox-inspired Three.js scene.');

                const container = document.getElementById('madbox-scene');
                const scene = new THREE.Scene();

                // Add ambient light
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
                scene.add(ambientLight);

                // Add directional light
                const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
                directionalLight.position.set(10, 10, 10);
                scene.add(directionalLight);

                // Camera Setup
                const camera = new THREE.PerspectiveCamera(
                    75,
                    container.clientWidth / container.clientHeight,
                    0.1,
                    1000
                );
                camera.position.set(0, 2, 5); // Adjusted position for better view

                // Renderer Setup
                const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
                renderer.setSize(container.clientWidth, container.clientHeight);
                renderer.setPixelRatio(window.devicePixelRatio);
                container.appendChild(renderer.domElement);

                // Controls Setup
                const controls = new THREE.OrbitControls(camera, renderer.domElement);
                controls.enableDamping = true;
                controls.dampingFactor = 0.05;

                // Responsive Design
                window.addEventListener('resize', () => {
                    camera.aspect = container.clientWidth / container.clientHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(container.clientWidth, container.clientHeight);
                });

                // Load 3D Model (GLTF/GLB format)
                const loader = new THREE.GLTFLoader();
                let model;
                loader.load(
                    'https://your-model-url/model.glb', // Replace with your 3D model URL
                    function (gltf) {
                        model = gltf.scene;
                        model.scale.set(2, 2, 2); // Adjust scale as needed
                        scene.add(model);
                        console.log('3D model loaded.');
                    },
                    undefined,
                    function (error) {
                        console.error('An error happened while loading the model:', error);
                    }
                );

                // Add interactive elements (e.g., clickable objects)
                const interactiveObjects = [];

                // Example: Adding spheres that respond to clicks
                const sphereGeometry = new THREE.SphereGeometry(0.5, 32, 32);
                const sphereMaterial = new THREE.MeshStandardMaterial({ color: 0xff0000 });
                for (let i = 0; i < 5; i++) {
                    const sphere = new THREE.Mesh(sphereGeometry, sphereMaterial.clone());
                    sphere.position.set(Math.random() * 10 - 5, Math.random() * 5, Math.random() * 10 - 5);
                    scene.add(sphere);
                    interactiveObjects.push(sphere);
                }

                // Raycaster for detecting clicks
                const raycaster = new THREE.Raycaster();
                const mouse = new THREE.Vector2();

                container.addEventListener('click', (event) => {
                    // Calculate mouse position in normalized device coordinates
                    mouse.x = (event.clientX / container.clientWidth) * 2 - 1;
                    mouse.y = -(event.clientY / container.clientHeight) * 2 + 1;

                    // Update the picking ray with the camera and mouse position
                    raycaster.setFromCamera(mouse, camera);

                    // Calculate objects intersecting the picking ray
                    const intersects = raycaster.intersectObjects(interactiveObjects);

                    if (intersects.length > 0) {
                        const clickedObject = intersects[0].object;
                        // Example interaction: Toggle color
                        clickedObject.material.color.set(
                            clickedObject.material.color.getHex() === 0xff0000 ? 0x0000ff : 0xff0000
                        );
                        // You can emit a Livewire event if needed
                    @this.emit('objectClicked', clickedObject.uuid);
                    }
                });

                // Animation Loop
                function animate() {
                    requestAnimationFrame(animate);

                    // Update controls
                    controls.update();

                    // Example animation: Rotate the model
                    if (model) {
                        model.rotation.y += 0.005;
                    }

                    renderer.render(scene, camera);
                }

                animate();
                console.log('Animation loop started.');
            });
        </script>
    @endpush
</div>
