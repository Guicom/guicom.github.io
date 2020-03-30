<?php

namespace Drupal\soc_sales_locations\Batch;

use Drupal\Core\Database\Database;
use Drupal\Core\Database\Transaction;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\file\FileInterface;
use Drupal\soc_job\Entity\JobEntity;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    // @todo: create d'un job.
    // @todo: connaitre le id du job.

    $date_start_import = time();
    /** @var \Drupal\soc_job\Entity\JobEntity $job */
    $job = \Drupal::entityTypeManager()->getStorage('job')->create();
    $job->setName('Untitled job '.$date_start_import);
    $job->get('field_job_type')->setValue('location');
    $job->get('field_job_status')->setValue('started');
    $job->get('field_job_start_date')->setValue($date_start_import);
    $job->save();


    $fp = file($file->getFileUri(), FILE_SKIP_EMPTY_LINES);
    $max = count($fp)-1;
    return [
      'title' => t('Import Sales Locations'),
      'operations' => [
        [
          '\Drupal\soc_sales_locations\Batch\LocationsImportBatch::processAllRows',
          [
            $file,
            $date_start_import,
            $job->id(),
            $max
          ],
        ],
      ],
      'progress_message' => t('Processed @current out of @total.'),
      'error_message'    => t('An error occurred during processing'),
      'finished' => '\Drupal\soc_sales_locations\Batch\LocationsImportBatch::finished',
    ];
  }


  /**
   * @param $file
   * @param $context
   */
  public static function processAllRows(FileInterface $file, $date_start_import, $job_id, $max, &$context){

    if (empty($context['sandbox'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['current_id'] = 0;
      $context['sandbox']['max'] = $max;
      $context['sandbox']['job_id'] = $job_id;
    }

    $i = 0;
    $fh = fopen($file->getFileUri(), 'r');
    while ($row = fgetcsv($fh, 0, ';')) {
      if ($i !== 0) {
        /** @var \Drupal\soc_sales_locations\Service\SalesLocationsManagerImportService $importer */
        $importer = \Drupal::service('soc_sales_locations.manager.import');
        // @notes: don't known argument for 'token', so using test word.
        $importer->importRow($row, $date_start_import);
        $context['message'] = $row[1];
        $context['sandbox']['progress']++;
        $context['results'][] = [
          'row' => $row,
          'options' => ['job_id' => $job_id],
        ];
        $context['sandbox']['current_id'] = $i;
        $importer->updateCurrentJob($job_id);
      }
      $i++;
    }
    if ($context['sandbox']['progress'] != $context['sandbox']['max']) {
      $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['max'];
    }

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
    if ($success) {
      $messenger->addMessage(t('@count store locators processed.', ['@count' => count($results)]));
      $options = reset($results)['options'];
      /** @var \Drupal\soc_sales_locations\Service\SalesLocationsManagerImportService $importer */
      $importer = \Drupal::service('soc_sales_locations.manager.import');
      $importer->updateCurrentJob($options['job_id'],'done');
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
    }

    //return new RedirectResponse(\Drupal::url('<front>'));

  }
}
