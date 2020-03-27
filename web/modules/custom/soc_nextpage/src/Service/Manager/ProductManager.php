<?php

namespace Drupal\soc_nextpage\Service\Manager;

use Drupal;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\soc_nextpage\Service\NextpageApi;
use Drupal\soc_nextpage\Service\NextpageItemHandler;

/**
 * Class ProductManager
 *
 * @package Drupal\soc_nextpage\Service\Manager
 */
class ProductManager {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\soc_nextpage\Service\Manager\ReferenceManager
   */
  protected $refrenceManager;

  /**
   * @var \Drupal\soc_nextpage\Service\NextpageApi
   */
  protected $nextpageApi;

  /**
   * @var \Drupal\soc_nextpage\Service\NextpageItemHandler
   */
  private $nextpageItemHandler;

  public function __construct(
    ReferenceManager $referenceManager,
    NextpageApi $nextpageApi,
    NextpageItemHandler $nextpageItemHandler) {
    $this->refrenceManager = $referenceManager;
    $this->nextpageApi = $nextpageApi;
    $this->nextpageItemHandler = $nextpageItemHandler;
  }

  /**
   * @param $pendingProduct
   *
   * @return \Drupal\Core\Entity\EntityInterface|mixed|string|void|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function handle($pendingProduct) {
    // First, manage product.
    // Check if all field are filled.
    if ($this->checkCreate($pendingProduct) == FALSE) {
      $this->nextpageItemHandler->deleteRelation($pendingProduct->ID);
      return;
    }
    else {
      // First, create references.
      $this->referencesNids =  $this->refrenceManager->handle($pendingProduct->ExtID);
      if ($entity = $this->nextpageItemHandler->loadByExtID($pendingProduct->ExtID, 'node', 'produit')) {
        // Update product.
        $entity = $this->updateProduct($entity, $pendingProduct);
      }
      else {
        // Create product.
        $entity = $this->createProduct($pendingProduct);
      }
    }

    return $entity;
  }

  /**
   * @param $pendingProduct
   *
   * @return bool
   */
  public function checkCreate($pendingProduct) {
    if (isset($pendingProduct->Values->DC_P_PRODUCT_NAME->Value) &&
    isset($pendingProduct->Values->DC_P_PRODUCT_SHORT_DESCRIPTION->Value) &&
    isset($pendingProduct->Values->DC_P_ASSORTMENT_WIDTH->Value) &&
    isset($pendingProduct->Values->DC_P_UNIQUE_VALUE_PROPOSAL->Value) &&
    isset($pendingProduct->Values->DC_P_FUNCTIONS->Value)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
  /**
   * @param $product
   *
   * @return \Drupal\Core\Entity\EntityInterface
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function createProduct($product) {
    $node = \Drupal::entityTypeManager()->getStorage('node')->create([
      'type'        => 'product',
    ]);

    $this->updateProduct($node, $product);
    return $node;
  }

  /**
   * @param $node
   * @param $product
   *
   * @return mixed
   */
  public function updateProduct($node, $product) {
    $node->set('field_product_teaser', $product->Values->DC_P_PRODUCT_SHORT_DESCRIPTION->Value . ' - ' . $product->Values->DC_P_ASSORTMENT_WIDTH->Value);
    $node->set('title', $product->Values->DC_P_PRODUCT_NAME->Value);
    $node->set('field_json_product_data', $this->nextpageItemHandler->formatJsonField($product->Values));
    $node->set('field_product_extid', $product->ExtID);
    $node->setPublished();
    $node->set('moderation_state', 'published');

    if (isset($this->referencesNids)) {
      foreach($this->referencesNids as $index => $referencesNid) {
        if ($index == 0) {
          $node->set('field_product_reference', $referencesNid);
        }
        else {
          $node->get('field_product_reference')->appendItem([
            'target_id' => $referencesNid,
          ]);
        }
      }
    }

    $results = $this->nextpageItemHandler->getRelation($product->ID);
    if (isset($results[0])) {
      $ancestors = \Drupal::service('entity_type.manager')->getStorage("taxonomy_term")->loadAllParents($results[0]->family_parent_id);
      foreach ($ancestors as $index => $term) {
        if ($index == 0) {
          $node->set('field_product_family', $term->id());
        }
        else {
          $node->get('field_product_family')->appendItem([
            'target_id' => $term->id(),
          ]);
        }
      }
    }

    $node->save();
    return $node;
  }
}
