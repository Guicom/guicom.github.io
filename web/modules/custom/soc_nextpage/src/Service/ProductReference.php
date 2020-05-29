<?php

namespace Drupal\soc_nextpage\Service;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;
use Drupal\Core\Link;

class ProductReference {

  /**
   * @var \Drupal\Core\Entity\Query\QueryFactory entityQuery
   */
  protected $entityQuery;

  /**
   * @var \Drupal\Core\Database\Connection $database
   */
  protected $database;

  /**
   * ProductReference constructor.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $entityQuery
   * @param \Drupal\Core\Database\Connection $connection
   */
  public function __construct(QueryFactory $entityQuery, Connection $connection) {
    $this->entityQuery = $entityQuery;
    $this->database = $connection;
  }

  public function getParentProductFamilyByProductReference(Node $reference) {
    if ($node = $this->getParentProductByProductReference($reference)) {
     $family = $node->get('field_product_family')->target_id;
     return $family;
    }
    return FALSE;
  }

  /**
   * @param \Drupal\node\Entity\Node $reference
   *
   * @return bool|\Drupal\Core\Entity\EntityInterface|\Drupal\node\Entity\Node|null
   */
  public function getParentProductByProductReference(Node $reference) {
    if ($rid = $reference->id()) {
      $entityQuery = $this->entityQuery->get('node');
      $entityQuery->condition('type', 'product');
      $entityQuery->condition('field_product_reference', $reference->id());
      $entityQuery->range(0, 1);
      $nids = $entityQuery->execute();
      $node = Node::load(reset($nids));
      return $node;
    }
    return FALSE;
  }

  /**
   * @param \Drupal\node\Entity\Node $reference
   *
   * @return array
   */
  public function getFamiliesLinkByProductReference(Node $reference) {
    $families = [];
    if ($tid = $this->getParentProductFamilyByProductReference($reference)) {
      if ($ancestors = \Drupal::service('entity_type.manager')->getStorage("taxonomy_term")->loadAllParents($tid)) {
        if (is_array($ancestors)) {
          foreach ($ancestors as $term) {
            if (!empty($term->label()) && !empty($term->id())) {
              $families[$term->id()] = Link::createFromRoute($term->label(),
                'entity.taxonomy_term.canonical',
                ['taxonomy_term' => $term->id()]);
            }
          }
        }
      }
    }
    return $families;
  }
}
