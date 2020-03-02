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

  const SOC_SEARCH_MENU_NAME = 'search-top-search';

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->setMenuName(self::SOC_SEARCH_MENU_NAME);
    return parent::build();
  }

}
