<?php

namespace Drupal\soc_blocks_promotion;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface BlockPromotionEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Block promotion entity revision IDs for a specific Block promotion entity.
   *
   * @param \Drupal\soc_blocks_promotion\Entity\BlockPromotionEntityInterface $entity
   *   The Block promotion entity entity.
   *
   * @return int[]
   *   Block promotion entity revision IDs (in ascending order).
   */
  public function revisionIds(BlockPromotionEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Block promotion entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Block promotion entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\soc_blocks_promotion\Entity\BlockPromotionEntityInterface $entity
   *   The Block promotion entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BlockPromotionEntityInterface $entity);

  /**
   * Unsets the language for all Block promotion entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
