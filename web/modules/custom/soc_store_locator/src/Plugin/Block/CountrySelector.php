<?php

namespace Drupal\soc_store_locator\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use function Sodium\randombytes_uniform;

/**
 * Provides a 'CountrySelector' block.
 *
 * @Block(
 *  id = "country_selector",
 *  admin_label = @Translation("Country selector"),
 * )
 */
class CountrySelector extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $current_path = \Drupal::service('path.current')->getPath();
    $url = parse_url($current_path);
    $tabs_header = [];
    $tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('location_areas', 0, 1);
    foreach ($tree as $areas) {
      $tabs_header[] = $areas->name;
      $subtree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('location_areas', $areas->tid, 1);
      foreach ($subtree as $country) {
        $tabs_content[$areas->name][] = $country->name;
      }
    }

    return array(
      '#theme' => 'soc_store_locator_country__selector_theme',
      '#tabs' => $tabs_header,
      '#tabs_content' => $tabs_content,
    );
  }

}
