import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import tailwindcss from 'tailwindcss';
import inject from "@rollup/plugin-inject";

export default defineConfig({
    plugins: [
        inject({   // => that should be first under plugins array
            $: 'jquery',
            jQuery: 'jquery',
        }),
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue({
            // This is needed, or else Vite will try to find image paths (which it wont be able to find because this will be called on the web, not directly)
            // For example <img src="/images/logo.png"> will not work without the code below
            template: {
                transformAssetUrls: {
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
});
