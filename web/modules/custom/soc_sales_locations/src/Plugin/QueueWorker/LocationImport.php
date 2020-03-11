<?php

namespace Drupal\soc_sales_locations\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * Processes Node Tasks.
 *
 * @QueueWorker(
 *   id = "location_import",
 *   title = @Translation("Import location"),
 *   cron = {"time" = 300}
 * )
 */
class LocationImport extends QueueWorkerBase {
  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
//    $mailManager = \Drupal::service('plugin.manager.mail');
//    $params = $data;
//    $mailManager->mail('learning', 'email_queue', $data['email'], 'en', $params , $send = TRUE);
  }
}
