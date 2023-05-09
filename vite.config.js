import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/img/*',
                'resources/img/sathy/*',
                'resources/libraries/aos/*',
                'resources/libraries/loading-attr/*',
                'resources/libraries/modernizr/*',
                'resources/libraries/notif/*',
            ],
            refresh: [
                ...refreshPaths,
                'app/Http/Livwire/**',
                'app/Forms/Components/**',
            ],
        }),
    ],
});
