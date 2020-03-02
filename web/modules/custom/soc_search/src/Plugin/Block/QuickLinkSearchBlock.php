<?php

namespace Drupal\soc_search\Plugin\Block;

/**
 * Provides a 'Quicklink Search' Block.
 *
 * @Block(
 *   id = "quick_link_block",
 *   admin_label = @Translation("Quicklinks"),
 *   category = @Translation("Soc Search"),
 * )
 */
class QuickLinkSearchBlock extends ParentSearchBlock {

  const SOC_SEARCH_MENU_NAME = 'search-quick-link';

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->setMenuName(self::SOC_SEARCH_MENU_NAME);
    return parent::build();
  }

}
