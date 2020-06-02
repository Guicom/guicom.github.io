<?php

namespace Drupal\soc_nextpage\Service\Manager;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\soc_nextpage\Service\NextpageApi;
use Drupal\soc_nextpage\Service\NextpageItemHandler;
use Drupal\soc_rollback\Service\RollbackImport;

/**
 * Class ProductManager.
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
   * @var \Drupal\soc_rollback\Service\RollbackImport
   */
  private $rollbackImport;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  private $logger;

  /**
   * ProductManager constructor.
   *
   * @param \Drupal\soc_nextpage\Service\Manager\ReferenceManager $referenceManager
   * @param \Drupal\soc_nextpage\Service\NextpageApi $nextpageApi
   * @param \Drupal\soc_nextpage\Service\NextpageItemHandler $nextpageItemHandler
   * @param \Drupal\soc_rollback\Service\RollbackImport $rollbackImport
   */
  public function __construct(ReferenceManager $referenceManager,
                              NextpageApi $nextpageApi,
                              NextpageItemHandler $nextpageItemHandler,
                              RollbackImport $rollbackImport,
                              LoggerChannelFactoryInterface $channelFactory) {
    $this->referenceManager = $referenceManager;
    $this->nextpageApi = $nextpageApi;
    $this->nextpageItemHandler = $nextpageItemHandler;
    $this->rollbackImport = $rollbackImport;
    $this->logger = $channelFactory->get('soc_nextpage');
  }

  /**
   * Handle a product: create if not already existing, else update.
   *
   * @param $pendingProduct
   *
   * @return \Drupal\Core\Entity\EntityInterface|mixed|string|void|null
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function handle($pendingProduct, $context) {
    $this->referencesNids = $this->referenceManager->handle($pendingProduct->ExtID, $context);
    $entity = $this->nextpageItemHandler->loadByExtID($pendingProduct->ExtID, 'node', 'product');
    if (is_null($entity)) {
      $entity = $this->createProduct($pendingProduct, $context['job_id']);
      $state = 'created';
    }
    else {
      $entity = $this->updateProduct($entity, $pendingProduct, $context['job_id']);
      $state = 'updated';
    }

    // Feed rollback service.
    $this->rollbackImport->updateJob($context['job_id'],
      [
        'operation' => 'update_entity',
        'state' => $state,
        'entity' => $entity,
      ]);
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
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function createProduct($product, $job_id) {
    $node = \Drupal::entityTypeManager()->getStorage('node')->create([
      'type' => 'product',
    ]);
    $this->updateProduct($node, $product, $job_id);
    return $node;
  }

  /**
   * Update a product node.
   *
   * @param $node
   * @param $product
   */
  public function updateProduct(&$node, $product, $job_id) {
    if (isset($product->Values->DC_P_PRODUCT_SHORT_DESCRIPTION->Value)
        && isset($product->Values->DC_P_ASSORTMENT_WIDTH->Value)) {
      $node->set('field_teaser',
        $product->Values->DC_P_PRODUCT_SHORT_DESCRIPTION->Value . ' - '
        . $product->Values->DC_P_ASSORTMENT_WIDTH->Value);
    }
    if (isset($product->Values->DC_P_PRODUCT_NAME)) {
      $title = $product->Values->DC_P_PRODUCT_NAME->Value ?? 'TITLEPLACEHOLDER';
    }
    else {
      $title = 'TITLEPLACEHOLDER';
    }
    $node->set('title', $title);
    $node->set('field_json_product_data', $this->nextpageItemHandler->formatJsonField($product->Values));
    $node->set('field_extid', $product->ExtID);

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

    if ($this->checkStatus($product) == TRUE) {
      $node->setPublished();
      $node->set('moderation_state', 'published');
    }
    else {
      $node->setUnpublished();
      $node->set('moderation_state', 'draft');
    }

    // Different way to set new revision because of content_moderation usage.
    $storage = \Drupal::entityTypeManager()->getStorage($node->getEntityTypeId());
    $node = $storage->createRevision($node, $node->isDefaultRevision());
    $node->setRevisionLogMessage(t('Created revision for @nid in Job @job_id',
      [
        "@nid" => $node->id(),
        "@job_id" => $job_id,
      ]));
    $node->setRevisionCreationTime(REQUEST_TIME);
    $node->setRevisionUserId(1);

    try {
      $node->save();
    }
    catch (\Exception $e) {
      throw new \Exception($e->getMessage(), 1);
    }
    return $node;
  }

}
