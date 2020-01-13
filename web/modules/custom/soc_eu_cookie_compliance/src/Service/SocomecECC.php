<?php
/**
 * SocECC return information eu_cookie_compliance has agree access.
 */

namespace Drupal\soc_eu_cookie_compliance\Service;

class SocomecECC {

  private $config;
  private $cookie_name;
  private $categorie;

  public function __construct() {
    if (\Drupal::moduleHandler()->moduleExists('eu_cookie_compliance')) {
      $this->config = \Drupal::config('eu_cookie_compliance.settings');
      $this->cookie_name = (!empty($this->config->get('cookie_name'))) ? $this->config->get('cookie_name') : 'cookie-agreed';
    }
  }

  public function setCategorie(string $categorie) {
    $this->categorie = $categorie;
  }

  /**
   * @param string $categorie
   *
   * @return bool
   */
  public function hasAccess() {
    if (\Drupal::moduleHandler()->moduleExists('eu_cookie_compliance')) {
      $cookie_name = (!empty($this->config->get('cookie_name'))) ? $this->config->get('cookie_name') : 'cookie-agreed';
      if (!empty($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] !== 0) {
        if ($this->config->get('method') === 'categories') {
          $cookie_name_categories = $cookie_name.'-categories';
          if (!empty($_COOKIE[$cookie_name_categories])) {
            $categories = $_COOKIE[$cookie_name_categories];
            $term = urldecode($categories);
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
      $cookie_categories = $this->config->get('cookie_categories');
      $cookie_categories = $this->config->get('method') === 'categories' ? _eu_cookie_compliance_extract_category_key_label_description($cookie_categories) : FALSE;
      return  t('Service <a href="#" class="display-ecc-popup">@service</a> must be enabled.', ['@service' => $cookie_categories[$this->categorie]['label']]);
    }
  }
}
