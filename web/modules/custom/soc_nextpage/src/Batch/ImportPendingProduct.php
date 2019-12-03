<?php

namespace Drupal\soc_nextpage\Batch;


use Drupal\soc_nextpage\Service\Manager\ProductManager;

class ImportPendingProduct {

  /**
   * Add a pending user to the batch.
   *
   * @param $item
   * @param $context
   */
  public static function addPendingProduct($item, &$context) {
    switch ($item->ElementType) {
      // Familly case.
      case 1:
        // @TODO : Implement familly manager
        break;
      // Product & reference case.
      case 2:
      case 3:
        $product = new ProductManager();
        $product->handle($item);
        break;
      default:
        break;
    }

    // Proceed operation.
    $context['sandbox']['current_item'] = $item;
  }
}
