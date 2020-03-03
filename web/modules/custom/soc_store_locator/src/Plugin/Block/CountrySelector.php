<?php

namespace Drupal\soc_store_locator\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Link;
use Drupal\Core\Url;


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
    $url = explode('/', $url["path"]);
    $url = end($url);
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $system_path = \Drupal::service('path.alias_manager')->getPathByAlias('/' . $url, $language);
    $path = explode("/", $system_path);
    $tid = end($path);
    $current = Term::load($tid);
    if (!empty($current)) {
      $parent = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($tid);
      $parent = reset($parent);
    }

    $tabs_header = [];
    $tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('location_areas', 0, 1);
    $aliasManager = \Drupal::service('path.alias_manager');
    foreach ($tree as $key => $areas) {
      $tabs_header[$key]['value'] = $areas->name;
      if (isset($parent)  && $parent->id() == $areas->tid) {
        $tabs_header[$key]['sub'] = $current->getName();
      }
      else {
        $tabs_header[$key]['sub'] = t('All');
      }
      $subtree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('location_areas', $areas->tid, 1);
      foreach ($subtree as $country) {
        $alias_parent = $aliasManager->getAliasByPath('/taxonomy/term/'.$areas->tid);
        $alias = $aliasManager->getAliasByPath('/taxonomy/term/'.$country->tid);
        $url_alias = '/' . t('where-to-buy') . $alias_parent . $alias;
        $param['query'] = \Drupal::request()->query->all();
        if (isset($param["query"]["page"])) {
          unset($param["query"]["page"]);
        }
        $url = Url::fromUri('internal:' . $url_alias, $param);
        $url = Link::fromTextAndUrl($country->name, $url);
        $tabs_content[$areas->name][$key]['value'] = $country->name;
        $tabs_content[$areas->name][$key]['link'] = render($url);
      }
    }

    return array(
      '#theme' => 'soc_store_locator_country__selector_theme',
      '#tabs' => $tabs_header,
      '#tabs_content' => $tabs_content,
    );
  }

}
