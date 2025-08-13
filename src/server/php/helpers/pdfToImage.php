<?php

namespace HELPERS;

use Exception;

class PdfConverter
{
  private const IMAGE_UPLOAD_DIR = '/server/uploads/sertificates/images/';

  /**
   * Конвертирует первую страницу PDF в изображение с помощью прямого вызова Ghostscript.
   *
   * @param string $pdfPath Абсолютный путь к PDF файлу.
   * @return string|null Веб-путь к созданному изображению или null в случае ошибки.
   */
  public static function convert(string $pdfPath): ?string
  {
    if (!file_exists($pdfPath)) {
      error_log("Файл PDF не найден: " . $pdfPath);
      return null;
    }

    $imageUploadPath = $_SERVER['DOCUMENT_ROOT'] . self::IMAGE_UPLOAD_DIR;
    if (!is_dir($imageUploadPath)) {
      mkdir($imageUploadPath, 0755, true);
    }

    $imageName = 'sertificate-preview-' . uniqid() . '.jpg';
    $fullImagePath = $imageUploadPath . $imageName;

    // Прямой вызов Ghostscript
    // Команда будет искать Ghostscript в системных путях (PATH),
    // что делает решение переносимым между Windows и Linux.
    // Убедитесь, что Ghostscript установлен и его путь добавлен в системную переменную PATH на сервере.
    $gsExecutable = PHP_OS_FAMILY === 'Windows' ? 'gswin64c.exe' : 'gs';
    $command = sprintf(
        '%s -sDEVICE=jpeg -dFirstPage=1 -dLastPage=1 -o %s -r150 %s',
        $gsExecutable,
        escapeshellarg($fullImagePath),
        escapeshellarg($pdfPath)
    );

    try {
      // Выполняем команду
      $output = null;
      $return_var = null;
      exec($command, $output, $return_var);

      // Проверяем, была ли команда успешной
      if ($return_var !== 0) {
        throw new Exception("Ghostscript не смог конвертировать файл. Код возврата: $return_var. Вывод: " . implode("\n", $output));
      }

      // Проверяем, был ли создан файл
      if (!file_exists($fullImagePath)) {
        throw new Exception("Ghostscript выполнился, но выходной файл не был создан.");
      }

      return self::IMAGE_UPLOAD_DIR . $imageName;

    } catch (Exception $e) {
      error_log("Ошибка конвертации PDF (прямой вызов Ghostscript): " . $e->getMessage());
      return null;
    }
  }
}

