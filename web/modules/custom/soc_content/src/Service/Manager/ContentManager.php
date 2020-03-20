<?php


namespace Drupal\soc_content\Service\Manager;


use Drupal\Core\Entity\EntityRepository;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

class ContentManager {

  /**
   * The entity repository.
   *
   * @var \Drupal\Core\Entity\EntityRepository
   */
  protected $entityRepository;

  /**
   * The soc_content logging channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityRepository $entity_repository
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $channel_factory
   */
  public function __construct(EntityRepository $entity_repository,
                              LoggerChannelFactoryInterface $channel_factory) {
    $this->entityRepository = $entity_repository;
    $this->logger = $channel_factory->get('soc_content');
  }

  /**
   * @param string $type
   * @param string $uuid
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  public function getEntityByUuid(string $type, string $uuid) {
    try {
      $entity = $this->entityRepository->loadEntityByUuid($type, $uuid);
      if ($entity->getEntityType() === $type) {
        return $entity;
      }
    } catch (\Exception $e) {
      $this->logger->error($e->getMessage());
    }
    return NULL;
  }

  /**
   * @param string $uuid
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  protected function getNodeByUuid(string $uuid) {
    return $this->getEntityByUuid('node', $uuid);
  }

}
