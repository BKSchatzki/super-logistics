import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import tailwindcss from 'tailwindcss'
import autoprefixer from 'autoprefixer'

const isDevBuild = true;

// https://vite.dev/config/
export default defineConfig({
  base: '/wp-content/plugins/super-logistics/view/dist/',
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
        entryFileNames: 'bundle[hash].js',
        assetFileNames: 'bundle[hash].css',
        chunkFileNames: 'bundle[hash].js',
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
  },
  define: {
    __DEV__: isDevBuild
  }
})
