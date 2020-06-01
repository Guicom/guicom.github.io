<?php

namespace Drupal\soc_nextpage\Batch;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\system\Entity\Menu;

/**
 *
 */
class ImportPendingElement {

  /**
   *
   */
  public static function buildBatch() {
    $pimData = \Drupal::service('soc_nextpage.nextpage_api')->descendantsAndLinks();

    // Get characteristics dictionary.
    $operations[] = [
      '\Drupal\soc_nextpage\Batch\ImportPendingElement::synchroDictionary',
      [],
    ];

    // Add pending elements from PIM data.
    foreach ($pimData->Elements ?? [] as $row) {
      $operations[] = [
        '\Drupal\soc_nextpage\Batch\ImportPendingElement::addPendingElement',
        [$row],
      ];
    }

    // Setup batch.
    $batch = [
      'title' => t('Importing product data from PIM...'),
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
        \Drupal::service('soc_nextpage.nextpage_family_manager')->handle($item, $context);
        break;

      // Product & reference case.
      case 2:
      case 3:
        \Drupal::service('soc_nextpage.nextpage_product_manager')->handle($item, $context);
        break;

      default:
        break;
    }

    // Proceed operation.
    $context['sandbox']['current_item'] = $item;
  }

  /**
   * Called at the end of the import.
   */
  public static function addPendingElementCallback() {
    \Drupal::messenger()->addStatus(t('The import has successfully finished.'));
    $menu = Menu::load('header');
    try {
      $menu->save();
    }
    catch (EntityStorageException $e) {
      \Drupal::logger('soc_netxpage')->warning($e->getMessage());
      throw new EntityStorageException($e->getMessage(), 1);
    }
  }

  /**
   * Trigger characteristics dictionary synchronization.
   */
  public static function synchroDictionary() {
    \Drupal::service('soc_nextpage.nextpage_api')
      ->synchroniseCharacteristicsDictionary();
  }

}
