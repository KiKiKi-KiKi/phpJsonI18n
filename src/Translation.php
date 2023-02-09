<?php
declare(strict_types=1);

namespace KikikiKiKi\jsonI18n;

include_once __DIR__ . '/FileLoader.php';

class Translation {
  public static function setResourceDir(string $path) {
    FileLoader::setPath($path);
  }

  public function __construct(string $locale = 'en-US', string $file = 'messages.json') {
    $fileLoader = new FileLoader($locale, $file);
    $data = $fileLoader->getData();
    var_dump($data);
  }
}
