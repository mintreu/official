<div class="h-screen w-full flex justify-center items-center overflow-hidden">
    <div id="paralax-background-container" class="w-full h-full"></div>
</div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('paralax-background-container');

            const scene = new window.THREE.Scene();
            const camera = new window.THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
            const renderer = new window.THREE.WebGLRenderer();
            renderer.setSize(container.clientWidth, container.clientHeight);
            container.appendChild(renderer.domElement);

            const loader = new window.THREE.TextureLoader();
            const texture = loader.load('{{ asset("images/panoramic.jpg") }}', (loadedTexture) => {
                loadedTexture.minFilter = window.THREE.LinearFilter;
                loadedTexture.magFilter = window.THREE.LinearFilter;
                loadedTexture.colorSpace = window.THREE.SRGBColorSpace; // Updated property
            });

            const geometry = new window.THREE.SphereGeometry(500, 60, 40);
            geometry.scale(-1, 1, 1);
            const material = new window.THREE.MeshBasicMaterial({ map: texture });
            const sphere = new window.THREE.Mesh(geometry, material);
            scene.add(sphere);

            camera.position.set(0, 0, 0.1);
            const controls = new window.OrbitControls(camera, renderer.domElement);
            controls.enableZoom = false;
            controls.enablePan = false;
            controls.rotateSpeed = 0.3;

            function onWindowResize() {
                camera.aspect = container.clientWidth / container.clientHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(container.clientWidth, container.clientHeight);
            }

            window.addEventListener('resize', onWindowResize, false);

            let lastTime = 0;
            const rotationSpeed = 0.00005;

            function animate(time) {
                const delta = time - lastTime;
                lastTime = time;
                requestAnimationFrame(animate);
                sphere.rotation.y += rotationSpeed * delta;
                controls.update();
                renderer.render(scene, camera);
            }

            animate(0);

            // Visibility check function
            function checkVisibility() {
                const isVisible = container.offsetParent !== null; // Check if the container is visible
                if (!isVisible) {
                    alert('The background is not visible. Please check your navigation or refresh the page.');
                }
            }

            // Check visibility when navigating
            window.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    checkVisibility();
                }
            });

            // Optional: check on load
            checkVisibility();
        });
    </script>
@endpush
