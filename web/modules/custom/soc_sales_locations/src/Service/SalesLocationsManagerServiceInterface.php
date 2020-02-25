<?php

namespace Drupal\soc_sales_locations\Service;

use Drupal\node\NodeInterface;

/**
 * Interface SalesLocationsManagerServiceInterface.
 */
interface SalesLocationsManagerServiceInterface {

  /**
   * Return an array nodes entities.
   *
   * @return mixed
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  public function getNodes();

  /**
   * Return an array for headers csv file.
   *
   * @return mixed
   */
  public function getHeaders();

  /**
   * Return an array for headers csv file.
   *
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getRow(NodeInterface $node);

}
