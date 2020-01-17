<?php
namespace Drupal\soc_eu_cookie_compliance\TwigExtension;


class SocomecGpdrECC extends \Twig_Extension {

  /**
  * Generates a list of all Twig filters that this extension defines.
  */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('socomecGpdrAccess', array($this, 'getAccess'), array('cache' => false)),
      new \Twig_SimpleFilter('socomecGpdrMessage', array($this, 'getMessage')),
    ];
  }

  /**
  * Gets a unique identifier for this Twig extension.
  */
  public function getName() {
    return 'soc_eu_cookie_compliance.twig_extension';
  }

  /**
   * @param $string
   *
   * @return bool
   */
  public static function getAccess($string) {
    $soc_ecc_service = \Drupal::service('soc_eu_cookie_compliance.soc_ecc');
    $soc_ecc_service->setCategorie($string);
    if ($soc_ecc_service->hasAccess()) {
      return true;
    }
    else {
      return false;
    }
  }

  public static function getMessage($string) {
    $soc_ecc_service = \Drupal::service('soc_eu_cookie_compliance.soc_ecc');
    $soc_ecc_service->setCategorie($string);
    return $soc_ecc_service->getMessage();
  }
}
