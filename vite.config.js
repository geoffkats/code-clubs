import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig(({ mode }) => {
    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            cors: true,
        },
        esbuild: {
            drop: mode === 'production' ? ['console', 'debugger'] : undefined,
        },
        optimizeDeps: {
            // Ensure Chart.js is included for dev server pre-bundling
            include: ['chart.js/auto'],
        },
        build: {
            rollupOptions: {
                // No external CDN, ensure chart.js is bundled
                // By default, dependencies are bundled; this is explicit for clarity
                external: [],
            },
        },
    };
});