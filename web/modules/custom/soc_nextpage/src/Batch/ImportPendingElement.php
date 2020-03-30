<?php

namespace Drupal\soc_nextpage\Batch;

use Drupal\system\Entity\Menu;

class ImportPendingElement {

  public static function buildBatch() {
    $product = \Drupal::service('soc_nextpage.nextpage_api')->descendantsAndLinks();

    foreach ($product->Elements ?? [] as $row) {
      $operations[] = [
        '\Drupal\soc_nextpage\Batch\ImportPendingElement::addPendingElement',
        [$row]
      ];
    }

    // Setup batch.
    $batch = [
      'title' => t('Importing pending product...'),
      'operations' => $operations,
      'init_message' => t('Import is starting.'),
      'finished' => '\Drupal\coc_nextpage\Batch\ImportPendingElement::addPendingElementCallback',
    ];
    batch_set($batch);
  }

  /**
   * Add a pending user to the batch.
   *
   * @param $item
   * @param $context
   */
  public static function addPendingElement($item, &$context) {
    $relationships = [];
    switch ($item->ElementType) {
      // Familly case.
      case 103:
        \Drupal::service('soc_nextpage.nextpage_family_manager')->handle($item);
        break;
      // Product & reference case.
      case 2:
      case 3:
        \Drupal::service('soc_nextpage.nextpage_product_manager')->handle($item);
        break;
      default:
        break;
    }

    // Proceed operation.
    $context['sandbox']['current_item'] = $item;
  }

  public static function addPendingElementCallback() {
    $menu = Menu::load('header');
    $menu->save();
  }
}
