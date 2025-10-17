import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
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
        drop: ['console', 'debugger'],
    },
    css: {
        // Use Lightning CSS for faster, smaller CSS in production builds
        minify: 'lightningcss',
        lightningcss: {
            targets: '>= 0.25%'
        }
    },
    build: {
        target: 'es2019',
        sourcemap: false,
        rollupOptions: {
            output: {
                // Ensure common deps are split out for better browser caching
                manualChunks: {
                    vendor: ['axios']
                }
            }
        }
    }
});