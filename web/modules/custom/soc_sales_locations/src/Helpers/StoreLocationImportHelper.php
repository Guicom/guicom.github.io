<?php


namespace Drupal\soc_sales_locations\Helpers;


use Drupal\Console\Bootstrap\Drupal;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\Entity\Term;

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

  public function importFirstName($firstname) {
    $this->node->set('field_location_firstname',$firstname);
  }
  public function importAddress(array $row) {
    $data = [
      'address_line1' => $row[10],
      'address_line2' => $row[11],
      'dependent_locality' => $row[12],
      'postal_code' => $row[13],
      'administrative_area' => $row[14],
      'locality' => $row[15],
      'sorting_code' => $row[16],
      'country_code' => $row[17],
    ];
    $this->node->get('field_location_address')->setValue($data);
  }
  public function importPhone($phone){
    $this->node->get('field_location_telephone')->setValue($phone);
  }

  public function importFax($fax) {
    $this->node->get('field_location_fax')->setValue($fax);
  }
  public function importWebsite($website) {
    $this->node->get('field_location_website')->setValue(['uri' => $website]);
  }

  public function importType($type) {
    $term = $this->importTerm('location_type',$type);
    if(!is_null($term) ){
      $this->node->get('field_location_type')->setValue(['target_id' => $term->id()]);
    }
  }

  /**
   * @param string $voc
   *    Vocabulary Machine Name.
   *
   * @param string $name
   *    Name Term.
   *
   * @return mixed Term.
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function importTerm($voc, $name=NULL) {
    if ($name === NULL) {
      return NULL;
    }
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties([
        'name' => $name,
      ]);
    if (count($terms) >= 1) {
      return reset($terms);
    }
    else{
      \Drupal::messenger()->addWarning('Création d\'un nouveau term taxonomy '. $name. ' pour le vocabulaire ' . $voc);
      $term = Term::create([
        'vid' => $voc,
        'name' => $name,
      ]);
      $term->save();
      return $term;
    }

    return NULL;
  }

  public function importActivity($activity) {
    $term = $this->importTerm('location_activity',$activity);
    if(!is_null($term) ){
      $this->node->get('field_location_activity')->setValue(['target_id' => $term->id()]);
    }
  }
  public function importArea($area){
    $term = $this->importTerm('location_areas',$area);
    if(!is_null($term) ){
      $this->node->get('field_location_area')->setValue(['target_id' => $term->id()]);
    }
  }

  public function importSubArea($subarea){
    $term = $this->importTerm('location_areas',$subarea);
    if(!is_null($term) ){
      $this->node->get('field_location_subarea')->setValue(['target_id' => $term->id()]);
    }
  }
  public function importContient($continent){
    $term = $this->importTerm('location_areas',$continent);
    if(!is_null($term) ){
      $this->node->get('field_location_continent')->setValue(['target_id' => $term->id()]);
    }
  }

}
