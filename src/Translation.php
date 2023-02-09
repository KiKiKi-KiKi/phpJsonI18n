<?php
declare(strict_types=1);

namespace KikikiKiKi\jsonI18n;

include_once __DIR__ . '/Resource.php';

class Translation {
  private $data = [];

  public static function setup(string $resourcePath, ?string $defaultLocale = 'en-US') {
    Resource::setup($resourcePath, $defaultLocale);
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

    $keys = $this->parseKey($key);

    // Default (en-US) locale: Use key as text, if un exist translate file.
    if ( empty($data) ) {
      $message = end($keys);
      return $this->makeReplacements($message, $replace);
    }

    try {
      $message = array_reduce($keys, function(array $carry, string $key) {
        if ( !isset($carry[$key]) ) {
          throw new \OutOfBoundsException("Invalid key: '{$key}'");
        }

        return $carry[$key];
      }, $data);
    } catch ( \OutOfBoundsException $exception ) {
      $message = end($keys);
    }

    if ( is_array($message) ) {
      throw new \OutOfBoundsException("Invalid key: '{$key}'");
    }

    // replace placeholder
    return $this->makeReplacements($message, $replace);
  }

  public function __e(string $key, array $replace = null): void {
    echo $this->__($key, $replace);
  }

  protected function makeReplacements(string $line, array $replace = null): string {
    if ( empty($replace) ) {
      return $line;
    }

    if ( !is_array($replace) ) {
      throw new \InvalidArgumentException('Placeholders have to be an array to return a formatted localized message');
    }

    $placeholders = [];

    foreach($replace as $key => $value) {
      $placeholders[':' . $key] = $value;
    }

    return strtr($line, $placeholders);
  }

  // Translation JSON file just allow nest single group.
  protected function parseKey(string $key): array {
    if (preg_match('/[^\s]\.[^\s$]/', $key)) {
      return explode('.', $key, 2);
    } else {
      return [$key];
    }
  }
}
