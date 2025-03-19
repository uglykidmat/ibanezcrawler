import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

// Initialize Scene
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ antialias: true });

// Set Renderer Size
renderer.setSize(window.innerWidth, window.innerHeight);
document.getElementById('guitarCanvas').appendChild(renderer.domElement);

// Load 3D Model
const loader = new GLTFLoader();
loader.load('/3D/models/Ibanez_GRX_40.glb', function (gltf) {
    scene.add(gltf.scene);
    gltf.scene.position.set(0, -1, 0); // Adjust model position if needed
}, undefined, function (error) {
    console.error('Error loading model:', error);
});

// Add Lighting
const light = new THREE.AmbientLight(0xffffff, 1);
scene.add(light);

const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
directionalLight.position.set(5, 5, 5).normalize();
scene.add(directionalLight);

// Set Camera Position
camera.position.z = 3;

// Animation Loop
function animate() {
    requestAnimationFrame(animate);
    renderer.render(scene, camera);

}
animate();

// Handle Window Resize
function onWindowResize() {
    const canvasContainer = document.getElementById('guitarCanvas');
    if (!canvasContainer) return;

    camera.aspect = canvasContainer.clientWidth / canvasContainer.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(canvasContainer.clientWidth, canvasContainer.clientHeight);
}

// Listen for window resize
window.addEventListener('resize', onWindowResize);
onWindowResize();  // Call once on load
