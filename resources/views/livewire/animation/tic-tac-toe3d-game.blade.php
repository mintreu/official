<div class="h-screen w-full flex justify-center items-center m-10 p-5">
    <div id="ping-pong-ball" class="w-full h-full"></div>
</div>

@push('script')
    <script>
        document.addEventListener('livewire:initialized', function () {
            // Three.js Initialization
            console.log('Three.js is initializing.');

            // Scene Setup
            const container = document.getElementById('ping-pong-ball');
            const scene = new THREE.Scene();

            // Camera Setup
            const camera = new THREE.PerspectiveCamera(
                75,
                container.clientWidth / container.clientHeight,
                0.1,
                1000
            );
            camera.position.z = 10; // Move the camera back

            // Renderer Setup
            const renderer = new THREE.WebGLRenderer({
                antialias: true,
                alpha: true // Transparent background
            });
            renderer.setSize(container.clientWidth, container.clientHeight);
            renderer.setPixelRatio(window.devicePixelRatio);
            container.appendChild(renderer.domElement);

            console.log('Renderer initialized.');

            // Array to hold all balls
            let balls = [];

            // Create and add the initial ball
            let initialBall = createBall(Math.random() * 0xffffff, 0.5);
            scene.add(initialBall);
            balls.push(initialBall); // Add the initial ball to the balls array

            // Animation Loop
            function animate() {
                requestAnimationFrame(animate);

                // Update all balls' positions
                for (let ball of balls) {
                    ball.position.add(ball.velocity.clone());

                    // Check for collisions with edges
                    if (Math.abs(ball.position.x) >= (container.clientWidth / 200) - 0.5) {
                        ball.velocity.x *= -1; // Reverse x direction
                        changeColor(ball); // Change color on collision
                    }

                    if (Math.abs(ball.position.y) >= (container.clientHeight / 200) - 0.5) {
                        ball.velocity.y *= -1; // Reverse y direction
                        changeColor(ball); // Change color on collision
                    }
                }

                renderer.render(scene, camera);
            }

            animate(); // Start the animation loop
            console.log('Animation loop started.');

            // Function to create a ball
            function createBall(color, size) {
                const geometry = new THREE.SphereGeometry(size, 32, 32);
                const material = new THREE.MeshBasicMaterial({ color: color });
                const ball = new THREE.Mesh(geometry, material);
                ball.velocity = new THREE.Vector3(Math.random() * 0.1 - 0.05, Math.random() * 0.1 - 0.05, 0); // Random initial velocity
                return ball;
            }

            // Change color function
            function changeColor(ball) {
                ball.material.color.set(Math.random() * 0xffffff); // Change color to a random value
            }

            // Function to explode a ball into smaller balls
            function explodeBall(ball) {
                const numberOfBalls = 10; // Number of smaller balls to create
                const color = ball.material.color.getHex(); // Get color of the original ball

                for (let i = 0; i < numberOfBalls; i++) {
                    const smallBall = createBall(color, 0.2); // Create smaller ball
                    smallBall.position.copy(ball.position); // Start at the original position
                    // Set random velocity to the smaller balls
                    smallBall.velocity = new THREE.Vector3(
                        (Math.random() - 0.5) * 0.2, // Random x velocity
                        (Math.random() - 0.5) * 0.2, // Random y velocity
                        0 // No z movement
                    );
                    balls.push(smallBall); // Add to balls array
                    scene.add(smallBall); // Add to the scene
                }

                // Hide the original ball
                ball.visible = false;
            }

            // Add event listener for clicks
            container.addEventListener('click', (event) => {
                const mouse = new THREE.Vector2();
                const raycaster = new THREE.Raycaster();

                // Calculate mouse position in normalized device coordinates
                mouse.x = (event.clientX / container.clientWidth) * 2 - 1;
                mouse.y = -(event.clientY / container.clientHeight) * 2 + 1;

                // Update the picking ray with the camera and mouse position
                raycaster.setFromCamera(mouse, camera);

                // Calculate objects intersecting the picking ray
                const intersects = raycaster.intersectObjects(balls);

                if (intersects.length > 0) {
                    const clickedBall = intersects[0].object;
                    explodeBall(clickedBall); // Explode the ball when clicked
                }
            });

            // Resize event listener
            window.addEventListener('resize', () => {
                camera.aspect = container.clientWidth / container.clientHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(container.clientWidth, container.clientHeight);
            });
        });
    </script>
@endpush
