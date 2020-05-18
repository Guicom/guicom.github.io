<?php

namespace Drupal\soc_blocks_promotion;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\soc_blocks_promotion\Entity\BlockPromotionEntityInterface;

/**
 * Defines the storage handler class for Block promotion entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Block promotion entity entities.
 *
 * @ingroup soc_blocks_promotion
 */
class BlockPromotionEntityStorage extends SqlContentEntityStorage implements BlockPromotionEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(BlockPromotionEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {block_promotion_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {block_promotion_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(BlockPromotionEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {block_promotion_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('block_promotion_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
