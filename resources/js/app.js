import './bootstrap';
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';
import { gsap } from 'gsap';
import Animate from "./animate.js";
import Alpine from 'alpinejs';

// Set global references for easy access
window.THREE = THREE;
window.gsap = gsap;
window.OrbitControls = OrbitControls;

// Create a new instance of Anima and assign it to the global window object
//window.Anima = new MintreuAnimate(); // This will make anima available globally
window.Anima = Animate;



// Start Alpine.js
Alpine.start();
window.AlpineJs = Alpine;
