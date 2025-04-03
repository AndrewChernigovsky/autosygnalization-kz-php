import esbuild from 'esbuild';
import path from 'path';
import { fileURLToPath } from 'url';
import { dirname } from 'path';
import { transform } from '@babel/core';
import fs from 'fs';
import { minify } from 'terser';
import nodeResolve from '@esbuild-plugins/node-resolve';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const PRODUCTION = process.env.PRODUCTION === 'true';

const entryPoints = [
  {
    entryPoint: path.resolve(__dirname, './src/client/js/main.js'),
    outdir: path.resolve(__dirname, './dist/client/js'),
  },
];

const libEntryPoints = [
  {
    entryPoint: path.resolve(__dirname, './src/client/libs/index.js'),
    outdir: path.resolve(__dirname, './dist/client/libs'),
  },
];

const babelConfig = {
  presets: [
    [
      '@babel/preset-env',
      {
        targets: {
          browsers: ['> 1%', 'not dead', 'not op_mini all'],
        },
        modules: false,
      },
    ],
  ],
  plugins: [
    'transform-remove-console',
    'transform-remove-debugger',
  ],
};

async function transformCode(code) {
  const result = await transform(code, babelConfig);
  return result.code;
}

async function minifyCode(code) {
  const result = await minify(code, {
    compress: {
      dead_code: true,
      drop_console: true,
      drop_debugger: true,
    },
    format: {
      comments: false,
    },
    mangle: true,
  });
  return result.code;
}

export async function esbuildFooWatch() {
  try {
    if (PRODUCTION === false) {
      for (const entryPoint of entryPoints) {
        const ctx = await esbuild.context({
          bundle: true,
          sourcemap: true,
          minify: false,
          format: 'esm',
          splitting: true,
          platform: 'node',
          entryPoints: [entryPoint.entryPoint],
          outdir: entryPoint.outdir,
          chunkNames: '[name]-[hash]',
          plugins: [
            {
              name: 'babel-plugin',
              setup(build) {
                build.onLoad({ filter: /\.js$/ }, async (args) => {
                  return {
                    loader: 'js',
                  };
                });
              },
            },
          ],
        });
        await ctx.watch();
      }
    } else {
      for (const entryPoint of entryPoints) {
        await esbuild.build({
          bundle: true,
          sourcemap: false,
          minify: true,
          format: 'esm',
          splitting: true,
          platform: 'node',
          entryPoints: [entryPoint.entryPoint],
          outdir: entryPoint.outdir,
          chunkNames: '[name]-[hash]',
          plugins: [
            {
              name: 'babel-plugin',
              setup(build) {
                build.onLoad({ filter: /\.js$/ }, async (args) => {
                  const code = await fs.promises.readFile(args.path, 'utf8');
                  const transformedCode = await transformCode(code);
                  const minifiedCode = await minifyCode(transformedCode);
                  return {
                    contents: minifiedCode,
                    loader: 'js',
                  };
                });
              },
            },
          ],
        });
      }
    }

    console.log('Watching...!');
  } catch (error) {
    console.error('Build failed:', error);
    process.exit(1);
  }
}

export async function esbuildLibBuild() {
  try {
    for (const entryPoint of libEntryPoints) {
      await esbuild.build({
        bundle: true,
        sourcemap: false,
        minify: true,
        format: 'esm',
        platform: 'node',
        entryPoints: [entryPoint.entryPoint],
        outdir: entryPoint.outdir,
        plugins: [
          nodeResolve.default({
            extensions: ['.ts', '.js'],
            onResolved: (resolved) => {
              if (resolved.includes('node_modules')) {
                return resolved
              }
              return resolved
            },
          }),
          {
            name: 'babel-plugin',
            setup(build) {
              build.onLoad({ filter: /\.js$/ }, async (args) => {
                const code = await fs.promises.readFile(args.path, 'utf8');
                const transformedCode = await transformCode(code);
                return {
                  contents: transformedCode,
                  loader: 'js',
                };
              });
            },
          },
        ],
      });
    }

    console.log('Lib compiled!');
  } catch (error) {
    console.error('Build lib failed:', error);
    process.exit(1);
  }
}