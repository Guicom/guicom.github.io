<?php


namespace Drupal\soc_content\Service;


use Drupal\Core\Entity\EntityStorageException;
use Drupal\soc_content\Service\Manager\ContentManager;
use Drupal\taxonomy\Entity\Term;

class TaxonomyTermContent extends ContentManager {

  /**
   * @param string $uuid
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  protected function getTermByUuid(string $uuid) {
    return $this->getEntityByUuid('taxonomy_term', $uuid);
  }

  /**
   * Create new term.
   *
   * @param string $name
   * @param string $vid
   * @param array $data
   *
   * @return bool|\Drupal\Core\Entity\EntityInterface|\Drupal\taxonomy\Entity\Term
   */
  public function createTerm(string $name, string $vid, array $data = []) {
    // Check if term already exists.
    $terms = \Drupal::entityQuery('taxonomy_term')
      ->condition('name', $name)
      ->condition('vid', $vid)
      ->execute();

    // If term does not exist, create it.
    if (empty($terms)) {
      $newTerm = Term::create($data);
      $newTerm->enforceIsNew();
      try {
        $newTerm->save();
        return $newTerm;
      } catch (EntityStorageException $e) {
        $this->logger->error($e->getMessage());
      }
    }
    return FALSE;
  }

  /**
   * Update existing term.
   *
   * @param string $uuid
   * @param array $data
   *
   * @return bool|\Drupal\Core\Entity\EntityInterface|\Drupal\taxonomy\Entity\Term
   */
  public function updateTerm(string $uuid, array $data) {
    // Check if term already exists.
    /** @var \Drupal\taxonomy\Entity\Term $term */
    if (!$term = $this->getTermByUuid($uuid)) {
      $this->logger->warning('Trying to update a term who does not exist, skipped...');
    }
    // Validate input.
    elseif (!isset($data['name'])) {
      $this->logger->warning('Trying to update a term without name, skipped...');
    }
    elseif (!isset($data['vid'])) {
      $this->logger->warning('Trying to update a term without vid, skipped...');
    }
    // If input is OK.
    else {
      // Update term.
      foreach ($data as $propertyName => $propertyValue) {
        $term->set($propertyName, $propertyValue);
      }
      try {
        $term->save();
        return $term;
      } catch (EntityStorageException $e) {
        $this->logger->error($e->getMessage());
      }
    }
    return FALSE;
  }

}
