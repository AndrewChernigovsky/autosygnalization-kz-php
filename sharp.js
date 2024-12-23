import sharp from 'sharp';
import fs from 'fs';
import path from 'path';

const convertImages = async (inputDir, outputDir) => {
  const files = fs.readdirSync(inputDir);

  for (const file of files) {
    const inputPath = path.join(inputDir, file);
    if (fs.statSync(inputPath).isDirectory()) {
      const newOutputDir = path.join(outputDir, file);
      if (!fs.existsSync(newOutputDir)) {
        fs.mkdirSync(newOutputDir);
      }
      await convertImages(inputPath, newOutputDir);
    } else if (file.endsWith('.jpg') || file.endsWith('.png')) {
      await sharp(inputPath)
        .toFile(path.join(outputDir, `${path.basename(file, path.extname(file))}.avif`));
      await sharp(inputPath)
        .toFile(path.join(outputDir, `${path.basename(file, path.extname(file))}.webp`));
    }
  }
};

const inputDirectory = './src/assets/images/';
const outputDirectory = './statics/images';

if (!fs.existsSync(outputDirectory)) {
  fs.mkdirSync(outputDirectory);
}

convertImages(inputDirectory, outputDirectory)
  .then(() => console.log('Conversion completed!'))
  .catch(err => console.error('Error during conversion:', err));

const inputDirectoryNewImages = './new_images/';
const outputDirectoryNewImages = './statics/images';

if (!fs.existsSync(outputDirectoryNewImages)) {
  fs.mkdirSync(outputDirectoryNewImages);
}

convertImages(inputDirectoryNewImages, outputDirectoryNewImages)
  .then(() => console.log('Conversion completed!'))
  .catch(err => console.error('Error during conversion:', err));