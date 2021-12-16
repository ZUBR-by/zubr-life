import {defineConfig} from "vite";
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
    resolve: {
        alias: [
            {
                find: /^~.+/,
                replacement: (val) => {
                    return val.replace(/^~/, "");
                },
            },
        ],
    },
    plugins: [
        vue()
    ],
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `
$--color-primary: #ff5c01;
$--color-success: #ff5c01;
$--input-placeholder-color: rgba(255,92,1,0.3);
$--border-color-base: #FF5C01;
`,
            },
        },
    },
});
