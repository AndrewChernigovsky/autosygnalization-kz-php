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

const config = {
  mode: {
    symbol: {
      sprite: '../sprite.svg',
      render: {
        css: false
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
    return src(['./src/files/php/helpers/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/helpers'))
  })();

  (() => {
    return src(['./src/files/php/functions/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/functions'))
  })();

  (() => {
    return src(['./src/files/php/data/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/data'))
  })();

  (() => {
    return src(['./src/index.php'])
      .pipe(dest(paths.dist))
  })();

  (() => {
    return src(['./src/files/php/pages/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/pages'))
  })();

  (() => {
    return src(['./src/files/php/sections/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/sections'))
  })();

  (() => {
    return src(['./src/files/php/layout/**/*.php'])
      .pipe(dest(paths.dist + '/files/php/layout'))
  })();

  if (!PRODUCTION) {
    return src(['./src/index.php', './src/files/php/pages/**/*.php'])
      .pipe(browserSync.stream());
  }
  cb();
};

const watchTask = () => {
  browserSync.init({
    proxy: "http://autosygnalization-kz-php/dist",
    serveStatic: [{
      route: '/',
      dir: 'dist'
    }],
    notify: false,
  });
  if (!PRODUCTION) {
    watch([paths.styles.watch], sassTask);
    watch([paths.scripts.src], rollupTask);
    watch(['./src/**/*.php'], phpTask);
  }
};

async function cleanDist(dirnames) {
  for (const dir of dirnames) {
    const distPath = path.join(__dirname, dir);

    try {
      await fs.access(distPath);
      const files = await fs.readdir(distPath);
      await Promise.all(files.map(file =>
        fs.rm(path.join(distPath, file), { recursive: true, force: true })
      ));

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
}

const sassTask = () => {
  let stream = src([paths.styles.src])
    .pipe(sass({
      outputStyle: 'expanded',
      silenceDeprecations: ['legacy-js-api'],
    }).on('error', sass.logError));

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
    .pipe(sass({ outputStyle: 'expanded', silenceDeprecations: ['legacy-js-api'] }).on('error', sass.logError))
    .pipe(autoPrefixer())
    .pipe(cleanCSS({ level: 2 }))
    .pipe(dest('./dist/assets/libs/'));
};


const copyStatics = (cb) => {

  const tasks = [];

  tasks.push(
    src(['./src/assets/**/*', '!./src/assets/images/**', '!./src/assets/videos/**'])
      .pipe(dest('./dist/assets'))
  );

  tasks.push(
    src([
      './src/.htaccess',
      './src/index.php',
      './src/404.php',
      './src/sitemap.xml',
      './src/robots.txt',
      './src/yandex_12ed8a33b1d44641.html',
      './src/browserconfig.xml',
      './src/favicon.ico',
      './src/manifest.json'
    ])
      .pipe(dest(paths.dist))
  );

  return Promise.all(tasks)
    .then(() => {
      cb();
    })
    .catch(err => {
      console.error('Ошибка при копировании статических файлов:', err);
      cb(err);
    });
};


const images = (cb) => {
  return src(['./src/assets/images/**/*.{png,jpg,svg}'], { encoding: false })
    .pipe(dest(paths.dist + '/assets/images'))
    .on('end', cb)
};

const videos = (cb) => {
  return src(['./src/assets/videos/**/*.{mp4,png,webp,avif,webm}'], { encoding: false })
    .pipe(dest(paths.dist + '/assets/videos'))
    .on('end', cb)
};

const docs = async () => {
  await cleanDist(['./src/files/docs']);

  return new Promise((resolve, reject) => {
    src(['./src/files/docs/**/*.pdf'], { encoding: false })
      .pipe(dest(paths.dist + '/files/docs/'))
      .on('end', resolve)
      .on('error', reject);
  });
};
const sprite = () => {
  return src(['./src/assets/images/vectors/**/*.svg', '!./src/assets/images/vectors/sprite.svg'])
    .pipe(svgSprite(config))
    .pipe(dest('./dist/assets/images/vectors'));
};

const fonts = (cb) => {
  src('./src/assets/fonts/**/*.{ttf,woff,woff2}')
    .pipe(dest(paths.dist + '/assets/fonts'))
    .on('end', cb);
};

const statics = parallel(() => cleanDist(['dist/assets']), copyStatics, fonts, images, videos, sprite, sassTaskLibs, rollupTask);
const dev = series(() => cleanDist(['dist/files', 'dist/assets/libs']), copyStatics, docs, images, sprite, videos, phpTask, sassTask, sassTaskLibs, rollupTask, watchTask);
const build = series(() => cleanDist(['dist/files']), copyStatics, docs, images, videos, phpTask, sassTask, sassTaskLibs, rollupTask);

export { images, sassTask, sassTaskLibs, rollupTask, phpTask, watchTask, build, statics, docs, sprite, fonts, videos };
export default dev;