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
      'Area',
      'Sub Area',
      'Activity',
      'Type',
      'Company',
      'Name',
      'First Name',
      'Address Line 1',
      'Address Line 2',
      'Dependent Locality',
      'Postal Code',
      'Administrative Area',
      'Locality',
      'Sorting Code',
      'Country Code',
      'Phone',
      'Fax',
      'Website',
    ];
    $csvData = [
      implode(';', $header),
    ];

    $nids = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->getQuery()
      ->condition('type', 'contenu_location')
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
      $fax = $this->manager->getFax($node);
      $address_line1 = $this->manager->getAddressLine1($node);
      $address_line2 = $this->manager->getAddressLine2($node);
      $postal_code = $this->manager->getPostalCode($node);
      $locality = $this->manager->getLocality($node);
      $website = $this->manager->getWebsite($node);
      $country_code = $this->manager->getCountryCode($node);
      $sorting_code = $this->manager->getSortingCode($node);
      $administrative_area = $this->manager->getAdministrativeArea($node);
      $dependent_locality = $this->manager->getDependentLocality($node);

      $csvData[] = implode(';', [
        $node->id(),
        $node->label(),
        $continent,
        $area,
        $subarea,
        $activity,
        $type,
        $company,
        $name_contact,
        $firstname,
        $address_line1,
        $address_line2,
        $dependent_locality,
        $postal_code,
        $administrative_area,
        $locality,
        $sorting_code,
        $country_code,
        $telephone,
        $fax,
        $website,
      ]);
    }

    $content = implode(PHP_EOL, $csvData);
    $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) );
    $content = $bom . $content;
    $response->setContent($content);
    return $response;
  }

}
