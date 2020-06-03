<?php

namespace Drupal\soc_nextpage\Service;

use Drupal;

/**
 *
 */
class RollbackImport extends Drupal\soc_rollback\Service\RollbackImport {

  /**
   * @return bool
   * @throws \Exception
   */
  public function checkAllGreen() {
    $job = $this->heartBeat->getInprogressJob();
    if (count($job) > 0) {
      throw new \Exception(t('There is a job in progress.'), 1);
    }
    $items = $this->selectRollbackEntry();
    if (count($items) > 0) {
      throw new \Exception(t('The rollback table is not empty.'), 1);
    }
    $relation = \Drupal::service('soc_nextpage.nextpage_item_handler')->selectRelation();
    if (count($relation) > 0) {
      throw new \Exception(t('The relation table is not empty.'), 1);
    }
    return TRUE;
  }

}
