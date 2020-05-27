<?php


namespace Drupal\soc_sales_locations\Helpers;


use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\Entity\Term;


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

  /**
   * StoreLocationImportHelper constructor.
   *
   * @param \Drupal\node\NodeInterface $node
   */
  public function __construct(NodeInterface $node ) {
    $this->node = $node;
  }


  /**
   * @param string $title
   *
   * @return void
   * @throws \Exception
   */
  public function importTitle(string $title) {
    if (strlen($title) < 2) {
      throw new \Exception();
    }
    else {
      $this->node->setTitle($title);
    }
  }

  /**
   * Set the company name.
   *
   * @param $company_name
   *
   * @return void
   * @throws \Exception
   */
  public function importCompanyName($company_name) {
    if (strlen($company_name) < 2) {
      throw new \Exception();
    }
    else {
      $this->node->set('field_location_company', $company_name);
    }
  }

  /**
   * Set the contact name.
   *
   * @param $contact_name
   *
   * @return void
   */
  public function importContactName($contact_name) {
    $this->node->set('field_location_name_contact', $contact_name);
  }

  /**
   * @param int $date_start_import
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\TypedData\Exception\ReadOnlyException
   */
  public function saveUpdatedRevisionsNode(int $date_start_import){
    if (!$this->node->getEntityType()->isRevisionable()){
      throw new EntityStorageException('The content type is not revisionable.');
    }
    $this->node->get('field_last_imported_timestamp')->setValue($date_start_import);
    $this->node->setNewRevision();
    $message = $this->t('Import done @date', ['@date' => date('Y/m/d H:i:s', $date_start_import)]);
    $this->node->setRevisionLogMessage($message);
    try {
      return $this->node->save();
    } catch (EntityStorageException $e) {
      \Drupal::logger('soc_sales_locations')->warning($e->getMessage());
    }
    return FALSE;
  }

  public function importFirstName($firstname) {
    $this->node->set('field_location_firstname', $firstname);
  }

  public function importAddress(array $row) {
    if (strlen($row[10]) < 2
        || strlen($row[13]) < 2
        || strlen($row[15]) < 2
        || strlen($row[17]) < 2) {
      throw new \Exception();
    }
    else {
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
  }

  public function importPhone($phone) {
    if (strlen($phone) < 2) {
      throw new \Exception();
    }
    else {
      $this->node->get('field_location_telephone')->setValue($phone);
    }
  }

  public function importFax($fax) {
    $this->node->get('field_location_fax')->setValue($fax);
  }

  public function importWebsite($website) {
    $this->node->get('field_location_website')->setValue(['uri' => $website]);
  }

  public function importType($type) {
    $term = $this->importTerm('location_type', $type);
    if(!is_null($term) ){
      $this->node->get('field_location_type')->setValue([
        'target_id' => $term->id(),
      ]);
    }
  }

  /**
   * @param string $voc
   *    Vocabulary Machine Name.
   *
   * @param string $name
   *    Name Term.
   *
   * @param null $parent
   *    Parent term.
   *
   * @return mixed Term.
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function importTerm($voc, $name, $parent = NULL) {
    // Handle case of multiple areas by recursively calling this method.
    if ($this->getCountTermsForRow($name) > 1) {
      $_multipleTerms = [];
      $parents = NULL;
      if (!is_null($parent)) {
        $parents = $this->getNameForRow($parent);
      }
      foreach ($this->getNameForRow($name) as $key => $item) {
        if (!is_null($parents)) {
          if (array_key_exists($key, $parents)) {
            $_multipleTerms[] = $this->importTerm($voc, $item, $parents[$key]);
          }
        }
        else {
          $_multipleTerms[] = $this->importTerm($voc, $item);
        }
      }
      return $_multipleTerms;
    }
    // If there is a parent, search for it by name.
    $parentTermId = NULL;
    if (!is_null($parent)) {
      $properties = [
        'vid' => $voc,
        'name' => $parent,
      ];
      $parentTerms = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadByProperties($properties);
      if (!sizeof($parentTerms)) {
        // It's not possible to create this element if the parent doesn't exist.
        return FALSE;
      }
      else {
        $parentTerm = reset($parentTerms);
        $parentTermId = $parentTerm->id();
      }
    }
    // Search for this term by name.
    $properties = [
      'vid' => $voc,
      'name' => $name,
    ];
    if (isset($parentTerm)) {
      $properties['parent'] = $parentTerm->id();
    }
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties($properties);
    if (count($terms) >= 1) {
      return reset($terms);
    }
    else {
      return $this->createTermIfNecessary($voc, $name, $parentTermId);
    }
  }

  /**
   * Create a new Term if it doesn't exists.
   *
   * @param $voc
   * @param $name
   * @param null $parentTid
   *
   * @return bool|\Drupal\taxonomy\Entity\Term
   */
  private function createTermIfNecessary($voc, $name, $parentTid = NULL) {
    $term = Term::create([
      'vid' => $voc,
      'name' => $name,
    ]);
    if (!is_null($parentTid)) {
      $term->set('parent', $parentTid);
    }
    try {
      $term->save();
      return $term;
    }
    catch (\Exception $e) {
      \Drupal::logger('soc_sale_locations')->warning($e->getMessage());
    }
    return FALSE;
  }

  public function importActivity($activity) {
    $term = $this->importTerm('location_activity', $activity);
    if (is_null($term) || $term === FALSE) {
      throw new \Exception();
    }
    else {
      $this->node->get('field_location_activity')->setValue([
        'target_id' => $term->id(),
      ]);
    }
  }

  public function importContinent($continent){
    $term = $this->importTerm('location_areas', $continent);

    if(!is_null($term) && $term !== FALSE && !is_array($term) ){
      $this->node->get('field_location_continent')->setValue(['target_id' => $term->id()]);
    }
    elseif (is_array($term)){
      $targets_id = $this->getTargetsId($term);
      $this->node->set('field_location_continent', $targets_id);
    }
    else {
      throw new \Exception();
    }
  }

  public function importArea($area, $continent) {
    $term = $this->importTerm('location_areas', $area, $continent);
    if(!is_null($term) && $term !== FALSE && !is_array($term) ){
      $this->node->get('field_location_area')->setValue([
        'target_id' => $term->id(),
      ]);
    }
    elseif (is_array($term)){
      $targets_id = $this->getTargetsId($term);
      $this->node->set('field_location_area', $targets_id);
    }
    else {
      throw new \Exception();
    }
  }

  public function importSubArea($subarea, $area) {
    if ($subarea == '') {
      return;
    }
    $term = $this->importTerm('location_areas', $subarea, $area);
    if (!is_null($term) && $term !== FALSE && !is_array($term)) {
      $this->node->get('field_location_subarea')->setValue([
        'target_id' => $term->id(),
      ]);
    }
    elseif (is_array($term)){
      $targets_id = $this->getTargetsId($term);
      $this->node->set('field_location_subarea', $targets_id);
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
    return array_filter(explode(self::DELIMITER, $name));
  }

  /**
   * Return an array ids, used to update some fields like continent, area, subarea.
   *
   *
   * @param array $term
   *
   * @return array
   */
  private function getTargetsId(array $terms): array {
    $targetIds = [];
    foreach ($terms as $term) {
      $targetIds[] = ['target_id' => $term->id()];
    }
    return $targetIds;
  }


}
