<?php

namespace Drupal\soc_sales_locations\Batch;

use Drupal\Core\Database\Database;
use Drupal\Core\Database\Transaction;
use Drupal\file\FileInterface;

/**
 * Class LocationsImportBatch.
 */
class LocationsImportBatch {

  /**
   * @param \Drupal\file\FileInterface $file
   *
   * @return array
   */
  public static function locationImport(FileInterface $file) {
    $queue = \Drupal::queue('location_import');
    $queue->createQueue();

    $operations = [];
    $fh = fopen($file->getFileUri(), 'r');
    $i = 0;
    while ($row = fgetcsv($fh, 0, ';')) {
      if ($i !== 0) {
        $operations[] = [
          '\Drupal\soc_sales_locations\Batch\LocationsImportBatch::processRow',
          [$row],
        ];
      }
      $i++;
    }

    return [
      'title' => t('Import Sales Locations Queue Worker'),
      'operations' => $operations,
      '\Drupal\soc_sales_locations\Batch\LocationsImportBatch::finished',
    ];
  }

  /**
   * @param $row
   * @param $context
   */
  public static function processRow($row, &$context) {
    $queue = \Drupal::queue('location_import');
    $queue->createItem($row);
  }

  /**
   * Finish method.
   *
   * @param $success
   * @param $results
   * @param $operations
   */
  public static function finished($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'One post processed.', '@count posts processed.'
      );
    }
    else {
      $message = t('Finished with an error.');
    }
    drupal_set_message($message);
  }
}
