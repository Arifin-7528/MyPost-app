import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/loading-skeleton.js', 'resources/js/post-modal.js', 'resources/js/video-control.js'],
            refresh: true,
        }),
    ],
});
