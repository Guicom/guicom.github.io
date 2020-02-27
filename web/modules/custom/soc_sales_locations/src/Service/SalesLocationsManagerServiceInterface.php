<?php

namespace Drupal\soc_sales_locations\Service;

use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\Response;

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
   * @param \Symfony\Component\HttpFoundation\Response $response
   *
   * @return mixed
   */
  public function getHeaders(Response $response);

  /**
   * Return an array for headers csv file.
   *
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getRow(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return string
   */
  public function getNameCompany(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return string
   */
  public function getFirstName(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return string
   */
  public function getNameContact(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getTelephone(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getContinent(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getSubArea(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getArea(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getActivity(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getType(NodeInterface $node);


  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getAddress(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getZipCode(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getCity(NodeInterface $node);

  /**
   * @param \Drupal\node\NodeInterface $node
   *
   * @return mixed
   */
  public function getWebsite(NodeInterface $node);

}
