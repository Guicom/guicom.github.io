<?php

namespace Drupal\soc_heartbeat\Service\Manager;

use DateInterval;
use DateTime;
use Drupal;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Driver\Exception\Exception;

/**
 *
 */
class HeartbeatManager {

  /**
   * @var \Drupal\soc_rollback\Service\RollbackImport
   */
  private $rollback;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  private $logger;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * HeartbeatManager constructor.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $channelFactory
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(LoggerChannelFactoryInterface $channelFactory,
                              EntityTypeManagerInterface $entityTypeManager) {
    $this->logger = $channelFactory->get('soc_heartbeat');
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Run cron method.
   */
  public function run() {
    $jobs = $this->getInprogressJob();
    if (count($jobs) > 1) {
      $this->logger->warning(t('WARNING : There is more than one job'));
      exit;
    }
    elseif (!empty($jobs)) {
      foreach ($jobs as $job) {
        Drupal::service('soc_rollback.rollback_import')->rollback($job);
      }
    }
  }

  /**
   * Get in_progress Job.
   *
   * @return array|int
   *
   * @throws \Exception
   */
  public function getInprogressJob() {
    $date = new DateTime();
    // 10 min interval.
    $interval = new DateInterval("PT10M");
    $interval->invert = 1;
    $date->add($interval);
    $now = $date->getTimestamp();
    try {
      $query = \Drupal::entityQuery('job');
      $query->condition('field_job_status', 'in_progress');
      $query->condition('field_job_heartbeat', $now, '<');
      return $query->execute();
    }
    catch (Exception $e) {
      throw new \Exception($e->getMessage(), 1);
    }
  }

}
