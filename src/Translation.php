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
    var_dump($this->data);
  }
}
