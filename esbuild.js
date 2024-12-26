import esbuild from 'esbuild';
import path from 'path';
import { fileURLToPath } from 'url';
import { dirname } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const PRODUCTION = process.env.PRODUCTION === 'true';

// Определяем пути к входным и выходным файлам
const entryPoints = [
  path.resolve(__dirname, './src/files/js/main.js'),
];

const buildOptions = {
  entryPoints,
  bundle: true,
  sourcemap: !PRODUCTION, // Включаем sourcemap в режиме разработки
  minify: PRODUCTION, // Минификация в продакшене
  format: 'esm', // Формат выходного файла
};

export async function esbuildFoo() {
  try {
    // Сборка основного файла (используем outdir)
    await esbuild.build({
      ...buildOptions,
      outdir: path.resolve(__dirname, './dist/files/js'), // Выходная директория для основного файла
      chunkNames: '[name]-[hash]', // Шаблон имен для чанков
    });

    // // Сборка библиотек (используем outdir)
    // await esbuild.build({
    //   entryPoints: [path.resolve(__dirname, './src/files/js/libs.js')],
    //   outdir: path.resolve(__dirname, './dist/assets/libs'), // Выходная директория для библиотек
    //   bundle: true,
    //   sourcemap: false, // Отключаем sourcemap для библиотек
    //   minify: PRODUCTION,
    //   format: 'esm',
    // });

    console.log('Build completed successfully!');
  } catch (error) {
    console.error('Build failed:', error);
    process.exit(1);
  }
}
