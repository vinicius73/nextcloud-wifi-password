import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  build: {
    // generate manifest.json in outDir
    manifest: true,
    // assetsDir: './assets',
    outDir: './js',
    polyfillModulePreload: true,
    rollupOptions: {
      output: {
        entryFileNames: `assets/[name].js`,
        chunkFileNames: `assets/[name].js`,
        assetFileNames: `assets/[name].[ext]`
      },
      input: "./src/page-main.ts",
    },
  },
});
