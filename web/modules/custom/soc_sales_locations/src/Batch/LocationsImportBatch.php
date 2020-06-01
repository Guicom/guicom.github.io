<?php

namespace Drupal\soc_sales_locations\Batch;


use Drupal\Core\Entity\EntityInterface;

/**
 * Class LocationsImportBatch.
 */
class LocationsImportBatch {


  /**
   * @param \Drupal\Core\Entity\EntityInterface $file
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\TypedData\Exception\ReadOnlyException
   */
  public static function locationImport(EntityInterface $file) {
    $date_start_import = time();
    /** @var \Drupal\soc_job\Entity\JobEntity $job */
    $job = \Drupal::entityTypeManager()->getStorage('job')->create();
    $job->setName('Untitled job '.$date_start_import);
    $job->get('field_job_type')->setValue('location');
    $job->get('field_job_status')->setValue('started');
    $job->get('field_job_start_date')->setValue($date_start_import);
    $job->save();


    // Get items count.
    $fp = file($file->getFileUri(), FILE_SKIP_EMPTY_LINES);
    $max = count($fp) -1 ;

    $i = 0;
    $operations = [];
    $fh = fopen($file->getFileUri(), 'r');
    while ($row = fgetcsv($fh, 0, ';')) {
      if ($i !== 0) {
        $options = [
          'row' => $row,
          'date_start_import' => $date_start_import,
          'job_id' => $job->id(),
          'max' => $max,
        ];
        $operations[] = [
          '\Drupal\soc_sales_locations\Batch\LocationsImportBatch::importOperationRow',
          [
            'options' => $options,
          ],
        ];
      }
      $i++;
    }
    return [
      'title' => t('Importing office locations...'),
      'operations' => $operations,
      'progress_message' => t('Processed @current out of @total.'),
      'error_message'    => t('An error occurred during processing'),
      'finished' => '\Drupal\soc_sales_locations\Batch\LocationsImportBatch::finished',
    ];
  }

  /**
   * @param array $options
   * @param $context
   */
  public static function importOperationRow(array $options, &$context) {
    $row = $options['row'];
    $date_start_import = $options['date_start_import'];
    $job_id = $options['job_id'];
    /** @var \Drupal\soc_sales_locations\Service\SalesLocationsManagerImportService $importer */
    $importer = \Drupal::service('soc_sales_locations.manager.import');
    try {
      $importer->importRow($row, $date_start_import);
      $status = TRUE;
    } catch (\Exception $e) {
      \Drupal::messenger()->addWarning($e->getMessage());
      $status = FALSE;
    }
    $context['results'][] = [
      'row' => $row,
      'options' => [
        'job_id' => $job_id,
        'status' => $status,
      ],
    ];
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
    $messenger = \Drupal::messenger();
    /** @var \Drupal\soc_sales_locations\Service\SalesLocationsManagerImportService $importer */
    $importer = \Drupal::service('soc_sales_locations.manager.import');
    $options = reset($results)['options'];

    if ($success) {
      if(self::findIfRowImportFailed($results)) {
        $messenger->addError('Import has partially failed, the other locations were successfully processed.');
        //$importer->updateCurrentJob($options['job_id'],'failed');
        //$importer->setRollbackStores($options['job_id']);
      }
      else {
        $messenger->addMessage(t('@count office locations processed.', ['@count' => count($results)]));
        //$importer->updateCurrentJob($options['job_id'],'done');
      }
    }
    else {
      // An error occurred.
      // $operations contains the operations that remained unprocessed.
      $error_operation = reset($operations);
      $messenger->addMessage(
        t('An error occurred while processing @operation with arguments : @args',
          [
            '@operation' => $error_operation[0],
            '@args' => print_r($error_operation[0], TRUE),
          ]
        )
      );
      //$importer->updateCurrentJob($options['job_id'],'failed');
      //$importer->setRollbackStores($options['job_id']);
    }
  }

  /**
   * Find if an import row has been failed.
   *
   */
  private static function findIfRowImportFailed($results):bool {
    $fail = FALSE;
    foreach ($results as $result_row) {
      if (!$result_row['options']['status']) {
        $fail = TRUE;
      }
    }
    return $fail;
  }
}
