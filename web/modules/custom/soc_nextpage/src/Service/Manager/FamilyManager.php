<?php

namespace Drupal\soc_nextpage\Service\Manager;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\soc_nextpage\Service\NextpageApi;
use Drupal\soc_nextpage\Service\NextpageItemHandler;
use Drupal\soc_rollback\Service\RollbackImport;
use Drupal\taxonomy\Entity\Term;

/**
 * Class ProductManager.
 *
 * @package Drupal\soc_nextpage\Service\Manager
 */
class FamilyManager {

  /**
   * @var \Drupal\soc_nextpage\Service\Manager\RollbackImport
   */
  private $rollbackImport;

  /**
   * @var \Drupal\soc_nextpage\Service\NextpageItemHandler
   */
  private $nextpageItemHandler;

  /**
   * @var \Drupal\soc_nextpage\Service\NextpageApi
   */
  private $nextpageApi;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  private $logger;

  /**
   *
   */
  public function __construct(
    NextpageApi $nextpageApi,
    NextpageItemHandler $nextpageItemHandler,
    RollbackImport $rollbackImport,
    LoggerChannelFactoryInterface $channelFactory) {
    $this->nextpageApi = $nextpageApi;
    $this->nextpageItemHandler = $nextpageItemHandler;
    $this->rollbackImport = $rollbackImport;
    $this->logger = $channelFactory->get('soc_nextpage');
  }

