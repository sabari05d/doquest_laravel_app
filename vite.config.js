import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';


// vite.config.js
export default defineConfig({
    base: process.env.APP_ENV === 'production' ? '/your/' : '/',
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
        proxy: {
            '/api': 'http://127.0.0.1:8000', // Laravel backend
        },
        allowedHosts: 'all',
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
