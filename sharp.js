import sharp from 'sharp';
import fs from 'fs';
import path from 'path';

const copyFile = (sourcePath, destinationPath) => {
  return new Promise((resolve, reject) => {
    const readStream = fs.createReadStream(sourcePath);
    const writeStream = fs.createWriteStream(destinationPath);

    readStream
      .pipe(writeStream)
      .on('finish', resolve)
      .on('error', reject);
  });
};

const convertImages = async (inputDir, outputDir) => {
  const files = fs.readdirSync(inputDir);

  for (const file of files) {
    const inputPath = path.join(inputDir, file);
    const stats = fs.statSync(inputPath);

    if (stats.isDirectory()) {
      // Если это директория, рекурсивно обрабатываем её
      const newOutputDir = path.join(outputDir, file);
      if (!fs.existsSync(newOutputDir)) {
        fs.mkdirSync(newOutputDir);
      }
      await convertImages(inputPath, newOutputDir);
    } else if (file.endsWith('.jpg') || file.endsWith('.png')) {
      // Преобразуем JPG и PNG в AVIF и WEBP
      await sharp(inputPath)
        .toFile(path.join(outputDir, `${path.basename(file, path.extname(file))}.avif`));
      await sharp(inputPath)
        .toFile(path.join(outputDir, `${path.basename(file, path.extname(file))}.webp`));
    } else if (file.endsWith('.webp') || file.endsWith('.avif')) {
      // Если файл уже является WEBP или AVIF, просто копируем его
      const outputPath = path.join(outputDir, file);
      await copyFile(inputPath, outputPath);
    }
  }
};

const inputDirectory = './statics/';
const outputDirectory = './src/client/images';

if (!fs.existsSync(outputDirectory)) {
  fs.mkdirSync(outputDirectory);
}

convertImages(inputDirectory, outputDirectory)
  .then(() => console.log('Конвертация завершена'))
  .catch(err => console.error('Ошибка при конвертации:', err));