<?php

namespace Drupal\soc_nextpage\TwigExtension;


use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\taxonomy\Entity\Term;

class NextpageTwig extends \Twig_Extension {

  /**
   * Generates a list of all Twig filters that this extension defines.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('getfield', array($this, 'getFieldData')),
      new \Twig_SimpleFilter('getMenuIcon', array($this, 'getMenuIcon')),
      new \Twig_SimpleFilter('getDefaultImg', array($this, 'getDefaultImg')),
    ];
  }

  /**
   * Gets a unique identifier for this Twig extension.
   */
  public function getName() {
    return 'NextpageTwig.twig_extension';
  }

  /**
   * Get json field content.
   */
  public static function getFieldData($string, $extid) {
    $data = '';
    if (isset($string[0])) {
      $json_value = json_decode($string[0]["#context"]["value"]);
      $field = \Drupal::service('soc_nextpage.nextpage_item_handler');
      $data = $field->getFieldFromJson($json_value, $extid);
    }
    return $data;
  }

  /**
   * Get menu item icon.
   */
  public static function getMenuIcon($string) {
    // Get storage attributes.
    $storage = $string->storage();
    // Set return string.
    $url = NULL;
    if ($storage["data-level"]->value() === 1) {
      // Get TID
      $uuid = $storage["data-id"]->value();
      $element = explode(".", $uuid);
      $tid = end($element);
      // Load term.
      $term =  Term::load($tid);
      $icon = $term->get('field_family_menu_icon')->getValue();
      // Load Media.
      if (isset($icon[0]["target_id"])) {
        $media = Media::load($icon[0]["target_id"]);
        $media_item = $media->get('field_media_image')->getValue();
        // Load file and get url
        if (isset($media_item[0]["target_id"])) {
          $file = File::load($media_item[0]["target_id"]);
          $url = $file->createFileUrl();
        }
      }
    }
    return $url;
  }

  /**
   * Get Default image
   */
  public static function getDefaultImg($field, $type) {
    if (empty($field[0])) {
      if ($type === 'product') {
        $data = \Drupal::config('soc_nextpage.product_default_config')
          ->get('default_image_product');
      } elseif ($type === 'product-reference') {
        $data = \Drupal::config('soc_nextpage.product_default_config')
          ->get('default_image_reference');
      }
      if (!empty($data)) {
        $fid = (!empty($data[0])) ? $data[0] : NULL;
        if (!empty($fid)) {
          if ($file = \Drupal\file\Entity\File::load($fid)) {
            if ($path = $file->getFileUri()) {
              if ($url = \Drupal\image\Entity\ImageStyle::load('product_detail')
                ->buildUrl($file->getFileUri())) {
                $render_array = [
                  '#markup' => "<img class='default-img-$type' src='$url'/>",
                ];
                return $render_array;
              }
            }
          }
        }
      }
    }
    return $field;
  }
}
