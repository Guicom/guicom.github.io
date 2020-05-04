<?php

namespace Drupal\soc_nextpage\Service\Manager;

use Drupal;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\soc_nextpage\Service\NextpageApi;
use Drupal\soc_nextpage\Service\NextpageItemHandler;

/**
 * Class ReferenceManager
 *
 * @package Drupal\soc_nextpage\Service\Manager
 */
class ReferenceManager {

  /**
   * @var \Drupal\soc_nextpage\Service\NextpageApi
   */
  private $nextpageApi;

  /**
   * @var \Drupal\soc_nextpage\Service\NextpageItemHandler
   */
  private $nextpageItemHandler;

  public function __construct(NextpageApi $nextpageApi,
                              NextpageItemHandler $nextpageItemHandler) {
    $this->nextpageApi = $nextpageApi;
    $this->nextpageItemHandler = $nextpageItemHandler;
  }


  /**
   * @param $ext_id
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function handle($ext_id) {
    $nids = [];
    // Manage reference.
    $references = $this->nextpageApi->descendantsAndLinks(TRUE, [], [], $ext_id);
    foreach ($references->Elements as $reference) {
      if ($reference->ElementType === 3) {
        if ($entity = $this->nextpageItemHandler->loadByExtID($reference->ExtID, 'node', 'product_reference')) {
          // Update product.
          $nids[] = $this->updateReference($entity, $reference);
        }
        else {
          // Create product.
          $nids[] = $this->createReference($reference);
        }
      }
    }
    return $nids;
  }

  /**
   * @param $reference
   *
   * @return \Drupal\Core\Entity\EntityInterface
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function createReference($reference) {
    $node = \Drupal::entityTypeManager()->getStorage('node')->create([
      'type'        => 'product_reference',
    ]);

    return $this->updateReference($node, $reference);
  }

  /**
   * @param $node
   * @param $reference
   *
   * @return mixed
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function updateReference($node, $reference) {
    $json_field = $this->nextpageItemHandler->formatJsonField($reference->Values);
    $node->set('title', $reference->Values->{'DC_R_ADMIN_Invoice_Description'}->Value);
    $node->set('field_teaser', $reference->Values->{'DC_R_REFERENCE_LONG_DESCRIPTION'}->Value);
    $node->set('field_json_product_data', $json_field);
    $node->set('field_reference_json_table', $this->buildJsonTable($reference->Values));
    $node->set('field_reference_extid', $reference->ExtID);
    $node->set('field_reference_ref', $reference->Values->{'DC_R_REFERENCE'}->Value);
    $node->setPublished();
    $node->set('moderation_state', 'published');

    if ($node->isNew()) {
      // Initialize Cta btn match with entity uuid of paragraphs_library_item defined in soc_content module install
      $ctaArray = [
        'field_product_cta_1' => 'e6335561-cf61-4ed3-b233-6d7b55c1c6e9',
        'field_product_cta_2' => '57f07f79-dd2a-4886-bf7d-417705232104',
        'field_product_cta_3' => '69e5214f-5759-46d5-a0f5-4c8c41e0adc3',
        // ready to by not use for moment 'field_product_cta_4' => 'bd080195-6284-4678-a773-c70b820a50f1',
      ];

      foreach ($ctaArray as $field => $uuid) {
        $entity = \Drupal::entityManager()->loadEntityByUuid('paragraphs_library_item', $uuid);
        if ($entity) {
          $paragraph = Paragraph::create(['type' => 'link']);
          $paragraph->set('field_link_paragraph', $entity->id());
          $paragraph->isNew();
          try {
            $paragraph->save();
            $current = [
              0 => [
                'target_id' => $paragraph->id(),
                'target_revision_id' => $paragraph->getRevisionId(),
              ]
            ];
            $node->set($field, $current);
          }
          catch (\Exception $e) {}
        }
      }
    }

    try {
      $node->save();
      return $node->id();
    }
    catch (\Exception $e) {
      \Drupal::logger('soc_nextpage')->warning($e->getMessage());
    }
    return FALSE;
  }


  public function buildJsonTable($reference) {
    if (isset($reference->DC_R_PRODUCT_STATUS)) {
      $status = $this->nextpageItemHandler->getJsonField($reference->DC_R_PRODUCT_STATUS);
      $json[$status["label"]] = $status["value"];
    }
    if (isset($reference->DC_R_REFERENCE)) {
      $name = $this->nextpageItemHandler->getJsonField($reference->DC_R_REFERENCE);
      $json[$name["label"]] = $name["value"];
    }
    if (isset($reference->DC_R_TC1_VALUE->Value)) {
      $value = $this->nextpageItemHandler->getJsonField($reference->DC_R_TC1_NAME);
      $json[$value["value"][0]] = $reference->DC_R_TC1_VALUE->Value;
    }
    if (isset($reference->DC_R_TC2_VALUE->Value)) {
      $value = $this->nextpageItemHandler->getJsonField($reference->DC_R_TC2_NAME);
      $json[$value["value"][0]] = $reference->DC_R_TC2_VALUE->Value;
    }
    if (isset($reference->DC_R_TC3_VALUE->Value)) {
      $value = $this->nextpageItemHandler->getJsonField($reference->DC_R_TC3_NAME);
      $json[$value["value"][0]] =  $reference->DC_R_TC3_VALUE->Value;
    }
    if (isset($reference->DC_R_TC4_VALUE->Value)) {
      $value = $this->nextpageItemHandler->getJsonField($reference->DC_R_TC4_NAME);
      $json[$value["value"][0]] = $reference->DC_R_TC4_VALUE->Value;
    }
    if (isset($reference->DC_R_PRODUCT_STATUS_DATE->Value)) {
      $status = $this->nextpageItemHandler->getJsonField($reference->DC_R_PRODUCT_STATUS);
      $json[$status["label"]] = $status["value"];
    }

    $json = json_encode($json);
    return $json;
  }


}
