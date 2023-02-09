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
   * @param string|array $placeholders: The text to be replaced within the localized text
   */
  public function __(string $key, $placeholders = null): string {
    // copy array
    $data = $this->data;

    $keys = explode('.', $key);

    $message = array_reduce($keys, function(array $carry, string $key) {
      if ( !isset($carry[$key]) ) {
        // throw new \OutOfBoundsException("Invalid key: '{$key}'");
        return $carry;
      }

      return $carry[$key];
    }, $data);

    if ( is_array($message) ) {
      throw new \OutOfBoundsException("Invalid key: '{$key}'");
    }

    if ( is_null($placeholders) ) {
      return $message;
    }

    // replace placeholder
    if ( is_array($placeholders) ) {
      return vsprintf($message, $placeholders);
    }

    if ( is_string($placeholders) || is_float($placeholders) || is_int($placeholders) ) {
      return sprintf($message, $placeholders);
    }

    throw new InvalidArgumentException('Placeholders have to be a string or array to return a formatted localized message');
  }

  public function __e(string $key, $placeholders = null): void {
    echo $this->__($key, $placeholders);
  }
}
