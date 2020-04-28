<?php

namespace Drupal\soc_traceparts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\soc_traceparts\Service\Manager\TracepartsDownloadsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Node cached block that displays the downloadable CAD files of a reference.
 *
 * @Block(
 *   id = "traceparts_downloads_block",
 *   admin_label = @Translation("Traceparts downloads block")
 * )
 */
class TracepartsDownloadsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /** @var TracepartsDownloadsManager $downloadsManager */
  protected $downloadsManager;

  public function __construct(array $configuration, $plugin_id, $plugin_definition,
      TracepartsDownloadsManager $downloads_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->downloadsManager = $downloads_manager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('soc_traceparts.traceparts_downloads_manager')
    );
  }

  public function build() {
    $build = [];
    /** @var $node \Drupal\node\NodeInterface $node */
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      if ($partNumber = $node->get('field_reference_ref')->value) {
        $downloadableFormats = $this->downloadsManager->getDownloadableFormats($partNumber);
        if (sizeof($downloadableFormats)) {
          $downloadLinks = [];
          foreach ($downloadableFormats as $formatId => $formatName) {
            $downloadLinks[] = [
              'format_id' => $formatId,
              'format_name' => $formatName,
            ];
          }
          $build['node_id'] = [
            '#theme' => 'soc_traceparts_download_block',
            '#formats' => $downloadLinks,
          ];
        }
      }
    }
    return $build;
  }

  public function getCacheTags() {
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      return Cache::mergeTags(parent::getCacheTags(), array('node:' . $node->id()));
    } else {
      return parent::getCacheTags();
    }
  }

  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), array('route'));
  }

}
