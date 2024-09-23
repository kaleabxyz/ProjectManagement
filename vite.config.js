import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
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
    server: {
        host: '0.0.0.0',  // This makes the Vite server accessible externally.
        port: 5173,       // Optional: Set a custom port if needed.
        hmr: {
            host: '192.168.1.14', // Replace with your local network IP
        }
    }
   
});
