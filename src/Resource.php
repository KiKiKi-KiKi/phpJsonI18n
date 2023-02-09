<?php
namespace KikikiKiKi\jsonI18n;

include_once __DIR__ . '/LocaleConverter.php';
include_once __DIR__ . '/FileLoader.php';

class Resource {
  private static $resourcePath;

  private $data = [];
  private $locale;

  public static function setPath(string $path) {
    if ( !is_dir($path) ) {
      throw new \InvalidArgumentException('Invalid resource path');
    }

    self::$resourcePath = $path;
  }

  public static function getPath(): string {
    return self::$resourcePath;
  }

  public function getLocale(): string {
    return $this->locale;
  }

  public function getData(): array {
    return $this->data;
  }

  public function __construct(string $locale, string $file) {
    $localeConverter = new LocaleConverter($locale);
    $localeStr = $localeConverter->getLocale();
    $this->locale = $localeStr;

    $filePath = $this->createFilePath($localeStr, $file);

    $fileLoader = new FileLoader();
    $data = $fileLoader->loadResource($filePath);
    $this->data = $data;
  }

  private function createFilePath(string $locale, string $file): string {
    $path = self::getPath();

    return "{$path}/{$locale}/{$file}";
  }
}
