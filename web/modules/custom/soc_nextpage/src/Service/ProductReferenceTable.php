<?php

namespace Drupal\soc_nextpage\Service;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Extension\ModuleHandlerInterface;

class ProductReferenceTable {

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   */
  public function __construct(ModuleHandlerInterface $module_handler) {
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('module_handler')
    );
  }

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
        $rows[$key][$head] = isset($json[$head]) ? Link::fromTextAndUrl($json[$head], $url) : '';
      }
      $rows[$key]['select'] = $this->getCartLink($item["content"]["#node"]);
    }

    $footer = $header;
    // We don't want translation ono this string as we have a test on it in JS
    $header['select'] = t('Select', [], ['context' => 'product-reference-table']);
    $lib['library'][] = 'soc_nextpage/product-datatable';
    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
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
  public function getCartLink($node) {
    if ($this->moduleHandler->moduleExists('soc_wishlist')) {
      $wishlistManager = \Drupal::service('soc_wishlist.wishlist_manager');
      $loadSavedItems = $wishlistManager->loadSavedItems();
      $fieldReferenceExtid = $node->get('field_reference_extid')->getValue();
      if (!empty($fieldReferenceExtid[0]['value'])) {
        $extid = $fieldReferenceExtid[0]['value'];
        $url = Url::fromRoute('soc_wishlist.add_item', ['item_id' => $extid])->toString();
      }
      if (!empty($url)) {
        $link = "<a class='add-to-favorite ajax-soc-content-list' data-soc-content-list-ajax='1' 
          data-soc-content-list-item='$extid' href='$url'></a>";
        if (!empty($loadSavedItems[$extid])) {
          $link = "<a class='add-to-favorite soc-list-is-active ajax-soc-content-list' data-soc-content-list-ajax='1' 
            data-soc-content-list-item='$extid' href='$url'></a>";
        }
        return new FormattableMarkup($link, []);
      }
    }
    return [];
  }
}
