<?php

namespace Drupal\soc_nextpage\Batch;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\system\Entity\Menu;

class ImportPendingElement {

  public static function buildBatch() {
    $product = \Drupal::service('soc_nextpage.nextpage_api')->descendantsAndLinks();

    $operations[] = [
      '\Drupal\soc_nextpage\Batch\ImportPendingElement::synchroDictionary',
      []
    ];
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
      'finished' => '\Drupal\soc_nextpage\Batch\ImportPendingElement::addPendingElementCallback',
    ];
    batch_set($batch);
  }

  /**
   * Add a pending item to the batch.
   *
   * @param $item
   * @param $context
   */
  public static function addPendingElement($item, &$context) {
    switch ($item->ElementType) {
      // Family case.
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
    try {
      $menu->save();
    } catch (EntityStorageException $e) {
    }
  }

  public static function synchroDictionary() {
    \Drupal::service('soc_nextpage.nextpage_api')
      ->synchroniseCharacteristicsDictionary();
  }
}
