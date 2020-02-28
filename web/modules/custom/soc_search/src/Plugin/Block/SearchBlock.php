<?php

namespace Drupal\soc_search\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Top Search' Block.
 *
 * @Block(
 *   id = "soc_search_block",
 *   admin_label = @Translation("Search"),
 *   category = @Translation("Soc Search"),
 * )
 */
class SearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Search block'),
    ];
  }

}
