<?php

namespace Drupal\soc_sales_locations\Service;


use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Messenger\Messenger;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SalesLocationsManagerService.
 */
class SalesLocationsManagerService implements SalesLocationsManagerServiceInterface {

  /** @var \Drupal\Core\Entity\EntityManager */
  private $em;

  /**
   * @var \Drupal\Core\Messenger\Messenger
   */
  private $messenger;


  /**
   * SalesLocationsManagerService constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $em
   * @param \Drupal\Core\Messenger\Messenger $messenger
   */
  public function __construct(EntityTypeManager $em, Messenger $messenger) {
    $this->em = $em;
    $this->messenger = $messenger;
  }

  /**
   * @inheritDoc
   */
  public function getHeaders(Response $response) {

    $name_file = 'export-sales-locations-' . date('Y-m-d');
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');
    $response->headers->set('Content-type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename=' . $name_file . '.csv');
    return $response;
  }

  public function getRow(NodeInterface $node) {
    // TODO: Implement getRow() method.
  }


  /**
   * @inheritDoc
   */
  public function getNodes() {
    /** @var array<NodeInterface>  $nodes */
    $nodes = $this->em->getStorage('node')
      ->loadByProperties(['type' => 'contenu_location']);
    /** @var \Drupal\node\NodeInterface $node */
    foreach ($nodes as $node) {
      $this->messenger->addStatus($node->label());
    }
  }

  /**
   * @inheritDoc
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  public function getNameCompany(NodeInterface $node) {
    if ($node->get('field_location_company')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_company')->first()->getValue()['value'] ?? '';
  }


  /**
   * @inheritDoc
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  public function getFirstName(NodeInterface $node) {
    if ($node->get('field_location_firstname')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_firstname')->first()->getValue()['value'] ?? '';
  }

  /**
   * @inheritDoc
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  public function getNameContact(NodeInterface $node) {
    if ($node->get('field_location_name_contact')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_name_contact')->first()->getValue()['value'] ?? '';
  }

  /**
   * @inheritDoc
   */
  public function getTelephone(NodeInterface $node) {
    if ($node->get('field_location_telephone')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_telephone')->first()->getValue()['value'] ?? '';
  }

  /**
   * @inheritDoc
   */
  public function getFax(NodeInterface $node) {
    if ($node->get('field_location_fax')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_fax')->first()->getValue()['value'] ?? '';
  }

  /**
   * @inheritDoc
   */
  public function getContinent(NodeInterface $node) {
    if ($node->get('field_location_continent')->isEmpty()) {
      return '';
    }
    $id =  $node->get('field_location_continent')->first()->getValue()['target_id'];
    $term = Term::load($id);
    return $term->label();
  }

  /**
   * @inheritDoc
   */
  public function getSubArea(NodeInterface $node) {
    if ($node->get('field_location_subarea')->isEmpty()) {
      return '';
    }
    $id =  $node->get('field_location_subarea')->first()->getValue()['target_id'];
    $term = Term::load($id);
    return $term->label();
  }

  /**
   * @inheritDoc
   */
  public function getArea(NodeInterface $node) {
    if ($node->get('field_location_area')->isEmpty()) {
      return '';
    }
    $id =  $node->get('field_location_area')->first()->getValue()['target_id'];
    $term = Term::load($id);
    return $term->label();
  }

  /**
   * @inheritDoc
   */
  public function getActivity(NodeInterface $node) {
    if ($node->get('field_location_activity')->isEmpty()) {
      return '';
    }
    $area_id =  $node->get('field_location_activity')->first()->getValue()['target_id'];
    $area = Term::load($area_id);
    return $area->label();
  }

  /**
   * @inheritDoc
   */
  public function getType(NodeInterface $node) {
    if ($node->get('field_location_type')->isEmpty()) {
      return '';
    }
    $term_id =  $node->get('field_location_type')->first()->getValue()['target_id'];
    $term = Term::load($term_id);
    return $term->label();

  }

  /**
   * @inheritDoc
   */
  public function getAddressLine1(NodeInterface $node) {
    if ($node->get('field_location_address')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_address')->getValue()[0]['address_line1'];
  }
  /**
   * @inheritDoc
   */
  public function getAddressLine2(NodeInterface $node) {
    if ($node->get('field_location_address')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_address')->getValue()[0]['address_line2'];
  }
  /**
   * @inheritDoc
   */
  public function getAdministrativeArea(NodeInterface $node) {
    if ($node->get('field_location_address')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_address')->getValue()[0]['administrative_area'];
  }

  /**
   * @inheritDoc
   */
  public function getPostalCode(NodeInterface $node) {
    if ($node->get('field_location_address')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_address')->getValue()[0]['postal_code'];
  }

  /**
   * @inheritDoc
   */
  public function getLocality(NodeInterface $node) {
    if ($node->get('field_location_address')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_address')->getValue()[0]['locality'];
  }

  /**
   * @inheritDoc
   */
  public function getWebsite(NodeInterface $node) {
    if ($node->get('field_location_website')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_website')->getValue()[0]['uri'];
  }

  /**
   * @inheritDoc
   */
  public function getCountryCode(NodeInterface $node) {
    if ($node->get('field_location_address')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_address')->getValue()[0]['country_code'];
  }

  /**
   * @inheritDoc
   */
  public function getSortingCode(NodeInterface $node) {
    if ($node->get('field_location_address')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_address')->getValue()[0]['sorting_code'];
  }

  /**
   * @inheritDoc
   */
  public function getDependentLocality(NodeInterface $node) {
    if ($node->get('field_location_address')->isEmpty()) {
      return '';
    }
    return $node->get('field_location_address')->getValue()[0]['dependent_locality'];
  }


}
