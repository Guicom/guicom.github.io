<?php

namespace Drupal\soc_job\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Job entities.
 *
 * @ingroup soc_job
 */
interface JobEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Job name.
   *
   * @return string
   *   Name of the Job.
   */
  public function getName();

  /**
   * Sets the Job name.
   *
   * @param string $name
   *   The Job name.
   *
   * @return \Drupal\soc_job\Entity\JobEntityInterface
   *   The called Job entity.
   */
  public function setName($name);

  /**
   * Gets the Job creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Job.
   */
  public function getCreatedTime();

  /**
   * Sets the Job creation timestamp.
   *
   * @param int $timestamp
   *   The Job creation timestamp.
   *
   * @return \Drupal\soc_job\Entity\JobEntityInterface
   *   The called Job entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Job revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Job revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\soc_job\Entity\JobEntityInterface
   *   The called Job entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Job revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Job revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\soc_job\Entity\JobEntityInterface
   *   The called Job entity.
   */
  public function setRevisionUserId($uid);

}
