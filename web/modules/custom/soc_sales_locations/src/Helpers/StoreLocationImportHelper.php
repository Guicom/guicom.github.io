<?php


namespace Drupal\soc_sales_locations\Helpers;


use Drupal\Console\Bootstrap\Drupal;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;

class StoreLocationImportHelper {

  use StringTranslationTrait;

  const CONTENT_TYPE = 'contenu_location';

  /**
   * @var \Drupal\node\NodeInterface
   */
  private $node;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $em;

  public function __construct(NodeInterface $node ) {
    $this->node = $node;
  }


  /**
   * @param string $title
   *
   * @return void
   */
  public function importTitle(string $title){
    $this->node->setTitle($title.' new');
  }

  /**
   * Import the name company form the csv file.
   *
   * @param $name_company
   *
   * @return void
   */
  public function importNameCompany($name_company) {
    $this->node->set('field_location_company',$name_company);
  }
  /**
   * Import the name company form the csv file.
   *
   * @param $name_contact
   *
   * @return void
   */
  public function importNameContact($name_contact) {
    $this->node->set('field_location_name_contact',$name_contact);
  }

  /**
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function saveUpdatedRevisionsNode(){
    // @todo: ajouter un message en anglais.
    if(!$this->node->getEntityType()->isRevisionable()){
      throw new EntityStorageException('Le type de contenu n\'est pas l\'option revision d\'activer');
    }
    $this->node->setNewRevision();
    $message = $this->t('Import done @date', ['@date' => date('d/m/y h:i:s', time())]);
    $this->node->setRevisionLogMessage($message);
    $this->node->save();
    // @todo: ajouter un message en anglais.
    \Drupal::messenger()->addMessage($this->node->label() . ' a été sauvegardé');
  }

  /**
   * @param $firstname
   */
  public function importFirstName($firstname) {

    $this->node->set('field_location_firstname',$firstname);
  }

  /**
   * @param array $row
   */
  public function importAddress(array $row) {
    // address_line1 || 10
    // address_line2 || 11
    // dependent_locality || 12
    // postal_code || 13
    // administrative_area || 14
    // locality || 15
    // sorting_code || 16
    // country_code || 17
    //dsm($row[10]);

    //$this->node->get('field_location_address')->set('address_line1',$row[10]);

  }


}