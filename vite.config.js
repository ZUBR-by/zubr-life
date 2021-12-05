import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import styleImport from "vite-plugin-style-import";

const { resolve } = require("path");

// https://vitejs.dev/config/
export default defineConfig({
  build: {
    rollupOptions: {
      input: {
        loshitsa: resolve(__dirname, "loshitsa.html"),
        vitebsk: resolve(__dirname, "vitebsk.html"),
        bntu: resolve(__dirname, "bntu.html"),
        index: resolve(__dirname, "index.html"),
      },
    },
  },
  plugins: [
    vue(),
    styleImport({
      libs: [
        {
          libraryName: "element-plus",
          esModule: true,
          ensureStyleFile: true,
          resolveStyle: (name) => {
            name = name.slice(3);
            return `element-plus/packages/theme-chalk/src/${name}.scss`;
          },
          resolveComponent: (name) => {
            return `element-plus/lib/${name}`;
          },
        },
      ],
    }),
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
