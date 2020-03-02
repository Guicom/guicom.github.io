<?php


namespace Drupal\soc_content\Service;


use Drupal\Core\Entity\EntityStorageException;
use Drupal\soc_content\Service\Manager\ContentManager;
use Drupal\taxonomy\Entity\Term;

class MenuItemContent extends ContentManager {

  /**
   * @param string $uuid
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  protected function getMenuItemByUuid(string $uuid) {
    return $this->getEntityByUuid('menu_link_content', $uuid);
  }

  /**
   * Create new menu item.
   *
   * @param $data
   *
   * @return bool|\Drupal\Core\Entity\EntityInterface|\Drupal\taxonomy\Entity\Term
   */
  public function createTerm($data) {
    // Validate input.
    if (!isset($data['name'])) {
      $this->logger->error('Trying to create a term without name, skipped...');
    }
    elseif (!isset($data['vid'])) {
      $this->logger->error('Trying to create a term without vid, skipped...');
    }
    // If input is OK.
    else {
      // Check if term already exists.
      $terms = \Drupal::entityQuery('taxonomy_term')
        ->condition('name', $data['name'])
        ->condition('vid', $data['vid'])
        ->execute();

      // If term does not exist, create it.
      if (empty($terms)) {
        $new_term = Term::create($data);
        $new_term->enforceIsNew();
        try {
          $new_term->save();
          return $new_term;
        } catch (EntityStorageException $e) {
          $this->logger->error($e->getMessage());
        }
      }
    }
    return FALSE;
  }

}
