<?php


namespace Drupal\soc_core\Service;


use Drupal\node\Entity\Node;
use Drupal\soc_core\Model\ContentType;

class Soc_node {
  /**
   * Get all Thank You Page with reference to Landing Page
   * @param Node $node
   * @return array|int|null
   */
  public function getAllThankYouPageFromLandingPage($node) {
    if (strcmp($node->getType(), ContentType::CT_LANDING) == 0) {
      return \Drupal::entityQuery('node')
        ->condition('type', ContentType::CT_TY_P)
        ->condition('field_landing_page', $node->id())
        ->execute()
        ;
    }
    else {
      return null;
    }
  }
}
