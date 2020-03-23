<?php


namespace Drupal\soc_content\Service;


use Drupal\Core\Entity\EntityStorageException;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\soc_content\Service\Manager\ContentManager;

class ParagraphContent extends ContentManager {

  /**
   * @param string $uuid
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  protected function getParagraphByUuid(string $uuid) {
    return $this->getEntityByUuid('paragraph', $uuid);
  }

  /**
   * Create new paragraph.
   *
   * @param string $type
   * @param array $data
   *
   * @return bool|\Drupal\Core\Entity\EntityInterface|\Drupal\paragraphs\Entity\Paragraph
   */
  public function createParagraph(string $type, array $data = []) {
    $data['type'] = $type;
    $newParagraph = Paragraph::create($data);
    $newParagraph->enforceIsNew();
    try {
      $newParagraph->save();
      return $newParagraph;
    } catch (EntityStorageException $e) {
      $this->logger->error($e->getMessage());
    }
    return FALSE;
  }

}
