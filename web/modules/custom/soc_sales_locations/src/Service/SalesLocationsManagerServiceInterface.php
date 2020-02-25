<?php

namespace Drupal\soc_sales_locations\Service;

/**
 * Interface SalesLocationsManagerServiceInterface.
 */
interface SalesLocationsManagerServiceInterface {

  /**
   * Return an array nodes entities.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *
   * @return mixed
   */
  public function getNodes();

}
