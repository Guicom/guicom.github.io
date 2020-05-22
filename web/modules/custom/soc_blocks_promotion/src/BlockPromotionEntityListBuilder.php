<?php

namespace Drupal\soc_blocks_promotion;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Block promotion entity entities.
 *
 * @ingroup soc_blocks_promotion
 */
class BlockPromotionEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Block promotion entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\soc_blocks_promotion\Entity\BlockPromotionEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.block_promotion_entity.edit_form',
      ['block_promotion_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
