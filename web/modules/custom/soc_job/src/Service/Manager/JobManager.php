<?php

namespace Drupal\soc_job\Service\Manager;

use DateTime;

/**
 * JobManager.
 */
class JobManager {

  /**
   * Method ti create Job.
   *
   * @param string $type
   *   Type for the job.
   *
   * @return int|string|null
   *   return Job ID
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function create($type) {
    $job = \Drupal::entityTypeManager()->getStorage('job')->create();

    $date = new DateTime();
    $now = $date->getTimestamp();
    $time = date('d/m/y H:i', $now);
    $title = t('@type job -  @time',
      [
        '@type' => $type,
        '@time' => $time,
      ]);

    $job->set('name', $title);
    $job->set('field_job_start_date', $now);
    $job->set('field_job_heartbeat', $now);
    $job->set('field_job_status', 'started');

    switch ($type) {
      case 'pim':
        $job->set('field_job_type', 'pim');
    }

    try {
      $job->save();
    }
    catch (\Exception $e) {
      \Drupal::logger('soc_job')->error($e->getMessage());
    }
    // Return Job ID.
    return $job->id();
  }

  /**
   * @param $job_id
   * @param $entity
   * @param $state
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function update($job_id, $entity, $state) {
    $job = $this->load($job_id);
    $date = new DateTime();
    $now = $date->getTimestamp();
    $job->set('field_job_heartbeat', $now);
    $this->fillBody($job, $entity, $state);
    $job->save();
  }

  /**
   * @param $job
   * @param $entity
   * @param $state
   */
  public function fillBody(&$job, $entity, $state) {
    switch ($entity->getEntityType()->id()) {
      case 'taxonomy_term':
        $title = $entity->getName();
        break;

      case 'node':
        $title = $entity->getTitle();
        break;
    }
    $id = $entity->id();
    $body = $job->get('field_job_info_status')->value;
    $body .= t('Entity @entity_type with id @entity_id and title @title is @state.
    ',
    [
      '@entity_type' => $entity->getEntityType()->id(),
      '@entity_id' => $id,
      '@title' => $title,
      '@state' => $state,
    ]);
    $job->set('field_job_info_status', $body);
  }

  /**
   * @param $state
   * @param $job_id
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function changeState($state, $job_id) {
    $job = $this->load($job_id);
    $old_state = $job->get('field_job_status')->value;
    $job->set('field_job_status', $state);
    $job->save();
  }

  /**
   * @param $job_id
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function load($job_id) {
    return \Drupal::entityTypeManager()->getStorage('job')->load($job_id);
  }

}
