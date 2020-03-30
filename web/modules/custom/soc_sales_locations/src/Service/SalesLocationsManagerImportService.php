<?php

namespace Drupal\soc_sales_locations\Service;

use Drupal\Core\Database\Database;
use Drupal\Core\Database\Transaction;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\FileInterface;
use Drupal\node\NodeInterface;
use Drupal\soc_sales_locations\Helpers\StoreLocationImportHelper;
use Exception;
use InvalidArgumentException;

/**
 * Class SalesLocationsManagerImportService.
 */
class SalesLocationsManagerImportService implements SalesLocationsManagerImportServiceInterface {

  /**
   * Drupal\Core\Entity\EntityManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $em;


  /**
   * @var \Drupal\soc_sales_locations\Helpers\StoreLocationImportHelper
   */
  private $rowNode;

  /**
   * Constructs a new SalesLocationsManagerImportService object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   */
  public function __construct(EntityTypeManagerInterface $entity_manager) {
    $this->em = $entity_manager;
  }

  /**
   * @inheritDoc
   */
  public function validate(FileInterface $file) {
    $fh = fopen($file->getFileUri(), 'r');
    $row = fgetcsv($fh, 0, ";");
    if (empty($row) || count($row) !== 21) {
      return FALSE;
    }
    return TRUE;
  }


  /**
   * @inheritDoc
   */
  public function importRow($row, $date_imported) {
    /** @var \Drupal\node\NodeInterface $node */
    if ($row[0] === '') {
      $node = $this->em->getStorage('node')
        ->create(['type' => StoreLocationImportHelper::CONTENT_TYPE]);
    }
    else {
      $node = $this->em->getStorage('node')->load($row[0]);
    }
    try {
      $this->rowNode = new StoreLocationImportHelper($node);
      $this->rowNode->importTitle($row[1]);
      $this->rowNode->importNameCompany($row[7]);
      $this->rowNode->importNameContact($row[8]);
      $this->rowNode->importFirstName($row[9]);
      $this->rowNode->importAddress($row);
      $this->rowNode->importPhone($row[18]);
      $this->rowNode->importFax($row[19]);
      $this->rowNode->importWebsite($row[20]);
      $this->rowNode->importType($row[6]);
      $this->rowNode->importActivity($row[5]);
      $this->rowNode->importContinent($row[2]);
      $this->rowNode->importArea($row[3]);
      $this->rowNode->importSubArea($row[4]);
      $this->rowNode->saveUpdatedRevisionsNode($date_imported);
      $status = TRUE;
    }
    catch (EntityStorageException $e) {
      $status = FALSE;
    }
    catch (InvalidArgumentException $e){
      $status = FALSE;
    }
    return $status;
  }

  /**
   * @inheritDoc
   */
  public function updateCurrentJob($job_id, $status = 'in_progress') {
    /** @var \Drupal\soc_job\Entity\JobEntity $job */
    $job = \Drupal::entityTypeManager()->getStorage('job')->load($job_id);
    $job->get('field_job_status')->setValue($status);
    $job->get('field_job_heartbeat')->setValue(time());
    $job->save();
    return TRUE;
  }

  /**
   * Rollback for a list of stores locator using an information date.
   *
   * @param $job_id
   *
   * @return bool
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */

  public function setRollbackStores($job_id) {
    $job = \Drupal::entityTypeManager()->getStorage('job')->load($job_id);
    // @done: find all location withe same date information;
    $job_start_date = $job->get('field_job_start_date')->getValue()[0]['value'];
    $stores = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties([
        'type' => 'contenu_location',
        'field_last_imported' => $job_start_date,
      ]);
    \Drupal::messenger()
      ->addError('Enable rollback for ' . count($stores) . ' stores');
    // @todo: how revision n-1 for specific nodes.
    foreach ($stores as $store) {
      // @todo: using a store node.
      \Drupal::messenger()->addWarning($store->label());
    }

    return TRUE;
  }


}
