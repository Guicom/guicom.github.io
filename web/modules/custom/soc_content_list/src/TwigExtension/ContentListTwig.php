<?php

namespace Drupal\soc_content_list\TwigExtension;

class ContentListTwig extends \Twig_Extension {

  const SOC_CONTENT_LIST_TWIG_TOKENS = [
    'ajax_btn_current_extid',
    'ajax_btn_current_nid'
  ];

  /**
   * Generates a list of all Twig filters that this extension defines.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('socContentListAddAjaxAtribute', array($this, 'AddAjaxAttribute')),
      new \Twig_SimpleFilter('socContentListGetBtn', array($this, 'getAddUrl')),
    ];
  }

  /**
   * Gets a unique identifier for this Twig extension.
   */
  public function getName() {
    return 'ContentListTwig.twig_extension';
  }

  /**
   * Transmform link.
   */
  public static function AddAjaxAttribute($url) {
    $tokens = self::SOC_CONTENT_LIST_TWIG_TOKENS;
    foreach ($tokens as $token) {
      $position = strpos($url, "[$token]");
      if ($position !== FALSE) {
        switch ($token) {
          case 'ajax_btn_current_extid':
            return t('Added, go to your BOM');
            break;
          case 'ajax_btn_current_nid':
            return t('Added, go to your bookmarks');
            break;
        }
      }
    }
    return FALSE;
  }

  /**
   * Transmform link.
   */
  public static function getAddUrl($url) {
    $tokens = self::SOC_CONTENT_LIST_TWIG_TOKENS;
    foreach ($tokens as $token) {
      $position = strpos($url, "[$token]");
      if ($position !== FALSE) {
        $value = "";
        $node = \Drupal::routeMatch()->getParameter('node');
        if ($node) {
          if ($token === "ajax_btn_current_extid") {
            if ($node->hasField('field_reference_extid')) {
              $fieldReferenceExtid = $node->get('field_reference_extid')->getValue();
              if (!empty($fieldReferenceExtid[0]['value'])) {
                $value = $fieldReferenceExtid[0]['value'];
              }
            }
          }
          elseif ($token === "ajax_btn_current_nid") {
            $value = $node->id();
          }
        }
        if (!empty($value)) {
          $url = str_replace("[$token]", $value, $url);
        }
      }
    }
    return $url;
  }

}
