<?php

namespace Drupal\soc_search\Plugin\Block;

/**
 * Provides a 'Top Search' Block.
 *
 * @Block(
 *   id = "top_search_block",
 *   admin_label = @Translation("Top 5 searches"),
 *   category = @Translation("Soc Search"),
 * )
 */
class TopSearchBlock extends ParentSearchBlock {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->setMenuName('search-top-search');
    return parent::build();
  }

}
