<div class="h-screen w-full flex justify-center items-center overflow-hidden bg-white">
    <div id="three-dev-deploy-container" class="w-full h-full"></div>
</div>

@script
<script>
    document.addEventListener('livewire:initialized', function () {
        // Three.js Initialization
        console.log('Three.js for DevDeployComponent is initializing.');

        // Scene Setup
        const container = document.getElementById('three-dev-deploy-container');
        const scene = new THREE.Scene();

        // Camera Setup
        const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
        camera.position.z = 5; // Position the camera

        // Renderer Setup
        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: false });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setClearColor(0x000000, 1); // Black background
        container.appendChild(renderer.domElement);

        console.log('Renderer initialized for DevDeployComponent.');

        // Create Shapes with Different Colors and Sizes
        function createShape(type, color, size, position) {
            let geometry;

            switch (type) {
                case 'box':
                    geometry = new THREE.BoxGeometry(size, size, size);
                    break;
                case 'sphere':
                    geometry = new THREE.SphereGeometry(size, 32, 32);
                    break;
                case 'cylinder':
                    geometry = new THREE.CylinderGeometry(size, size, size * 2, 8);
                    break;
                case 'cone':
                    geometry = new THREE.ConeGeometry(size, size * 2, 8);
                    break;
                default:
                    geometry = new THREE.BoxGeometry(size, size, size);
            }

            const material = new THREE.MeshStandardMaterial({ color: color });
            const shape = new THREE.Mesh(geometry, material);
            shape.position.set(position.x, position.y, position.z);
            scene.add(shape);
            return shape;
        }

        // Add Multiple Shapes to the Scene
        const shapes = [
            createShape('box', 0xff0000, 1, { x: -2, y: 0, z: 0 }),   // Red Box
            createShape('sphere', 0x00ff00, 0.8, { x: 0, y: 0, z: 0 }), // Green Sphere
            createShape('cylinder', 0x0000ff, 0.5, { x: 2, y: 0, z: 0 }), // Blue Cylinder
            createShape('cone', 0xffff00, 0.6, { x: 0, y: -2, z: 0 })   // Yellow Cone
        ];

        // Lighting Setup
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
        const pointLight = new THREE.PointLight(0xffffff, 1);
        pointLight.position.set(5, 5, 5);
        scene.add(ambientLight);
        scene.add(pointLight);

        // Animation Loop
        function animate() {
            requestAnimationFrame(animate);

            // Rotate each shape
            shapes.forEach(shape => {
                shape.rotation.x += 0.01;
                shape.rotation.y += 0.01;
            });

            renderer.render(scene, camera); // Render the scene
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
