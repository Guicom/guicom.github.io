<?php

namespace Drupal\soc_blocks_promotion\Plugin\Block;

use Drupal\Core\Cache\Cache;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\soc_blocks_promotion\Entity\BlockPromotionEntity;

/**
 * Provides a block that displays the a promo block entity.
 *
 * @Block(
 *   id = "promotion_block_product_page",
 *   admin_label = @Translation("Product page Promotion block")
 * )
 */
class ProductPagePromotionBlock extends PromotionBlock {

  /**
   * @return array
   */
  public function build() {
    $build = [];
    $build['node_id'] = [
      '#type' => 'inline_template',
      '#template' => $this->getContent() ?? '',
    ];
    return $build;
  }

  /**
   * Get the promo block as rendered HTML.
   *
   * @return string|null
   */
  public function getContent(): ?string {
    // Get current node.
    $node = \Drupal::routeMatch()->getParameter('node');
    $field = $node->get('field_promo_block_paragraph');
    $fieldValue = $field->getValue();
    if (sizeof($fieldValue)) {
      $paragraphId = $fieldValue[0]['target_id'];
      // Get promo block paragraph.
      if ($paragraph = Paragraph::load($paragraphId)) {
        $field = $paragraph->get('field_promo_block');
        $fieldValue = $field->getValue();
        if (sizeof($fieldValue)) {
          // Select block to display.
          $promoBlockIds = [];
          foreach ($fieldValue as $value) {
            $promoBlockIds[] = $value['target_id'];
          }
          $promoBlocks = BlockPromotionEntity::loadMultiple($promoBlockIds);
          // By default, pick last block.
          $lastBlock = end($promoBlocks);
          $blockToDisplay = $lastBlock;
          // But if last block is not published, then pick first block.
          $firstBlock = reset($promoBlocks);
          if ($lastBlock->get('moderation_state')->getString() !== 'published') {
            $blockToDisplay = $firstBlock;
          }
          // Get HTML render for the chosen block.
          $view_builder = \Drupal::entityTypeManager()->getViewBuilder('block_promotion_entity');
          $pre_render = $view_builder->view($blockToDisplay);
          return render($pre_render);
        }
      }
    }
    return '';
  }

  /**
   * @return array|string[]
   */
  public function getCacheTags() {
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      return Cache::mergeTags(parent::getCacheTags(), array('node:' . $node->id()));
    } else {
      return parent::getCacheTags();
    }
  }

  /**
   * @return array|string[]
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), array('route'));
  }

}
