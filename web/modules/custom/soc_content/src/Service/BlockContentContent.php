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
   * @param string $type
   * @param string $info
   * @param $data
   *
   * @return bool|\Drupal\block_content\Entity\BlockContent
   */
  public function createBlockContent(string $type, string $info, $data) {
    // Check if block content already exists.
    $blockContents = \Drupal::entityQuery('block_content')
      ->condition('type', $type)
      ->condition('info', $info)
      ->execute();

    // If block content does not exist, create it.
    if (empty($blockContents)) {
      $data['type'] = $type;
      $data['info'] = $info;
      $newBlockContent = BlockContent::create($data);
      $newBlockContent->enforceIsNew();
      try {
        $newBlockContent->save();
        return $newBlockContent;
      } catch (EntityStorageException $e) {
        $this->logger->error($e->getMessage());
      }
    }
    return FALSE;
  }

  /**
   * Update existing block content.
   *
   * @param string $uuid
   * @param array $data
   *
   * @return bool|\Drupal\Core\Entity\EntityInterface|\Drupal\block_content\Entity\BlockContent
   */
  public function updateBlockContent(string $uuid, array $data) {
    // Check if block content already exists.
    /** @var \Drupal\block_content\Entity\BlockContent $blockContent */
    if (!$blockContent = $this->getTermByUuid($uuid)) {
      $this->logger->warning('Trying to update a block content who does not exist, skipped...');
    }
    // Validate input.
    elseif (!isset($data['type'])) {
      $this->logger->warning('Trying to update a block content without type, skipped...');
    }
    elseif (!isset($data['info'])) {
      $this->logger->warning('Trying to update a block content without info, skipped...');
    }
    // If input is OK.
    else {
      // Update block content.
      foreach ($data as $propertyName => $propertyValue) {
        $blockContent->set($propertyName, $propertyValue);
      }
      try {
        $blockContent->save();
        return $blockContent;
      } catch (EntityStorageException $e) {
        $this->logger->error($e->getMessage());
      }
    }
    return FALSE;
  }

}
