<?php

namespace Drupal\soc_sales_locations\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\soc_sales_locations\Service\SalesLocationsManagerServiceInterface;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExportCsvController.
 */
class ExportCsvController extends ControllerBase {

  /**
   * @var \Drupal\soc_sales_locations\Service\SalesLocationsManagerServiceInterface
   */
  private $manager;


  /**
   * ExportSampleFileSalesLocationsForm constructor.
   *
   * @param \Drupal\soc_sales_locations\Service\SalesLocationsManagerServiceInterface $manager
   */
  public function __construct(SalesLocationsManagerServiceInterface $manager) {
    $this->manager = $manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('soc_sales_locations.manager'));
  }

  /**
   * Export a CSV File.
   *
   * @return Response
   *   Return an Response object with an array for the CSV File.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @see \Drupal\soc_wishlist\Service\Manager\WishlistExport::exportCSV()
   *
   */
  public function exportFile() {
    $response = new Response();
    $this->manager->getHeaders($response);
    $header = [
      'ID',
      'Title',
      'Continent',
      'Sub Area',
      'Area',
      'Activity',
      'Module',
      'Type',
      'Company',
      'Name',
      'First Name',
      'Address',
      'Zip Code',
      'City',
      'Phone',
      'Website',
    ];
    $csvData = [
      implode(';', $header),
    ];

    $nids = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->getQuery()
      ->condition('type', 'contenu_location')
//      ->condition('nid', '56')
//      ->range(0, 2)
      ->execute();
    $nodes = Node::loadMultiple($nids);
    /** @var \Drupal\node\NodeInterface $node */
    foreach ($nodes as $node) {
      $area = $this->manager->getArea($node);
      $continent = $this->manager->getContinent($node);
      $subarea = $this->manager->getSubArea($node);
      $activity = $this->manager->getActivity($node);
      $type = strtoupper($this->manager->getType($node));
      $company = $this->manager->getNameCompany($node);
      $name_contact = $this->manager->getNameContact($node);
      $firstname = $this->manager->getFirstName($node);
      $telephone = $this->manager->getTelephone($node);
      $address = $this->manager->getAddress($node);
      $zip_code = $this->manager->getZipCode($node);
      $city = $this->manager->getCity($node);
      $website = $this->manager->getWebsite($node);
      $csvData[] = implode(';', [
        $node->id(),
        $node->label(),
        $continent,
        $subarea,
        $area,
        $activity,
        'IMPL',
        $type,
        $company,
        $name_contact,
        $firstname,
        $address,
        $zip_code,
        $city,
        $telephone,
        $website,
      ]);
    }

    $content = implode(PHP_EOL, $csvData);
    $response->setContent($content);
    return $response;
  }

}
