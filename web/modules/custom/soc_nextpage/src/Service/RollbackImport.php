<?php

namespace Drupal\soc_nextpage\Service;

use Drupal;
use Drupal\Core\Database\Connection;
use Drupal\soc_heartbeat\Service\Manager\HeartbeatManager;

/**
 *
 */
class RollbackImport {

  /**
   * @var \Drupal\soc_heartbeat\Service\Manager\HeartbeatManager
   */
  protected $heartBeat;

  /**
   * @var \Drupal\soc_nextpage\Service\NextpageItemHandler
   */
  protected $nextpageItemHandler;

  /**
   * @var \Drupal\Core\Database\Connection
   */
  private $connection;

  /**
   * RollbackImport constructor.
   *
   * @param \Drupal\soc_heartbeat\Service\Manager\HeartbeatManager $heartbeatManager
   * @param \Drupal\soc_nextpage\Service\NextpageItemHandler $nextpageItemHandler
   * @param \Drupal\Core\Database\Connection $connection
   */
  public function __construct(HeartbeatManager $heartbeatManager,
                              NextpageItemHandler $nextpageItemHandler,
                              Connection $connection) {
    $this->heartBeat = $heartbeatManager;
    $this->nextpageItemHandler = $nextpageItemHandler;
    $this->connection = $connection;
  }

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
    $relation = $this->nextpageItemHandler->selectRelation();
    if (count($relation) > 0) {
      throw new \Exception(t('The relation table is not empty.'), 1);
    }
    return TRUE;
  }

  /**
   * @return mixed
   */
  public function selectRollbackEntry() {
    $items = $this->connection->select('soc_rollback_items', 'sri')
      ->fields('sri', ['entity_id']);
    $data = $items->execute();
    return $data->fetchAll(\PDO::FETCH_OBJ);
  }

}
