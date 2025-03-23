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
        assetFileNames: (assetInfo) => {
          // Keep original filenames for fonts
          if (/\.woff|\.woff2|\.eot|\.ttf|\.otf$/.test(assetInfo.name)) {
            return '[name][extname]'; // Do not append hash
          }
          if (/\.css$/.test(assetInfo.name)) {
            return '[name][hash][extname]'; // Append hash for CSS only
          }
          return '[name][hash][extname]';
        },
        chunkFileNames: 'bundle[hash].js',
      },
      external: [
        /\.test\.[jt]s$/,
        /\.spec\.[jt]s$/,
        /\/__tests__\//
      ]
    },
    assetsInlineLimit: 0, // Ensure fonts are not inlined as base64
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
});
