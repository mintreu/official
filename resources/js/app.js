import './bootstrap';
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Alpine from 'alpinejs';


// Set globally if needed
window.THREE = THREE;
gsap.registerPlugin(ScrollTrigger);
window.gsap = gsap;
window.OrbitControls = OrbitControls;


 Alpine.start()
 window.AlpineJs = Alpine



