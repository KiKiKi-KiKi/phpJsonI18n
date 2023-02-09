<?php
declare(strict_types=1);

namespace KikikiKiKi\jsonI18n;

include_once __DIR__ . '/Resource.php';

class Translation {
  private $data = [];

  public static function setResourceDir(string $path) {
    Resource::setPath($path);
  }

  public function __construct(string $locale = 'en-US', string $file = 'messages.json') {
    $resource = new Resource($locale, $file);
    $this->data = $resource->getData();
  }

  /**
   * @param string $key: The key of localized text (json)
   * @param string[] $replace: The text to be replaced within the localized text
   */
  public function __(string $key, array $replace = null): string {
    // copy array
    $data = $this->data;

    $keys = explode('.', $key);

    $message = array_reduce($keys, function(array $carry, string $key) {
      if ( !isset($carry[$key]) ) {
        throw new \OutOfBoundsException("Invalid key: '{$key}'");
      }

      return $carry[$key];
    }, $data);

    if ( is_array($message) ) {
      throw new \OutOfBoundsException("Invalid key: '{$key}'");
    }

    if ( is_null($replace) ) {
      return $message;
    }

    // replace placeholder
    if ( is_array($replace) ) {
      return $this->makeReplacements($message, $replace);
    }

    throw new InvalidArgumentException('Placeholders have to be an array to return a formatted localized message');
  }

  public function __e(string $key, array $replace = null): void {
    echo $this->__($key, $replace);
  }

  protected function makeReplacements($line, array $replace): string {
    if ( empty($replace) ) {
      return $line;
    }

    $placeholders = [];

    foreach($replace as $key => $value) {
      $placeholders[':' . $key] = $value;
    }

    return strtr($line, $placeholders);
  }
}
