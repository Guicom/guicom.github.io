<?php

namespace Drupal\soc_blocks_promotion\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Block promotion entity entities.
 */
class BlockPromotionEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
