<?php

namespace Drupal\soc_sales_locations\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;

/**
 * Processes Node Tasks.
 *
 * @QueueWorker(
 *   id = "location_import",
 *   title = @Translation("Import location"),
 *   cron = {"time" = 300}
 * )
 */
class LocationImport extends QueueWorkerBase {
  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    /** @var \Drupal\soc_sales_locations\Service\SalesLocationsManagerImportService $importer */
    $importer = \Drupal::service('soc_sales_locations.manager.import');
    // @notes: don't known argument for 'token', so using test word.
    $importer->importRow($data, 'test');
  }

}
