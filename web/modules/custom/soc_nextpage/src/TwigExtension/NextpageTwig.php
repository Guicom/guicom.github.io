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
    $json_value = json_decode($string[0]["#context"]["value"]);
    $data = NULL;
    if (isset($json_value->{$extid})) {
      $data = $json_value->{$extid}->value;
    }
    else {
      foreach ($json_value as $values) {
        if (isset($values->value)) {
          if (isset($values->value->{$extid})) {
            $data = $values->value->{$extid}->value;
          }
        }
      }
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
