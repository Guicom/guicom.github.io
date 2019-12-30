<?php

namespace Drupal\soc_nextpage\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "contextual_family",
 *   admin_label = @Translation("Contextual family"),
 *   category = @Translation("Contextual family"),
 * )
 */
class ContextualFamily extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = '';
    if (\Drupal::routeMatch()->getRouteName() == 'entity.taxonomy_term.canonical') {
      // load the term entity and get the data from there
      $term = \Drupal::routeMatch()->getParameter('taxonomy_term');
      // Get term ID.
      $tid = $term->id();
      // Get vocabulary ID.
      $vid = $term->bundle();
      // Get tree of this term, only 1 level
      $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $tid, 1);

      // If no child, get sister
      if (empty($terms)) {
        // Get parent of term
        $parent = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($tid);
        $parent = reset($parent);
        // Get tree of this term, only 1 level
        $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $parent->id(), 1);
      }

      // Build list
      // We don't use item_list them as the generated markup is too specific
      $output = '<ul>';
      foreach($terms as $t) {
        $text = $t->name;
        // If not current term, display as link
        if ($t->tid != $tid) {
          $url = Url::fromRoute('entity.taxonomy_term.canonical', ['taxonomy_term' => $t->tid]);
          $link = Link::fromTextAndUrl($text, $url);
          $link = $link->toRenderable();
          $text = render($link);
        }
        $output .='<li>' . $text . '</li>';
      }
      $output .= '</ul>';
    }

    return [
      '#markup' => $output
    ];
  }

}
