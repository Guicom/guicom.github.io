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

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->setMenuName('search-quick-link');
    return parent::build();
  }

}
