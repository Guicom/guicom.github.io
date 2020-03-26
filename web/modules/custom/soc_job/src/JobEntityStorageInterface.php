<?php

namespace Drupal\soc_job;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\soc_job\Entity\JobEntityInterface;

/**
 * Defines the storage handler class for Job entities.
 *
 * This extends the base storage class, adding required special handling for
 * Job entities.
 *
 * @ingroup soc_job
 */
interface JobEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Job revision IDs for a specific Job.
   *
   * @param \Drupal\soc_job\Entity\JobEntityInterface $entity
   *   The Job entity.
   *
   * @return int[]
   *   Job revision IDs (in ascending order).
   */
  public function revisionIds(JobEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Job author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Job revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\soc_job\Entity\JobEntityInterface $entity
   *   The Job entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(JobEntityInterface $entity);

  /**
   * Unsets the language for all Job with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
