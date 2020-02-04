<?php

namespace Drupal\soc_core\Service\Cache;

/**
 * Cache key sanitizer helper.
 */
class CacheKeySanitizer {
  /**
   * Sanitize a cache key string.
   *
   * @param string $key
   *
   * @return string
   */
  public static function sanitize($key) {
    return str_replace(
      [':', '-', '{', '}', '(', ')', '/', '\\', '@'],
      ['', '', '', '', '', '', '', '', ''],
      $key
    );
  }

  /**
   * Sanitize a class name into a key string.
   *
   * @param object $object
   * @param bool $lowerCase
   *
   * @return string
   */
  public static function cleanClass($object, bool $lowerCase = TRUE) {
    // Avoiding "only pass by reference" warning.
    $class = get_class($object);
    $classes = explode('\\', $class);
    $class = end($classes);

    if ($lowerCase) {
      $class = strtolower($class);
    }

    return $class;
  }
}
