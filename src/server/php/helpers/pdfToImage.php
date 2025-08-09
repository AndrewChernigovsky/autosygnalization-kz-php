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
    // ВАЖНО: Укажите здесь абсолютный путь к исполняемому файлу gs.exe (Ghostscript) на вашем сервере.
    // Пример для Windows: 'C:\\Program Files\\gs\\gs10.03.1\\bin\\gswin64c.exe'
    // Пример для Linux: '/usr/bin/gs'
    $gsPath = 'C:\\OSPanel\\modules\\php\\PHP_8.1\\gs.exe'; // Жестко заданный путь
    $command = sprintf(
        '"%s" -sDEVICE=jpeg -dFirstPage=1 -dLastPage=1 -o "%s" -r150 "%s"',
        $gsPath,
        $fullImagePath,
        $pdfPath
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

