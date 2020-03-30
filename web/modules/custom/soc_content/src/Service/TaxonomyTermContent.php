<?php


namespace Drupal\soc_content\Service;


use Drupal\Core\Entity\EntityStorageException;
use Drupal\soc_content\Service\Manager\ContentManager;

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
    $termsQuery = \Drupal::entityQuery('taxonomy_term');
    if (isset($data['uuid'])) {
      $termsQuery->condition('uuid', $data['uuid']);
    }
    else {
      $termsQuery->condition('name', $name);
      $termsQuery->condition('vid', $vid);
    }
    $terms = $termsQuery->execute();

    // If term does not exist, create it.
    if (empty($terms)) {
      $data['name'] = $name;
      $data['vid'] = $vid;
      return $this->createEntity('Drupal\taxonomy\Entity\Term', $data);
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