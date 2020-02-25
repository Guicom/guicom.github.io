<?php

namespace Drupal\soc_sales_locations\Service;


/**
 * Class SalesLocationsManagerService.
 */
class SalesLocationsManagerService implements SalesLocationsManagerServiceInterface {


  /**
   * @inheritDoc
   */
  public function getNodes(){
    /** @var  $nodes */

    $nodes =\Drupal::service('entity_type.manager')->getStorage('node')
      ->loadByProperties(['type' => 'contenu_location']);
    /** @var \Drupal\node\NodeInterface $node */
    foreach ($nodes as $node){
      print ($node->label());
      \Drupal::messenger()->addStatus($node->label());
    }
  }

}
