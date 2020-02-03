<?php
/**
 * @file
 * Contains Drupal\soc_eu_cookie_compliance\Cache\Context\SocomecEccCacheContext.
 */

namespace Drupal\soc_eu_cookie_compliance\Cache\Context;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;


/**
 * Defines the EuCookieComplianceCacheContext service.
 *
 * Cache context ID: 'soc_ecc_cache_context'.
 */
class SocomecEccCacheContext implements CacheContextInterface {

  /**
   * context
   *
   * @const string
   */
  const CONTEXT_ID = 'soc_ecc_cache_context';

  /**
   * Return service
   *
   * @return static
   *   service.
   */
  public static function me(){
    return \Drupal::service('cache_context.soc_ecc_cache_context');
  }

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t('Socomec GPDR ECC');
  }

  /**
   * {@inheritdoc}
   */
  public function getContext() {
    $gpdr = 'socomec_gpdr:no';
    $soc_ecc_service = \Drupal::service('soc_eu_cookie_compliance.soc_ecc');
    if ($soc_ecc_service->getCookieValue() !== 0) {
      $gpdr = 'socomec_gpdr:yes';
    }
    if (!empty($soc_ecc_service->getCookieCategorieValue())) {
      $base64 = base64_encode($soc_ecc_service->getCookieCategorieValue());
      $gpdr .= '-'.$base64;
    }
    return $gpdr;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata() {
    return new CacheableMetadata();
  }

}
