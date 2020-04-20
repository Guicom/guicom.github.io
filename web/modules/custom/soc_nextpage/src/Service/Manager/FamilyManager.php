<?php

namespace Drupal\soc_nextpage\Service\Manager;

use Drupal;
use Drupal\soc_nextpage\Service\NextpageApi;
use Drupal\soc_nextpage\Service\NextpageItemHandler;
use Drupal\system\Entity\Menu;

/**
 * Class ProductManager
 *
 * @package Drupal\soc_nextpage\Service\Manager
 */
class FamilyManager {

  public function __construct(
    NextpageApi $nextpageApi,
    NextpageItemHandler $nextpageItemHandler) {
    $this->nextpageApi = $nextpageApi;
    $this->nextpageItemHandler = $nextpageItemHandler;
  }

  /**
   * @param $pendingFamily
   *
   * @return \Drupal\Core\Entity\EntityInterface|mixed|string|void|null
   */
  public function handle($pendingFamily) {
    // Check if we had to handle this family
    if ($this->isFamily($pendingFamily) === TRUE) {
      // Check if term exist
      if (!$term = $this->loadByExtID($pendingFamily->ExtID)) {
        $term = $this->createFamilyTerm($pendingFamily->ExtID);
      }
      $this->updateFamilyTerm($term, $pendingFamily);
      return $term;
    }
    else {
      $this->getParents($pendingFamily);
    }
  }

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
      'vid' => 'family'
    ]);
    return $term;
  }

  /**
   * @param $node
   * @param $product
   *
   * @return mixed
   */
  function updateFamilyTerm($term, $pendingFamily) {
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

    // Get Subtitle
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

    // Get Description
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

    $term->set('field_family_extid', $pendingFamily->ExtID);

    if (isset($pendingFamily->ParentExtID) && !empty($pendingFamily->ParentExtID)) {
      $term->set('parent', [$this->getTidByExtID($pendingFamily->ParentExtID)]);
    }
    $term->save();

    return $term;
  }

  /**
   *
   */
  public function delete() {
    // @todo : delete script.
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
    $query->condition('field_family_extid', $extID);
    $result = $query->execute();
    if (!empty($result)) {
      $id = reset($result);
      $entity = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->load($id);
    }
    return $entity;
  }

  public function getTidByExtID($extID) {
    $tid = 0;
    $database = \Drupal::database();
    $query = $database->select('taxonomy_term__field_family_extid', 'f');
    $query->fields('f', ['entity_id']);
    $query->condition('f.field_family_extid_value', $extID);
    $result = $query->execute();
    $result = $result->fetchAll();

    if (!empty($result)) {
      $tid = $result[0]->entity_id;
    }

    return $tid;

  }

}
