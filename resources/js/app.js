import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


// Get Vite to version and serve these files
import.meta.glob([
    './libraries/**',
    './img/**',
    './icons/*',
    './*',
])