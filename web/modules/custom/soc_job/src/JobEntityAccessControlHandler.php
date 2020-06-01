<?php

namespace Drupal\soc_job;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Job entity.
 *
 * @see \Drupal\soc_job\Entity\JobEntity.
 */
class JobEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\soc_job\Entity\JobEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished job entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published job entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit job entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete job entities');

      default:
        break;
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add job entities');
  }

}
