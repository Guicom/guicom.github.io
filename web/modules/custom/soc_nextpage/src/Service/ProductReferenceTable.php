<?php

namespace Drupal\soc_nextpage\Service;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Link;
use Drupal\Core\Url;

class ProductReferenceTable {

  /**
   * @param $field
   *
   * @return array
   *   return table header
   */
  public function buildTable($field) {
    $rows = [];
    $header = $this->getHeader($field["items"]);

    foreach ($field["items"] as $key => $item) {
      $json = $item["content"]["#node"]->get('field_reference_json_table')->getValue();
      $url = Url::fromRoute('entity.node.canonical', ['node' => $item["content"]["#node"]->id()]);
      $json = (array) json_decode($json[0]["value"]);
      foreach ($header as $head) {
        if ($head == 'Reference') {
          $rows[$key][$head] = $json[$head] ? Link::fromTextAndUrl($json[$head], $url) : '';
        }
        else {
          $rows[$key][$head] = $json[$head] ? $json[$head]: '';
        }
      }
      $rows[$key]['select'] = $this->getCartLink();
    }

    $footer = $header;
    $header['select'] = t('Select');
    $lib['library'][] = 'soc_nextpage/product-datatable';
    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#footer' => [$footer],
      '#attached' => $lib,
      '#attributes' => [
        'id' => 'product-reference-table'
      ]
    ];
  }

  /**
   * @param $items
   *
   * @return array
   *   return table header
   */
  public function getHeader($items) {
    $header = [];
    foreach ($items as $key => $item) {
      $json = $item["content"]["#node"]->get('field_reference_json_table')->getValue();
      $json = (array) json_decode($json[0]["value"]);
      foreach ($json as $label => $data) {
        //$rows[$label][$key] = $data;
        if (!in_array($label, $header) && !empty($data)) {
          $header[$label] = $label;
        }
      }
    }
    return $header;
  }

  /**
   * @return \Drupal\Component\Render\FormattableMarkup
   *   Formatted html
   */
  public function getCartLink() {
    return new FormattableMarkup("<span class='add-to-favorite'></span>", []);
  }
}
