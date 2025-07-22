<?php
namespace HELPERS;

class SlugHelper
{
  public static function generate($text, $divider = '-')
  {
    // Transliterate
    $text = self::transliterate($text);

    // Replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // Trim
    $text = trim($text, $divider);

    // Remove duplicate dividers
    $text = preg_replace('~-+~', $divider, $text);

    // Lowercase
    $text = strtolower($text);

    if (empty($text)) {
      return 'n-a';
    }

    return $text;
  }

  private static function transliterate($string)
  {
    $roman = array("Sch", "sch", 'Yo', 'yo', 'Zh', 'zh', 'Kh', 'kh', 'Ts', 'ts', 'Ch', 'ch', 'Sh', 'sh', 'Yu', 'yu', 'Ya', 'ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f');
    $cyrillic = array("Щ", "щ", 'Ё', 'ё', 'Ж', 'ж', 'Х', 'х', 'Ц', 'ц', 'Ч', 'ч', 'Ш', 'ш', 'Ю', 'ю', 'Я', 'я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф');
    return str_replace($cyrillic, $roman, $string);
  }
}