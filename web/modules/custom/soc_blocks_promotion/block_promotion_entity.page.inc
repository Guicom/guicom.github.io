<?php

/**
 * @file
 * Contains block_promotion_entity.page.inc.
 *
 * Page callback for Block promotion entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Block promotion entity templates.
 *
 * Default template: block_promotion_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_block_promotion_entity(array &$variables) {
  // Fetch BlockPromotionEntity Entity Object.
  $block_promotion_entity = $variables['elements']['#block_promotion_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
