<?php

namespace Drupal\prae_core\Service\Cache;

/**
 * Cache key generator helper.
 *
 * This service should host all caching configuration logic so it can be reused
 * easily when we want to create/clear cache for some specific entities.
 */
class CacheKeyGenerator {
  /**
   * Get caching configuration for user warranty families.
   *
   * @param int $uid
   *   The user Id
   * @param int $lvl
   *   Search term lvl
   * @param int $tid
   *   Tid lvl 1 selected
   *
   * @return array
   */
  public static function getDictionnaryCache(int $uid, int $lvl, int $tid): array {
    return [
      'key'        => 'warranty_families_' . $lvl . '_' . $tid,
      'parameters' => [
        'scope' => CacheHandler::SCOPE_USER,
        'tags'  => [
          'warranty-families',
          'users',
          'user-' . $uid,
        ],
      ],
    ];
  }
}
