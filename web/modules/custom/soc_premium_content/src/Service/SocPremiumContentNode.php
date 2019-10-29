<?php
/**
 * Get thank you page associate to current landing page.
 */
namespace Drupal\soc_premium_content\Service;

use Drupal\node\Entity\Node;

class SocPremiumContentNode {
  /**
   * Get all Thank You Page with reference to Landing Page
   * @param Node $node
   * @return array|int|null
   */
  public function getAllThankYouPageFromLandingPage($node) {
    if (strcmp($node->getType(), 'landing_page') === 0) {
      return \Drupal::entityQuery('node')
        ->condition('type', 'thank_you_page')
        ->condition('field_landing_page', $node->id())
        ->execute()
        ;
    }
    else {
      return null;
    }
  }
}
