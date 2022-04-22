import { defineConfig } from "vite";
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
  plugins: [vue()],
  css: {
    preprocessorOptions: {
      scss: {
        charset: false,
        additionalData: `
$--color-primary: #da1039;
$--color-success: #da1039;
$--input-placeholder-color: rgba(255,92,1,0.3);
$--border-color-base: #da1039;
`,
      },
    },
  },
});
