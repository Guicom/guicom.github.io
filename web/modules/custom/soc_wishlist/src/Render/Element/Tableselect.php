<?php

namespace Drupal\soc_wishlist\Render\Element;

use Drupal\Core\Render\Element;
use Drupal\Core\Render\Element\Tableselect as CoreTableSelect;

class Tableselect extends CoreTableSelect {

  /**
   * @param array $element
   *
   * @return array
   */
  public static function preRenderTableselect($element) {
    $rows = [];
    $header = $element['#header'];
    if (!empty($element['#options'])) {
      // Generate a table row for each selectable item in #options.
      foreach (Element::children($element) as $key) {
        $row = [];

        $row['data'] = [];
        if (isset($element['#options'][$key]['#attributes'])) {
          $row += $element['#options'][$key]['#attributes'];
        }
        // As table.html.twig only maps header and row columns by order, create
        // the correct order by iterating over the header fields.
        foreach ($element['#header'] as $fieldname => $title) {
          // A row cell can span over multiple headers, which means less row
          // cells than headers could be present.
          if (isset($element['#options'][$key][$fieldname])) {
            // A header can span over multiple cells and in this case the cells
            // are passed in an array. The order of this array determines the
            // order in which they are added.
            if (is_array($element['#options'][$key][$fieldname]) && !isset($element['#options'][$key][$fieldname]['data'])) {
              foreach ($element['#options'][$key][$fieldname] as $cell) {
                $row['data'][] = $cell;
              }
            }
            else {
              $row['data'][] = $element['#options'][$key][$fieldname];
            }
          }
        }

        // Render the checkbox / radio element.
        $row['data'][] = \Drupal::service('renderer')->render($element[$key]);

        $rows[] = $row;
      }
      // Add an empty header or a "Select all" checkbox to provide room for the
      // checkboxes/radios in the first table column.
      if ($element['#js_select']) {
        // Add a "Select all" checkbox.
        $element['#attached']['library'][] = 'core/drupal.tableselect';
        array_push($header, ['class' => ['select-all']]);
      }
      else {
        // Add an empty header when radio buttons are displayed or a "Select all"
        // checkbox is not desired.
        array_push($header, '');
      }
    }

    $element['#header'] = $header;
    $element['#rows'] = $rows;

    return $element;
  }

}
