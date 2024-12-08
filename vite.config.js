import { defineConfig } from 'vite';
import { fileURLToPath } from 'node:url';
import usePHP from 'vite-plugin-php';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import { ViteEjsPlugin } from 'vite-plugin-ejs';
import { imagetools } from 'vite-imagetools';
import { existsSync } from 'node:fs';
import path from 'path';
import fs from 'fs/promises';
import { exec } from 'child_process';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const paths = {
  styles: {
    src: './src/files/scss/style.scss',
    dest: './dist/files/css/'
  },
  scripts: {
    src: './src/files/js/**/*.js',
  },
  src: './src',
  dist: './dist'
};

// Получаем значение переменной окружения PRODUCTION
const PRODUCTION = process.env.PRODUCTION === 'true';

// Функция для очистки директории
async function cleanDist(dirname) {
  const distPath = path.join(__dirname, dirname);
  try {
    await fs.access(distPath);
    await fs.rm(distPath, { recursive: true, force: true });
    console.log(`${distPath} успешно удалена!`);
  } catch (err) {
    if (err.code === 'ENOENT') {
      await fs.mkdir(distPath, { recursive: true });
      console.log(`${distPath} успешно создана!`);
    } else {
      console.error('Ошибка при удалении папки "dist":', err);
    }
  }
}

// Задача для компиляции SASS
function compileSass() {
  return new Promise((resolve, reject) => {
    exec(`sass ${paths.styles.src}:${paths.styles.dest} --style ${PRODUCTION ? 'compressed' : 'expanded'}`, (err, stdout, stderr) => {
      if (err) {
        console.error(stderr);
        reject(err);
        return;
      }
      console.log(stdout);
      resolve();
    });
  });
}
async function buildWithRollup() {
  return new Promise((resolve, reject) => {
    exec('rollup -c rollup.config.js', (err, stdout, stderr) => {
      if (err) {
        console.error(stderr);
        reject(err);
        return;
      }
      console.log(stdout);
      resolve();
    });
  });
}
// Задача для копирования статических файлов
async function copyStatics() {
  try {
    await Promise.all([
      fs.cp('./src/assets', './dist/assets', { recursive: true }),
      fs.cp('./src/files/php', './dist/files/php', { recursive: true }),
      fs.cp('./src/.htaccess', './dist/.htaccess'),
      fs.cp('./src/index.php', './dist/index.php'),
    ]);
  } catch (err) {
    console.error('Ошибка при копировании статических файлов:', err);
  }
}

// Настройка Vite
export default defineConfig(({ command }) => {
  const publicBasePath = '/'; // Укажите базовый путь, если необходимо
  const base = command === 'serve' ? '/' : publicBasePath;

  // Определяем значение для DEV_PATH
  const devPath = PRODUCTION ? '/' : '/dist/';

  return {
    base,
    plugins: [
      usePHP({
        entry: [
          'index.php',
          '404.php',
          './src/files/php/**/*.php'
        ],
        rewriteUrl(requestUrl) {
          const filePath = fileURLToPath(new URL('.' + requestUrl.pathname, import.meta.url));
          if (!requestUrl.pathname.includes('.php') && existsSync(filePath)) {
            return undefined; // Если файл существует, не перенаправляем
          }
          requestUrl.pathname = 'index.php'; // Перенаправляем на index.php
          return requestUrl;
        },
      }),
      viteStaticCopy({
        targets: [
          { src: './src/files/php', dest: '' },
          { src: './src/index.php', dest: '' },
          { src: './src/404.php', dest: '' },
        ],
        silent: command === 'serve',
      }),
      ViteEjsPlugin({
        BASE: base,
      }),
      imagetools(),
    ],
    resolve: {
      alias: {
        '~/': fileURLToPath(new URL('./src/', import.meta.url)),
      },
    },
    server: {
      proxy: {
        '/': {
          target: 'http://autosygnalization-kz-php', // Ваш PHP сервер
          changeOrigin: true,
          secure: false,
        }
      },
      fs: {
        allow: ['..'], // Разрешить доступ к родительским директориям
      },
      port: 5173,
    },
    build: {
      outDir: path.resolve(__dirname, 'dist'), // Укажите директорию сборки
      emptyOutDir: true,
    },

    css: {
      preprocessorOptions: {
        scss: {
          additionalData: `$DEV_PATH: '${devPath}';`, // Передаем переменную в SASS
        },
      },
    },
  };
});

// Основная задача разработки
async function dev() {
  try {
    await cleanDist('dist/files');
    await copyStatics();
    await buildWithRollup();

    // Компилируем SASS
    await compileSass();
    // Запускаем сервер Vite
    const { createServer } = require('vite');
    createServer({
      server: { middlewareMode: true }
    }).listen(5173); // Укажите нужный порт
  } catch (err) {
    console.error('Ошибка в процессе разработки:', err);
  }
}

// Основная задача сборки
async function build() {
  try {
    await cleanDist('dist');
    await copyStatics();
    await buildWithRollup();
    // Компилируем SASS
    await compileSass();
  } catch (err) {
    console.error('Ошибка в процессе сборки:', err);
  }
}

// Экспортируем задачи
export { dev, build };