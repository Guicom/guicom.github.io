<?php


namespace Drupal\soc_content\Service;


use Drupal\block_content\Entity\BlockContent;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\soc_content\Service\Manager\ContentManager;

class BlockContentContent extends ContentManager {

  /**
   * @param string $uuid
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  protected function getTermByUuid(string $uuid) {
    return $this->getEntityByUuid('block_content', $uuid);
  }

  /**
   * Create new block content.
   *
   * @param $data
   *
   * @return bool|\Drupal\block_content\Entity\BlockContent
   */
  public function createBlockContent($data) {
    // Validate input.
    if (!isset($data['type'])) {
      $this->logger->warning('Trying to create a block content without type, skipped...');
    }
    elseif (!isset($data['info'])) {
      $this->logger->warning('Trying to create a block content without info, skipped...');
    }
    // If input is OK.
    else {
      // Check if term already exists.
      $terms = \Drupal::entityQuery('block_content')
        ->condition('type', $data['type'])
        ->condition('info', $data['info'])
        ->execute();

      // If block content does not exist, create it.
      if (empty($terms)) {
        $newBlockContent = BlockContent::create($data);
        $newBlockContent->enforceIsNew();
        try {
          $newBlockContent->save();
          return $newBlockContent;
        } catch (EntityStorageException $e) {
          $this->logger->error($e->getMessage());
        }
      }
    }
    return FALSE;
  }

}
