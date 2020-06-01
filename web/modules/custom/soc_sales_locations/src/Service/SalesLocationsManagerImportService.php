<?php

namespace Drupal\soc_sales_locations\Service;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\soc_sales_locations\Helpers\StoreLocationImportHelper;

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
  public function validate(EntityInterface $file) {
    $fh = fopen($file->getFileUri(), 'r');
    $row = fgetcsv($fh, 0, ";");
    if (empty($row)) {
      return FALSE;
    }
    return TRUE;
  }


  /**
   * @inheritDoc
   * @throws \Exception
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
    $this->rowNode = new StoreLocationImportHelper($node);
    try {
      $this->rowNode->importTitle($row[1]);
      $this->rowNode->importCompanyName($row[7]);
      $this->rowNode->importContactName($row[8]);
      $this->rowNode->importFirstName($row[9]);
      $this->rowNode->importAddress($row);
      $this->rowNode->importPhone($row[18]);
      $this->rowNode->importFax($row[19]);
      $this->rowNode->importWebsite($row[20]);
      $this->rowNode->importType($row[6]);
      $this->rowNode->importActivity($row[5]);
      $this->rowNode->importContinent($row[2]);
      $this->rowNode->importArea($row[3], $row[2]);
      $this->rowNode->importSubArea($row[4], $row[3]);
      $this->rowNode->saveUpdatedRevisionsNode($date_imported);
    }
    catch (\Exception $e) {
      throw new \Exception(t('Row @row was not imported because of an error.', [
        '@row' => $row[1],
      ]));
    }
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
        'field_last_imported_timestamp' => $job_start_date,
      ]);
    \Drupal::messenger()
      ->addWarning('Rollback à faire sur ' . count($stores) . ' stores');
    // @todo: how revision n-1 for specific nodes.
    foreach ($stores as $store) {
      \Drupal::messenger()->addWarning($store->label() . '|'.$store->id());
      $vids = \Drupal::entityTypeManager()->getStorage('node')->revisionIds($store);
      if (count($vids) < 2) {
        \Drupal::messenger()->addWarning('Le node '.$store->label() . '|'.$store->id() . ' récemment importé n\'est plus présent.');
        $store->delete();
      }
      else{
        $revision_id = end($vids);
        /** Node */
        $node  = \Drupal::entityTypeManager()->getStorage('node')->loadRevision($revision_id);
        // @see \Drupal\node\Form\NodeRevisionRevertForm::submitForm()
        // @see \Drupal\node\Form\NodeRevisionRevertForm::buildForm()
/*        ksm($node->label());
        ksm($revision_id);*/
      }
    }

    return TRUE;
  }


}
