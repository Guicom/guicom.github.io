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
      new \Twig_SimpleFilter('getJsonDecode', array($this, 'getJsonDecodeData')),
      new \Twig_SimpleFilter('getfield', array($this, 'getFieldData')),
      new \Twig_SimpleFilter('getMenuIcon', array($this, 'getMenuIcon')),
      new \Twig_SimpleFilter('getKsort', array($this, 'getKsort')),
    ];
  }

  /**
   * Gets a unique identifier for this Twig extension.
   */
  public function getName() {
    return 'NextpageTwig.twig_extension';
  }

  /**
   * Get ksort as array.
   */
  public static function getKsort($string) {
    if (is_array($string)) {
      ksort($string);
    }
    return $string;
  }


  /**
   * Get json as array.
   */
  public static function getJsonDecodeData($string) {
    return json_decode(trim($string), TRUE);
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
}
