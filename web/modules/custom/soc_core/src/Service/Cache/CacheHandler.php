<?php

namespace Drupal\soc_core\Service\Cache;

use Drupal\Core\Cache\Cache;

/**
 * Class CacheHandler
 *
 * @package Drupal\praconis\Service
 */
class CacheHandler {
  /**
   * Not cached constant.
   */
  const NO_CACHE     = 'NO-CACHE';

  /**
   * Available scopes.
   */
  const SCOPE_GLOBAL = 'global';    // Global scope : no key prefixing - USE WITH CAUTION.
  const SCOPE_USER   = 'user';      // User scope   : key prefixed by user - DEFAULT.

  /**
   * Default TTLs.
   */
  const DEFAULT_USER_TTL = 7200;    // User cache will last 2h by default.
  const DEFAULT_GLOBAL_TTL = 86400; // Global cache will last 24h by default.
  const DEFAULT_TRINITA_TTL = 3600; // Trinita cache will last 1h by default.

  /**
   * Get item from cache by key.
   *
   * @param string $key
   *   The cache unique key.
   * @param array $parameters
   *   Some parameters to adjust the cache.
   *
   * @return mixed|self::NO_CACHE
   *   The cached result of the NO_CACHE constant.
   *
   * @throws \Exception
   */
  public static function get($key, array $parameters = []) {
    // Make sure parameters are clean and prepare cache key.
    $parameters = self::prepareParameters($parameters);
    $key = CacheKeyPrefixer::prepare($key, $parameters['scope']);

    $cached = \Drupal::cache()->get($key, FALSE);
    return $cached ? $cached->data : self::NO_CACHE;
  }

  /**
   * Set item into cache.
   *
   * @param string $key
   *   The cache key to use.
   * @param mixed $item
   *   The value to set into the cache.
   * @param array $parameters
   *   Some parameters to set the domain, scope, etc.
   *
   * @throws \Exception
   */
  public static function set($key, $item, array $parameters = []) {
    // Make sure parameters are clean and prepare cache key.
    $parameters = self::prepareParameters($parameters);
    $key = CacheKeyPrefixer::prepare($key, $parameters['scope']);

    \Drupal::cache()->set($key, $item, $parameters['expire'], $parameters['tags']);
  }

  /**
   * Delete one or more cache keys.
   *
   * @param string|array $keys
   * @param array $parameters
   *
   * @throws \Exception
   *   If the argument is not an array nor a string.
   */
  public static function delete($keys, array $parameters = []) {
    // Make sure parameters are clean and prepare cache key.
    $parameters = self::prepareParameters($parameters);

    // Force array if string given.
    if (!is_array($keys) && is_string($keys)) {
      $keys = [$keys];
    } elseif (!is_array($keys)) {
      throw new \Exception('the "keys" argument must be an array of strings, or a simple string.');
    }

    if (!empty($keys)) {
      $cleanKeys = [];
      foreach ($keys as $key) {
        $cleanKeys[] = CacheKeyPrefixer::prepare($key, $parameters['scope']);
      }

      \Drupal::cache()->deleteMultiple($cleanKeys);
    }
  }

  /**
   * Invalidate some cache tags.
   *
   * @param array $tags
   */
  public static function invalidateTags(array $tags) {
    Cache::invalidateTags($tags);
  }

  /**
   * Invalidate this cache tag.
   *
   * @param string $tag
   */
  public static function invalidateTag(string $tag) {
    self::invalidateTags([$tag]);
  }

  /**
   * Invalidate this user cache tag.
   *
   * @param int $id
   */
  public static function invalidateUserTag(int $id = -1) {
    if (-1 === $id) {
      $id = \Drupal::currentUser()->id();
    }

    self::invalidateTag('user-' . $id);
  }

  /**
   * Helper to prepare parameters.
   *
   * @param array $parameters
   *
   * @return array
   *   An array with key 'scope', 'tags', 'expire'.
   */
  protected static function prepareParameters(array $parameters = []) {
    // Set cache scope.
    $parameters['scope'] = $parameters['scope'] ?? self::SCOPE_USER;

    // Set tags.
    self::defaultCacheTags($parameters);

    // Set expiration.
    self::defaultExpiration($parameters);

    return $parameters;
  }

  /**
   * Define default cache tags for the given parameters.
   *
   * @param array &$parameters
   */
  protected static function defaultCacheTags(array &$parameters) {
    switch ($parameters['scope']) {
      case self::SCOPE_GLOBAL:
        $defaultTags = ['praeconis', 'global'];
        break;

      case self::SCOPE_USER:
      default:
      $defaultTags = ['praeconis', 'user', 'user-' . \Drupal::currentUser()->id()];
        break;
    }

    $parameters['tags'] = Cache::mergeTags($parameters['tags'] ?? [], $defaultTags);
  }

  /**
   * Define default cache expiration for the given parameters.
   *
   * @param array &$parameters
   */
  protected static function defaultExpiration(array &$parameters) {
    // Define the TTL if specified.
    if (isset($parameters['expire']) && $parameters['expire'] !== false) {
      $expire = $parameters['expire'];
      if ($expire instanceof \DateTimeInterface) {
        $expire = $expire->getTimestamp();
      } elseif (is_integer($expire)) {
        // Do nothing if we already have an unix timestamp.
      }
    } else {
      $config = \Drupal::config('prae_core.cache.settings');

      // First check based on scope.
      switch ($parameters['scope']) {
        case self::SCOPE_GLOBAL:
          // Global cache will last 24h by default.
          $expire = time() + ($config->get('global_cache_duration_validity') ?? self::DEFAULT_GLOBAL_TTL);
          break;

        case self::SCOPE_USER:
        default:
          // User cache will last 2h by default.
          $expire = time() + ($config->get('user_cache_duration_validity') ?? self::DEFAULT_USER_TTL);
          break;
      }

      // Then override by tags.
      if (in_array('trinita', $parameters['tags'] ?? [])) {
        // Trinita cache will last 1h by default.
        $expire = time() + ($config->get('trinita_cache_duration_validity') ?? self::DEFAULT_TRINITA_TTL);
      }
    }

    $parameters['expire'] = $expire;
  }
}
