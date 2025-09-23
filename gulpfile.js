import { src, dest, watch, series } from 'gulp';
import * as dartSass from 'sass';
import gulpSass from 'gulp-sass';
import autoPrefixer from 'gulp-autoprefixer';
import cleanCSS from 'gulp-clean-css';
import browserSync from 'browser-sync';
import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';
import svgSprite from 'gulp-svg-sprite';
import { esbuildFooWatch, esbuildLibBuild } from './esbuild.js';
import changed from 'gulp-changed';

const config = {
  mode: {
    symbol: {
      sprite: '../sprite.svg',
      render: {
        css: false,
      },
    },
  },
};

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const PRODUCTION = process.env.PRODUCTION === 'true';

const sass = gulpSass(dartSass);

const paths = {
  styles: {
    srcLib: './src/client/libs/index.scss',
    src: './src/client/scss/style.scss',
    watch: './src/client/scss/**/*.scss',
    dest: './dist/client/css/',
  },
  scripts: {
    src: './src/client/js/**/*.{js,jsx}',
  },
  src: './src',
  dist: './dist',
};

const esbuildTask = async (done) => {
  await esbuildFooWatch();
  browserSync.reload();
  done();
};

const esbuildLibsTask = async (done) => {
  await esbuildLibBuild();
  done();
};

const phpTask = (cb) => {
  let tasks = [];

  const destPathPhp = './dist/server/php';
  const destPathErrors = './dist/server/errors'; // Новый путь для errors

  // Копирование PHP файлов
  tasks.push(
    src(['./src/server/php/**', '!./src/server/php/**/.env'], {
      encoding: false,
    })
      .pipe(changed(destPathPhp))
      .pipe(dest(destPathPhp))
  );

  // Копирование файлов errors
  tasks.push(
    src(['./src/server/errors/**/*.php'], { encoding: false }).pipe(
      dest(destPathErrors)
    )
  );

  // Копирование index.php
  tasks.push(
    src(['./src/index.php'], { encoding: false })
      .pipe(changed(paths.dist))
      .pipe(dest(paths.dist))
  );

  return Promise.all(tasks)
    .then(() => {
      if (!PRODUCTION) {
        return src([
          './src/index.php',
          './src/server/errors/**/*.php',
          './src/server/php/pages/**/*.php',
        ]).pipe(browserSync.stream());
      }
      cb();
    })
    .catch((err) => {
      console.error('Ошибка при копировании php файлов:', err);
      cb(err);
    });
};

const watchTask = (done) => {
  browserSync.init({
    proxy: 'http://autosygnalization-kz-php/',
    notify: false,
    open: false,
  });
  if (!PRODUCTION) {
    watch([paths.styles.watch], sassTask).on('change', browserSync.reload);
    watch([paths.scripts.src], esbuildTask).on('change', browserSync.reload);
    watch(
      [
        './src/server/**/*.php',
        './src/**/*.php',
        './src/server/errors/**/*.php',
      ],
      phpTask
    ).on('change', browserSync.reload);
  }

  done();
};

async function cleanDist(dirnames) {
  const promises = [];

  // Создайте массив для исключений
  const exclusions = ['dist/client/libs', 'dist/client/images'];

  for (const dir of dirnames) {
    const distPath = path.join(__dirname, dir);

    // Проверяем, нужно ли исключить этот путь
    if (
      exclusions.some((exclusion) =>
        distPath.includes(path.join(__dirname, exclusion))
      )
    ) {
      console.log(`Пропуск ${distPath} (исключено из удаления)`);
      continue; // Пропускаем удаление для исключенных директорий
    }

    try {
      await fs.access(distPath);
      await fs.rm(distPath, { recursive: true, force: true });
      console.log(`Содержимое ${distPath} успешно удалено!`);
    } catch (err) {
      if (err.code === 'ENOENT') {
        await fs.mkdir(distPath, { recursive: true });
        console.log(`${distPath} успешно создана!`);
      } else {
        console.error(`Ошибка при удалении содержимого папки ${dir}:`, err);
      }
    }
  }

  await Promise.all(promises);
  console.log('Все операции завершены!');
}

const sassTask = () => {
  let stream = src([paths.styles.src]).pipe(
    sass({
      outputStyle: 'expanded',
      silenceDeprecations: ['legacy-js-api'],
    }).on('error', sass.logError)
  );

  stream = stream.pipe(autoPrefixer());

  if (PRODUCTION) {
    stream = stream.pipe(cleanCSS({ level: 2 }));
  } else {
    stream = stream.pipe(browserSync.stream());
  }

  return stream.pipe(dest(paths.styles.dest));
};

