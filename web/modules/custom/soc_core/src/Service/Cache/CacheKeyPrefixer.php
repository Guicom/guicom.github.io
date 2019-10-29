<?php

namespace Drupal\prae_core\Service\Cache;

/**
 * Cache key prefixer helper.
 */
class CacheKeyPrefixer {
  /**
   * Prepare the given cache key for given scope.
   *
   * According to the given parameters, the cache key
   * will be automatically prefixed to be unique per-user.
   *
   * By default, local scope will be used.
   *
   * @param string $key
   *   The cache key.
   * @param string $scope
   *   Scope parameter.
   *
   * @return string
   *   The cache key according to the asked scope.
   *
   * @throws \Exception
   *   If the user is not defined and their scope is used.
   */
  public static function prepare(string $key, string $scope): string {
    // Sanitize the key.
    $key = CacheKeySanitizer::sanitize($key);

    // Check which scope was asked, and add prefix accordingly.
    switch ($scope) {
      case CacheHandler::SCOPE_GLOBAL:
        // Global scope, no prefix.
        // Use with care, as global to all.
        $prefix = 'prae_global_';
        break;

      case CacheHandler::SCOPE_USER:
      default:
        // User scope, user related prefix.
        $user = \Drupal::currentUser();

        if (!is_null($user)) {
          $prefix = 'prae_user_' . $user->id() . '_';
        } else {
          throw new \Exception('Can not use SCOPE_USER without a well defined user for key "' . $key . '"');
        }
        break;
    }

    // Return the new prefixed cache key.
    return $prefix . $key;
  }
}
