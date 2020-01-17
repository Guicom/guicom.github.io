<?php

namespace Drupal\soc_eu_cookie_compliance\Cache\Context;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;


/**
 * Defines the EuCookieComplianceCacheContext service.
 *
 * Cache context ID: 'socomec_ecc_gpdr'.
 */
class SocomecEccCacheContext implements CacheContextInterface {
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
    $gpdr = 'socomec-gpdr:no';
    $soc_ecc_service = \Drupal::service('soc_eu_cookie_compliance.soc_ecc');
    if ($soc_ecc_service->getCookieValue() !== 0) {
      $gpdr = 'socomec-gpdr:yes';
    }
    if (!empty($soc_ecc_service->getCookieCategorieValue())) {
      $serializeCategorie = serialize($soc_ecc_service->getCookieCategorieValue());
      $gpdr .= '-'.$serializeCategorie;
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