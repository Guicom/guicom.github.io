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

  public function __construct(NextpageApi $nextpageApi, NextpageItemHandler $nextpageItemHandler) {
    $this->nextpageApi = $nextpageApi;
    $this->nextpageItemHandler = $nextpageItemHandler;
  }


  /**
   * @param $pendingReference
   *
   * @return \Drupal\Core\Entity\EntityInterface|mixed|string|void|null
   */
  public function handle($ExtId) {
    // Manage refernce
    $references = $this->nextpageApi->descendantsAndLinks(TRUE, [], [], $ExtId);
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
   */
  public function updateReference($node, $reference) {
    $json_field = $this->nextpageItemHandler->formatJsonField($reference->Values);
    $node->set('title', $reference->Values->DC_R_REFERENCE->Value);
    $node->set('field_json_product_data', $json_field);
    $node->set('field_reference_json_table', $this->buildJsonTable($reference->Values ));
    $node->set('field_reference_extid', $reference->ExtID);
    $node->setPublished();
    $node->set('moderation_state', 'published');
    $node->save();
    return $node->id();
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
