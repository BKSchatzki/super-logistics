import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import tailwindcss from 'tailwindcss'
import autoprefixer from 'autoprefixer'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./view', import.meta.url)),
      '@utils': fileURLToPath(new URL('./view/root/utils', import.meta.url)),
    },
  },
  build: {
    outDir: 'view/dist',
    rollupOptions: {
      input: fileURLToPath(new URL('./view/main.js', import.meta.url)),
      output: {
        entryFileNames: 'bundle.js',
        assetFileNames: 'bundle.css',
      }
    }
  },
  css: {
    postcss: {
      plugins: [
        tailwindcss,
        autoprefixer
      ],
    },
  }
})
