<?php

namespace Drupal\soc_nextpage\TwigExtension;


class GetJsonElement extends \Twig_Extension {
  
  /**
   * Generates a list of all Twig filters that this extension defines.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('getfield', array($this, 'getFieldData')),
    ];
  }
  
  /**
   * Gets a unique identifier for this Twig extension.
   */
  public function getName() {
    return 'GetJsonElement.twig_extension';
  }
  
  /**
   * Replaces all numbers from the string.
   */
  public static function getFieldData($string, $extid) {
    $json_value = json_decode($string[0]["#context"]["value"]);
    $data = $json_value->Marketing->value->{$extid}->value ? $json_value->Marketing->value->{$extid}->value : NULL;
    return $data;
  }
}
