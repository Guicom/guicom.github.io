<?php

namespace Drupal\soc_nextpage\Service\Manager;

use Drupal;
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
  protected $referenceManager;

  /**
   * @var \Drupal\soc_nextpage\Service\NextpageApi
   */
  protected $nextpageApi;

  /**
   * @var \Drupal\soc_nextpage\Service\NextpageItemHandler
   */
  private $nextpageItemHandler;

  /**
   * @var \Drupal\Core\Entity\EntityInterface|string|void|null
   */
  private $referencesNids;

  /**
   * ProductManager constructor.
   *
   * @param \Drupal\soc_nextpage\Service\Manager\ReferenceManager $referenceManager
   * @param \Drupal\soc_nextpage\Service\NextpageApi $nextpageApi
   * @param \Drupal\soc_nextpage\Service\NextpageItemHandler $nextpageItemHandler
   */
  public function __construct(ReferenceManager $referenceManager,
                             NextpageApi $nextpageApi,
                             NextpageItemHandler $nextpageItemHandler) {
    $this->referenceManager = $referenceManager;
    $this->nextpageApi = $nextpageApi;
    $this->nextpageItemHandler = $nextpageItemHandler;
  }

  /**
   * Handle a product: create if not already existing, else update.
   *
   * @param $pendingProduct
   *
   * @return \Drupal\Core\Entity\EntityInterface|mixed|string|void|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function handle($pendingProduct) {
    $this->referencesNids = $this->referenceManager->handle($pendingProduct->ExtID);
    if ($entity = $this->nextpageItemHandler->loadByExtID($pendingProduct->ExtID, 'node', 'product')) {
      $entity = $this->updateProduct($entity, $pendingProduct);
    }
    else {
      $entity = $this->createProduct($pendingProduct);
    }

    return $entity;
  }

  /**
   * Check if all mandatory fields are present.
   *
   * @param $pendingProduct
   *
   * @return bool
   */
  public function checkStatus($pendingProduct) {
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
   * Create a product node.
   *
   * @param $product
   *
   * @return \Drupal\Core\Entity\EntityInterface
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function createProduct($product) {
    $node = \Drupal::entityTypeManager()->getStorage('node')->create([
      'type' => 'product',
    ]);
    $this->updateProduct($node, $product);
    return $node;
  }

  /**
   * Update a product node.
   *
   * @param $node
   * @param $product
   */
  public function updateProduct(&$node, $product) {
    if (!isset($product->Values->DC_P_PRODUCT_SHORT_DESCRIPTION->Value)
        && isset($product->Values->DC_P_ASSORTMENT_WIDTH->Value)) {
      $node->set('field_product_teaser',
        $product->Values->DC_P_PRODUCT_SHORT_DESCRIPTION->Value . ' - '
        . $product->Values->DC_P_ASSORTMENT_WIDTH->Value);
    }
    $title = $product->Values->DC_P_PRODUCT_NAME->Value ?
      $product->Values->DC_P_PRODUCT_NAME->Value : 'TITLEPLACEHOLDER';
    $node->set('title', $title);
    $node->set('field_json_product_data', $this->nextpageItemHandler->formatJsonField($product->Values));
    $node->set('field_product_extid', $product->ExtID);
    if ($this->checkStatus($product) == TRUE) {
      $node->setPublished();
      $node->set('moderation_state', 'published');
    }

    if (isset($this->referencesNids)) {
      foreach ($this->referencesNids as $index => $referencesNid) {
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
      $index = 0;
      foreach ($ancestors as $term) {
        if ($index == 0) {
          $node->set('field_product_family', $term->id());
        }
        else {
          $node->get('field_product_family')->appendItem([
            'target_id' => $term->id(),
          ]);
        }
        $index++;
      }
    }

    try {
      $node->save();
    }
    catch (\Exception $e) {
      \Drupal::logger('soc_nextpage')->warning($e->getMessage());
    }
    return $node;
  }
}
