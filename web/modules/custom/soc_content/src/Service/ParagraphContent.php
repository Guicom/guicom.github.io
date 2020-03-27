<?php


namespace Drupal\soc_content\Service;


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
    return $this->createEntity('Drupal\paragraphs\Entity\Paragraph', $data);
  }

}
