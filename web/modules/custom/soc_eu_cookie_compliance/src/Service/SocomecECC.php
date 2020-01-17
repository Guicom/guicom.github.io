<?php
/**
 * SocECC return information eu_cookie_compliance has agree access.
 */

namespace Drupal\soc_eu_cookie_compliance\Service;

class SocomecECC {

  private $config;
  private $cookieName;
  private $cookieNameCategorie;
  private $categorie;

  public function __construct() {
    if (\Drupal::moduleHandler()->moduleExists('eu_cookie_compliance')) {
      $this->config = \Drupal::config('eu_cookie_compliance.settings');
      $this->cookieName = 'cookie-agreed';
      if (!empty($this->config->get('cookie_name'))) {
        $this->cookieName = $this->config->get('cookie_name');
      }
      $this->cookieNameCategorie = $this->cookieName . '-categories';
    }
  }

  public function setCategorie(string $categorie) {
    $this->categorie = $categorie;
  }

  public function getCookieValue() {
    if (!empty($_COOKIE[$this->cookieName])) {
      return $_COOKIE[$this->cookieName];
    }
    return 0;
  }

  public function getCookieCategorieValue() {
    if (!empty($_COOKIE[$this->cookieNameCategorie])) {
      return urldecode($_COOKIE[$this->cookieNameCategorie]);
    }
    return NULL;
  }

  /**
   * @param string $categorie
   *
   * @return bool
   */
  public function hasAccess() {
    if (\Drupal::moduleHandler()->moduleExists('eu_cookie_compliance')) {
      if ($this->getCookieValue() !== 0) {
        if ($this->config->get('method') === 'categories') {
          if (!empty($this->getCookieCategorieValue())) {
            $term = $this->getCookieCategorieValue();
            if (strpos($term, '"'.$this->categorie.'"') !== false) {
              return true;
            }
          }
        }
      }
    }
    return false;
  }

  /**
   * @param string $categorie
   *
   * @return bool
   */
  public function getMessage() {
    if (\Drupal::moduleHandler()->moduleExists('eu_cookie_compliance')) {
      $cookieCategories = $this->config->get('cookie_categories');
      if ($this->config->get('method') === 'categories') {
        $cookieCategories = _eu_cookie_compliance_extract_category_key_label_description($cookieCategories);
        $service = $cookieCategories[$this->categorie]['label'];
        return  t('Service <a href="#" class="display-ecc-popup">@service</a> must be enabled.', ['@service' => $service]);
      }
      else {
        return  t('Service must be enabled <a href="#" class="display-ecc-popup">Here</a>.');
      }
    }
  }
}
