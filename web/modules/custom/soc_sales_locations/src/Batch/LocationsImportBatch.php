<?php

namespace Drupal\soc_sales_locations\Batch;

use Drupal\Core\Database\Database;
use Drupal\Core\Database\Transaction;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\file\FileInterface;
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

    /** @var \Drupal\soc_job\Entity\JobEntity $job */
    $job = \Drupal::entityTypeManager()->getStorage('job')->create();
    $job->setName('Untitled job');
    $job->get('field_job_type')->setValue('location');
    $job->get('field_job_status')->setValue('started');
    $job->get('field_job_start_date')->setValue(time());
    $job->save();
    $operations = [];
    $fh = fopen($file->getFileUri(), 'r');
    $i = 0;
    while ($row = fgetcsv($fh, 0, ';')) {
      if ($i !== 0) {
        $operations[] = [
          '\Drupal\soc_sales_locations\Batch\LocationsImportBatch::processRow',
          [$row, $job->id()],
        ];
      }
      $i++;
    }

    return [
      'title' => t('Import Sales Locations'),
      'operations' => $operations,

      'progress_message' => t('Processed @current out of @total.'),
      'error_message'    => t('An error occurred during processing'),
      'finished' => '\Drupal\soc_sales_locations\Batch\LocationsImportBatch::finished',
    ];
  }

  /**
   * @param $row
   * @param $job_id
   * @param $context
   */
  public static function processRow($row, $job_id, &$context) {

    /** @var \Drupal\soc_sales_locations\Service\SalesLocationsManagerImportService $importer */
    $importer = \Drupal::service('soc_sales_locations.manager.import');
    // @notes: don't known argument for 'token', so using test word.
    $importer->importRow($row, 'test');
    $importer->updateCurrentJob($job_id);
    $context['message'] = 'Traitement en cours…' . $row[1];

    $context['results'][] = $row;
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

    // @todo: loaded le job avec $context['results']['job_id'] pour indiquer que le job est terminé.
    $messenger = \Drupal::messenger();
    if ($success) {
      $messenger->addMessage(t('@count store locators processed.', ['@count' => count($results)]));
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
