/**
 * Anima (Component Based Animation Library)
 * Anima requires Three.js and GSAP to perform all actions
 * version: v.1
 * @author:
 */

import * as THREE from 'three';
import { gsap } from 'gsap';

class Animate {
    constructor(param = '') {
        this.param = param;
        this.scene = new THREE.Scene();
        this.scene.background = new THREE.Color(0x000000); // Transparent black background
        this.maxWidth = window.innerWidth;
        this.maxHeight = window.innerHeight;

        this.mouseX = 0;
        this.mouseY = 0;
        this.myTween = null;
        this.animate = this.animate.bind(this);
    }

    setCamera() {
        this.camera = new THREE.PerspectiveCamera(75, this.maxWidth / this.maxHeight, 0.1, 1000);
        this.camera.position.set(0, 0, 0.1); // Set initial position close to sphere
    }

    setRenderer() {
        try {
            this.renderer = new THREE.WebGLRenderer({ alpha: true });
            this.resetRenderer();
            console.log("Renderer initialized successfully.");
        } catch (error) {
            console.error("Error initializing the renderer:", error);
            this.renderer = null;
        }
    }

    resetRenderer() {
        if (this.renderer) {
            this.renderer.setSize(this.maxWidth, this.maxHeight);
        } else {
            console.warn("Renderer is not initialized. Cannot reset size.");
        }
    }

    renderOn(divID = '') {
        if (divID) {
            this.container = document.getElementById(divID);
            if (this.renderer && this.renderer.domElement) {
                this.container.appendChild(this.renderer.domElement);
            } else {
                console.error("Error rendering on specified div: Renderer or renderer.domElement is undefined.");
            }
        } else {
            console.warn("No divID provided for rendering.");
        }
    }

    run() {
        if (this.renderer) {
            document.body.appendChild(this.renderer.domElement);
        } else {
            this.setRenderer();
            if (this.renderer) {
                document.body.appendChild(this.renderer.domElement);
            } else {
                console.error("Cannot run animation: Renderer is not initialized.");
            }
        }
    }

    init(divID = '') {
        this.setCamera();
        this.setRenderer();
        if (divID) {
            this.renderOn(divID);
        }
        this.run();
        this.setControls(); // Set up controls after rendering
        this.animate(); // Start the animation loop
    }

    setDistance(value = 200) {
        this.distance = Math.min(value, this.maxWidth / 4);
    }

    // Set a panoramic sphere geometry
    setSphereGeometry(radius = 500, widthSegments = 60, heightSegments = 40) {
        this.geometry = new THREE.SphereGeometry(radius, widthSegments, heightSegments);
        this.geometry.scale(-1, 1, 1); // Invert geometry for a panoramic effect
    }

    // Apply texture with color adjustments for panoramic background
    applyTexture(textureURL) {
        const loader = new THREE.TextureLoader();
        loader.load(textureURL, (texture) => {
            texture.minFilter = THREE.LinearFilter;
            texture.magFilter = THREE.LinearFilter;
            texture.colorSpace = THREE.SRGBColorSpace; // Apply SRGB color space
            this.material = new THREE.MeshBasicMaterial({ map: texture });
            this.sphere = new THREE.Mesh(this.geometry, this.material);
            this.addToScene(this.sphere);
        });
    }

    setParticle(pointSize = 2, colorValue = 0xff44ff) {
        this.particles = new THREE.Points(this.geometry, new THREE.PointsMaterial({ color: colorValue, size: pointSize }));
    }

    attachParticleIntoSphere(value = 50) {
        this.particles.boundingSphere = value;
    }

    attach() {
        // Additional functionality can be implemented here
    }

    rr(object) {
        const renderingGroup = new THREE.Group();
        renderingGroup.add(object);
        return renderingGroup;
    }

    renderGroup(object) {
        return this.rr(object);
    }

    addToScene(object) {
        this.scene.add(object);
    }

    zCamera(value = 400) {
        this.camera.position.z = value;
    }