const sassTaskLibs = () => {
  return src(paths.styles.srcLib)
    .pipe(
      sass({
        outputStyle: 'expanded',
        silenceDeprecations: ['legacy-js-api'],
      }).on('error', sass.logError)
    )
    .pipe(autoPrefixer())
    .pipe(cleanCSS({ level: 2 }))
    .pipe(dest('./dist/client/libs/'));
};

const setConfig = (cb) => {
  const tasks = [];

  if (!PRODUCTION) {
    tasks.push(
      src(['./src/config/config.php']).pipe(dest('./dist/server/php/config/'))
    );
  } else {
    tasks.push(
      src(['./src/server/php/config/config.php']).pipe(
        dest('./dist/server/php/config/')
      )
    );
  }

  return Promise.all(tasks)
    .then(() => {
      cb();
    })
    .catch((err) => {
      console.error('Ошибка при копировании config файлов:', err);
      cb(err);
    });
};

const setConfigServe = (cb) => {
  const tasks = [];

  tasks.push(
    src(['./src/config/config.php']).pipe(dest('./dist/server/php/config/'))
  );

  return Promise.all(tasks)
    .then(() => {
      cb();
    })
    .catch((err) => {
      console.error('Ошибка при копировании config файлов:', err);
      cb(err);
    });
};

const copyStatics = (cb) => {
  const tasks = [];

  tasks.push(
    src(
      [
        './src/client/**/*',
        '!./statics/images/**',
        '!./src/client/scss/**',
        '!./src/client/libs/*.scss',
      ],
      { encoding: false }
    ).pipe(dest('./dist/client'))
  );

  tasks.push(
    src([
      './src/.htaccess',
      './src/index.php',
      './src/sitemap.xml',
      './src/robots.txt',
      './src/yandex_12ed8a33b1d44641.html',
      './src/browserconfig.xml',
      './src/favicon.ico',
      './src/manifest.json',
    ]).pipe(dest(paths.dist))
  );

  tasks.push(
    src(['./src/server/errors/**/*.php'], { encoding: false }).pipe(
      dest(paths.dist + '/server/errors')
    )
  );

  tasks.push(
    src(['./src/server/vendor/**/*'], { encoding: false }).pipe(
      dest(paths.dist + '/server/vendor')
    )
  );

  tasks.push(
    src(['./src/server/composer.json', './src/server/composer.lock']).pipe(
      dest(paths.dist + '/server')
    )
  );

  // Копирование .env для server и auth
  tasks.push(
    src(['./src/server/.env'], { encoding: false, allowEmpty: true }).pipe(
      dest(paths.dist + '/server')
    )
  );

  return Promise.all(tasks)
    .then(() => {
      cb();
    })
    .catch((err) => {
      console.error('Ошибка при копировании статических файлов:', err);
      cb(err);
    });
};

const sprite = () => {
  console.log('Starting sprite generation...');
  return src(['./src/client/vectors/icons/**/*.svg'])
    .pipe(svgSprite(config))
    .on('error', function (error) {
      console.error('Error in sprite generation:', error);
    })
    .pipe(dest('./dist/client/vectors/'))
    .on('end', function () {
      console.log('Sprite generation completed');
    });
};

const copySprite = () => {
  return src(['./dist/client/vectors/sprite.svg']).pipe(
    dest('./dist/client/vectors/')
  );
};

const fonts = (cb) => {
  src(['./src/client/fonts/**/*.{ttf,woff,woff2}'], { encoding: false })
    .pipe(dest(paths.dist + '/client/fonts'))
    .on('end', cb);
};

// --- Обновленные цепочки задач ---
const buildPhp = series(phpTask);

const statics = series(
  () => cleanDist(['dist']),
  copyStatics,
  fonts,
  sprite,
  sassTaskLibs,
  esbuildLibsTask
);

const dev = series(
  () => cleanDist(['dist/client']),
  copyStatics,
  setConfig,
  sprite,
  copySprite,
  buildPhp, // Используем новую задачу
  sassTask,
  sassTaskLibs,
  esbuildLibsTask,
  esbuildTask,
  watchTask
);

const build = series(
  () => cleanDist(['dist/client']),
  copyStatics,
  setConfig,
  buildPhp,
  sassTask,
  sprite,
  copySprite,
  sassTaskLibs,
  esbuildTask
);

const serve = series(
  () => cleanDist(['dist/client']),
  copyStatics,
  setConfigServe,
  buildPhp,
  sassTask,
  sprite,
  copySprite,
  sassTaskLibs,
  esbuildTask,
  watchTask
);

export {
  sassTask,
  sassTaskLibs,
  esbuildTask,
  esbuildLibsTask,
  phpTask,
  watchTask,
  build,
  statics,
  sprite,
  fonts,
  serve,
};
export default dev;
