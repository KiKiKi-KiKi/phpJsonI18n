<?php
declare(strict_types=1);

namespace KikikiKiKi\jsonI18n;

include_once __DIR__ . '/LocaleConverter.php';

class Translation {
  public function __construct(string $locale = 'en-US') {
    $locale = new LocaleConverter($locale);
    echo $locale->getLocale();
  }
}
