<?php

namespace Drupal\soc_blocks_promotion\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Block promotion entity entities.
 *
 * @ingroup soc_blocks_promotion
 */
interface BlockPromotionEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Block promotion entity name.
   *
   * @return string
   *   Name of the Block promotion entity.
   */
  public function getName();

  /**
   * Sets the Block promotion entity name.
   *
   * @param string $name
   *   The Block promotion entity name.
   *
   * @return \Drupal\soc_blocks_promotion\Entity\BlockPromotionEntityInterface
   *   The called Block promotion entity entity.
   */
  public function setName($name);

  /**
   * Gets the Block promotion entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Block promotion entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Block promotion entity creation timestamp.
   *
   * @param int $timestamp
   *   The Block promotion entity creation timestamp.
   *
   * @return \Drupal\soc_blocks_promotion\Entity\BlockPromotionEntityInterface
   *   The called Block promotion entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Block promotion entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Block promotion entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\soc_blocks_promotion\Entity\BlockPromotionEntityInterface
   *   The called Block promotion entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Block promotion entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Block promotion entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\soc_blocks_promotion\Entity\BlockPromotionEntityInterface
   *   The called Block promotion entity entity.
   */
  public function setRevisionUserId($uid);

}
