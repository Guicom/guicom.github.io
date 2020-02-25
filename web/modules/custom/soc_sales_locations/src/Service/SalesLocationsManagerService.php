<?php

namespace Drupal\soc_sales_locations\Service;


use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Messenger\Messenger;
use Drupal\node\NodeInterface;

/**
 * Class SalesLocationsManagerService.
 */
class SalesLocationsManagerService implements SalesLocationsManagerServiceInterface {

  /** @var \Drupal\Core\Entity\EntityManager */
  private $em;

  /**
   * @var \Drupal\Core\Messenger\Messenger
   */
  private $messenger;


  /**
   * SalesLocationsManagerService constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $em
   * @param \Drupal\Core\Messenger\Messenger $messenger
   */
  public function __construct(EntityTypeManager $em, Messenger $messenger) {
    $this->em = $em;
    $this->messenger = $messenger;
  }

  public function getHeaders() {
    // TODO: Implement getHeaders() method.
  }

  public function getRow(NodeInterface $node) {
    // TODO: Implement getRow() method.
  }


  /**
   * @inheritDoc
   */
  public function getNodes() {
    /** @var  $nodes */

    $nodes = $this->em->getStorage('node')
      ->loadByProperties(['type' => 'contenu_location']);
    /** @var \Drupal\node\NodeInterface $node */
    foreach ($nodes as $node) {
      $this->messenger->addStatus($node->label());
    }
  }

}