    // Set up OrbitControls with limited rotation speed
    setControls() {
        if (window.OrbitControls) {
            this.controls = new window.OrbitControls(this.camera, this.renderer.domElement);
            this.controls.enableZoom = false;
            this.controls.enablePan = false;
            this.controls.rotateSpeed = 0.3;
        } else {
            console.error("OrbitControls not available.");
        }
    }

    // Check visibility of the container to reload if not visible
    checkVisibility(divID) {
        const container = document.getElementById(divID);
        if (container && container.offsetParent === null) {
            location.reload(); // Reload if the container is not visible
        }
    }

    resize_event() {
        this.camera.aspect = this.maxWidth / this.maxHeight;
        this.camera.updateProjectionMatrix();
        this.resetRenderer();
    }

    // GSAP animation methods
    fadeIn(object, duration = 1) {
        gsap.to(object.material, { opacity: 1, duration, ease: "power2.inOut" });
    }

    fadeOut(object, duration = 1) {
        gsap.to(object.material, { opacity: 0, duration, ease: "power2.inOut" });
    }

    scaleUp(object, scaleFactor = 1.5, duration = 1) {
        gsap.to(object.scale, { x: scaleFactor, y: scaleFactor, z: scaleFactor, duration, ease: "power2.inOut" });
    }

    scaleDown(object, scaleFactor = 1, duration = 1) {
        gsap.to(object.scale, { x: scaleFactor, y: scaleFactor, z: scaleFactor, duration, ease: "power2.inOut" });
    }

    rotate(object, axis = 'y', rotationAmount = Math.PI / 2, duration = 1) {
        let rotationProperty = `${axis}`;
        gsap.to(object.rotation, { [rotationProperty]: object.rotation[rotationProperty] + rotationAmount, duration, ease: "power2.inOut" });
    }

    moveTo(object, x = 0, y = 0, z = 0, duration = 1) {
        gsap.to(object.position, { x, y, z, duration, ease: "power2.inOut" });
    }

    bounce(object, yMovement = 5, duration = 1) {
        gsap.to(object.position, {
            y: object.position.y + yMovement,
            duration: duration,
            repeat: -1,
            yoyo: true,
            ease: "bounce.inOut"
        });
    }

    shake(object, intensity = 0.1, duration = 0.1, repeat = 5) {
        gsap.to(object.position, {
            x: `+=${intensity}`,
            y: `+=${intensity}`,
            duration,
            repeat,
            yoyo: true,
            ease: "power1.inOut"
        });
    }

    rotateAroundAxis(object, axis, rotationAmount = Math.PI / 4, duration = 1) {
        gsap.to(object.rotation, { [axis]: object.rotation[axis] + rotationAmount, duration, ease: "power2.inOut" });
    }

    oscillate(object, distance = 5, duration = 2) {
        gsap.to(object.position, {
            x: `+=${distance}`,
            duration,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut"
        });
    }

    pulse(object, minScale = 0.9, maxScale = 1.1, duration = 1) {
        gsap.to(object.scale, {
            x: minScale,
            y: minScale,
            z: minScale,
            duration: duration / 2,
            yoyo: true,
            repeat: -1,
            ease: "power1.inOut",
            onRepeat: () => {
                gsap.to(object.scale, { x: maxScale, y: maxScale, z: maxScale, duration: duration / 2, yoyo: true });
            }
        });
    }

    // Mouse-based interaction using GSAP
    onMouseMove(event) {
        if (this.myTween) this.myTween.kill();

        this.mouseX = (event.clientX / window.innerWidth) * 2 - 1;
        this.mouseY = (event.clientY / window.innerHeight) * 2 + 1;
        this.myTween = gsap.to(this.particles.rotation, { duration: 0.1, x: this.mouseY * -1, y: this.mouseX });
    }

    // Core animation loop
    animate() {
        requestAnimationFrame(this.animate);

        if (this.sphere) {
            // Continuous horizontal rotation for parallax effect
            this.sphere.rotation.y += 0.001;  // Adjust speed as desired
        }

        if (this.controls) this.controls.update();
        this.renderer.render(this.scene, this.camera);
    }
}

// Export the class for external use
export default Animate;
