<?php

namespace Drupal\soc_search\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Parent Search' Block.
 *
 * @Block(
 *   id = "parent_search_block",
 *   admin_label = @Translation("Parent Search"),
 *   category = @Translation("Soc Search"),
 * )
 */
class ParentSearchBlock extends BlockBase {
  private $menuName;

   /**
   * {@inheritdoc}
   */
  public function getMenuName() {
    return $this->menuName;
  }

  /**
   * {@inheritdoc}
   */
  public function setMenuName($menuName) {
    $this->menuName = $menuName;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $menu_name = $this->menuName;
    if ($menu_name) {
      $menu_tree = \Drupal::menuTree();
      // Build the typical default set of menu tree parameters.
      $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);
      $parameters->setMaxDepth(1);
      // Load the tree based on this set of parameters.
      $tree = $menu_tree->load($menu_name, $parameters);
      $manipulators = array(
        array('callable' => 'menu.default_tree_manipulators:checkNodeAccess'),
        array('callable' => 'menu.default_tree_manipulators:checkAccess'),
        array('callable' => 'soc_search.soc_search_tree_manipulators:filterLanguage'),
        array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
      );
      $tree = $menu_tree->transform($tree, $manipulators);
      $search_number = \Drupal::config('soc_search.settings')->get('quick_links_number') ?? 5;
      $i = 1;
      foreach ($tree as $key => $value) {
        if ($i > $search_number) {
          unset($tree[$key]);
        }
        $i++;
      }
      return $menu_tree->build($tree);
    }
   return '';
  }

}
