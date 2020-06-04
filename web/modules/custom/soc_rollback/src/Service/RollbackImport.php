<?php

namespace Drupal\soc_rollback\Service;

use Drupal;
use Drupal\Core\Database\Connection;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Driver\Exception\Exception;
use Drupal\soc_heartbeat\Service\Manager\HeartbeatManager;
use Drupal\soc_job\Service\Manager\JobManager;

/**
 *
 */
class RollbackImport {

  /**
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * @var \Drupal\Core\Database\Connection
   */
  private $connection;

  /**
   * @var \Drupal\soc_job\Service\Manager\JobManager
   */
  protected $job;

  /**
   * @var \Drupal\soc_heartbeat\Service\Manager\HeartbeatManager
   */
  protected $heartBeat;

  /**
   * RollbackImport constructor.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $channelFactory
   * @param \Drupal\Core\Database\Connection $connection
   * @param \Drupal\soc_job\Service\Manager\JobManager $jobManager
   * @param \Drupal\soc_heartbeat\Service\Manager\HeartbeatManager $heartbeatManager
   */
  public function __construct(LoggerChannelFactoryInterface $channelFactory,
                              Connection $connection,
                              JobManager $jobManager,
                              HeartbeatManager $heartbeatManager) {
    $this->logger = $channelFactory->get('soc_rollback');
    $this->connection = $connection;
    $this->job = $jobManager;
    $this->heartBeat = $heartbeatManager;
  }

  /**
   * @param $type
   *
   * @return int|string|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function initJob($type) {
    return $this->job->create($type);
  }

  /**
   * @param $job_id
   * @param $data
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function updateJob($job_id, $data) {
    switch ($data['operation']) {
      case 'update_state':
        $this->job->changeState($data['state'], $job_id);
        break;

      case 'update_entity':
        $this->writeJob($job_id, $data['entity'], $data['state']);
        break;

      default:
        break;
    }
  }

  /**
   * @param $job_id
   * @param $entity
   * @param $state
   */
  public function writeJob($job_id, $entity, $state) {
    // @todo: update de la date du job et status + ajout dans le champs body + appel du markAsUpdated
    $this->job->update($job_id, $entity, $state);
    $this->markAsUpdated($entity, $entity->id(), $state);
  }

  /**
   * @param $entity
   * @param $entity_id
   * @param $state
   *
   * @throws \Exception
   */
  public function markAsUpdated($entity, $entity_id, $state) {
    // Add entity to table for rollback.
    try {
      $this->connection->insert('soc_rollback_items')
        ->fields([
          'entity_type' => $entity->getEntityType()->id(),
          'entity_id' => $entity_id,
          'entity_statut' => $state,
        ])
        ->execute();
    }
    catch (Exception $e) {
      $this->logger->error($e->getMessage());
    }

    // Log.
    $this->logger->info('@entity_type ID: @entity_id processed',
      [
        '@entity_id' => $entity_id,
        '@entity_type' => $entity->bundle(),
      ]
    );
  }

  /**
   * @param $entityId
   */
  public function deleteRollbackEntry($entityId) {
    $this->connection->delete('soc_rollback_items')
      ->condition('entity_id', $entityId)
      ->execute();
  }

  /**
   *
   */
  public function purgeRollbackEntry() {
    $this->connection->delete('soc_rollback_items')
      ->execute();
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

  /**
   * @param $job_id
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function rollback($job_id) {
    $sth = $this->connection->select('soc_rollback_items', 'sri')
      ->fields('sri', ['entity_type', 'entity_id', 'entity_statut']);
    // Execute the statement.
    $data = $sth->execute();
    $results = $data->fetchAll(\PDO::FETCH_OBJ);
    foreach ($results as $result) {
      switch ($result->entity_statut) {
        case 'updated':
          $storage = \Drupal::entityTypeManager()->getStorage($result->entity_type);
          if ($entity = $storage->load($result->entity_id)) {
            switch ($result->entity_type) {
              case 'taxonomy_term':
                $query = $storage->getQuery()->condition($entity->getEntityType()->getKey('id'), $entity->id())->allRevisions()->execute();
                $vids = array_keys($query);
                break;

              case 'node':
                $vids = $storage->revisionIds($entity);
                break;

              default:
                break;
            }
            end($vids);
            $vid = prev($vids);
            $new_revision = $storage->loadRevision($vid);
            $new_revision->setNewRevision(TRUE);
            $new_revision->isDefaultRevision(TRUE);
            $new_revision->revision_log = t('Rollback');
            $new_revision->setRevisionCreationTime(REQUEST_TIME);
            try {
              $new_revision->save();
            }
            catch (\Exception $e) {
              throw new \Exception($e->getMessage(), 1);
            }

            $this->logger->info('@entity_type ID: @entity_id processed',
              [
                '@entity_id' => $new_revision->id(),
                '@entity_type' => $new_revision->bundle(),
              ]
            );
          }
          break;

        case 'created':
          $storage = \Drupal::entityTypeManager()->getStorage($result->entity_type);
          $entity = $storage->load($result->entity_id);
          $entity->delete();
          break;

        default:
          break;
      }
      // Delete entry in DB.
      $this->deleteRollbackEntry($result->entity_id);
    }
    $this->job->changeState('failed', $job_id);
  }

}
