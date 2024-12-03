import sharp from 'sharp';
import fs from 'fs';
import path from 'path';

const convertImages = async (inputDir, outputDir) => {
  // Читаем содержимое директории
  const files = fs.readdirSync(inputDir);

  for (const file of files) {
    const inputPath = path.join(inputDir, file);
    const outputPath = path.join(outputDir, file);

    // Проверяем, является ли текущий элемент директорией
    if (fs.statSync(inputPath).isDirectory()) {
      // Если это директория, создаем соответствующую директорию в outputDir
      const newOutputDir = path.join(outputDir, file);
      if (!fs.existsSync(newOutputDir)) {
        fs.mkdirSync(newOutputDir);
      }
      // Рекурсивно обрабатываем вложенные директории
      await convertImages(inputPath, newOutputDir);
    } else if (file.endsWith('.jpg') || file.endsWith('.png')) {
      // Если это файл изображения, конвертируем его
      await sharp(inputPath)
        .toFile(path.join(outputDir, `${path.basename(file, path.extname(file))}.avif`));
      await sharp(inputPath)
        .toFile(path.join(outputDir, `${path.basename(file, path.extname(file))}.webp`));
    }
  }
};

const inputDirectory = './src/assets/images/';
const outputDirectory = './dist/assets/images';

if (!fs.existsSync(outputDirectory)) {
  fs.mkdirSync(outputDirectory);
}

convertImages(inputDirectory, outputDirectory)
  .then(() => console.log('Conversion completed!'))
  .catch(err => console.error('Error during conversion:', err));