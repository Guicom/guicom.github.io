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
    $tid = '';
    $options = ['fragment' => 'product-reference-section'];
    if (\Drupal::routeMatch()->getRouteName() == 'entity.taxonomy_term.canonical') {
      // load the term entity and get the data from there
      $term = \Drupal::routeMatch()->getParameter('taxonomy_term');
      // Get term ID.
      $tid = $term->id();
      // Check if level 1
      $parent =
        \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($tid) ?
          \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($tid) : FALSE;
      if ($parent != FALSE) {
        $parent_level2 = reset($parent);
        $parent =
          \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($parent_level2->id()) ?
            \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($parent_level2->id()) : $parent_level2;
        if ($parent != $parent_level2) {
          $parent = reset($parent);
        }
      }

      // Get vocabulary ID.
      $vid = $term->bundle();
      // Load tree oone level
      $tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, 0, 1);

      // Set current level term
      $base_tid = $parent ? $parent->id() : $tid;

      //build listing
      $output = '<ul class="first-level">';
      foreach ($tree as $term) {
        $text = $term->name;
        // If current bas term.
        if ($term->tid == $base_tid) {
          if ($term->tid == $tid) {
            $output .= '<li class="current" ><p>' . $text . '</p></li>';
          }
          else {
            $url = Url::fromRoute('entity.taxonomy_term.canonical', ['taxonomy_term' => $term->tid])->setOptions($options);
            $link = Link::fromTextAndUrl($text, $url);
            $link = $link->toRenderable();
            $text = render($link);
            $output .= '<li>' . $text . '</li>';
          }
          $output .= '<ul class="second-level">';
          $level2_tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $term->tid, 1);
          foreach ($level2_tree as $term_2) {
            $text = $term_2->name;
            // If current term or parent term.
            if ($term_2->tid == $tid || (isset($parent_level2) && $term_2->tid == $parent_level2->id())) {
              if ($term_2->tid == $tid) {
                $output .= '<li class="current"><p>' . $text . '</p></li>';
              }
              else {
                $url = Url::fromRoute('entity.taxonomy_term.canonical', ['taxonomy_term' => $term->tid])->setOptions($options);
                $link = Link::fromTextAndUrl($text, $url);
                $link = $link->toRenderable();
                $text = render($link);
                $output .= '<li class="current">' . $text . '</li>';
              }
              $output .= '<ul class="third-level">';
              $level3_tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $term_2->tid, 1);
              foreach ($level3_tree as $term_3) {
                $text = $term_3->name;
                // If current term.
                if ($term_3->tid == $tid) {
                  $output .= '<li class="current"><p>' . $text . '</p></li>';
                }
                else {
                  $url = Url::fromRoute('entity.taxonomy_term.canonical', ['taxonomy_term' => $term_3->tid])->setOptions($options);
                  $link = Link::fromTextAndUrl($text, $url);
                  $link = $link->toRenderable();
                  $text = render($link);
                  $output .= '<li>' . $text . '</li>';
                }
              }
              $output .= '</ul>';
            }
            else {
              $url = Url::fromRoute('entity.taxonomy_term.canonical', ['taxonomy_term' => $term_2->tid])->setOptions($options);
              $link = Link::fromTextAndUrl($text, $url);
              $link = $link->toRenderable();
              $text = render($link);
              $output .= '<li>' . $text . '</li>';
            }
          }
          $output .= '</ul>';
        }
        else {
          $url = Url::fromRoute('entity.taxonomy_term.canonical', ['taxonomy_term' => $term->tid])->setOptions($options);
          $link = Link::fromTextAndUrl($text, $url);
          $link = $link->toRenderable();
          $text = render($link);
          $output .= '<li>' . $text . '</li>';
        }
      }
      $output .= '</ul>';
    }

    return [
      '#markup' => $output,
      '#cache' => [
        'tags' => ['block-contextual-family-' . $tid, 'taxonomy_term_list']
      ]
    ];
  }
}