  /**
   * @param $pendingFamily
   *
   * @param $context
   *
   * @return \Drupal\Core\Entity\EntityInterface|mixed|string|void|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function handle($pendingFamily, $context) {
    // Check if we had to handle this family.
    if ($this->isFamily($pendingFamily) === TRUE) {
      $state = 'updated';
      // Check if term exist.
      if (!$term = $this->loadByExtID($pendingFamily->ExtID)) {
        $term = $this->createFamilyTerm($pendingFamily->ExtID);
        $state = 'created';
      }
      $this->updateFamilyTerm($term, $pendingFamily, $context);

      // Feed rollback service.
      $this->rollbackImport->updateJob($context['job_id'],
        [
          'operation' => 'update_entity',
          'state' => $state,
          'entity' => $term,
        ]);

      return $term;
    }
    else {
      $this->getParents($pendingFamily);
    }
  }

  /**
   * @param $pendingFamily
   *
   * @return bool
   */
  public function isFamily($pendingFamily) {
    if (isset($pendingFamily->Values->C_LV1_TITLE) ||
      isset($pendingFamily->Values->C_LV2_TITLE) ||
      isset($pendingFamily->Values->C_LV3_TITLE)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   *
   */
  public function getParents($pendingFamily) {
    $linkNode = "##LinkNodeFPR";
    if (isset($pendingFamily->Values->{$linkNode}->LinkedElements[0]->ElementID)) {
      $productId = $pendingFamily->Values->{$linkNode}->LinkedElements[0]->ElementID;
      $familyID = $pendingFamily->ID;
      $familyParentId = $this->getTidByExtID($pendingFamily->ParentExtID);
      $this->nextpageItemHandler->insertRelation($familyID, $productId, $familyParentId);
    }
  }

  /**
   * @param $product
   *
   * @return \Drupal\Core\Entity\EntityInterface
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function createFamilyTerm($textId) {
    $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->create([
      'vid' => 'family',
    ]);
    return $term;
  }

  /**
   * Update product family.
   *
   * @param $term
   * @param $pendingFamily
   *
   * @return mixed
   */
  public function updateFamilyTerm(Term $term, $pendingFamily, $job_id) {
    // Get title.
    $name = $pendingFamily->ExtID;
    if (isset($pendingFamily->Values->C_LV1_TITLE)) {
      $name = $pendingFamily->Values->C_LV1_TITLE->Value;
    }
    elseif (isset($pendingFamily->Values->C_LV2_TITLE)) {
      $name = $pendingFamily->Values->C_LV2_TITLE->Value;
    }
    elseif (isset($pendingFamily->Values->C_LV3_TITLE)) {
      $name = $pendingFamily->Values->C_LV3_TITLE->Value;
    }
    $term->set('name', $name);

    // Get Subtitle.
    $subtitle = '';
    if (isset($pendingFamily->Values->C_LV1_SUBTITLE)) {
      $subtitle = $pendingFamily->Values->C_LV1_SUBTITLE->Value;
    }
    elseif (isset($pendingFamily->Values->C_LV2_SUBTITLE)) {
      $subtitle = $pendingFamily->Values->C_LV2_SUBTITLE->Value;
    }
    elseif (isset($pendingFamily->Values->C_LV3_SUBTITLE)) {
      $subtitle = $pendingFamily->Values->C_LV3_SUBTITLE->Value;
    }
    $term->set('field_family_sub_title', $subtitle);

    // Get Description.
    $description = '';
    if (isset($pendingFamily->Values->C_LV1_DESCRIPTION)) {
      $description = $pendingFamily->Values->C_LV1_DESCRIPTION->Value;
    }
    elseif (isset($pendingFamily->Values->C_LV2_DESCRIPTION)) {
      $description = $pendingFamily->Values->C_LV2_DESCRIPTION->Value;
    }
    elseif (isset($pendingFamily->Values->C_LV3_DESCRIPTION)) {
      $description = $pendingFamily->Values->C_LV3_DESCRIPTION->Value;
    }
    $term->set('description', $description);

    $term->set('field_extid', $pendingFamily->ExtID);

    if (isset($pendingFamily->ParentExtID) && !empty($pendingFamily->ParentExtID)) {
      $term->set('parent', [$this->getTidByExtID($pendingFamily->ParentExtID)]);
    }
    $term->setNewRevision(TRUE);
    $term->revision_log = t('Created revision for @nid in Job @job_id',
      [
        "@nid" => $term->id(),
        "@job_id" => $job_id,
      ]);
    $term->setRevisionCreationTime(REQUEST_TIME);

    try {
      $term->save();
    }
    catch (\Exception $e) {
      throw new \Exception($e->getMessage(), 1);
    }

    return $term;
  }

  /**
   * Delete non imported family.
   */
  public function delete() {
    // Get all Imported family.
    $imported = [];
    $connection = \Drupal::database();
    $items = $connection->select('soc_rollback_items', 'sri')
      ->condition('sri.entity_type', 'taxonomy_term')
      ->fields('sri', ['entity_id']);
    $data = $items->execute();
    $results = $data->fetchAll(\PDO::FETCH_OBJ);
    foreach ($results as $result) {
      $imported[] = $result->entity_id;
    }

    $family = [];
    $storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    $terms = $storage->loadTree('family');
    foreach ($terms as $term) {
      if (!in_array($term->tid, $imported)) {
        $family[] = $term->tid;
      }
    }

    $family = $storage->loadMultiple($family);

    try {
      $storage->delete($family);
    }
    catch (\Exception $e) {
      throw new \Exception($e->getMessage(), 1);
    }
    $this->logger()->info(t('Family purged'));
  }

  /**
   * @param $extID
   * @param $type
   *
   * @return \Drupal\Core\Entity\EntityInterface|string|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function loadByExtID($extID) {
    $entity = '';
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', 'family');
    $query->condition('field_extid', $extID);
    $result = $query->execute();

    if (!empty($result)) {
      $id = reset($result);
      $entity = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->load($id);
    }
    return $entity;
  }

  /**
   *
   */
  public function getTidByExtID($extID) {
    $tid = 0;
    $database = \Drupal::database();
    $query = $database->select('taxonomy_term__field_extid', 'f');
    $query->fields('f', ['entity_id']);
    $query->condition('f.field_extid_value', $extID);
    $result = $query->execute();
    $result = $result->fetchAll();

    if (!empty($result)) {
      $tid = $result[0]->entity_id;
    }
    return $tid;

  }

}
