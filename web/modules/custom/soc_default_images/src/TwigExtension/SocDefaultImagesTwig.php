<?php

namespace Drupal\soc_default_images\TwigExtension;


use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\taxonomy\Entity\Term;

class SocDefaultImagesTwig extends \Twig_Extension {

  /**
   * Generates a list of all Twig filters that this extension defines.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('getDefaultImg', array($this, 'getDefaultImg')),
    ];
  }

  /**
   * Gets a unique identifier for this Twig extension.
   */
  public function getName() {
    return 'SocDefaultImagesTwig.twig_extension';
  }

  /**
   * Get Default image
   */
  public static function getDefaultImg($field, $type, $style = "product_detail") {
    if (empty($field[0])) {
      $key = NULL;
      switch ($type) {
        case 'product':
          $key = 'default_image_product';
          break;
        case 'product-reference':
          $key = 'default_image_reference';
          break;
        case 'bloc-promo-offer':
          $key = 'default_image_bloc_promo_offer';
          break;
        case 'bloc-digital-asset':
          $key = 'default_image_bloc_promo_digital_asset';
          break;
        case 'bloc-digital-toolbox':
          $key = 'default_image_bloc_promo_toolbox';
          break;
        case 'bloc-digital-custom':
          $key = 'default_image_bloc_promo_custom';
          break;
      }
      if ($key !== NULL) {
        $config = \Drupal::config('soc_default_images.socomec_default_image_form');
        $uuid = $config->get($key);
      }
      if (!empty($uuid)) {
        try {
          /** @var \Drupal\file\Entity\File $file */
          $file = \Drupal::service('entity.repository')->loadEntityByUuid('file', $uuid);
        } catch (\Exception $e) {
          \Drupal::logger('soc_default_images')->error($e->getMessage());
        }
        if ($file) {
          if ($path = $file->getFileUri()) {
            if ($url = \Drupal\image\Entity\ImageStyle::load($style)
              ->buildUrl($file->getFileUri())) {
                return [
                  '#markup' => "<img class='default-img-$type' src='$url'/>",
                  '#url' => $url,
                  '#img-type' => $type,
                ];
            }
          }
        }
      }
    }
    return $field;
  }
}
