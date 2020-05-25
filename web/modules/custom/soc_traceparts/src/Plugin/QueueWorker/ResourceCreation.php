<?php


namespace Drupal\soc_traceparts\Plugin\QueueWorker;


use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\Entity\Node;
use Drupal\soc_content\Service\Manager\ContentManager;
use Drupal\soc_traceparts\Service\Manager\TracepartsDownloadsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Executes interface translation queue tasks.
 *
 * @QueueWorker(
 *   id = "soc_traceparts_resource_creation",
 *   title = @Translation("Generate resource for every reference with 3D data."),
 *   cron = {"time" = 60}
 * )
 */
class ResourceCreation extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * An instance of the entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /** @var TracepartsDownloadsManager $downloadsManager */
  protected $downloadsManager;

  /** @var ContentManager $contentManager */
  protected $contentManager;

  /**
   * Constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\soc_traceparts\Service\Manager\TracepartsDownloadsManager $downloads_manager
   * @param \Drupal\soc_content\Service\Manager\ContentManager $content_manager
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              array $plugin_definition,
                              EntityTypeManagerInterface $entityTypeManager,
                              TracepartsDownloadsManager $downloads_manager,
                              ContentManager $content_manager) {
    $this->downloadsManager = $downloads_manager;
    $this->entityTypeManager = $entityTypeManager;
    $this->contentManager = $content_manager;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('soc_traceparts.traceparts_downloads_manager'),
      $container->get('soc_content.content_manager')
    );
  }

  /**
   * @inheritDoc
   */
  public function processItem($data) {
    /** @var \Drupal\node\Entity\Node $referenceNode */
    $referenceNode = $data['entity'];
    $partNumber = $referenceNode->get('field_reference_ref')->value;
    $title = $referenceNode->getTitle() . ' CAD ' . $partNumber;
    $resourceType = $this->contentManager
      ->getEntityByUuid('taxonomy_term', 'be54a7ae-c75a-4c02-ad49-32382e9a5fdf');
    $host = \Drupal::request()->getSchemeAndHttpHost();

    // Check if resource already exists.
    $resourceNids = $this->entityTypeManager
      ->getListBuilder('node')
      ->getStorage()
      ->loadByProperties([
        'type' => 'resource',
        'field_res_reference' => $partNumber,
      ]);

    // If the resource does not already exist, then try to create it.
    if (!sizeof($resourceNids)) {
      // Check if Traceparts 3D model exists.
      $downloadableFormats = $this->downloadsManager->getDownloadableFormats($partNumber);
      if (sizeof($downloadableFormats)) {
        $resourceNodeData = [
          'type' => 'resource',
          'title' => $title,
          'field_res_title' => $title,
          'field_res_original_title' => $referenceNode->getTitle(),
          'field_res_reference' => $partNumber,
          'field_res_downloadable' => 0,
          'field_res_link_url' => $host . $referenceNode->toUrl()->toString(),
          'field_res_resource_type' => [
            'target_id' => $resourceType->id(),
          ],
          'moderation_state' => 'published',
        ];
        $resourceNode = Node::create($resourceNodeData);
        $resourceNode->setPublished();
        try {
          $resourceNode->save();
        }
        catch (\Exception $e) {
          \Drupal::logger('soc_traceparts')->warning($e->getMessage());
        }
      }
    }
  }
}
