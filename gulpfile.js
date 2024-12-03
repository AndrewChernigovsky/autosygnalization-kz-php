import { src, dest, watch, parallel, series } from 'gulp';
import * as dartSass from 'sass';
import gulpSass from 'gulp-sass';
import autoPrefixer from 'gulp-autoprefixer';
import cleanCSS from 'gulp-clean-css';
import browserSync from 'browser-sync';
import { exec } from 'child_process';
import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';
import svgSprite from 'gulp-svg-sprite';
import ttf2woff from 'gulp-ttf2woff';
import ttf2woff2 from 'gulp-ttf2woff2';

const config = {
  mode: {
    symbol: {
      sprite: '../sprite.svg',
      render: {
        css: false
      }
    }
  }
};

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const sass = gulpSass(dartSass);

const paths = {
  styles: {
    srcLib: './src/files/libs/libs.scss',
    src: './src/files/scss/style.scss',
    watch: './src/files/scss/**/*.scss',
    dest: './dist/files/css/'
  },
  scripts: {
    src: './src/files/js/**/*.js',
  },
  src: './src',
  dist: './dist'
};

const PRODUCTION = process.env.PRODUCTION === 'true';

const rollupTask = (done) => {
  exec('rollup -c', (err, stdout, stderr) => {
    if (err) {
      console.error(stderr);
      done(err);
      return;
    }
    console.log(stdout);
    browserSync.reload();
    done();
  });
};

const phpTask = (cb) => {

  (() => {
    return src(['./src/index.php'])
      .pipe(dest(paths.dist))
  })();

  (() => {
    return src(['./src/files/php/pages/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/pages'))
  })();

  (() => {
    return src(['./src/files/php/helpers/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/helpers'))
  })();

  (() => {
    return src(['./src/files/php/sections/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/sections'))
  })();

  (() => {
    return src(['./src/files/php/layout/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/layout'))
  })();

  (() => {
    return src(['./src/files/php/functions/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/functions'))
  })();

  (() => {
    return src(['./src/files/php/data/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/data'))
  })();

  if (!PRODUCTION) {
    return src(['./src/index.php', './src/files/php/pages/**/*.php'])
      .pipe(browserSync.stream());
  }
  cb();
};

const watchTask = () => {
  browserSync.init({
    proxy: "http://aaa/dist",
    serveStatic: [{
      route: '/',
      dir: 'dist'
    },
    {
      route: '/php/pages',
      dir: 'assets'
    }],
    notify: false,
  });
  if (!PRODUCTION) {
    watch([paths.styles.watch], sassTask);
    watch([paths.scripts.src], rollupTask);
    watch(['./src/**/*.php'], phpTask);
  }
};

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

const sassTask = () => {
  let stream = src(paths.styles.src)
    .pipe(sass({ silenceDeprecations: ['legacy-js-api'] }).on('error', sass.logError));
  if (PRODUCTION) {
    stream = stream.pipe(autoPrefixer());
    stream = stream.pipe(cleanCSS({ level: 2 }));
  }
  if (!PRODUCTION) {
    stream = stream.pipe(browserSync.stream());
  }
  return stream.pipe(dest(paths.styles.dest));
};

const sassTaskLibs = () => {
  let stream = src(paths.styles.srcLib)
    .pipe(sass({ silenceDeprecations: ['legacy-js-api'] }).on('error', sass.logError));
  if (PRODUCTION) {
    stream = stream.pipe(autoPrefixer());
    stream = stream.pipe(cleanCSS({ level: 2 }));
  }
  return stream.pipe(dest('./dist/assets/libs/'));
};

const copyStatics = (cb) => {
  (() => {
    return src(['./src/assets/**/*', '!./src/assets/images/**'])
      .pipe(dest('./dist/assets'))
  })();
  (() => {
    return src(['./src/.htaccess', './src/index.php', './src/sitemap.xml', './src/robots.txt', './src/yandex_12ed8a33b1d44641.html', './src/browserconfig.xml', './src/favicon.ico', './src/manifest.json'])
      .pipe(dest(paths.dist))
  })()
  cb();
}

const images = (cb) => {
  return src(['./dist/assets/images/**/*.{png,jpg}'], { encoding: false })
    .pipe(dest(paths.dist + '/assets/images'))
    .on('end', cb)
};

const docs = (cb) => {
  return src(['./src/files/docs/**/*.pdf'], { encoding: false })
    .pipe(dest(paths.dist + '/files/docs/'))
    .on('end', cb)
};

const sprite = () => {
  return src(['./src/assets/images/vectors/**/*.svg', '!./src/assets/images/vectors/sprite.svg'])
    .pipe(svgSprite(config))
    .pipe(dest('./dist/assets/images/vectors'));
};

const vectors = () => {
  return src('./src/assets/images/**/*.svg')
    .pipe(dest(paths.dist + '/assets/images/vectors'));
};

const fonts = (cb) => {
  src('./src/assets/fonts/**/*.{ttf,woff,woff2}')
    .pipe(dest(paths.dist + '/assets/fonts'))

  cb()
};

const statics = parallel(() => cleanDist('dist/assets/libs'), sprite, sassTaskLibs, rollupTask);
const dev = series(() => cleanDist('dist/files'), copyStatics, docs, phpTask, sassTask, sassTaskLibs, rollupTask, watchTask);
const build = series(() => cleanDist('dist/files'), copyStatics, docs, images, vectors, phpTask, sassTask, sassTaskLibs, rollupTask);

export { images, sassTask, vectors, sassTaskLibs, rollupTask, phpTask, watchTask, dev, build, statics, docs, sprite, fonts };
export default dev;
