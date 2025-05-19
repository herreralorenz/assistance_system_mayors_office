import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    //added base for building
    base: '/build/',
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
    },
    // server: {
    //     host: '192.168.1.29', // Replace with your local IP (e.g., 192.168.1.100)
    //     port: 5173,              // Or any other available port
    //     watch: {
    //         usePolling: true,    // Useful if you experience issues with file changes not reflecting
    //     },
    // },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
