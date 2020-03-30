<?php

namespace Drupal\soc_job;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class JobEntityStorage extends SqlContentEntityStorage implements JobEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(JobEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {job_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {job_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(JobEntityInterface $entity) {
    $query = 'SELECT COUNT(*) FROM {job_field_revision} WHERE id = :id AND default_langcode = 1';
    return $this->database->query($query, [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('job_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
