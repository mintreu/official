import './bootstrap';
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';
import { gsap } from 'gsap';
import Alpine from 'alpinejs'

// Set globally if needed
window.THREE = THREE;
window.gsap = gsap;
window.OrbitControls = OrbitControls;



window.Alpine = Alpine

Alpine.start()
