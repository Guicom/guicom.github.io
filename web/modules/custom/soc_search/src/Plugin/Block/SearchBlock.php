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

    $suggestion_title = \Drupal::config('soc_search.settings')->get('suggestion_title') ??
      t('More than 1250 Results here some suggestions');
    $categorized_title = \Drupal::config('soc_search.settings')->get('categorized_title') ??
      t('Categorized suggestions');

    return [
      '#markup' => $this->t('Search block'),
      'suggestion' => $suggestion_title,
      'categorized' => $categorized_title,
    ];
  }

}
