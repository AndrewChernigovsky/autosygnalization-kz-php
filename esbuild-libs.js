import esbuild from 'esbuild';
import path from 'path';
import { fileURLToPath } from 'url';
import { dirname } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

async function buildLibrary() {
  try {
    await esbuild.build({
      entryPoints: [path.resolve(__dirname, './src/files/libs/libs.js')],
      bundle: true,
      minify: true,
      sourcemap: true, // Включение sourcemap для отладки
      outfile: path.resolve(__dirname, './dist/assets/libs/libs.js'), // Укажите выходной файл
      format: 'esm', // Используйте формат ESM или IIFE в зависимости от ваших нужд
    });

    console.log('Library built successfully!');
  } catch (error) {
    console.error('Build failed:', error);
    process.exit(1);
  }
}

buildLibrary();
