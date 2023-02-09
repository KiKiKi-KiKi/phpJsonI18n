<?php
namespace KikikiKiKi\jsonI18n;

class LocaleConverter {
  // $locale: string[];
  private $locale;

  private function setLocale(string $locale): void {
    $locale_array = locale_parse($locale);
    if (!$locale_array) {
      throw new \InvalidArgumentException('Invalid locale');
    }

    $this->locale = $locale_array;
  }

  public function getLocale(): string {
    return str_replace('_', '-', locale_compose($this->locale));
  }

  public function __construct(string $locale = 'ja') {
    $this->setLocale($locale);
  }
}
