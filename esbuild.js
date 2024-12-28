import esbuild from 'esbuild';
import path from 'path';
import { fileURLToPath } from 'url';
import { dirname } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const PRODUCTION = process.env.PRODUCTION === 'true';

const entryPoints = [
  path.resolve(__dirname, './src/files/js/main.js'),
];

const buildOptions = {
  entryPoints,
  bundle: true,
  sourcemap: !PRODUCTION,
  minify: PRODUCTION,
  format: 'esm',
};

export async function esbuildFooWatch() {
  try {
    const ctx = await esbuild.context({
      ...buildOptions,
      outdir: path.resolve(__dirname, './dist/files/js'),
      chunkNames: '[name]-[hash]'
    });
    await ctx.watch();

    console.log('Watching...!');
  } catch (error) {
    console.error('Build failed:', error);
    process.exit(1);
  }
}

esbuildFooWatch();
