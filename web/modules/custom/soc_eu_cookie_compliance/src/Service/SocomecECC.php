<?php
/**
 * SocECC return information eu_cookie_compliance has agree access.
 */

namespace Drupal\soc_eu_cookie_compliance\Service;

class SocomecECC {

  private $config;
  private $cookieName;
  private $cookieValue;
  private $cookieNameCategorie;
  private $cookieCategorieValue;
  private $categorie;

  public function __construct() {
    if (\Drupal::moduleHandler()->moduleExists('eu_cookie_compliance')) {
      $this->config = \Drupal::config('eu_cookie_compliance.settings');
      $this->cookieName = 'cookie-agreed';
      if (!empty($this->config->get('cookie_name'))) {
        $this->cookieName = $this->config->get('cookie_name');
      }
      $this->cookieNameCategorie = $this->cookieName . '-categories';
      if (!empty($_COOKIE[$this->cookieName])) {
        $this->cookieValue = $_COOKIE[$this->cookieName];
      }
      else{
        $this->cookieValue = 0;
      }
      if (!empty($_COOKIE[$this->cookieNameCategorie])) {
        $this->cookieCategorieValue = $_COOKIE[$this->cookieNameCategorie];
      }
      else {
        $this->cookieCategorieValue = null;
      }
    }
  }

  public function setCategorie(string $categorie) {
    $this->categorie = $categorie;
  }

  public function getCookieValue() {
    return $this->cookieValue;
  }

  public function getCookieCategorieValue() {
    if (!empty($this->cookieCategorieValue)) {
      return urldecode($this->cookieCategorieValue);
    }
    return null;
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
