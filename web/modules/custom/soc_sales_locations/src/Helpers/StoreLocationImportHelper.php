<?php


namespace Drupal\soc_sales_locations\Helpers;


use Drupal\Console\Bootstrap\Drupal;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\TermInterface;
use Exception;

class StoreLocationImportHelper {

  use StringTranslationTrait;


  const CONTENT_TYPE = 'contenu_location';

  const DELIMITER = "|";

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
    $this->node->setTitle($title);
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
   * @param int $date_start_import
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\TypedData\Exception\ReadOnlyException
   */
  public function saveUpdatedRevisionsNode(int $date_start_import){
    if(!$this->node->getEntityType()->isRevisionable()){
      throw new EntityStorageException('The content type has not the revision option.');
    }
    $this->node->get('field_last_imported')->setValue($date_start_import);
    $this->node->setNewRevision();
    $message = $this->t('Import done @date', ['@date' => date('d/m/y h:i:s', $date_start_import)]);
    $this->node->setRevisionLogMessage($message);
    $this->node->save();
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
  public function importTerm($voc, $name=NULL) {
    if ($name === NULL) {
      return NULL;
    }
    if ($this->getCountTermsForRow($name) > 1) {
      $_multipleTerms =  [];
        foreach ($this->getNameForRow($name) as $item){
          $_multipleTerms[]  = $this->importTerm($voc, $item);
        }
      return $_multipleTerms;
    }
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties([
        'vid' => $voc,
        'name' => $name,
      ]);
    if (count($terms) >= 1) {
      return reset($terms);
    }
    else{
      return $this->createTermIfNecessary($voc, $name);
    }
  }

  /**
   * Create a new Term if it doesn't exists.
   *
   * @param $voc
   * @param $name
   *
   * @return Term
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function createTermIfNecessary($voc, $name){
    $message = $this->t('Creation of a new taxonomy term @name for the vocabulary @voc.', [
      '@name' => $name,
      '@voc' => $voc,
    ]);
    \Drupal::messenger()->addWarning($message);
    $term = Term::create([
      'vid' => $voc,
      'name' => $name,
    ]);
    $term->save();
    return $term;
  }

  public function importActivity($activity) {
    $term = $this->importTerm('location_activity',$activity);
    if(!is_null($term) ){
      $this->node->get('field_location_activity')->setValue(['target_id' => $term->id()]);
    }
  }
  public function importArea($area){
    $term = $this->importTerm('location_areas',$area);
    if(!is_null($term) && !is_array($term) ){
      $this->node->get('field_location_area')->setValue(['target_id' => $term->id()]);
    }
    elseif (is_array($term)){
      $targets_id = $this->getTargetsId($term);
      $this->node->set('field_location_area', $targets_id);
    }
  }

  public function importSubArea($subarea){
    $term = $this->importTerm('location_areas',$subarea);
    if(!is_null($term) && !is_array($term) ){
      $this->node->get('field_location_subarea')->setValue(['target_id' => $term->id()]);
    }
    elseif (is_array($term)){
      $targets_id = $this->getTargetsId($term);
      $this->node->set('field_location_subarea', $targets_id);
    }
  }
  public function importContinent($continent){
    $term = $this->importTerm('location_areas',$continent);
    
    if(!is_null($term) && !is_array($term) ){
      $this->node->get('field_location_continent')->setValue(['target_id' => $term->id()]);
    }
    elseif (is_array($term)){
      $targets_id = $this->getTargetsId($term);
      $this->node->set('field_location_continent', $targets_id);
    }
  }

  /**
   * @param $name
   *
   * @return int|void
   *   If count == 1 not multiple.
   */

  public function getCountTermsForRow($name){
    $sample = explode(self::DELIMITER, $name);
    $sample = array_filter($sample);
    return count($sample);
  }

  public function getNameForRow($name){
    $sample = explode(self::DELIMITER, $name);
    $sample = array_filter($sample);
    return $sample;

  }

  /**
   * Return an array ids, using to update some fiels like continent, area, subarea.
   *
   *
   * @param array $term
   *
   * @return array
   */
  private function getTargetsId(array $term): array {
    $targets_id = [];
    foreach ($term as $t) {
      $targets_id[] = ['target_id' => $t->id()];
    }
    return $targets_id;
  }


}
