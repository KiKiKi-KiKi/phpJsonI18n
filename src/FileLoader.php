<?php
namespace KikikiKiKi\jsonI18n;

include_once __DIR__ . '/LocaleConverter.php';

class FileLoader {
  private static $resourcePath;

  public static function setPath(string $path) {
    if ( !is_dir($path) ) {
      throw new \InvalidArgumentException('Invalid resource path');
    }

    self::$resourcePath = $path;
  }

  public static function getPath(): string {
    return self::$resourcePath;
  }

  private $data = [];

  public function getData() {
    return $this->data;
  }

  public function __construct(string $locale, string $file) {
    $path = self::getPath();
    $locale = new LocaleConverter($locale);
    $lang = $locale->getLocale();

    $data = $this->loadFile( "{$path}/{$lang}/{$file}" );
    $this->data = $data;
  }

  private function convertJsonToArray(string $input): array {
    // Json to Array
    $data = json_decode($input, true);

    // cf. https://www.php.net/manual/en/function.json-last-error.php
    if ( json_last_error() !== \JSON_ERROR_NONE ) {
      throw new \InvalidArgumentException("Error parsing JSON.\n" . json_last_error_msg() . ":" . json_last_error());
    }

    return $data;
  }

  private function loadFile(string $file): array {
    if ( !is_file($file) ) {
      throw new \InvalidArgumentException("{$file} is not a file");
    }

    $contents = file_get_contents($file);

    if ( $contents === false ) {
      throw new \RuntimeException("Error reading file at {$file}.");
    }

    return $this->convertJsonToArray($contents);
  }
}
