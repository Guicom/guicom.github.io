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
  public function importRow($row, $token) {
    /** @var \Drupal\node\NodeInterface $node */
    if ($row[0] === '') {
      $node = $this->em->getStorage('node')
        ->create(['type' => StoreLocationImportHelper::CONTENT_TYPE]);
    }
    else {
      $node = $this->em->getStorage('node')
        ->load($row[0]);
    }
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
    try {
      $this->rowNode->saveUpdatedRevisionsNode();
    }
    catch (EntityStorageException $e){
      \Drupal::messenger()->addError($e->getMessage());
    }
  }
  /**
   * @inheritDoc
   */
  public function updateCurrentJob($job_id){
    /** @var \Drupal\soc_job\Entity\JobEntity $job */
    $job = \Drupal::entityTypeManager()->getStorage('job')->load($job_id);
    $job->get('field_job_status')->setValue('in_progress');
    $job->get('field_job_heartbeat')->setValue(time());
    $job->save();
    return TRUE;
  }


  public function setRollbackStores($job_id){
    $job = \Drupal::entityTypeManager()->getStorage('job')->load($job_id);
  }


}
