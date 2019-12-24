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
      // Add vocabulary ono body class
      $tid = $term->id();
      $vid = $term->bundle();
      $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $tid);

      if (empty($terms)) {
        $parent = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($tid);
        $parent = reset($parent);
        $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $parent->id());
      }

      $output = '<ul>';
      foreach($terms as $t) {
        $text = $t->name;
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
