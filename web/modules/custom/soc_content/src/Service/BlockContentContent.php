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
  public function getBlockContentByUuid(string $uuid) {
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
    $blockContentQuery = \Drupal::entityQuery('block_content');
    if (isset($data['uuid'])) {
      $blockContentQuery->condition('uuid', $data['uuid']);
    }
    else {
      $blockContentQuery->condition('type', $type);
      $blockContentQuery->condition('info', $info);
    }
    $blockContents = $blockContentQuery->execute();

    // If block content does not exist, create it.
    if (empty($blockContents)) {
      $data['type'] = $type;
      $data['info'] = $info;
      if (!isset($data['langcode'])) {
        $data['langcode'] = 'en';
      }
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
    if (!$blockContent = $this->getBlockContentByUuid($uuid)) {
      $this->logger->warning('Trying to update a block content who does not exist, skipped...');
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
