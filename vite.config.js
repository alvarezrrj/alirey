import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Stick in here any assets you wanna inject into blade views using @vite
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/libraries/modernizr/modernizr-webp.js',
                'resources/libraries/aos/aos.css',
                'resources/libraries/loading-attr/loading-attribute-polyfill.css',
                'resources/css/home-styles.css',
                'resources/libraries/aos/aos.js',
                'resources/libraries/loading-attr/loading-attribute-polyfill.umd.js',
                'resources/libraries/notif/notif.js',
                'resources/css/config.css',
                'resources/libraries/flexselect/flexselect.css',
                'resources/libraries/flexselect/liquidmetal.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Http/Livwire/**',
                'app/Forms/Components/**',
            ],
        }),
    ],
});
