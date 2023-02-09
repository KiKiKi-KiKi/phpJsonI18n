<?php
namespace KikikiKiKi\jsonI18n;

class FileLoader {
  public function loadResource(string $file): array {
    return $this->loadFile( $file );
  }

  public function __construct() {}

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
